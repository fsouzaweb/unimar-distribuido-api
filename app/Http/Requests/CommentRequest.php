<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'post_id' => 'required|exists:posts,id',
            'body' => 'required|string|min:3',
        ];
    }

    public function messages(): array
    {
        return [
            'post_id.required' => 'O campo post é obrigatório.',
            'post_id.exists' => 'O post selecionado não existe.',
            'body.required' => 'O campo comentário é obrigatório.',
            'body.min' => 'O comentário deve ter pelo menos 3 caracteres.',
        ];
    }
}
