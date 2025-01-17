<?php

namespace App\Http\Requests\User;

use App\Enums\RoleEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UserUpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phoneNumber' => [
                'required',
                'string',
                Rule::unique('users', 'phone_number')->ignore($this->user),
            ],
            'address' => ['required', 'string', 'max:500'],
            'role' => ['required', new Enum(RoleEnum::class)],
            'companyId' => ['required', 'uuid', 'exists:companies,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'password_confirmation' => $this->input('passwordConfirmation'),
        ]);
    }

    public function failedValidation(Validator $validator): array
    {
        throw new HttpResponseException(response()->json([
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 403));
    }
}
