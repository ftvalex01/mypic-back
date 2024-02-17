<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        // Genera un valor para 'username'. Aquí usamos el email local part si está disponible.
        // Asegúrate de que este valor sea único en tu base de datos o ajusta la lógica según sea necesario.
        $username = $this->generateUsername($googleUser->email);

        $user = User::updateOrCreate(
            ['google_id' => $googleUser->id], // Condición para buscar el usuario.
            [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'username' => $username, // Proporciona el valor de 'username' aquí.
                'google_id' => $googleUser->id,
                'google_token' => $googleUser->token,
                // Asumiendo que 'password' es opcional en tu esquema de base de datos.
                // Si no, también deberías proporcionar un valor por defecto o hacerlo nullable.
            ]
        );

        Auth::login($user);

        return redirect('http://localhost:5173/');
    }
    protected function generateUsername($email)
    {
        $username = explode('@', $email)[0];

        // Verifica si el nombre de usuario generado ya existe y ajusta si es necesario.
        // Esta es una lógica simple para demostración. Puede necesitar ser más compleja para asegurar la unicidad.
        return $username;
    }
}
