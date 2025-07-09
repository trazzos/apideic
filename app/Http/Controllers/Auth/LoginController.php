<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests\Auth\LoginPostRequest;
use App\Services\AuthService;
class LoginController extends BaseController
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

        // Log the login attempt
        \Log::info('Login attempt', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
        ]);

        // Call the auth service to handle the login logic
       return $this->authService->login($request);
    }
}
