<?php

namespace App\Components\Notification\Infrastructure\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class MarkAsReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notification_uuid' => ['required', 'string', 'exists:notifications,uuid'],
        ];
    }

    public function messages(): array
    {
        return [
            'notification_uuid.required' => 'Notification UUID is required',
            'notification_uuid.exists' => 'Notification not found',
        ];
    }
}
