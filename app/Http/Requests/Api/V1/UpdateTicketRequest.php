<?php

namespace App\Http\Requests\Api\V1;


class UpdateTicketRequest extends BaseTicketRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

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
        ];

        if ($this->routeIs('tickets.update')) {
            if ($this->user()->tokenCan("ticket:own:update")) {
                $rules['data.relationships.author.data.id'] = 'prohibited';
            } else {
                $rules['data.relationships.author.data.id'] = 'sometimes|integer|exists:users,id';
            }
        } else {
            $rules['data.relationships.author.data.id'] = 'prohibited';
        }
        return $rules;
    }
}
