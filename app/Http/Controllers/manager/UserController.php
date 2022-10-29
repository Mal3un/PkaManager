<?php

namespace App\Http\Controllers\manager;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Js;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    use ResponseTrait;

    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = User::query();
        $this->table = (new User())->getTable();

        View::share('title', ucwords($this->table));
        View::share('table', $this->table);
    }

    public function index()
    {
        if (Auth::user()->role_id === 2) {
            $info = Teacher::query()->where('user_id', Auth::user()->id)->first()->only([
                'id', 'first_name', 'email', 'last_name', 'birthdate', 'address'
            ]);
            $info['birthdate'] = date('d-m-Y', strtotime($info['birthdate']));
            $info['name'] = $info['first_name'].' '.$info['last_name'];
            $info['role'] = RoleEnum::getKey(Auth::user()->role_id);
        } elseif (Auth::user()->role_id === 1) {
            $info = Student::query()->where('user_id', Auth::user()->id)->first()->only([
                'id', 'first_name', 'email', 'last_name', 'birthdate', 'address'
            ]);
            $info['birthdate'] = date('d-m-Y', strtotime($info['birthdate']));
            $info['name'] = $info['first_name'].' '.$info['last_name'];
            $info['role'] = RoleEnum::getKey(Auth::user()->role_id);
        }

        return view("manager.$this->table.index", [
            'info' => $info,
        ]);
    }

    public function changePassword(request $request): JsonResponse
    {
        try {
            $user = User::query()->where('id', Auth::user()->id)->first();
            if ((Hash::check($request->get('oldPass'), $user->password))) {
                $request->user()->fill([
                    'password' => Hash::make($request->get('newPass'))
                ])->save();
                return $this->successResponse('Password changed successfully');
            }
        } catch (Exception $e) {
            return $this->errorResponse('Old password is incorrect', 400);
        }
    }
}
