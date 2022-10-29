<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exam;
use App\Models\Major;
use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExamController extends Controller
{
    use ResponseTrait;
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Exam::query();
        $this->table = (new Exam())->getTable();

        View::share('title', ucwords($this->table));
        View::share('table', $this->table);
    }

    public function index(request $request)
    {
        $selectedMajor = $request->get('majorName');
        $selectedCourse = $request->get('courseName');
        $selectedSubject = $request->get('subjectName');

        $major = Major::query()->get(['id', 'name']);
        $course = Course::query()->get(['id', 'name']);
        $subjects = Subject::query()->get(['id', 'name']);

        $query = $this->model->clone()
            ->with(['major:id,name', 'course:id,name','subject:id,name' ])
            ->latest();
        if(Auth::user()->role_id === 1) {
            $studentId = Student::query()->where('user_id', Auth::user()->id)->first()->id;
            $subject = Score::query()->where('student_id', $studentId)->pluck('subject_id');
            $subjects = Subject::query()->wherein('id', $subject)->get(['id', 'name']);
            $query->wherein('subject_id', $subject);
        }
        if($selectedCourse !== 'All...' && $selectedCourse !== null){
            ($query->where('course_id', $selectedCourse));
        }
        if($selectedMajor !== 'All...' && $selectedMajor !== null){
            ($query->where('major_id', $selectedMajor));
            $subject = Subject::query()->where('major_id', $selectedMajor)->get(['id', 'name']);
        }
        if($selectedSubject !== 'All...' && $selectedSubject !== null){
            ($query->where('subject_id', $selectedSubject));
        }
        $data = $query->paginate();

        return view("manager.$this->table.index",[
            'data' => $data,
            'majors' => $major,
            'courses' => $course,
            'subjects' => $subjects,

            'selectedMajor' => $selectedMajor,
            'selectedCourse' => $selectedCourse,
            'selectedSubject' => $selectedSubject,
        ]);
    }

    public function getSubject(request $request) : JsonResponse
    {
        try{
            $subject = Subject::query()->where('major_id', $request->majorId)->get(['id', 'name']);

            return $this->successResponse($subject);
        }
        catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(), 500);
        }
    }
    public function store(request $request) : jsonresponse
    {
        try{
            $arr = [
                'major_id' => $request->majorId,
                'course_id' => $request->courseId,
                'subject_id' => $request->subjectId,
                'time_start' => $request->timeStart,
                'day_exam' => $request->date,
                'room' => $request->room,
                'shift_exam' => $request->shift,
            ];
            $this->model->create($arr);
            return $this->successResponse('Create success');
        }catch(\Exception $exception){
            return $this->errorResponse($exception->getMessage(), 500);
        }
    }
}
