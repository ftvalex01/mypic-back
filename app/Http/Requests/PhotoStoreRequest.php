<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhotoStoreRequest extends FormRequest
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
            'user_id' => ['required', 'integer'],
            'album_id' => ['required', 'integer'],
            'url' => ['required', 'string'],
            'title' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'upload_date' => ['required'],
        ];
    }
}
