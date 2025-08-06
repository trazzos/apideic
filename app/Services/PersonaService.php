<?php

namespace App\Services;

use App\Services\Traits\Searchable;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Eloquent\PersonaRepository;
use App\Dtos\Persona\CreatePersonaDto;
use App\Dtos\Persona\UpdatePersonaDto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Actions\Fortify\CreateNewUser;

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
}
