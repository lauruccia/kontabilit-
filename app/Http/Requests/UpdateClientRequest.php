<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage clients') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['company', 'person'])],
            'company_name' => ['nullable', 'string', 'max:255', 'required_if:type,company'],
            'contact_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('clients', 'email')->ignore($this->route('client'))],
            'phone' => ['nullable', 'string', 'max:50'],
            'vat_number' => ['nullable', 'string', 'max:32'],
            'tax_code' => ['nullable', 'string', 'max:32'],
            'pec' => ['nullable', 'email', 'max:255'],
            'sdi' => ['nullable', 'string', 'max:16'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'province' => ['nullable', 'string', 'size:2'],
            'postal_code' => ['nullable', 'string', 'max:12'],
            'internal_notes' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['lead', 'active', 'suspended', 'former'])],
        ];
    }
}
