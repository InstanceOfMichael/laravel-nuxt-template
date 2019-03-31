<?php

namespace App\Http\Requests;

use App\Topic;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTopic extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->topic);
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
                new \App\Rules\UniqueLowerCase('topics', 'name', $this->topic),
                new \App\Rules\OnlyCaseChange($this->topic),
            ],
            'text' => 'sometimes|string',
        ];
    }
}
