<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'payment_type' => ['nullable', 'string', 'max:100'],
            'nif' => ['nullable','integer', 'digits:9'],
            'photo_file' => 'sometimes|image|max:4096',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('payment_ref', 'regex:/^\d{16}$/', function ($input) {
            return $input->payment_type === 'VISA';
        });

        $validator->sometimes('payment_ref', 'email', function ($input) {
            return $input->payment_type === 'PAYPAL';
        });

        $validator->sometimes('payment_ref', 'regex:/^\d{9}$/', function ($input) {
            return $input->payment_type === 'MBWAY';
        });
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'payment_ref.regex' => 'The payment reference format is invalid for the selected payment type.',
            'payment_type.in' => 'The selected payment type is invalid.',
            'nif.required' => 'The NIF field is required.',
            'nif.integer' => 'The NIF must be an integer.',
            'nif.digits' => 'The NIF must be exactly 9 digits.',
        ];
    }
}
