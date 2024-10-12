<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'LoginRequestBody',
    type: 'object',
    properties: [
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
    ],
    required: [
        'email',
        'password',
    ],
)]
class LoginRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
            ],
        ];
    }
}
