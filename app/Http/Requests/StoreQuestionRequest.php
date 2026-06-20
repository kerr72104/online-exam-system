<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'teacher';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body'           => 'required|string',
            'subject_id'     => 'required|exists:subjects,id',
            'correct_index'  => 'required|integer|min:0|max:3', // Validates the radio button
            'choices'        => 'required|array|size:4',
            'choices.*.body' => 'required|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'correct_index.required' => 'Exactly one correct answer must be selected.',
        ];
    }
}
