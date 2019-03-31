<?php

namespace App\Http\Requests;

use App\Topic;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreTopic extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Topic::class);
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
                new \App\Rules\UniqueLowerCase('topics', 'name', $this->topic),
            ],
            'text' => 'sometimes|string',
        ];
    }
}
