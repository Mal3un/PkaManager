<?php

    use App\Http\Controllers\AuthController;
    use Illuminate\Support\Facades\Route;
    /*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'authCheck'])->name('authCheck');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');



