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


class MajorController extends Controller
{
    use ResponseTrait;
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Major::query();
        $this->table = (new Major())->getTable();

        View::share('title', ucwords($this->table));
        View::share('table', $this->table);
    }
    public function index(Request $request)
    {
        $selectedMajor = $request->get('majorName');
        $majorNames = $this->model->pluck('name', 'id');
        $query = $this->model->clone();
        if($selectedMajor !== 'All...' && $selectedMajor !== null){
            ($query->where('name', $selectedMajor));
        }
        $subject = Subject::query()->get();
        $data = $query->paginate();
        return view('manager.majors.index',[
            'data' => $data,
            'subjects'=>$subject,
            'selectedMajor'=> $selectedMajor,
            'majorNames'=>$majorNames
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


    public function store(StoreMajorRequest $request):JsonResponse
    {
        try{
            $arr = $request->validated();
            Major::create($arr);
            return $this->successResponse("thành công");
        }catch(exception $e){
            return $this->errorResponse("thất bại",$e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function show(Major $major)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function edit(Major $major)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMajorRequest  $request
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMajorRequest $request, Major $major)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function destroy(Major $major)
    {
        //
    }
}
