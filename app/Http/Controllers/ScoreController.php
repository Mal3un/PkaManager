<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Course;
use App\Models\Major;
use App\Models\Score;
use App\Http\Requests\StoreScoreRequest;
use App\Http\Requests\UpdateScoreRequest;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class ScoreController extends Controller
{
    use ResponseTrait;
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Score::query();
        $this->table = (new Score())->getTable();

        View::share('title', ucwords($this->table));
        View::share('table', $this->table);
    }

    public function index(request $request)
    {
        if(Auth::user()->role_id === 3){
            $selectedCourse = $request->courseName;
            $selectedMajor = $request->majorName;
            $selectedStudent = $request->studentsName;
            $selectedSubject = $request->subjectName;
            $course = Course::query()->get(['id', 'name']);
            $major = Major::query()->get(['id', 'name']);
            $subject = Subject::query()->get(['id', 'name','number_credits']);
            $student = Student::query()->get(['id', 'first_name', 'last_name']);
            $query = $this->model->clone()
                ->latest();
            if($selectedCourse !== 'All...' && $selectedCourse !== null){
                $subject = subject::query()->where('course_id', $selectedCourse)->get(['id', 'name','number_credits']);
                $student = Student::query()->where('course_id', $selectedCourse)->get(['id', 'first_name', 'last_name']);
            }
            if($selectedMajor !== 'All...' && $selectedMajor !== null){
                $subject = subject::query()->where('major_id', $selectedMajor)->get(['id', 'name','number_credits']);
                $student = Student::query()->where('major_id', $selectedMajor)->get(['id', 'first_name', 'last_name']);
            }
            if($selectedSubject !== 'All...' && $selectedSubject !== null){
                ($query->where('subject_id', $selectedSubject));
                $arr = Score::query()->where('subject_id', $selectedSubject)->pluck('student_id');
                $student = Student::query()->wherein('id', $arr)->get(['id', 'first_name', 'last_name']);
            }
            if($selectedStudent !== 'All...' && $selectedStudent !== null){
                ($query->where('student_id', $selectedStudent));
            }
            $data = $query->paginate();

            return view('manager.scores.index', [
                'data' => $data,

                'selectedSubject' => $selectedSubject,
                'selectedCourse' => $selectedCourse,
                'selectedMajor' => $selectedMajor,
                'selectedStudent' => $selectedStudent,

                'students' => $student,
                'subjects' => $subject,
                'courses' => $course,
                'majors' => $major,
            ]);
        }
        $selectedSubject = $request->subjectName;
        $subjects = Score::query()->where('student_id', Student::query()->where('user_id',Auth::id())->first()->id)->pluck('subject_id');
        $subject = Subject::query()->wherein('id', $subjects)->get(['id', 'name','number_credits']);
        $query = $this->model->clone()
            ->latest();
        $query->where('student_id',Student::query()->where('user_id',Auth::id())->first()->id);
        if($selectedSubject !== 'All...' && $selectedSubject !== null){
            ($query->where('subject_id', $selectedSubject));
        }
        $data = $query->paginate();
        return view('manager.scores.index', [
            'data' => $data,
            'selectedSubject' => $selectedSubject,
            'subjects' => $subject,
        ]);

    }

    public function updateLastScore(Request $request): JsonResponse
    {
        try{
            $score = Score::query()->where('student_id', $request->stdid)->where('subject_id',$request->subid)->first();

            $scoretb = ($request->score * 0.6) + ($score->midterm_score * 0.3) + ($score->listpoint_score * 0.1);
            $rank = Score::getRankByScore($scoretb);
            $last_score = $request->score;
            Score::query()->where('student_id', $request->stdid)->where('classe_id',$score->classe_id)->update([
                'lastterm_score' => $last_score,
                'rank' => $rank,
            ]);
            return $this->successResponse('Update successfully');
        }
        catch (\Exception $e){
            return $this->errorResponse($e->getMessage(),500);
        }

    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreScoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreScoreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function show(Score $score)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function edit(Score $score)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateScoreRequest  $request
     * @param  \App\Models\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateScoreRequest $request, Score $score)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function destroy(Score $score)
    {
        //
    }
}
