<?php

namespace App\Http\Controllers;


use App\Models\Course;
use App\Models\Major;
use App\Models\Student;
use App\Models\Teacher;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Teacher::query();
        $this->table = (new Teacher())->getTable();

        View::share('title', ucwords($this->table));
        View::share('table', $this->table);
    }
    public function index(Request $request)
    {
        $selectedTeacher = $request['teachers'];
        $arrNameTeacher = explode(" ",$selectedTeacher);
        $query = $this->model->clone()->latest();
        if($selectedTeacher !== 'All...' && !empty($selectedTeacher)){
            $query->where('first_name',  $arrNameTeacher[0])->where('last_name', $arrNameTeacher[1]);
        }
        $data = $query->paginate();
        $teacher = Teacher::all();
        return (view('manager.teachers.index', [
            'title' => 'Teacher',
            'data' => $data,
            'teachers' => $teacher,

            'selectedTeacher' => $selectedTeacher,
        ]));
    }


    public function schedule(){
        if(Auth::user()->role_id === 3){
            $teachers = Teacher::all();
        }
        else{
            $teachers = Teacher::query()->where('user_id', Auth::id())->first()->id;
        }
        return view('manager.teachers.schedule',[
            'title' => 'Schedule',
            'teachers' => $teachers
        ]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTeacherRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTeacherRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTeacherRequest  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        //
    }
}
