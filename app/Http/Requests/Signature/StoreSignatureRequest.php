<?php

namespace App\Http\Requests\Signature;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreSignatureRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'regex:/^[A-z0-9_\-\s]+$/',
                'min:5',
                'max:255',
            ],
        ];
    }

    public function getData(): array
    {
        $data = $this->validated();
        $data['sign'] = Str::uuid7();

        return $data;
    }
}
