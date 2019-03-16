<?php

namespace App\Http\Controllers;

use App\Claimrelation;
use App\Http\Requests\UpdateClaimrelation;
use App\Http\Requests\StoreClaimrelation;
use Illuminate\Http\Request;

class ClaimrelationController extends Controller
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
    public function index()
    {
        return Claimrelation::query()
            ->with(['parentclaim.op', 'replyclaim.op', 'op'])
            ->orderBy('claimrelations.id', 'DESC')
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
    public function store(StoreClaimrelation $request)
    {
        return $request->user()
            ->claimrelations()
            ->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Claimrelation  $claimrelation
     * @return \Illuminate\Http\Response
     */
    public function show(Claimrelation $claimrelation)
    {
        return $claimrelation->load([
            'parentclaim',
            'parentclaim.op',
            'replyclaim',
            'replyclaim.op',
            'op',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Claimrelation  $claimrelation
     * @return \Illuminate\Http\Response
     */
    public function edit(Claimrelation $claimrelation)
    {
        return [
            'claimrelation' => $claimrelation->load([
                'parentclaim',
                'parentclaim.op',
                'replyclaim',
                'replyclaim.op',
                'op',
            ]),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Claimrelation  $claimrelation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClaimrelation $request, Claimrelation $claimrelation)
    {
        $claimrelation->update($request->all());
        return $claimrelation;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Claimrelation  $claimrelation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Claimrelation $claimrelation)
    {
        $this->authorize($claimrelation);
        $claimrelation->delete();
        return $claimrelation;
    }
}
