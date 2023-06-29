<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    /**
     * Registro de usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * Cierre de sesión del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            //code...
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Sesión cerrada correctamente', 'status' => true]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'la sesion no existe', 'status' => false]);
        }
    }
    public function whoiam(Request $request)
    {
        return response()->json($request->user()->only(['name', 'email', 'profile_photo_path', "email_verified_at"]));
    }
}
