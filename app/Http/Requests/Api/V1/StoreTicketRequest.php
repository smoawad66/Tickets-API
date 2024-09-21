<?php

namespace App\Http\Requests\Api\V1;


class StoreTicketRequest extends BaseTicketRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|in:Active,Hold,Completed,Canceled',
            'data.relationships.author.data.id' => ['required', 'integer', 'size:' . $this->user()->id],
        ];
        if ($this->routeIs('tickets.store')) {
            if ($this->user()->tokenCan('ticket:create')) {
                $rules['data.relationships.author.data.id'][2] ='exists:users,id';
            }
        } else {
            $rules['data.relationships.author.data.id'] = 'prohibited';
        }

        return $rules;
    }
}
