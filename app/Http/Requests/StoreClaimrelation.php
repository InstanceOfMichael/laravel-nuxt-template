<?php

namespace App\Http\Requests;

use App\Claimrelation;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreClaimrelation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Claimrelation::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pc_id' => 'required|exists:claims,id',
            'rc_id' => 'required|exists:claims,id',
        ];
    }
}
