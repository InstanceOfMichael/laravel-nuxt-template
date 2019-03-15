<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSide;
use App\Http\Requests\UpdateSide;
use App\Side;
use Illuminate\Http\Request;

class SideController extends Controller
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
        return Side::query()
            ->with('op')
            ->orderBy('sides.id', 'DESC')
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
    public function store(StoreSide $request)
    {
        return $request->user()
            ->sides()
            ->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Side  $side
     * @return \Illuminate\Http\Response
     */
    public function show(Side $side)
    {
        return $side->load('op');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Side  $side
     * @return \Illuminate\Http\Response
     */
    public function edit(Side $side)
    {
        return [
            'side' => $side,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Side  $side
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSide $request, Side $side)
    {
        $side->update($request->all());
        return $side->load('op');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Side  $side
     * @return \Illuminate\Http\Response
     */
    public function destroy(Side $side)
    {
        $this->authorize($side);
        $side->delete();
        return $side;
    }
}
