<?php

namespace App\Http\Controllers\Claim;

use App\Claimside;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClaimside;
use App\Http\Requests\UpdateClaimside;
use App\Claim;
use App\Side;
use Illuminate\Http\Request;

class ClaimsideController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Claim $claim)
    {
        return $claim->claimsides()
            ->orderBy('id', 'desc')
            ->get()
            ->load('side', 'op', 'side.op');
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
    public function store(StoreClaimside $request, Claim $claim)
    {
        return $claim->claimsides()->create([
            'op_id' => $request->user()->id,
        ]+$request->all())->load('side', 'op', 'side.op', 'claim', 'claim.op');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Claim  $claimside
     * @param  \App\Claimside  $claimside
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Claim $claim, Claimside $claimside)
    {
        abort_if($claim->id !== $claimside->claim_id, 404);
        return $claimside->load('side', 'op', 'side.op', 'claim', 'claim.op');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Claimside  $claimside
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Claim $claim, Claimside $claimside)
    {
        abort_if($claim->id !== $claimside->claim_id, 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Claimside  $claimside
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClaimside $request, Claim $claim, Claimside $claimside)
    {
        abort_if($claim->id !== $claimside->claim_id, 404);
        $claimside->update($request->all());
        return $claimside->load('side', 'op', 'side.op');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Claimside  $claimside
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Claim $claim, Claimside $claimside)
    {
        abort_if($claim->id !== $claimside->claim_id, 404);
        $claimside->destroy();
        return $claimside;
    }
}
