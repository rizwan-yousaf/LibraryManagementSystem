<?php

namespace App\Http\Requests\Book;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateBookRequest extends FormRequest
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
        $id = $this->route('id');
    
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('books')->ignore($id),
            ],
            'publication_date' => 'required|date',
            'author_ids' => 'required|array',
            'author_ids.*' => 'exists:authors,id',
        ];
    }

    public function messages()
    {
        return [
            'author_ids.*.exists' => 'The selected authors is invalid',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->wantsJson()) {
            $response = response()->json([
                'success' => false,
                'message' => $validator->messages()->first(),
                // 'errors' => $validator->errors()
            ], 422);
        } else {
            $response = redirect()
                ->route('guest.login')
                ->with('message', 'Ops! Some errors occurred')
                ->withErrors($validator);
        }

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
