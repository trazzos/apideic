<?php

namespace App\Services;

use App\Services\Traits\Searchable;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Eloquent\PersonaRepository;
use App\Dtos\Persona\CreatePersonaDto;
use App\Dtos\Persona\UpdatePersonaDto;
use App\Dtos\Persona\ActualizarCuentaDto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Resources\Persona\CuentaResource;

class PersonaService extends BaseService {

    use Searchable;

    /**
     * @param PersonaRepository $personaRepository
     */
    public function __construct(
        private readonly PersonaRepository $personaRepository,
        private readonly CreateNewUser $createNewUser
    )
    {

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
            'departamento_id' => $createPersonaDto->departamentoId,
            'nombre' => $createPersonaDto->nombre,
            'apellido_paterno' => $createPersonaDto->apellidoPaterno,
            'apellido_materno' => $createPersonaDto->apellidoMaterno,
            'responsable_departamento' => $createPersonaDto->responsableDepartamento,
            'url_fotografia' => $urlFotografia,
        ];

        $nuevaPersonaResource = null;
        DB::transaction(function() use ($data, $createPersonaDto, &$nuevaPersonaResource){
            $nuevaPersonaResource =  parent::create($data);
            $nuevaPersona = $nuevaPersonaResource->resource;

            if ($createPersonaDto->email && $createPersonaDto->passsword) {
                $dataUser  = [
                    'name' => $createPersonaDto->nombre." ".$createPersonaDto->apellidoPaterno. " ". $createPersonaDto->apellidoMaterno,
                    'email' => $createPersonaDto->email,
                    'password' => $createPersonaDto->passsword,
                    'password_confirmation' => $createPersonaDto->passsword,
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
            'departamento_id' => $updatePersonaDto->departamentoId,
            'nombre' => $updatePersonaDto->nombre,
            'apellido_paterno' => $updatePersonaDto->apellidoPaterno,
            'apellido_materno' => $updatePersonaDto->apellidoMaterno,
            'responsable_departamento' => $updatePersonaDto->responsableDepartamento,
        ];

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
     * Buscar personas con criterios específicos.
     */
    public function searchPersonas(\Illuminate\Http\Request $request): \Illuminate\Http\Resources\Json\ResourceCollection
    {
        $criteria = $this->createSearchCriteriaFromRequest($request);
        
        // Establecer campos de búsqueda específicos para personas
        $criteria->setSearchFields([
            'nombre',
            'apellido', 
            'email',
            'telefono',
            'departamento.nombre' // Buscar también en el nombre del departamento
        ]);

        return $this->searchWithPagination($criteria, $request->get('per_page', 15));
    }

    /**
     * Búsqueda rápida de personas por nombre o email.
     */
    public function quickSearchPersonas(string $term): \Illuminate\Http\Resources\Json\ResourceCollection
    {
        return $this->quickSearch($term, ['nombre', 'apellido', 'email']);
    }

    public function infoCuenta(int $personaId): JsonResource
    {
        $persona = $this->repository->findById($personaId);
        
        if (!$persona) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Persona no encontrada');
        }

        // Cargar el usuario asociado si existe
        $persona->load('user');
        // si no hay usuario, retornar un recurso vacío
        if (!$persona->user) {
            return new CuentaResource([]);
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
                
                $persona->load('user');
            } else {
                // Si tiene usuario, actualizar datos existentes
                

                // Actualizar datos del usuario
                $updateData = $dto->toUserUpdateArray();
                if (!empty($updateData)) {
                    $user = $this->repository->updateAndReturn($user->id, $updateData);
                    // Refrescar el modelo para obtener los datos actualizados
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
        $this->repository->updateAndReturn($user->id, [
            'active' => false,
            'email_verified_at' => null
        ]);

        return [
            'message' => 'Cuenta desactivada exitosamente',
            'persona_id' => $personaId,
        ];
    }
}
