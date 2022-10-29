<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function point(){
        return view('manager.charts.point');
    }
}
