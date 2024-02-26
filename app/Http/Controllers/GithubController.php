<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleProviderCallback()
    {
        $githubUser = Socialite::driver('github')->user();
        $user = User::where('email', $githubUser->email)->first();

        if ($user) {
            // La cuenta existe, actualiza la información de GitHub.
            $user->update([
                'github_id' => $githubUser->id,
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
        } else {
            // La cuenta no existe, crea una nueva.
            $username = $this->generateUniqueUsername($githubUser->nickname, $githubUser->email);
            $user = User::create([
                'name' => $githubUser->name,
                'email' => $githubUser->email,
                'username' => $username,
                'github_id' => $githubUser->id,
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
        }

        Auth::login($user);

        return redirect('https://lucasreact.informaticamajada.es/');
    }

    protected function generateUsernameFromEmail($email)
    {
        return explode('@', $email)[0];
    }

    protected function generateUniqueUsername($username, $email)
    {
        $baseUsername = $username ?: explode('@', $email)[0];
        $newUsername = $baseUsername;
        $counter = 1;

        // Busca usuarios con usernames similares
        while (User::where('username', '=', $newUsername)->exists()) {
            // Añade o incrementa un número al final del username para hacerlo único
            $newUsername = $baseUsername . $counter++;
        }

        return $newUsername;
    }
}
