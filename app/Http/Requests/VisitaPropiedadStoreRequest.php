<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisitaPropiedadStoreRequest extends FormRequest
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
            'persona_id' => 'required|numeric|exists:personas,id',
            'propiedad_id' => 'required|numeric|exists:propiedades,id',
            'fecha_visita' => 'required|date|date_format:Y-m-d|after:today',
            'comentarios' => 'nullable|string',
        ];
    }
}
