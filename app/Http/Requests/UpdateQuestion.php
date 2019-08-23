<?php

namespace App\Http\Requests;

use App\Question;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestion extends FormRequest
{
    // public function __construct (\App\Question $question) {
    // }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->question);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => [
                'sometimes',
                'string',
                'min:9',
                new \App\Rules\EndsWith('?'),
            ],
            'text' => 'sometimes|string',
            'sides_type' => 'sometimes|in:0,1,2',
        ];
    }
}