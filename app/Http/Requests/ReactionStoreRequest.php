<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReactionStoreRequest extends FormRequest
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
            'reactable_id' => 'required|integer',
            'reactable_type' => 'required|in:Post,Comment',
          
        ];
    }
}
