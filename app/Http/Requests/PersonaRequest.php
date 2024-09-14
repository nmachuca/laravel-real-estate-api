<?php

namespace App\Http\Requests;

use App\Enums\PersonaSortFields;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonaRequest extends FormRequest
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
            "nombre" => 'sometimes|string',
            "email" => 'sometimes|string',
            "sort" => ['sometimes','string', Rule::enum(PersonaSortFields::class)],
            "sort_asc" => 'required_with:sort|bool',
        ];
    }
}
