<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    // Obtener todos los dispositivos
    public function index()
    {
        $devices = Device::with('user')->get();
        return response()->json($devices);
    }

    // Obtener un dispositivo específico
    public function show($id)
    {
        $device = Device::with('user')->find($id);

        if (!$device) {
            return response()->json(['message' => 'Dispositivo no encontrado'], 404);
        }

        return response()->json($device);
    }

    // Crear un nuevo dispositivo
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'device_id' => 'required|string|unique:devices',
            'name' => 'required|string',
            'online' => 'required|string',
            'switchStatus' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $device = Device::create($request->all());

        return response()->json([
            'message' => 'Dispositivo creado correctamente',
            'device' => $device,
        ], 201);
    }

    // Actualizar un dispositivo existente
    public function update(Request $request, $id)
    {
        $device = Device::find($id);

        if (!$device) {
            return response()->json(['message' => 'Dispositivo no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'device_id' => 'sometimes|string|unique:devices,device_id,' . $id,
            'name' => 'sometimes|string',
            'online' => 'sometimes|string',
            'switchStatus' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $device->update($request->all());

        return response()->json([
            'message' => 'Dispositivo actualizado correctamente',
            'device' => $device,
        ]);
    }

    // Eliminar un dispositivo
    public function destroy($id)
    {
        $device = Device::find($id);

        if (!$device) {
            return response()->json(['message' => 'Dispositivo no encontrado'], 404);
        }

        $device->delete();

        return response()->json(['message' => 'Dispositivo eliminado correctamente']);
    }
}
