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
use App\Events\UserFollowed;
use App\Models\Notification;

class UserController extends Controller
{


    public function index(Request $request)
    {
        $users = User::all();

        return new UserCollection($users);
    }

    public function checkUsernameAvailability($username)
    {   
        Log::info($username);
        $userExists = User::where('username', $username)->exists();
        Log::info($userExists);
        if ($userExists) {
            return response()->json(['message' => 'Username is already taken'], Response::HTTP_CONFLICT);
        }
        return response()->json(['message' => 'Username is available'], Response::HTTP_OK);
    }
    
    // Método para cargar el perfil de usuario
    public function getUserByUsername($username)
    {
        $user = User::where('username', $username)->first();
    
        if (!$user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($user);
    }
    
    public function store(Request $request)
    {
        try {
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
                'is_2fa_enabled' =>$request->enable2FA,
            ]);

            event(new Registered($user));

            Auth::login($user);

            return response()->noContent();
        } catch (\Exception $e) {
            // Log de errores y datos
            Log::error('Error durante el registro: ' . $e->getMessage());
            Log::info('Datos recibidos:', $request->all());

            return response()->json(['error' => 'An unexpected error occurred during registration. Please try again.'], 422);
        }
    }
    public function toggleFollow(Request $request, $userId)
{
    $userToFollow = User::find($userId);
    if (!$userToFollow) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $currentUser = auth()->user();

    // Verificar si el usuario actual ya sigue al usuario objetivo
    if ($currentUser->following()->where('user_id', $userId)->exists()) {
        // Ya está siguiendo al usuario, procede a dejar de seguir
        $currentUser->following()->detach($userId);
        return response()->json(['isFollowing' => false]);
    } else if ($userToFollow->is_private) {
        // El perfil es privado, manejar solicitud de seguimiento
        $existingNotification = Notification::where([
            'user_id' => $userId,
            'related_id' => $currentUser->id,
            'type' => 'follow_request',
        ])->first();

        if ($existingNotification) {
            // Ya existe una solicitud de seguimiento pendiente, proceder a cancelarla
            $existingNotification->delete(); // Eliminar la solicitud existente
            return response()->json(['message' => 'Follow request cancelled', 'isRequested' => false]);
        } else {
            // Crear una nueva solicitud de seguimiento
            Notification::create([
                'user_id' => $userId,
                'type' => 'follow_request',
                'related_id' => $currentUser->id,
                'read' => false,
                'notification_date' => now(),
            ]);

            return response()->json(['message' => 'Follow request sent', 'isRequested' => true]);
        }
    } else {
        // El perfil no es privado, proceder a seguir al usuario
        $currentUser->following()->attach($userId);
        return response()->json(['isFollowing' => true]);
    }
}
    
public function rejectFollowRequest(Request $request, $notificationId)
{
    $notification = Notification::find($notificationId);

    if (!$notification || $notification->type !== 'follow_request') {
        return response()->json(['message' => 'Invalid request'], 404);
    }

    // Asegurarse de que el usuario autenticado es el destinatario de la solicitud de seguimiento
    if ($notification->user_id !== auth()->id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Eliminar la notificación
    $notification->delete();

    return response()->json(['message' => 'Follow request rejected']);
}

public function acceptFollowRequest(Request $request, $notificationId)
{
    $notification = Notification::find($notificationId);

    if (!$notification || $notification->type !== 'follow_request') {
        return response()->json(['message' => 'Invalid request'], 404);
    }

    // Asegurarse de que el usuario autenticado es el destinatario de la solicitud de seguimiento
    if ($notification->user_id !== auth()->id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Establecer la relación de seguimiento
    $followerId = $notification->related_id;
    $user = User::find(auth()->id());
    $user->followers()->attach($followerId);

    // Marcar la notificación como leída o eliminarla
    $notification->delete();

    return response()->json(['message' => 'Follow request accepted']);
}

public function updatePrivacy(Request $request, $userId)
{
    $user = auth()->user();

    // Asegúrate de que el usuario solo pueda actualizar su propia privacidad
    if ($user->id != $userId) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Validar la entrada
    $data = $request->validate([
        'isPrivate' => 'required|boolean',
    ]);

    // Actualizar la privacidad del perfil
    $user->is_private = $data['isPrivate'];
    $user->save();

    return response()->json(['isPrivate' => $user->is_private]);
}


    public function follows()
    {
        $users = User::with('following')->get();
        return response()->json($users);
    }


    public function show(Request $request, User $user)
    {
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user)
    {

        Log::info('Datos del Request:', $request->all());
        $user->update($request->validated());
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
            $user->save();
        }
        return new UserResource($user);
    }

    public function login(LoginRequest $request)
    {
        // Autentica al usuario
        $request->authenticate();
    
        // Regenera la sesión
        $request->session()->regenerate();
    
        // Obtiene el usuario autenticado
        $user = $request->user();
    
        // Verifica si el usuario tiene habilitada la verificación de dos factores
        if ($user->is_2fa_enabled) {
            return new UserResource($user);
            // Si la verificación de dos factores está habilitada, devuelve una respuesta indicando que se requiere la verificación de dos factores
           /*  return response()->json(['requires_2fa_verification' => true]); */

        }
    
        // Si la verificación de dos factores no está habilitada, devuelve una respuesta JSON con los datos del usuario
        return new UserResource($user);
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

    public function followData($userId)
{
    $user = User::withCount(['followers', 'following'])->find($userId);
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Obtiene el usuario actual autenticado
    $currentUser = auth()->user();
    // Verifica si el usuario actual está siguiendo al usuario de destino
    $isFollowing = false;
    if ($currentUser) {
        $isFollowing = $currentUser->following()->where('users.id', $userId)->exists();
    }

    return response()->json([
        'followersCount' => $user->followers_count,
        'followingCount' => $user->following_count,
        'isFollowing' => $isFollowing,
    ]);
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
