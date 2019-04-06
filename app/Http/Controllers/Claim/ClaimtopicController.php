<?php

namespace App\Http\Controllers\Claim;

use App\Claimtopic;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClaimtopic;
use App\Http\Requests\UpdateClaimtopic;
use App\Claim;
use App\Side;
use Illuminate\Http\Request;

class ClaimtopicController extends Controller
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
    public function index(Request $request, Claim $claim)
    {
        return $claim->claimtopics()
            ->orderBy('claimtopics.id', 'desc')
            ->get()
            ->load('claim', 'topic', 'op');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Claim $claim)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClaimtopic $request, Claim $claim)
    {
        return $claim->claimtopics()->create([
            'op_id' => $request->user()->id,
        ]+$request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Claim  $claimtopic
     * @param  \App\Claimtopic  $claimtopic
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Claim $claim, Claimtopic $claimtopic)
    {
        abort_if($claim->id !== $claimtopic->claim_id, 404);
        return $claimtopic->load('topic', 'op', 'claim', 'claim.op');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Claimtopic  $claimtopic
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Claim $claim, Claimtopic $claimtopic)
    {
        abort_if($claim->id !== $claimtopic->claim_id, 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Claimtopic  $claimtopic
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClaimtopic $request, Claim $claim, Claimtopic $claimtopic)
    {
        abort_if($claim->id !== $claimtopic->claim_id, 404);
        $claimtopic->update($request->all());
        return $claimtopic;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Claimtopic  $claimtopic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Claim $claim, Claimtopic $claimtopic)
    {
        abort_if($claim->id !== $claimtopic->claim_id, 404);
        $claimtopic->destroy();
        return $claimtopic;
    }
}
