<?php

namespace App\Http\Controllers\User;

use App\Comment;
use App\User;
use App\Http\Requests\StoreComment;
use App\Http\Requests\UpdateComment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $reqest, User $user)
    {
        return $user->comments()
            ->with('op')
            ->orderBy('comments.id', 'desc')
            ->paginate();
    }
}
