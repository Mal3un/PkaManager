<?php

    namespace App\Http\Controllers\manager;

    use App\Http\Controllers\Controller;
    use App\Http\Controllers\ResponseTrait;
    use App\Models\Classe;
    use App\Models\Course;
    use App\Models\ListPoint;
    use App\Http\Requests\StoreListPointRequest;
    use App\Http\Requests\UpdateListPointRequest;
    use App\Models\Subject;
    use App\Models\Teacher;
    use Exception;
    use Illuminate\Http\Request;
    use Symfony\Component\HttpFoundation\JsonResponse;

    class DivisionController extends Controller
    {

        use ResponseTrait;
        public function index(Request $request)
        {
            $selectedTeacher = $request->get('teacher');
            $query = Teacher::query()->clone()
//                ->with(['subject:id,name' ])
                ->latest();
            if($selectedTeacher !== 'All...' && !empty($selectedTeacher)){
                $query->where('id',  $selectedTeacher);
            }
            $data = $query->paginate();
            $classes = Classe::query()->get();
            return(view('manager.division.index', [
                'title' => 'Division',
                'data' => $data,
                'classes' => $classes,
                'selectedTeacher' => $selectedTeacher
            ]));
        }
        public function index2(Request $request)
        {

            $teacher = Teacher::query()->get();
            $classes = Classe::query()->get()->where('teacher_id', '=', null);
            return(view('manager.division.division', [
                'title' => 'Division',
                'teachers' => $teacher,
                'classes' => $classes,

            ]));
        }
        public function info(Request $request): JsonResponse
        {
            $count = count($request->get('classes'));
            $teacherInfo = Teacher::find($request->get('teacher'));
            $classes = [];
            for($i = 0; $i < $count; $i++){
                $classes[$i] = Classe::find($request->get('classes')[$i]);
            }
            return $this->successResponse([$teacherInfo, $classes,]);
        }
        public function info2(Request $request): JsonResponse
        {
            $teacherInfo = Teacher::find($request->get('teacher'));
            $classes= Classe::query()->get()->where('teacher_id', '=', $request->get('teacher'))->pluck('name','id');
            return $this->successResponse([$teacherInfo,$classes]);
//            $teacherInfo = Teacher::find($request->get('teacher'));
//            $classes = Classe::find($request->get('classes'));
//            $selectedTeacher = $request->get('teacher');
//            $selectedClass = $request->get('class');
//            return $this->successResponse([$teacherInfo, $classes,]);
        }

        public function set(Request $request)
        {

            $request['class_id'] = substr($request->get('class_id'), 0, -1);
            $arr = explode(",",$request['class_id']);
            try{
                foreach($arr as $class_id){
                    $class = Classe::find($class_id);
                    $class->teacher_id = $request['teacher_id'];
//                    $list_point = ListPoint::query()->where('classe_id', '=', $class_id)->get();
//                    if(!$list_point->isEmpty()){
//                        foreach($list_point as $point){
//                            $point->teacher_id = $request['teacher_id'];
//                            $point->save();
//                        }
//                    }
                    $class->save();
                }
                return $this->successResponse();
            }catch(Exception $e){
                return $this->errorResponse($e->getMessage(), 500);
            }
        }

        public function unset(Request $request){
            try{
                $class = Classe::query()->where('id','=', $request->get('classId'))->where('teacher_id','=', $request->get('teacherId'))->first();
                $class->teacher_id = null;
                $class->save();
                return $this->successResponse();
            }catch(Exception $e){
                return $this->errorResponse($e->getMessage(), 500);
            }
        }
        public function create()
        {
            //
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \App\Http\Requests\StoreListPointRequest  $request
         * @return \Illuminate\Http\Response
         */
        public function store(StoreListPointRequest $request)
        {
            //
        }

        /**
         * Display the specified resource.
         *
         * @param  \App\Models\ListPoint  $listPoint
         * @return \Illuminate\Http\Response
         */
        public function show(ListPoint $listPoint)
        {
            //
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  \App\Models\ListPoint  $listPoint
         * @return \Illuminate\Http\Response
         */
        public function edit(Request $request): JsonResponse
        {
            $teacherInfo = Teacher::find($request->get('teacher'));
            $classes= Classe::query()->get()->where('teacher_id', '=', $request->get('teacher'))->pluck('name','id');
            return $this->successResponse([$teacherInfo,$classes]);
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \App\Http\Requests\UpdateListPointRequest  $request
         * @param  \App\Models\ListPoint  $listPoint
         * @return \Illuminate\Http\Response
         */
        public function update(UpdateListPointRequest $request, ListPoint $listPoint)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  \App\Models\ListPoint  $listPoint
         * @return \Illuminate\Http\Response
         */
        public function destroy(ListPoint $listPoint)
        {
            //
        }
    }
