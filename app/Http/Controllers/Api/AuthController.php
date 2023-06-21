<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\TryCatch;

class AuthController extends Controller
{
    /**
     * Registro de usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->save();
        $token = $user->createToken(name:'SPA TOKEN')->plainTextToken;
        return response()->json(['message' => 'Usuario registrado correctamente', 'token' => $token]);
    }

    /**
     * Inicio de sesión del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {

            return response()->json(['message' => 'auth error', 'status' => False]);
        }

        $user = $request->user();
        $token = $user->createToken('SPA Token')->plainTextToken;

        return response()->json(['message' => 'Success', 'status' => True, 'token' => $token]);
    }

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
            return response()->json(['message' => 'Sesión cerrada correctamente','status'=>true]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'la sesion no existe','status'=>false]);
        }

    }
    public function whoiam(Request $request)
    {
        dd($request);
    }
}
