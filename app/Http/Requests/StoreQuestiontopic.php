<?php

namespace App\Http\Requests;

use App\Questiontopic;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuestiontopic extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Questiontopic::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'topic_id' => [
                'required',
                'exists:topics,id',
                Rule::unique('questiontopics')->where(function ($query) {
                    return $query
                        ->where('questiontopics.topic_id', $this->topic_id)
                        ->where('questiontopics.question_id', $this->question->id);
                }),
            ],
        ];
    }

    public function messages () {
        return [
            'topic_id.unique' => 'This topic is already associated with this question.',
        ];
    }
}
