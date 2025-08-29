<?php

namespace App\Services;

use App\Services\Traits\Searchable;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Interfaces\Repositories\PersonaRepositoryInterface;
use App\Dtos\Persona\CreatePersonaDto;
use App\Dtos\Persona\UpdatePersonaDto;
use App\Dtos\Persona\ActualizarCuentaDto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Resources\Persona\CuentaResource;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Http\JsonResponse;

class PersonaService extends BaseService {

    use Searchable;

    /**
     * @param PersonaRepositoryInterface $personaRepository
     */
    public function __construct(
        private readonly PersonaRepositoryInterface $personaRepository,
        private readonly UserRepository $userRepository,
        private readonly CreateNewUser $createNewUser
    ) {
        $this->repository = $this->personaRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\Persona\\PersonaCollection";
        $this->customResource = "App\\Http\\Resources\\Persona\\PersonaResource";
    }


    /**
     * @param CreatePersonaDto $createPersonaDto
     * @param UploadedFile|null $fotografiaFile
     * @return JsonResource
     */
    public function createFromDto(CreatePersonaDto $createPersonaDto, ?UploadedFile $fotografiaFile): JsonResource
    {
        $urlFotografia = '';

        if ($fotografiaFile) {
            $fileName = (string) Str::uuid() . '.' . $fotografiaFile->getClientOriginalExtension();
            $path = $fotografiaFile->storeAs('fotografias_persona', $fileName, 'public');
            $urlFotografia = Storage::url($path);
        }

        $data = [
            'dependencia_type' => $createPersonaDto->getDependenciaModel(),
            'dependencia_id' => $createPersonaDto->dependenciaId,
            'nombre' => $createPersonaDto->nombre,
            'apellido_paterno' => $createPersonaDto->apellidoPaterno,
            'apellido_materno' => $createPersonaDto->apellidoMaterno,
            'es_titular' => $createPersonaDto->esTitular,
            'url_fotografia' => $urlFotografia,
        ];

        $nuevaPersonaResource = null;
        DB::transaction(function() use ($data, $createPersonaDto, &$nuevaPersonaResource){
            $nuevaPersonaResource = parent::create($data);
            $nuevaPersona = $nuevaPersonaResource->resource;

            if ($createPersonaDto->email && $createPersonaDto->password) {
                $dataUser = [
                    'name' => trim($createPersonaDto->nombre . " " . $createPersonaDto->apellidoPaterno . " " . $createPersonaDto->apellidoMaterno),
                    'email' => $createPersonaDto->email,
                    'password' => $createPersonaDto->password,
                    'password_confirmation' => $createPersonaDto->password,
                ];

                $user = $this->createNewUser->create($dataUser);
                $user->owner()->associate($nuevaPersona);
                $user->save();
            }

        });
        if (!$nuevaPersonaResource) {
            throw new \RuntimeException('No se pudo crear la persona o el usuario.');
        }

        return $nuevaPersonaResource;
    }


    /**
     * @param UpdatePersonaDto $updatePersonaDto
     * @param int $id
     * @param UploadedFile|null $fotografiaFile
     * @return JsonResource
     */
    public function updateFromDto(UpdatePersonaDto $updatePersonaDto, int $id, ?UploadedFile $fotografiaFile): JsonResource
    {
        $data = [
            'nombre' => $updatePersonaDto->nombre,
            'apellido_paterno' => $updatePersonaDto->apellidoPaterno,
            'apellido_materno' => $updatePersonaDto->apellidoMaterno,
        ];

        // Solo actualizar dependencia si se proporciona
        if ($updatePersonaDto->shouldUpdateDependencia()) {
            $data['dependencia_type'] = $updatePersonaDto->getDependenciaModel();
            $data['dependencia_id'] = $updatePersonaDto->dependenciaId;
        }

        // Solo actualizar es_titular si se proporciona
        if ($updatePersonaDto->esTitular !== null) {
            $data['es_titular'] = $updatePersonaDto->esTitular;
        }

        if ($fotografiaFile) {
            $persona = $this->repository->findById($id);
            if ($persona->url_fotografia) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $persona->url_fotografia));
            }

            $fileName = (string) Str::uuid() . '.' . $fotografiaFile->getClientOriginalExtension();
            $path = $fotografiaFile->storeAs('fotografias_persona', $fileName, 'public');

            $data['url_fotografia'] = Storage::url($path);
        }

        return parent::update($id, $data);
    }

    /**
     * Obtener información de la cuenta (email/estado) de una persona.
     *
     * @param int $personaId
     * @return JsonResource | JsonResponse
     * @throws \Exception
     */
    public function infoCuenta(int $personaId): JsonResource | JsonResponse
    {
        $persona = $this->repository->findById($personaId);
        
        if (!$persona) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Persona no encontrada');
        }

        // Cargar el usuario asociado si existe
        $persona->load('user');
        // si no hay usuario, retornar un recurso vacío
        if (!$persona->user) {
            return response()->json([]);
        }

        return new CuentaResource($persona->user);
    }

    /**
     * Actualizar cuenta (email/contraseña) de una persona.
     * @param ActualizarCuentaDto $dto
     */
    public function actualizarCuenta(ActualizarCuentaDto $dto, int $personaId): JsonResource
    {
        $persona = $this->repository->findById($personaId);
        
        if (!$persona) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Persona no encontrada');
        }

        return DB::transaction(function() use ($dto, $persona) {
            // Verificar si la persona tiene usuario asociado
            $user = $persona->user;
            
            if (!$user) {
                // Si no tiene usuario, crear uno nuevo
                if (!$dto->shouldUpdateEmail() || !$dto->shouldUpdatePassword()) {
                    throw new \Exception('Para crear una cuenta nueva se requiere tanto email como contraseña');
                }

                $dataUser = [
                    'name' => trim($persona->nombre . " " . $persona->apellido_paterno . " " . ($persona->apellido_materno ?? '')),
                    'email' => $dto->email,
                    'password' => $dto->password,
                    'password_confirmation' => $dto->password,
                ];

                $user = $this->createNewUser->create($dataUser);
                $user->owner()->associate($persona);
                $user->save();
                $user->assignRole($dto->getRoles());

                $persona->load('user');
            } else {
                // Si ya tiene usuario, actualizarlo
                $updateData = $dto->toUserUpdateArray();
                if (!empty($updateData)) {
                    $user = $this->userRepository->updateAndReturn($user->id, $updateData);
                    $user->assignRole($dto->getRoles());

                    $persona->load('user');
                }
            }

            // Retornar la persona actualizada con su usuario
            if ($this->customResource) {
                return new $this->customResource($persona);
            }

            return JsonResource::make($persona);
        });
    }

    /**
     * Desactivar cuenta de usuario de una persona.
     */
    public function desactivarCuenta(int $personaId): array
    {
        $persona = $this->repository->findById($personaId);
        
        if (!$persona) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Persona no encontrada');
        }

        $user = $persona->user;
        if (!$user) {
            throw new \Exception('Usuario no encontrado');
        }

        // Marcar como inactivo en lugar de eliminar
        $this->userRepository->updateAndReturn($user->id, [
            'active' => 0,
            'email_verified_at' => null
        ]);

        return [
            'message' => 'Cuenta desactivada exitosamente',
            'persona_id' => $personaId,
        ];
    }
}
