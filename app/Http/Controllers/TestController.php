<?php

namespace App\Http\Controllers;


use App\Models\Course;
use App\Models\Major;
use App\Http\Requests\StoreMajorRequest;
use App\Http\Requests\UpdateMajorRequest;
use App\Models\Subject;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;


class TestController extends Controller
{
    public function test(){
        dd(asset('/images/avatar/role1.png'));
    }
}
