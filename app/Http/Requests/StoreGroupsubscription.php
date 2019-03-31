<?php

namespace App\Http\Requests;

use App\Groupsubscription;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGroupsubscription extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Groupsubscription::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('groupsubscriptions')->where(function ($query) {
                    return $query
                        ->where('groupsubscriptions.user_id', $this->user_id)
                        ->where('groupsubscriptions.group_id', $this->group->id);
                }),
            ],
        ];
    }

    public function messages () {
        return [
            'user_id.unique' => 'This user is already subscribed to this group.',
        ];
    }
}
