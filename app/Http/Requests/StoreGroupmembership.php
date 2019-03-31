<?php

namespace App\Http\Requests;

use App\Groupmembership;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGroupmembership extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Groupmembership::class);
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
                Rule::unique('groupmemberships')->where(function ($query) {
                    return $query
                        ->where('groupmemberships.user_id', $this->user_id)
                        ->where('groupmemberships.group_id', $this->group->id);
                }),
            ],
        ];
    }

    public function messages () {
        return [
            'user_id.unique' => 'This user is already a member of this group.',
        ];
    }
}
