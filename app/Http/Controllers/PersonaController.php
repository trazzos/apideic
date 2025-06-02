<?php

namespace App\Http\Controllers;

use App\Dtos\Persona\CreatePersonaDto;
use App\Dtos\Persona\UpdatePersonaDto;
use App\Http\Requests\Persona\PersonaPostRequest;
use App\Http\Requests\Persona\PersonaPutRequest;
use App\Models\Persona;
use App\Services\PersonaService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class PersonaController extends BaseController
{

    /**
     * @param PersonaService $personaService
     */
    public function __construct(
      private readonly PersonaService $personaService
    )
    {

        //$this->middleware('permission:persona')->only(['lista']);
        //$this->middleware('permission:persona.crear')->only(['store']);
        //$this->middleware('permission:persona.editar')->only(['update']);
        //$this->middleware('permission:persona.eliminar')->only(['eliminar']);
    }

    /**
     * @return ResourceCollection
     */
    public function list(): ResourceCollection
    {
        return $this->personaService->lista();
    }


    /**
     * @param PersonaPostRequest $request
     * @return JsonResource
     */
    public function create(PersonaPostRequest $request): JsonResource
    {
        $createPersonaDto = CreatePersonaDto::fromRequest($request);
        $fotografiaFile = $request->file('fotografia');
        return $this->personaService->crearDesdeDto($createPersonaDto, $fotografiaFile);
    }


    /**
     * @param Persona $persona
     * @param PersonaPutRequest $request
     * @return JsonResource
     */
    public function update(Persona $persona, PersonaPutRequest $request):JsonResource
    {
        $updatePersonaDto = UpdatePersonaDto::fromRequest($request);
        $fotografiaFile = $request->file('fotografia');
        return $this->personaService->actualizarDesdeDto($updatePersonaDto, $persona->id, $fotografiaFile);
    }

    /**
     * @param Persona $persona
     * @return Response
     */
    public function delete(Persona $persona):Response
    {
        return $this->personaService->eliminar($persona->id);
    }
}
