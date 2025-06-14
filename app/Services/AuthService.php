<?php

namespace App\Services;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthService
{
  public function login($request)
  {
      $data = $request->only('email', 'password');
      if (!Auth::attempt($data)) {
        throw new AuthenticationException('Credenciales invalidas');
      }
      $request->session()->regenerate();

      $user = User::where('email', $data['email'])->firstOrFail();
     // $token = $user->createToken('auth_token')->plainTextToken;

      return response()->json(
          [
              'message' => 'Login exitoso',
              'user' => $user,
          ],
          200
      );
  }
}
