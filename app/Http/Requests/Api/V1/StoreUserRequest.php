<?php

namespace App\Http\Requests\Api\V1;


class StoreUserRequest extends BaseUserRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.name' => 'required|string',
            'data.attributes.email' => 'required|email|unique:users,email',
            'data.attributes.password' => 'required|string|min:8',
            'data.attributes.isManager' => 'sometimes|boolean',
        ];
    }
}
