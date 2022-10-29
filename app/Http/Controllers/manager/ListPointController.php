<?php

namespace App\Http\Controllers;

use App\Models\ListPoint;
use App\Http\Requests\StoreListPointRequest;
use App\Http\Requests\UpdateListPointRequest;

class ListPointController extends Controller
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
     * @param  \App\Http\Requests\StoreListPointRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreListPointRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ListPoint  $listPoint
     * @return \Illuminate\Http\Response
     */
    public function show(ListPoint $listPoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ListPoint  $listPoint
     * @return \Illuminate\Http\Response
     */
    public function edit(ListPoint $listPoint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateListPointRequest  $request
     * @param  \App\Models\ListPoint  $listPoint
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateListPointRequest $request, ListPoint $listPoint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ListPoint  $listPoint
     * @return \Illuminate\Http\Response
     */
    public function destroy(ListPoint $listPoint)
    {
        //
    }
}
