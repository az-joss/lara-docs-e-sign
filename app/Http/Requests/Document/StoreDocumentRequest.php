<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
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
            'document_file' => [
                'required',
                'mimetypes:application/pdf',
                'extensions:pdf',
                'file',
                'max:512',
            ],
            'file_name' => [
                'required',
                'string',
                'regex:/^[A-z0-9_-]+$/',
                'min:5',
                'max:255',
            ],
        ];
    }
}
