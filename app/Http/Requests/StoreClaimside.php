<?php

namespace App\Http\Requests;

use App\Claimside;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

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
            'side_id' => 'required|exists:sides,id',
        ];
    }
}
