<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => ['string', 'email', 'max:255', Rule::unique('users')->ignore($this->user()->id)],
            'profile_image' => ['nullable', 'image', 'max:2048'],
            'department' => ['nullable', 'string', 'max:100'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
