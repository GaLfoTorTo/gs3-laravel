<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|unique:usuarios',
            'password' => 'required'
        ];
    }

    public function messages(){
        return [
            'name.required' => 'O nome do usuário é obrigatório!',
            'email.required' => 'O email do usuário é obrigatório!',
            'email.unique' => 'Email já está em uso!',
            'password.required' => 'A senha do usuário é obrigatória!',
        ];
    }
}
