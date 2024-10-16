<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'RegisterRequestBody',
    type: 'object',
    properties: [
        new OAT\Property(
            property: 'name',
            type: 'string',
            example: 'John Dow',
        ),
        new OAT\Property(
            property: 'email',
            type: 'string',
            example: 'user@mail.com',
        ),
        new OAT\Property(
            property: 'password',
            type: 'string',
            example: 'strongPassword_123'
        ),
        new OAT\Property(
            property: 'password_confirmation',
            type: 'string',
            example: 'strongPassword_123'
        ),
    ],
    required: [
        'name',
        'email',
        'password',
        'password_confirmation',
    ],
)]
class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users',
            'password' => 'required|confirmed'
        ];
    }

    public function getData(): array
    {
        $data = $this->validated();
        $data['password'] = Hash::make($data['password']);

        return $data;
    }
}
