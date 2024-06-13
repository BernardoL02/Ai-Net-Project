<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Http\FormRequest;

class CartConfirmationFormRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user()?->id)],
            'payment_type' => ['required', 'string', Rule::in(['VISA', 'PAYPAL', 'MBWAY'])],
            'payment_ref' => ['required', 'string', 'max:100'],
            'nif' => ['required', 'integer', 'digits:9'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
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
            'payment_type.required' => 'The payment type field is required.',
            'payment_type.in' => 'The selected payment type is invalid.',
            'payment_ref.required' => 'The payment reference field is required.',
            'payment_ref.regex' => 'The payment reference format is invalid for the selected payment type.',
            'nif.required' => 'The NIF field is required.',
            'nif.integer' => 'The NIF must be an integer.',
            'nif.digits' => 'The NIF must be exactly 9 digits.',
        ];
    }
}
