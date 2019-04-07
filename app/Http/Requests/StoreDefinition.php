<?php

namespace App\Http\Requests;

use App\Definition;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreDefinition extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Definition::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                new \App\Rules\UniqueLowerCase('definitions', 'name', $this->definition),
            ],
            'text' => 'sometimes|string',
        ];
    }
}
