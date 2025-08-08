<?php

namespace App\Http\Controllers;

use App\Services\PermisoService;
use Illuminate\Http\Resources\Json\JsonResource;

class PermisoController extends Controller
{

    public function __construct(private readonly PermisoService $permisoService)
    {

    }
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return JsonResource::make($this->permisoService->getPermissionTreeOptimized());
    }
}
