<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class PerfilController extends BaseController
{
    /**
     *
     */
    public function __construct()
    {

    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {

        return response()->json(['user' => $request->user()]);

    }
}
