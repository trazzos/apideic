<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;


use App\Models\TipoDocumento;
use App\Services\TipoDocumentoService;
use App\Http\Requests\TipoDocumento\TipoDocumentoPostRequest;
use App\Http\Requests\TipoDocumento\TipoDocumentoPutRequest;
class TipoDocumentoController extends BaseController
{
    public function __construct(
      private readonly TipoDocumentoService $tipoDocumentoService
    )
    {

        //$this->middleware('permission:tipo_documento')->only(['lista']);
        //$this->middleware('permission:tipo_documento.crear')->only(['create']);
        //$this->middleware('permission:tipo_documento.editar')->only(['update']);
        //$this->middleware('permission:tipo_documento.editar')->only(['delete']);

    }


    public function list()
    {
        return $this->tipoDocumentoService->lista();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(TipoDocumentoPostRequest $request)
    {
        return $this->tipoDocumentoService->crear($request->validated());
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TipoDocumento $tiposDocumento, TipoDocumentoPutRequest $request)
    {
        return $this->tipoDocumentoService->actualizar($tiposDocumento->id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(TipoDocumento $tiposDocumento)
    {
        return $this->tipoDocumentoService->eliminar($tiposDocumento->id);
    }
}
