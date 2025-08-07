<?php

namespace App\Http\Controllers;

use App\Dtos\Persona\CreatePersonaDto;
use App\Dtos\Persona\UpdatePersonaDto;
use App\Dtos\Persona\ActualizarCuentaDto;
use App\Http\Requests\Persona\PersonaPostRequest;
use App\Http\Requests\Persona\PersonaPatchRequest;
use App\Http\Requests\Persona\ActualizarCuentaRequest;
use App\Models\Persona;
use App\Models\User;
use App\Services\PersonaService;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
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
        return $this->personaService->list();
    }


    /**
     * @param PersonaPostRequest $request
     * @return JsonResource
     */
    public function create(PersonaPostRequest $request): JsonResource
    {
        $createPersonaDto = CreatePersonaDto::fromRequest($request);
        $fotografiaFile = $request->file('fotografia');
        return $this->personaService->createFromDto($createPersonaDto, $fotografiaFile);
    }


    /**
     * @param Persona $persona
     * @param PersonaPatchRequest $request
     * @return JsonResource
     */
    public function update(Persona $persona, PersonaPatchRequest $request):JsonResource
    {
        $updatePersonaDto = UpdatePersonaDto::fromRequest($request);
        $fotografiaFile = $request->file('fotografia');
        return $this->personaService->updateFromDto($updatePersonaDto, $persona->id, $fotografiaFile);
    }

    /**
     * @param Persona $persona
     * @return JsonResource
     */
    public function show(Persona $persona): JsonResource
    {
        return $this->personaService->findById($persona->id);
    }

    /**
     * @param Persona $persona
     * @return Response
     */
    public function delete(Persona $persona):Response
    {
        return $this->personaService->delete($persona->id);
    }

    /**
     * Obtener información de la cuenta (email/estado) de una persona.
     *
     * @param Persona $persona
     * @return JsonResource | JsonResponse
     * @throws \Exception
     */
    public function infoCuenta(Persona $persona): JsonResource | JsonResponse
    {
        try {
            return $this->personaService->infoCuenta($persona->id);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Actualizar o crear cuenta (email/contraseña) de una persona.
     *
     * @param Persona $persona
     * @param ActualizarCuentaRequest $request
     * @return JsonResource
     * @throws \Exception
     */
    public function actualizarCuenta(Persona $persona, ActualizarCuentaRequest $request)
    {
        try {
            $dto = ActualizarCuentaDto::fromRequest($request);
            return $this->personaService->actualizarCuenta($dto, $persona->id);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Desactivar cuenta de usuario de una persona.
     *
     * @param Persona $persona
     * @return JsonResponse
     * @throws \Exception
     */
    public function desactivarCuenta(Persona $persona): JsonResponse
    {
        try {
            $result = $this->personaService->desactivarCuenta($persona->id);
            return response()->json($result, 200);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
