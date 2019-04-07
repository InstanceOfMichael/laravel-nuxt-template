<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDefinition;
use App\Http\Requests\UpdateDefinition;
use App\Definition;
use Illuminate\Http\Request;

class DefinitionController extends Controller
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
        return Definition::query()
            ->orderBy('definitions.id', 'DESC')
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
    public function store(StoreDefinition $request)
    {
        return Definition::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Definition  $definition
     * @return \Illuminate\Http\Response
     */
    public function show(Definition $definition)
    {
        return $definition;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Definition  $definition
     * @return \Illuminate\Http\Response
     */
    public function edit(Definition $definition)
    {
        return [
            'definition' => $definition,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Definition  $definition
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDefinition $request, Definition $definition)
    {
        $definition->update($request->all());
        return $definition;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Definition  $definition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Definition $definition)
    {
        $this->authorize($definition);
        $definition->delete();
        return $definition;
    }
}
