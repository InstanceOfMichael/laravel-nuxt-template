<?php

namespace App\Http\Controllers;

use App\Linkdomain;
use App\Http\Requests\StoreLinkdomain;
use App\Http\Requests\UpdateLinkdomain;
use Illuminate\Http\Request;

class LinkdomainController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Linkdomain::query()
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
    public function store(StoreLinkdomain $request)
    {
        return Linkdomain::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Linkdomain  $linkdomain
     * @return \Illuminate\Http\Response
     */
    public function show(Linkdomain $linkdomain)
    {
        return $linkdomain;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Linkdomain  $linkdomain
     * @return \Illuminate\Http\Response
     */
    public function edit(Linkdomain $linkdomain)
    {
        return [
            'linkdomain' => $linkdomain,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Linkdomain  $linkdomain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UpdateLinkdomain $linkdomain)
    {
        $linkdomain->update($request->all());
        return $linkdomain;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Linkdomain  $linkdomain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Linkdomain $linkdomain)
    {
        $this->authorize($linkdomain);
        $linkdomain->delete();
        return $linkdomain;
    }
}
