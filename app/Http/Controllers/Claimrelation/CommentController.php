<?php

namespace App\Http\Controllers\Claimrelation;

use App\Comment;
use App\Claimrelation;
use App\Http\Requests\StoreComment;
use App\Http\Requests\UpdateComment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $reqest, Claimrelation $claimrelation)
    {
        return $claimrelation->comments()
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
    public function store(StoreComment $request, Claimrelation $claimrelation)
    {
        return $claimrelation->comments()
            ->create([
                'op_id' => $request->user()->id,
            ] + $request->all())
            ->load('op');
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
    public function edit(Claimrelation $claimrelation, Comment $comment)
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
    public function update(UpdateComment $request, Claimrelation $claimrelation, Comment $comment)
    {
        abort(405, 'Use without commentable context: PATCH /comments/:id');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Claimrelation $claimrelation, Comment $comment)
    {
        abort(405, 'Use without commentable context: DELETE /comments/:id');
    }
}
