<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyUpdateRequest extends FormRequest
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
        $nit = $this->route('nit');

        return [
            'name' => 'sometimes|required|string|max:100',
            'address' => 'sometimes|required|string|max:200',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|max:100',
            'status' => 'sometimes|required|string|in:active,inactive',
        ];
    }
}