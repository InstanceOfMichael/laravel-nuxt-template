<?php

namespace App\Http\Requests;

use App\Definition;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDefinition extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->definition);
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
                'sometimes',
                'string',
                'min:3',
                new \App\Rules\UniqueLowerCase('definitions', 'name', $this->definition),
                new \App\Rules\OnlyCaseChange($this->definition),
            ],
            'text' => 'sometimes|string',
        ];
    }
}
