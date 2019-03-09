<?php

namespace App\Http\Controllers;

use App\Allowedquestionside;
use Illuminate\Http\Request;

class AllowedquestionsideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(405, 'Use context /questions/:question');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(405, 'Use context /questions/:question');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(405, 'Use context /questions/:question');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Allowedquestionsides  $allowedquestionsides
     * @return \Illuminate\Http\Response
     */
    public function show(Allowedquestionsides $allowedquestionsides)
    {
        abort(405, 'Use context /questions/:question');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Allowedquestionsides  $allowedquestionsides
     * @return \Illuminate\Http\Response
     */
    public function edit(Allowedquestionsides $allowedquestionsides)
    {
        abort(405, 'Use context /questions/:question');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Allowedquestionsides  $allowedquestionsides
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Allowedquestionsides $allowedquestionsides)
    {
        abort(405, 'Use context /questions/:question');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Allowedquestionsides  $allowedquestionsides
     * @return \Illuminate\Http\Response
     */
    public function destroy(Allowedquestionsides $allowedquestionsides)
    {
        abort(405, 'Use context /questions/:question');
    }
}
