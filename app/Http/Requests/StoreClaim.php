<?php

namespace App\Http\Requests;

use App\Claim;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreClaim extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Claim::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'text' => 'string',
        ];
    }
}
