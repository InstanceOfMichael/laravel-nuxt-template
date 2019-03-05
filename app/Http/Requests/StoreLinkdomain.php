<?php

namespace App\Http\Requests;

use App\Linkdomain;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreLinkdomain extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Linkdomain::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'domain' => 'required|string',
            'meta' => 'sometimes|array',
            'text' => 'sometimes|string',
        ];
    }
}
