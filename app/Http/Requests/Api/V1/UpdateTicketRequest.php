<?php

namespace App\Http\Requests\Api\V1;


class UpdateTicketRequest extends BaseTicketRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'data.attributes.title' => 'sometimes|string',
            'data.attributes.description' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|in:Active,Hold,Completed,Canceled',
            'data.relationships.author.data.id' => 'prohibited',
        ];

        if ($this->user()->tokenCan("ticket:update")) {
            $rules['data.relationships.author.data.id'] = 'sometimes|integer|exists:users,id';
        }
        return $rules;
    }
}
