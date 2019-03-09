<?php

namespace App\Http\Requests;

use App\Allowedquestionside;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

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
            'side_id' => 'required_without:side_id_list',
            'side_id_list' => 'required_without:side_id|array',
        ];
    }
}
