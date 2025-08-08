<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Services\AuthService;

class PerfilController extends BaseController
{
    /**
     *
     */
    public function __construct(private readonly AuthService $authService)
    {

    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(): \Illuminate\Http\JsonResponse
    {

        return $this->authService->profile();

    }
}
