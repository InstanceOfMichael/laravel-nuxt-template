<?php

namespace App\Http\Requests;

use App\Side;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreSide extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Side::class);
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
                'min:1',
                new \App\Rules\UniqueLowerCase('sides', 'name', $this->side),
            ],
            'text' => 'sometimes|string',
        ];
    }
}
