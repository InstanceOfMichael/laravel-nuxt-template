<?php

namespace App\Http\Requests;

use App\Comment;
use App\Contracts\HasComments;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ExistingParentCommentId;

class StoreComment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Comment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text' => 'required|string',
            'pc_id' => [
                new ExistingParentCommentId(/*$this->commentable()*/),
                'nullable',
            ],
        ];
    }

    // public function commentable(): HasComments {
    //     foreach([
    //         // 'question',
    //         // 'answer',
    //         // 'link',
    //         'users',
    //     ] as $key) {
    //         if ($this->{$key} instanceof HasComments) {
    //             return $this->{$key};
    //         }
    //     }
    // }
}
