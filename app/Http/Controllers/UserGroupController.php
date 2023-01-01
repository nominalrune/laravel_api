<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserGroupRequest;
use App\Http\Requests\UpdateUserGroupRequest;
use App\Models\UserGroup;

class UserGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreUserGroupRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserGroupRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function show(UserGroup $userGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(UserGroup $userGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserGroupRequest  $request
     * @param  \App\Models\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserGroupRequest $request, UserGroup $userGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserGroup $userGroup)
    {
        //
    }
}
