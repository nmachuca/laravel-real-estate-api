<?php

namespace App\Http\Requests;

use App\Enums\PropiedadSortFields;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PropiedadRequest extends FormRequest
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
            "direccion" => 'sometimes|string',
            "ciudad" => 'sometimes|string',
            "precio_min" => 'sometimes|integer|min:1',
            "precio_max" => 'sometimes|integer|between:2,10000',
            "sort" => ['sometimes','string', Rule::enum(PropiedadSortFields::class)],
            "sort_asc" => 'required_with:sort|bool',
        ];
    }
}
