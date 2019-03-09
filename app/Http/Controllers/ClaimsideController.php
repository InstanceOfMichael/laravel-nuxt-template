<?php

namespace App\Http\Controllers;

use App\Claimside;
use Illuminate\Http\Request;

class ClaimsideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(405, 'Use context /claims/:claim');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(405, 'Use context /claims/:claim');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(405, 'Use context /claims/:claim');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Claimside  $claimsides
     * @return \Illuminate\Http\Response
     */
    public function show(Claimside $claimsides)
    {
        abort(405, 'Use context /claims/:claim');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Claimside  $claimsides
     * @return \Illuminate\Http\Response
     */
    public function edit(Claimside $claimsides)
    {
        abort(405, 'Use context /claims/:claim');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Claimside  $claimsides
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Claimside $claimsides)
    {
        abort(405, 'Use context /claims/:claim');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Claimside  $claimsides
     * @return \Illuminate\Http\Response
     */
    public function destroy(Claimside $claimsides)
    {
        abort(405, 'Use context /claims/:claim');
    }
}
