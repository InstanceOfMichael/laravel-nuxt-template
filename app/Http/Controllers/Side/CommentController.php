<?php

namespace App\Http\Controllers\Side;

use App\Comment;
use App\Side;
use App\Http\Requests\StoreComment;
use App\Http\Requests\UpdateComment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('transaction')->only(['update', 'store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Side $side)
    {
        return $side->comments()
            ->with('op')
            ->orderBy('comments.id', 'desc')
            ->paginate();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreComment $request, Side $side)
    {
        return $side->comments()
            ->create([
                'op_id' => $request->user()->id,
            ] + $request->all());
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        abort(405, 'Use without commentable context: GET /comments/:id');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Side $side, Comment $comment)
    {
        return [
            'comment' => $comment,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateComment $request, Side $side, Comment $comment)
    {
        abort(405, 'Use without commentable context: PATCH /comments/:id');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Side $side, Comment $comment)
    {
        abort(405, 'Use without commentable context: DELETE /comments/:id');
    }
}
