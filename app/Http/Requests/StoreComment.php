<?php

namespace App\Http\Requests;

use App\Comment;
use App\Commentable;
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
            'pc_id' => $this->commentable() ? new ExistingParentCommentId($this->commentable()) : '',
        ];
    }

    public function commentable() {
        if ($this->question instanceof Commentable) return $this->question;
        if ($this->claim instanceof Commentable) return $this->claim;
    }
}
