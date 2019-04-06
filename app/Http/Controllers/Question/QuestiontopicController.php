<?php

namespace App\Http\Controllers\Question;

use App\Questiontopic;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestiontopic;
use App\Http\Requests\UpdateQuestiontopic;
use App\Question;
use App\Side;
use Illuminate\Http\Request;

class QuestiontopicController extends Controller
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
    public function index(Request $request, Question $question)
    {
        return $question->questiontopics()
            ->orderBy('questiontopics.id', 'desc')
            ->get()
            ->load('question', 'topic', 'op');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Question $question)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestiontopic $request, Question $question)
    {
        return $question->questiontopics()->create([
            'op_id' => $request->user()->id,
        ]+$request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $questiontopic
     * @param  \App\Questiontopic  $questiontopic
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Question $question, Questiontopic $questiontopic)
    {
        abort_if($question->id !== $questiontopic->question_id, 404);
        return $questiontopic->load('topic', 'op', 'question', 'question.op');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Questiontopic  $questiontopic
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Question $question, Questiontopic $questiontopic)
    {
        abort_if($question->id !== $questiontopic->question_id, 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Questiontopic  $questiontopic
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuestiontopic $request, Question $question, Questiontopic $questiontopic)
    {
        abort_if($question->id !== $questiontopic->question_id, 404);
        $questiontopic->update($request->all());
        return $questiontopic;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Questiontopic  $questiontopic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Question $question, Questiontopic $questiontopic)
    {
        abort_if($question->id !== $questiontopic->question_id, 404);
        $questiontopic->destroy();
        return $questiontopic;
    }
}
