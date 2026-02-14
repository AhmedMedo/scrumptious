<?php

namespace App\Components\Notification\Infrastructure\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class GetNotificationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'unread_only' => ['nullable', 'boolean'],
        ];
    }
}
