<?php

namespace App\Http\Requests\Api\V1;


class UpdateUserRequest extends BaseUserRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.name' => 'sometimes|string',
            'data.attributes.email' => 'sometimes|email|unique:users,email,' . $this->route('user')->id,
            'data.attributes.password' => 'sometimes|string|min:8',
            'data.attributes.isManager' => 'sometimes|boolean',
        ];
    }
}
