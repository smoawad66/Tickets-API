<?php

namespace App\Http\Requests\Api\V1;


class StoreTicketRequest extends BaseTicketRequest
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
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|in:Active,Hold,Completed,Canceled',
        ];
        if ($this->routeIs('tickets.store')) {
            $rules['data.relationships.author.data.id'] = 'required|integer|' . (
                $this->user()->tokenCan('ticket:own:create') ?
                "size:{$this->user()->id}" :
                'exists:users,id'
            );
        } else {
            $rules['data.relationships.author.data.id'] = 'prohibited';
        }

        return $rules;
    }
}
