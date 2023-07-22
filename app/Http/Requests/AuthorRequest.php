<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'source' => ['required'],
            'name' => ['required'],
            'title' => ['required'],
            'description' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
