<?php

namespace App\Http\Requests;

use App\Linkdomain;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLinkdomain extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->linkdomain);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|string',
            // 'domain' => 'string',
            'meta' => 'sometimes|array',
            'text' => 'sometimes|string',
        ];
    }
}
