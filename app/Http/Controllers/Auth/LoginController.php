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
       return $this->authService->login($request);
    }
}
