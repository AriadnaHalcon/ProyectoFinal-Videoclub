<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'dni' => 'required|string|unique:clientes',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
        ]);

        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'usuario',
        ]);

        // Obtener tarifa Estándar por defecto
        $tarifaStandard = \App\Models\Tarifa::where('nombre', 'Estándar')->first();

        // Crear cliente vinculado con users
        \App\Models\Cliente::create([
            'dni' => $request->dni,
            'nombre' => $request->name,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'id_tarifa' => $tarifaStandard ? $tarifaStandard->id_tarifa : null,
            'user_id' => $user->id, 
        ]);

        event(new Registered($user));

        return redirect()->route('login');
    }

}
