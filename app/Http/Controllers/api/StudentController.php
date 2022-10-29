<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Classe;
use App\Models\Course;
use App\Models\ListPoint;
use App\Models\Major;
use App\Models\Score;
use App\Models\Student;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class StudentController extends Controller
{
    use ResponseTrait;
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

    public function Schedule(request $request) : JsonResponse
    {
        try{
            $student = Student::find($request->student)->only('first_name', 'last_name','birthdate','gender','major_id','course_id');
            $student['birthdate'] = date('d/m/Y', strtotime($student['birthdate']));
            $student['major'] = Major::find($student['major_id'])->name;
            $student['course'] = Course::find($student['course_id'])->name;
            $studentinfo = $student;
            $student = Score::where('student_id', $request->student)->pluck('classe_id');
            $arr = [];
            foreach ($student as $each){
                $classe = Classe::find($each);
                $classename = $classe->name;
                $classe->schedule = str_replace(' ', '', $classe->schedule);
                if($classe->schedule !== '' && $classe->schedule !== null){
                    $classe->schedule = explode('-', $classe->schedule);
                    foreach ($classe->schedule as $each2){
                        $day = substr($each2, 0, 1);
                        $time = explode(',',substr($each2, 1));
                        for($i = $time[0]; $i <= $time[1]; $i++){
                            $arr[$day][$i] = $classename;
                        }
                    }
                }
            }
            return $this->successResponse([$arr,$studentinfo]);
        }catch(Exception $e){
            return $this->errorResponse($e->getMessage(),500);
        }
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
