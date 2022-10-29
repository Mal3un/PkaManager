<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Course;
use App\Models\Major;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class StudentController extends Controller
{
    use ResponseTrait;

    private object $model;
    private string $table;
    public function __construct()
    {
        $this->model = Student::query();
        $this->table = (new Student())->getTable();

        View::share('title', ucwords($this->table));
        View::share('table', $this->table);
    }
    public function index(Request $request)
    {
        $selectedCourse = $request['course'];
        $selectedMajor = $request['major'];
        $selectedStudent = $request['student'];
        $arrNameStudent = explode(" ",$selectedStudent);
        $query = $this->model->clone()
            ->with(['major:id,name','course:id,name', ])
            ->latest();
        if($selectedCourse !== 'All...' && !empty($selectedCourse)){
            $query->whereHas('course',  function($q) use ($selectedCourse){
                return $q->where('course_id',$selectedCourse);
            });
        }
        if($selectedMajor !== 'All...' && !empty($selectedMajor)){
            $query->whereHas('course',  function($q) use ($selectedMajor){
                return $q->where('major_id',$selectedMajor);
            });
        }
        if($selectedStudent !== 'All...' && !empty($selectedStudent)){
            $query->where('first_name',  $arrNameStudent[0])->where('last_name', $arrNameStudent[1]);
        }
        $data = $query->paginate();
        $student = Student::all();
        $course = Course::all();
        $major = Major::all();
        if(($selectedMajor !== 'All...' && !empty($selectedMajor)) && ($selectedCourse !== 'All...' && !empty($selectedCourse))){
            $student = Student::query()->where('course_id', $selectedCourse)->where('major_id', $selectedMajor)->get();
        }else if($selectedMajor !== 'All...' && !empty($selectedMajor) && ($selectedCourse === 'All...' && empty($selectedCourse))){
            $student = Student::query()->where('course_id', $selectedCourse)->get();
        }else if($selectedMajor === 'All...' && empty($selectedMajor) && ($selectedCourse !== 'All...' && !empty($selectedCourse))){
            $student = Student::query()->where('major_id', $selectedMajor)->get();
        }
        return (view('manager.students.index', [
            'title' => 'Students',
            'data' => $data,
            'students' => $student,
            'courses' => $course,
            'majors' => $major,

            'selectedStudent' => $selectedStudent,
            'selectedCourse' => $selectedCourse,
            'selectedMajor' => $selectedMajor,
        ]));
    }

    public function schedule(){
        if(Auth::user()->role_id === 3){
            $students = Student::all();
        }
        else{
            $students = Student::query()->where('user_id', Auth::id())->first()->id;
        }
        return view('manager.students.schedule',[
            'title' => 'Schedule',
            'students' => $students
        ]);
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
     * @param  \App\Http\Requests\StoreStudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStudentRequest  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
