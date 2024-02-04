<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rules;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{


    public function index(Request $request)
    {
        $users = User::all();

        return new UserCollection($users);
    }

    public function store(Request $request): Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'birth_date' => ['required', 'date'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birth_date' => $request->birth_date,
            'register_date' => now(),

        ]);
        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }

    public function show(Request $request, User $user)
    {
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        return new UserResource($user);
    }

    public function login(LoginRequest $request): Response
    {
        /* Log::info('Email: ' . $request->input('email'));
        Log::info('Password: ' . $request->input('password')); */
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }


     /**
    * Handle an incoming password reset link request.
    *
    * @throws \Illuminate\Validation\ValidationException
    */
   public function restore(Request $request): JsonResponse
   {
       $request->validate([
           'email' => ['required', 'email'],
       ]);

       $status = Password::sendResetLink(
           $request->only('email')
       );

       if ($status != Password::RESET_LINK_SENT) {
           throw ValidationException::withMessages([
               'email' => [__($status)],
           ]);
       }

       return response()->json(['status' => __($status)]);
   }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function newpassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

       
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return response()->json(['status' => __($status)]);
    }
    public function profile(Request $request)
    {

        $user = $request->user();


        return new UserResource($user);
    }
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
    
}
