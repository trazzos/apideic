<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PerfilController extends Controller
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
