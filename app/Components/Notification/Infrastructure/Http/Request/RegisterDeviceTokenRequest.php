<?php

namespace App\Components\Notification\Infrastructure\Http\Request;

use App\Components\Notification\Data\Enums\DeviceTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterDeviceTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_token' => ['required', 'string'],
            'device_type' => ['required', 'string', Rule::in(DeviceTypeEnum::values())],
            'device_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'device_token.required' => 'Device token is required',
            'device_type.required' => 'Device type is required',
            'device_type.in' => 'Device type must be one of: ios, android, web',
        ];
    }
}
