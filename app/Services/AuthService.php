<?php

namespace App\Services;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Support\Facades\Hash;

class AuthService
{
  public function login($request)
  {
      $data = $request->only('email', 'password');
      
      $user = User::where('email', $data['email'])->first();
      if (!$user || !Hash::check($data['password'], $user->password)) {
        throw new AuthenticationException('Credenciales invalidas');
      }

      $user->load('owner');
      
      // Create API token
      $token = $user->createToken('API Token')->plainTextToken;

      // Cargar permisos del usuario
      $permissions = $user->getAllPermissions()->pluck('name')->toArray();
      $roles = $user->getRoleNames();

      return response()->json(
          [
              'message' => 'Login exitoso',
              'user' => [
                  'name' => $user->name,
                  'email' => $user->email,
                  'url_img_profile' => $user->owner->public_url_fotografia ?? null
              ],
              'roles' => $roles,
              'permissions' => $permissions,
              'token' => $token,
          ],
          200
      );
  }

  public function profile()
  {
      $user = Auth::user();
      if (!$user) {
          throw new AuthenticationException('Usuario no autenticado');
      }

      $user->load('owner');
      
      $permissions = $user->getAllPermissions()->pluck('name')->toArray();
      $roles = $user->getRoleNames();

      return response()->json(
          [
              'user' => [
                  'name' => $user->name,
                  'email' => $user->email,
                  'url_img_profile' => $user->owner->public_url_fotografia ?? null
              ],
              'roles' => $roles,
              'permissions' => $permissions,
          ],
          200
      );
    }
}
