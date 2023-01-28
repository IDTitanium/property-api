<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'properties' => 'file|sometimes|mimes:csv',
            'address' => [Rule::requiredIf( fn() => !request()->hasFile('properties') ), 'array'],
            'address.line_1' => [Rule::requiredIf( fn() => !request()->hasFile('properties') ), 'string'],
            'address.line_2' => [Rule::requiredIf( fn() => !request()->hasFile('properties') ), 'string'],
            'address.postcode' => [Rule::requiredIf( fn() => !request()->hasFile('properties') ), 'numeric']
        ];
    }
}
