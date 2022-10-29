<?php

namespace App\Http\Controllers\manager;

use App\Enums\StatusPostEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class PostController extends Controller
{
    use ResponseTrait;
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Post::query();
        $this->table = (new Post())->getTable();

        View::share('title', ucwords($this->table));
        View::share('table', $this->table);
    }

    public function index(request $request)
    {
        $selectedPost = $request->get('select-post');
        $selectedStatus = $request->get('select-status');
        $query = $this->model->clone();
        if($selectedPost !== 'All...' && $selectedPost !== null){
            ($query->where('title', $selectedPost));
        }
        if(Auth::user()->role_id !== 3){
            $query->where('status', '=',1);
        }else{
            if($selectedStatus !== 'All...' && $selectedStatus !== null){
                ($query->where('status', $selectedStatus));
            }
        }
        $data = $query->latest()->paginate();
        $status = StatusPostEnum::asArray();
        return view("manager.$this->table.index",[
            'data' => $data,
            'selectedPost'=>$selectedPost,
            'selectedStatus'=>$selectedStatus,
            'status'=>$status
        ]);
    }

    public function create()
    {
        return view("manager.$this->table.create",[
            'title'=>'Create ' . $this->table,
        ]);
    }

    public function store(request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:posts',
            'content' => 'required',
        ]);
        $imageName = 'default.png';
        if(request()->has('image')) {
            $image = request()->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images/post'), 'images/post/'.$imageName);
        }

        $arr = [
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'image' => 'images/post/'.$imageName,
            'user_id' => auth()->user()->id,
            'status' => auth()->user()->role_id === 3 ? 1 : 0,
        ];
        $this->model->create($arr);
        return redirect()->route("manager.$this->table.index");
    }


    public function preview(request $request) : JsonResponse
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $file = $request->file('image');

//        $image = $file->store('public/images/posts');
        dd($file);
//        dd($image);
        return $this->responseSuccess($request->all());

    }

    public function detail($id)
    {
        $data = $this->model->find($id);
        return view("manager.$this->table.detail",[
            'data' => $data,
        ]);
    }

    public function publicPost($id){
        $data = $this->model->find($id);
        $data->status = 1;
        $data->save();
        return redirect()->route("manager.$this->table.index");
    }
}
