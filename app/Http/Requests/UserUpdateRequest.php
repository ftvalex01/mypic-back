<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'username' => ['required', 'string', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'password'],
            'birth_date' => ['required', 'date'],
            'register_date' => ['required'],
            'bio' => ['nullable', 'string'],
            'email_verified_at' => ['nullable'],
            'available_pines' => ['required', 'integer'],
            'profile_picture' => ['nullable', 'string'],
            'accumulated_points' => ['required', 'integer'],
        ];
    }
}
