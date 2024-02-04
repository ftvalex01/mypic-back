<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;

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
            'name' => ['sometimes', 'string'],
            'password' => ['sometimes', 'confirmed', Password::defaults()],
            'birth_date' => ['sometimes', 'date'],
            'bio' => ['nullable', 'string'],
            'profile_picture' => ['nullable', 'string'],
            'available_pines' => ['sometimes', 'integer'],
            'accumulated_points' => ['sometimes', 'integer'],
        ];
    }
}
