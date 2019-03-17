<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Requests\UpdateAnswer;
use App\Http\Requests\StoreAnswer;
use Illuminate\Http\Request;

class AnswerController extends Controller
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
    public function index(Request $request)
    {
        return Answer::query()
            ->whereRequest($request)
            ->with(['question.op', 'claim.op', 'op'])
            ->orderBy('answers.id', 'DESC')
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
    public function store(StoreAnswer $request)
    {
        return $request->user()
            ->answers()
            ->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer)
    {
        return $answer->load([
            'question',
            'question.op',
            'claim',
            'claim.op',
            'op',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer)
    {
        return [
            'answer' => $answer->load([
                'question',
                'question.op',
                'claim',
                'claim.op',
                'op',
            ]),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAnswer $request, Answer $answer)
    {
        $answer->update($request->all());
        return $answer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        $this->authorize($answer);
        $answer->delete();
        return $answer;
    }
}
