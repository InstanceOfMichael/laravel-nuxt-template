<?php

namespace App\Http\Requests;

use App\Allowedquestionside;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAllowedquestionside extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Allowedquestionside::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'side_id' => [
                'required_without:side_id_list',
                'exists:sides,id',
                Rule::unique('allowedquestionsides')->where(function ($query) {
                    return $query
                        ->where('allowedquestionsides.side_id', $this->side_id)
                        ->where('allowedquestionsides.question_id', $this->question->id);
                }),
            ],
            'side_id_list' => 'required_without:side_id|array',
        ];
    }

    public function messages () {
        return [
            'side_id.unique' => 'This side is already associated with this question.',
        ];
    }
}
