<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Obtener todos los usuarios
    public function index()
    {
        $users = User::with('devices')->get();
        return response()->json($users);
    }

    // Obtener un usuario específico
    public function show($id)
    {
        $user = User::with('devices')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user);
    }

    // Crear un nuevo usuario
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'cedula' => 'required|string|unique:users',
            'phone' => 'required|string',
            'email' => 'required|email|unique:users',
            'address' => 'required|string',
            'coordinates' => 'required|string',
            'deviceId' => 'required|string',
            'neighborhood' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create($request->all());

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'user' => $user,
        ], 201);
    }

    // Actualizar un usuario existente
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string',
            'cedula' => 'sometimes|string|unique:users,cedula,' . $id,
            'phone' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'address' => 'sometimes|string',
            'coordinates' => 'sometimes|string',
            'deviceId' => 'sometimes|string',
            'neighborhood' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user->update($request->all());

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'user' => $user,
        ]);
    }

    // Eliminar un usuario
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }
}
