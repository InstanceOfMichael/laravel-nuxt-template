<?php

namespace App\Http\Controllers\Group;

use App\Groupmembership;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroupmembership;
use App\Http\Requests\UpdateGroupmembership;
use App\Group;
use App\User;
use Illuminate\Http\Request;

class GroupmembershipController extends Controller
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
        return $group->groupmemberships()
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
    public function store(StoreGroupmembership $request, Group $group)
    {
        return $group->groupmemberships()->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $groupmembership
     * @param  \App\Groupmembership  $groupmembership
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Group $group, Groupmembership $groupmembership)
    {
        abort_if($group->id !== $groupmembership->group_id, 404);
        return $groupmembership->load('user', 'group', 'group.op');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Groupmembership  $groupmembership
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Group $group, Groupmembership $groupmembership)
    {
        abort_if($group->id !== $groupmembership->group_id, 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Groupmembership  $groupmembership
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGroupmembership $request, Group $group, Groupmembership $groupmembership)
    {
        abort_if($group->id !== $groupmembership->group_id, 404);
        $groupmembership->update($request->all());
        return $groupmembership->load('user', 'group', 'group.op');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Groupmembership  $groupmembership
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Group $group, Groupmembership $groupmembership)
    {
        abort_if($group->id !== $groupmembership->group_id, 404);
        $groupmembership->destroy();
        return $groupmembership;
    }
}
