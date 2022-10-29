<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\ResponseTrait;
use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\JsonResponse;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use ResponseTrait;
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Course::query();
        $this->table = (new Course())->getTable();

        View::share('title', ucwords($this->table));
        View::share('table', $this->table);
    }

    public function index(Request $request)
    {
        $selectedCourse = $request->get('courseName');
        $courseNames = $this->model->pluck('name', 'id');
        $query = $this->model->clone();
        if($selectedCourse !== 'All...' && $selectedCourse !== null){
            ($query->where('name', $selectedCourse));
        }
        $data = $query->paginate();
        return view("manager.$this->table.index",[
            'data' => $data,
            'selectedCourse'=>$selectedCourse,
            'courseNames'=>$courseNames
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseRequest $request) : JsonResponse
    {

        try{
            $arr = $request->validated();
            Course::create($arr);
            return $this->successResponse("thành công");
        }catch(exception $e){
            return $this->errorResponse("thất bại",$e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    public function edit($course)
    {
        $data = $this->model->findOrFail($course);
        return view('manager.courses.edit', ['data'=>$data]);
    }

    public function update(UpdateCourseRequest $request,$courseId): JsonResponse
    {
        try{
            $course = $this->model->find($courseId);
            $course->fill($request->validated());
            $course->save();
            return $this->successResponse("thành công");
        }catch(Exception $e){
            return $this->errorResponse("thất bại",$e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($courseId)
    {
        Course::destroy($courseId);
        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
