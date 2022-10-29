<?php

namespace App\Http\Controllers\manager;


use App\Enums\ClassTypeEnum;
use App\Enums\SubjectTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Classe;
use App\Models\Major;
use App\Models\Subject;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Throwable;


class SubjectController extends Controller
{


    use ResponseTrait;

    private object $model;
    private string $table;
    public function __construct()
    {
        $this->model = Subject::query();
        $this->table = (new Subject())->getTable();

        View::share('title', ucwords($this->table));
        View::share('table', $this->table);
    }

    public function index(Request $request)
    {
        $selectedMajor = $request['major'];
        $selectedSubject = $request['subject'];
        $query = $this->model->clone()
            ->with(['major:id,name'])
            ->latest();
        if($selectedSubject !== 'All...' && !empty($selectedSubject)){
            $query->where('id',  $selectedSubject);
        }
        if($selectedMajor !== 'All...' && !empty($selectedMajor)){
            $query->whereHas('major',  function($q) use ($selectedMajor){
                return $q->where('major_id',$selectedMajor);
            });
        }
        $data = $query->paginate();
        $major = Major::all();
        $subject = Subject::all();
        $subject_type = SubjectTypeEnum::getKeys();
        if(($selectedMajor !== 'All...' && !empty($selectedMajor))) {
            $subject = Subject::query()->where('major_id', $selectedMajor)->get();
        }
        return (view('manager.subjects.index', [
            'title' => $this->table,
            'data' => $data,
            'subjects' => $subject,
            'majors' => $major,

            'selectedSubject' => $selectedSubject,
            'selectedMajor' => $selectedMajor,

            'subject_types' => $subject_type,
        ]));
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
     * @param  \App\Http\Requests\StoreSubjectRequest  $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function store(StoreSubjectRequest $request) : JsonResponse
    {
        DB::beginTransaction();
        try{
                $arr = $request->only([
                    'name',
                    'major_id',
                    'number_credits',
                    'study_type',
                ]);
                Subject::create($arr);
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
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubjectRequest  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        //
    }
}
