<?php

namespace App\Http\Controllers;

use App\Imports\ListPointImport;
use App\Models\ListPoint;
use App\Http\Requests\StoreListPointRequest;
use App\Http\Requests\UpdateListPointRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelExcel;

class ListPointController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request) : JsonResponse
    {
        try{
            $file = $request->file('file')->store('temp');
            $path = storage_path('app').'/'.$file;
            $arr = Excel::toArray(new ListPointImport, $path);
            $status = [];
            foreach ($arr as $key => $value){
                foreach ($value as $k => $v){
                    if ($k != 0){
                        $st = '';
                        if ($v[4] == 'x'){
                            $st = 1;
                        }elseif ($v[5] == 'x'){
                            $st = 2;
                        }elseif ($v[6] == 'x'){
                            $st = 3;
                        }
                        $status[$v[0]] = $st;
                    }
                }
            }
            return $this->successResponse($status);
        }catch(Exception $e){
            return $this->errorResponse($e->getMessage());
        }


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
