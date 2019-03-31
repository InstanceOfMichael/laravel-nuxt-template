<?php

namespace App\Http\Controllers\Group;

use App\Groupsubscription;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroupsubscription;
use App\Http\Requests\UpdateGroupsubscription;
use App\Group;
use App\User;
use Illuminate\Http\Request;

class GroupsubscriptionController extends Controller
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
    public function index(Request $request, Group $group)
    {
        return $group->groupsubscriptions()
            ->orderBy('id', 'desc')
            ->get()
            ->load('user', 'group', 'group.op');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Group $group)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGroupsubscription $request, Group $group)
    {
        return $group->groupsubscriptions()->create([
            'op_id' => $request->user()->id,
        ]+$request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $groupsubscription
     * @param  \App\groupsubscription  $groupsubscription
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Group $group, Groupsubscription $groupsubscription)
    {
        abort_if($group->id !== $groupsubscription->group_id, 404);
        return $groupsubscription->load('user', 'group', 'group.op');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\groupsubscription  $groupsubscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Group $group, Groupsubscription $groupsubscription)
    {
        abort_if($group->id !== $groupsubscription->group_id, 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\groupsubscription  $groupsubscription
     * @return \Illuminate\Http\Response
     */
    public function update(Updategroupsubscription $request, Group $group, Groupsubscription $groupsubscription)
    {
        abort_if($group->id !== $groupsubscription->group_id, 404);
        $groupsubscription->update($request->all());
        return $groupsubscription->load('user', 'group', 'group.op');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\groupsubscription  $groupsubscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Group $group, Groupsubscription $groupsubscription)
    {
        abort_if($group->id !== $groupsubscription->group_id, 404);
        $groupsubscription->destroy();
        return $groupsubscription;
    }
}
