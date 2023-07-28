<?php

namespace App\Http\Requests;

use App\Services\NewsSource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'categories' => 'array',
            'categories.*' => 'integer|exists:main_categories,id',
            'authors' => 'array',
            'authors.*' => 'integer|exists:authors,id',
            'sources' => 'array',
            'sources.*' => ['string',Rule::in([NewsSource::NEW_YORK_TIMES, NewsSource::THE_GUARDIAN, NewsSource::NEWS_API_ORG])],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
