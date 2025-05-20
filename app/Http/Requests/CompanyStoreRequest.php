<?php

namespace App\Http\Requests;

use App\Enums\CompanyStatus;
use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nit' => 'required|string|unique:companies,nit|max:20',
            'name' => 'required|string|max:200',
            'address' => 'required|string|max:200',
            'phone' => 'required|string|max:50',
            'status' => 'string|in:' . implode(',', array_column(CompanyStatus::cases(), 'value')), // no required
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.in' => 'The status must be one of the following: ' . implode(', ', array_column(CompanyStatus::cases(), 'value')),
        ];
    }
}