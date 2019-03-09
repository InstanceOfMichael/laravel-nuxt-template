<?php

namespace App\Http\Requests;

use App\Allowedquestionside;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAllowedquestionside extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->allowedquestionside);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
