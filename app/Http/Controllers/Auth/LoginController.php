<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginPostRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class LoginController extends Controller
{

    public function __construct(private readonly AuthService $authService)
    {

    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginPostRequest $request)
    {
       $request->validated();
       return $this->authService->login($request->only('email', 'password'));
    }
}
