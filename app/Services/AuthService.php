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
      
      // Cargar permisos del usuario
      $permissions = $user->getAllPermissions()->pluck('name')->toArray();
      $roles = $user->getRoleNames();

      return response()->json(
          [
              'message' => 'Login exitoso',
              'user' => [
                  'name' => $user->name,
                  'email' => $user->email,
              ],
              'roles' => $roles,
              'permissions' => $permissions,
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
      $permissions = $user->getAllPermissions()->pluck('name')->toArray();
      $roles = $user->getRoleNames();

      return response()->json(
          [
              'user' => [
                  'name' => $user->name,
                  'email' => $user->email,
              ],
              'roles' => $roles,
              'permissions' => $permissions,
          ],
          200
      );
    }
}
