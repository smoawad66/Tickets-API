<?php

namespace App\Http\Requests\Api\V1;


class ReplaceTicketRequest extends BaseTicketRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|in:Active,Hold,Completed,Canceled',
            'data.relationships.author.data.id' => 'required|integer|exists:users,id',
        ];
    }
}
