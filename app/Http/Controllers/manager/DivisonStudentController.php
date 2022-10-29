<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Classe;
use App\Models\Course;
use App\Models\ListPoint;
use App\Models\Major;
use App\Models\Score;
use App\Models\Student;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class DivisonStudentController extends Controller
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
        $classInfo = Classe::find($request->classes);

        $classes = Classe::all();
        $student = Student::all();
        $course = Course::all();
        $major = Major::all();


        if($classInfo){
            $teacher = Teacher::find($classInfo['teacher_id']);
        }else{
            $teacher = '';
        }

        $selectedCourse = $request['course'];
        $selectedClass = $request['classes'];
        $classtest = Classe::find($selectedClass);
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
        if($selectedClass !== null && $selectedClass !== 'Chọn lớp học'){
            $studentList = ListPoint::query()->where('session',1)->where('subject_id',$classtest['subject_id'])->pluck("student_id");
            $query->whereNotIn('id',$studentList);
        }
        $data = $query->paginate(30);
        if(($selectedMajor !== 'All...' && !empty($selectedMajor)) && ($selectedCourse !== 'All...' && !empty($selectedCourse))){
            $student = $student->where('course_id', $selectedCourse)->where('major_id', $selectedMajor);
        }else if($selectedMajor !== 'All...' && !empty($selectedMajor) && ($selectedCourse === 'All...' && empty($selectedCourse))){
            $student = $student->where('course_id', $selectedCourse);
        }else if($selectedMajor === 'All...' && empty($selectedMajor) && ($selectedCourse !== 'All...' && !empty($selectedCourse))){
            $student = $student->where('major_id', $selectedMajor);
        }
        return (view('manager.divisionStudent.index', [
            'title' => 'Students',
            'classes' => $classes,
            'data' => $data,
            'students' => $student,
            'courses' => $course,
            'majors' => $major,
            'classInfo' => $classInfo ,
            'teacher' => $teacher ,
            'selectedClass' => $selectedClass,
            'selectedStudent' => $selectedStudent,
            'selectedCourse' => $selectedCourse,
            'selectedMajor' => $selectedMajor,
        ]));
    }
//    public function info(Request $request): JsonResponse
//    {
//        $classes = Classe::find($request->get('classes'));
//        $teacher = Teacher::find($classes['teacher_id']);
//        if(!empty($teacher)){
//            $classes['teacher'] = $teacher->first_name.' '.$teacher->last_name;
//        }
//        else{
//            $classes['teacher'] = "";
//        }
//        return $this->successResponse($classes);
//    }

    public function set(Request $request)
    {
        try{
           $student = Student::find($request->get('id'));
           $classes = Classe::find($request->get('class'));
           $schedule = explode('-',$classes->schedule);
           $info=[];
           foreach ($schedule as $each){
               $day = substr($each, 0, 1);
               $info[$day] = substr($each, 1);
           }
           $class = Score::query()->where('student_id',$student['id'])->get('classe_id')->toArray();
           foreach ($class as $each){
                $check = Classe::find($each['classe_id'])->get(['schedule','name'])->toArray();
                $name = $check[0]['name'];
                $scheduled = $check[0]['schedule'];
                $scheduled = explode('-',$scheduled);
                foreach ($scheduled as $schedule){
                    foreach ($info as $key => $value){
                        if(substr($schedule, 0, 1) === $key){
                            $time = substr($schedule, 1);
                            $time = explode(',',$time);
                            $value= explode(',',$value);
                            if(($time[0] >= $value[0] && $time[0] <= $value[1]) || ($time[1] >= $value[0] && $time[1] <= $value[1])){
                                switch ($key) {
                                    case 'M':
                                        $day='Thứ 2';
                                        break;
                                    case 'T':
                                        $day='Thứ 3';
                                        break;
                                    case 'W':
                                        $day='Thứ 4';
                                        break;
                                    case 't':
                                        $day='Thứ 5';
                                        break;
                                    case 'F':
                                        $day='Thứ 6';
                                        break;
                                    case 'S':
                                        $day='Thứ 7';
                                        break;
                                    case 's':
                                        $day='Chủ nhật';
                                        break;
                                }
                                return $this->errorResponse("$name |  $day (T$time[0] - $time[1])",500);
                            }
                        }
                    }
                }
           }
           $arrScore = [
                'student_id' =>  $student['id'],
                'subject_id' => $classes['subject_id'],
                'classe_id' => $classes['id'],
           ];
           Score::create($arrScore);
           for($i = 1; $i <= (int)$classes['all_session']; $i++) {
               $data = [
                     'student_id' => $student['id'],
                     'classe_id' => $classes['id'],
                     'subject_id' => $classes['subject_id'],
                     'session' => $i,
               ];
               ListPoint::create($data);
            }
            return $this->successResponse();
        }catch(Exception $e){
            return $this->errorResponse($e->getMessage(),500);
        }
    }
}
