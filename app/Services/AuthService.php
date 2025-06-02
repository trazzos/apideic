<?php

namespace App\Services;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthService
{
  public function login($data)
  {
      if (!Auth::attempt($data)) {
        throw new AuthenticationException('Credenciales invalidas');
      }
      $user = User::where('email', $data['email'])->firstOrFail();
      $token = $user->createToken('auth_token')->plainTextToken;

      return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
  }
}
