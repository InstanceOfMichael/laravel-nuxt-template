<?php

namespace App\Http\Requests;

use App\Link;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLink extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->link);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'string',
            // 'url' => 'string',
        ];
    }
}
