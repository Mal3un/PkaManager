<?php

namespace App\Http\Controllers\manager;

use App\Enums\ClassTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Classe;
use App\Http\Requests\StoreClasseRequest;
use App\Http\Requests\UpdateClasseRequest;
use App\Models\Course;
use App\Models\ListPoint;
use App\Models\Major;
use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use mysql_xdevapi\Exception;
use PDOException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class ClasseController extends Controller
{

    use ResponseTrait;

    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Classe::query();
        $this->table = (new Classe())->getTable();

        View::share('title', ucwords($this->table));
        View::share('table', $this->table);
    }
    public function index(Request $request)
    {

        $selectedCourse = $request->get('course');
        $selectedMajor = $request->get('major');
        $selectedClassType = $request->get('classType');
        $selectedTeacher = $request->get('teacher');
        $selectedSubject = $request->get('subject');
        $query = $this->model->clone()
            ->with(['major:id,name', 'teacher:id,first_name,last_name,gender,email', 'course:id,name','subject:id,name' ])
            ->latest();
        if(Auth::user()->role_id === 2) {
            $query->where('teacher_id', Teacher::query()->where('user_id',Auth::id())->first()->id);
        }
        if(Auth::user()->role_id === 1) {
            $class_id = Score::query()->where('student_id', Student::query()->where('user_id',Auth::id())->first()->id)->pluck('classe_id');
            $query->whereIn('id', $class_id);
        }
        if($selectedClassType !== 'All...' && !empty($selectedClassType)){
            $query->where('class_type',  $selectedClassType);
        }
        if($selectedSubject !== 'All...' && !empty($selectedSubject)){
            $query->where('subject_id',  $selectedSubject);
        }
        if($selectedCourse !== 'All...' && !empty($selectedCourse)){
            $query->whereHas('course',  function($q) use ($selectedCourse){
                return $q->where('course_id',$selectedCourse);
            });
        }

        if($selectedMajor !== 'All...' && !empty($selectedMajor)){
            $query->whereHas('major',  function($q) use ($selectedMajor){
                return $q->where('major_id',$selectedMajor);
            });
        }
        if($selectedTeacher !== 'All...' && !empty($selectedTeacher)){
            $query->whereHas('teacher',  function($q) use ($selectedTeacher){
                return $q->where('teacher_id',$selectedTeacher );
            });
        }

        $data = $query->paginate();
        $teacher = Teacher::query()
            ->get([
                'id',
                'first_name',
                'last_name',
                'first_name',
                'gender',
                'email'
            ]);
        $course = Course::query()
            ->get([
                'id',
                'name',
            ]);
        $major = Major::query()
            ->get([
                'id',
                'name',
            ]);
        $subject= Subject::query()
            ->get([
                'id',
                'name',
            ]);
        $class_type = ClassTypeEnum::asArray();
        return view('manager.classes.index', [
            'data' => $data,
            'class_types' => $class_type,
            'teachers'=>$teacher,
            'courses'=>$course,
            'majors'=>$major,
            'subjects'=>$subject,

            'selectedCourse'=> $selectedCourse,
            'selectedMajor'=> $selectedMajor,
            'selectedClassType'=> $selectedClassType,
            'selectedTeacher'=> $selectedTeacher,
            'selectedSubject'=> $selectedSubject,
            ]);
    }
    public function point_list(Request $request):JsonResponse
    {
        try{
            $studentIds = listPoint::query()
                ->where('session',$request->get('lesson'))
                ->where('classe_id',$request->get('class_id'))->get(['student_id','status','note'])->toArray();
            $students = [];
            foreach($studentIds as $student){
                $student['info'] = Student::find($student['student_id'])->toArray();
                $students[] = $student;
            }
            return $this->successResponse($students);
        }
        catch (Throwable $e){
            return $this->errorResponse($e->getMessage(),500);
        }
    }

    public function score(Request $request) : JsonResponse
    {
        try{
            $class = Classe::find($request->get('class_id'))->id;
            $score = Score::query()->where('classe_id',$class)->get(['student_id','listpoint_score','midterm_score','note'])->toArray();
            $data = [];
            foreach($score as $each){
                $each['student_id'] = Student::find($each['student_id'])->toArray();
                $data[] = $each;
            }
            return $this->successResponse($data);
        }catch (Throwable $e){
            return $this->errorResponse($e->getMessage(),500);
        }
    }

    public function setPointList(Request $request) : JsonResponse
    {
        try{
            $class = $request->get('classid');
            $lesson = $request->get('lesson');
            $a =ListPoint::query()
                ->where('classe_id',$class)
                ->where('session',$lesson)->first();
            $arr = explode("&", $request->get('status'));
            foreach ($arr as $key=>$each){
                $each = str_replace('id','',$each);
                $set = explode('=',$each);
                if($key === 0){
                    $note = $request->get('note');
                    ListPoint::query()
                        ->where('student_id',$set[0])
                        ->where('session',$lesson)
                        ->where('classe_id',$class)
                        ->update([
                            'note'=>$note,
                        ]);
                }
                ListPoint::query()
                    ->where('student_id',$set[0])
                    ->where('session',$lesson)
                    ->where('classe_id',$class)
                    ->update([
                        'status'=>$set[1],
                    ]);
            }
            return $this->successResponse();
        }catch(Exception $e){
            return $this->errorResponse($e->getMessage(),500);
        }
    }

    public function setScore(Request $request) : JsonResponse
    {
        try{
            $data = $request->get('data');
            $class = $request->get('classid');
            $notelist = $request->get('arr');
            $arr = explode("&HS=&", $request->get('data'));
            $arr[count($arr)-1] = str_replace('&HS=','',$arr[count($arr)-1]);
            foreach ($arr as $each){
                $each = explode('&',$each);
                $each[0] = explode('=',str_replace('-listpoint','',$each[0]));
                $listpoint_score = $each[0][1];
                $student_id = $each[0][0];
                $each[1] = str_replace($student_id . '-mid=','',$each[1]);
                $midterm_score = $each[1];
                if($listpoint_score === ''){$listpoint_score = null;}
                if($midterm_score === ''){$midterm_score = null;}

                Score::query()
                    ->where('student_id',$student_id)
                    ->where('classe_id',$class)
                    ->update([
                        'listpoint_score'=>$listpoint_score,
                        'midterm_score'=>$midterm_score,
                    ]);
            }
            foreach ($notelist as $each){
                $set = explode('-note=',$each);
                Score::query()
                    ->where('student_id',$set[0])
                    ->where('classe_id',$class)
                    ->update([
                        'note'=>$set[1],
                    ]);
            }
            return $this->successResponse();
        }catch(Exception $e){
            return $this->errorResponse($e->getMessage(),500);
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

    public function store(StoreClasseRequest $request) : JsonResponse
    {
        DB::beginTransaction();
        try{
            $days = (int) $request->get('quality_day');
            $schedule = '';
            for($i = 1; $i <= $days; $i++){
                $day = $request->get('day-'.$i);
                switch ($day){
                    case '2':
                        $day='M';
                        break;
                    case '3':
                        $day='T';
                        break;
                    case '4':
                        $day='W';
                        break;
                    case '5':
                        $day='t';
                        break;
                    case '6':
                        $day='F';
                        break;
                    case '7':
                        $day='S';
                        break;
                    case '8':
                        $day='s';
                        break;
                }
                $start = $request->get('start-lesson-'.$i);
                $end = $request->get('end-lesson-'.$i);
                $schedule = $schedule.$day.$start.','.$end.'-';
            }
            $schedule = rtrim($schedule,'-');
            $quality = (int) $request->get('quality-class');
            if($quality > 1 ){
                for($i = 1; $i <= $quality; $i++){
                    $name = str_replace('1.1', "1.$i", $request->get('name'));
                    $data = $request->only([
                        'major_id',
                        'course_id',
                        'class_type',
                        'subject_id',
                        'start_date',
                        'end_date',
                        'all_session',
                    ]);
                    $data['name'] =  $name;
                    $data['schedule'] = $schedule;
                    Classe::create($data);
//                    for($j = 1; $j <= (int)$request['all_session']; $j++){
//                        listPoint::create(['classe_id' => $class->id, 'session' => $j]);
//                    }
                }
            }else{
                $arr = $request->only([
                    "course_id",
                    "major_id",
                    "class_type",
                    "subject_id",
                    "name",
                    'start_date',
                    'end_date',
                    'all_session'
                    ]);
                $arr['schedule'] = $schedule;
                Classe::create($arr);
//                for($i = 1; $i <= (int)$request['all_session']; $i++){
//                    listPoint::create(['classe_id' => $class->id, 'session' => $i]);
//                }
            }
            DB::commit();
            return $this->successResponse();
        } catch (Throwable $e){
            DB::rollback();
            return $this->errorResponse($e, 500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Classe  $classe
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($classeId)
    {
        $data = $this->model->findOrFail($classeId);
        if(Auth::user()->role_id === 2){
            $teacher_id = Teacher::query()->where('user_id',Auth::id())->first()->id;
            if($data->teacher_id !== $teacher_id && auth()->user()->role_id !== 3){
                return abort(403,'Bạn không có đủ quyền truy cập');
            }
        }
        if(Auth::user()->role_id === 1){
            $student_id = Student::query()->where('user_id',Auth::id())->first()->id;
            $check = Score::query()->where('student_id',$student_id)->where('classe_id',$classeId)->first();
            if(!$check){
                return abort(403,'Bạn không có đủ quyền truy cập');
            }
        }
        $teacher = Teacher::query()->where('id', $data->teacher_id)->first();
        $studentid= ListPoint::query()->where('classe_id', $classeId)->Where('session',1)->pluck('student_id');
        $students = [];
        foreach($studentid as $student){
            $student = Student::find($student);
            $status = ListPoint::query()->where('student_id', $student->id)->where('classe_id', $classeId)->where('status', 1)->count();
            $student['status'] = $status;
            $students[] = $student;
        }
        $arr = [];
        for($i = 1; $i <= $data->all_session; $i++){
            $arr[] = $i;
        }
        return(view('manager.classes.detail', [
            'lesson'=>$arr,
            'data' => $data,
            'teacher' => $teacher,
            'students' => $students,
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Classe  $classe
     * @return \Illuminate\Http\Response
     */
    public function edit(request $request)
    {
        $student = Score::query()->where('student_id', $request->student)->get();
        dd($student);
    }

    public function schedule(){
        $classes = Classe::query()->where('schedule', '=',null)->get(['id','name','course_id','major_id','class_type']);
        foreach ($classes as $each){
            $each->course = Course::query()->where('id', $each->course_id)->first()->name;
            $each->major = Major::query()->where('id', $each->major_id)->first()->name;
            $each->class_type = ClassTypeEnum::getKey($each->class_type);
        }
        return view('manager.classes.schedule', [
            'title' => 'Schedule',
            'classes' => $classes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClasseRequest  $request
     * @param  \App\Models\Classe  $classe
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClasseRequest $request, Classe $classe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classe  $classe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classe $classe)
    {
        //
    }
}
