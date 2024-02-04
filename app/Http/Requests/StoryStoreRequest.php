<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoryStoreRequest extends FormRequest
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
            'content' => ['nullable', 'string'],
            'publish_date' => ['required'],
            'expiration_date' => ['required'],
        ];
    }
}
