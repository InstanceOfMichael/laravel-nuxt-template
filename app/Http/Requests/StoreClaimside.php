<?php

namespace App\Http\Requests;

use App\Claimside;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClaimside extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Claimside::class);
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
                'required',
                'exists:sides,id',
                Rule::unique('claimsides')->where(function ($query) {
                    return $query
                        ->where('claimsides.claim_id', $this->claim->id)
                        ->where('claimsides.side_id', $this->side_id);
                }),
            ],
        ];
    }

    public function messages () {
        return [
            'side_id.unique' => 'This claim is already associated with this side.',
        ];
    }
}
