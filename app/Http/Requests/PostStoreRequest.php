<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
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
            'title' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'publish_date' => ['required'],
            'life_time' => ['required', 'integer'],
            'permanent' => ['required'],
            'media_id' => ['required', 'integer'],
        ];
    }
}
