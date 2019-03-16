<?php

namespace App\Http\Requests;

use App\Answer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnswer extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Answer::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question_id' => 'required|exists:questions,id',
            'claim_id' => [
                'required',
                'exists:claims,id',
                Rule::unique('answers')->where(function ($query) {
                    return $query
                        ->where('answers.claim_id', $this->claim_id)
                        ->where('answers.question_id', $this->question_id);
                }),
            ],
        ];
    }

    public function messages () {
        return [
            'claim_id.unique' => 'This claim is already associated with this question.',
        ];
    }
}
