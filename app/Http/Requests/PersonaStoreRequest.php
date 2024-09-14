<?php

namespace App\Http\Requests;

use App\Rules\ChileanNumber;
use Illuminate\Foundation\Http\FormRequest;

class PersonaStoreRequest extends FormRequest
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
            'nombre' => 'required|string|min:3',
            'email' => 'required|email|email:rfc,dns|unique:personas,email',
            'telefono' => ['required', 'string', new ChileanNumber()],
        ];
    }
}
