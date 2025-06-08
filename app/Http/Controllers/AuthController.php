<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Tarifa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $redirectTo = $user->rol === 'administrador' 
                ? route('inicio')  
                : route('interfazUsuario.peliculas');

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'redirectTo' => $redirectTo,
            ]);
        }

        return response()->json([
            'errors' => [
                'email' => 'Las credenciales no coinciden con nuestros registros.'
            ]
        ], 422);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'dni' => [
                'required',
                'string',
                'unique:clientes',
                'regex:/^[0-9]{8}[A-Za-z]$/',
                'size:9',
            ],
            'direccion' => 'required|string|max:255',
            'telefono' => [
                'required',
                'string',
                'size:9',
                'regex:/^[0-9]+$/',
            ],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.unique' => 'Este DNI ya está registrado.',
            'dni.regex' => 'El DNI debe tener 8 números seguidos de una letra (ejemplo: 12345678A).',
            'dni.size' => 'El DNI debe tener exactamente 9 caracteres.',
            'direccion.required' => 'La dirección es obligatoria.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
            'telefono.size' => 'El teléfono debe tener exactamente 9 dígitos.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'rol' => 'usuario',
                    'dni' => $request->dni,
                ]);

                $tarifaStandard = Tarifa::where('nombre', 'Estándar')->first();

                Cliente::create([
                    'dni' => $request->dni,
                    'nombre' => $request->name,
                    'direccion' => $request->direccion,
                    'telefono' => $request->telefono,
                    'email' => $request->email,
                    'id_tarifa' => $tarifaStandard ? $tarifaStandard->id_tarifa : null,
                    'user_id' => $user->id,
                ]);
            });

            return redirect()->route('login');
        } catch (\Exception $e) {
            return back()->withErrors(['register' => 'Error al registrar usuario y cliente: ' . $e->getMessage()])->withInput();
        }
    }

}
