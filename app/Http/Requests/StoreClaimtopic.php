<?php

namespace App\Http\Requests;

use App\Claimtopic;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClaimtopic extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Claimtopic::class);
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
                Rule::unique('claimtopics')->where(function ($query) {
                    return $query
                        ->where('claimtopics.topic_id', $this->topic_id)
                        ->where('claimtopics.claim_id', $this->claim->id);
                }),
            ],
        ];
    }

    public function messages () {
        return [
            'topic_id.unique' => 'This topic is already associated with this claim.',
        ];
    }
}
