<?php

namespace App\Http\Controllers\Question;

use App\Allowedquestionside;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAllowedquestionside;
use App\Http\Requests\UpdateAllowedquestionside;
use App\Question;
use App\Side;
use Illuminate\Http\Request;

class AllowedquestionsideController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Question $question)
    {
        return $question->allowedsides()
            ->orderBy('id', 'desc')
            ->get()
            ->load('side', 'op', 'side.op');
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
    public function store(StoreAllowedquestionside $request, Question $question)
    {
        if (is_array($request->side_id_list)) {
            $sides = Side::findOrFail($request->side_id_list);
            $aqs = [];
            foreach ($sides as $side) {
                $aqs[] = $question->allowedsides()->create([
                    'side_id' => $side->id,
                    'op_id' => $request->user()->id,
                ]);
            }
            return response()->json($aqs, 201);
        } else {
            $side = Side::findOrFail($request->side_id);
            return $question->allowedsides()->create([
                'side_id' => $side->id,
                'op_id' => $request->user()->id,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $allowedquestionside
     * @param  \App\Allowedquestionside  $allowedquestionside
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Question $question, Allowedquestionside $allowedquestionside)
    {
        abort_if($question->id !== $allowedquestionside->question_id, 404);
        return $allowedquestionside->load('side', 'op', 'side.op', 'question', 'question.op');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Allowedquestionside  $allowedquestionside
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Question $question, Allowedquestionside $allowedquestionside)
    {
        abort_if($question->id !== $allowedquestionside->question_id, 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Allowedquestionside  $allowedquestionside
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAllowedquestionside $request, Question $question, Allowedquestionside $allowedquestionside)
    {
        abort_if($question->id !== $allowedquestionside->question_id, 404);
        $allowedquestionside->update($request->all());
        return $allowedquestionside->load('side', 'op', 'side.op');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Allowedquestionside  $allowedquestionside
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Question $question, Allowedquestionside $allowedquestionside)
    {
        abort_if($question->id !== $allowedquestionside->question_id, 404);
        $allowedquestionside->destroy();
        return $allowedquestionside;
    }
}
