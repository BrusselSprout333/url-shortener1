<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EncodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => 'required|url',
        ];
    }

    public function getUrl(): string
    {
        return trim($this->input('url'), '/');
    }
}
