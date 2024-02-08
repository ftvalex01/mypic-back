<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
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
          
            'text' => ['required', 'string'],
            'comment_date' => ['required', 'date'], // Asegúrate de validar como fecha
            //'post_id' => ['required', 'integer', 'exists:posts,id'], // Añade esto para asegurar que el post exista
        ];
    }
    
}
