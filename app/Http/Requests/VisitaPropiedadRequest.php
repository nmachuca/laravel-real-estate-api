<?php

namespace App\Http\Requests;

use App\Enums\VisitaSortFields;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisitaPropiedadRequest extends FormRequest
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
            "pagination" => 'required|bool',
            "elements_per_page" => 'required_if:pagination,true|int|between:1,100',
            "filters" => 'required|bool',
            "propiedad_id" => 'sometimes|numeric|exists:propiedades,id',
            "persona_id" => 'sometimes|numeric|exists:personas,id',
            "fecha_min" => 'sometimes|date',
            "fecha_max" => 'sometimes|date',
            "sort" => ['sometimes','string', Rule::enum(VisitaSortFields::class)],
            "sort_asc" => 'required_with:sort|bool',
        ];
    }
}
