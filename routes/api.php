<?php

    use App\Http\Controllers\api\StudentController;
    use App\Http\Controllers\api\TeacherController;
    use App\Http\Controllers\CourseController;
    use App\Http\Controllers\ExamController;
use App\Http\Controllers\ListPointController;
use App\Http\Controllers\MajorController;
    use App\Http\Controllers\manager\ClasseController;
    use App\Http\Controllers\manager\DivisionController;
    use App\Http\Controllers\manager\DivisonStudentController;
    use App\Http\Controllers\manager\PostController;
    use App\Http\Controllers\ScoreController;
    use App\Http\Controllers\SubjectController;
    use App\Http\Controllers\TestController;
    use App\Models\Major;
    use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::get('/test', [TestController::class, 'test'])->name('test');

    Route::get('/{course}', [CourseController::class, 'info'])->name('courses.info');
    Route::get('/subject/getSubjectByMajor', [SubjectController::class, 'getSubjectByMajor'])->name('subject.getSubjectByMajor');
    Route::get('/division/info', [DivisionController::class, 'info'])->name('division.info');
    Route::get('/division/info2', [DivisionController::class, 'info2'])->name('division.info2');
    Route::get('/division/set', [DivisionController::class, 'set'])->name('division.set');
    Route::get('/division/edit', [DivisionController::class, 'edit'])->name('division.edit');
    Route::get('/division/unset', [DivisionController::class, 'unset'])->name('division.unset');

    Route::get('/divisionStudent/info', [DivisonStudentController::class, 'info'])->name('divisionStudent.info');
    Route::post('/divisionStudent/set', [DivisonStudentController::class, 'set'])->name('divisionStudent.set');

    Route::post('/classes/point_list', [ClasseController::class, 'point_list'])->name('classes.point_list');
    Route::post('/classes/setPointList', [ClasseController::class, 'setPointList'])->name('classes.setPointList');
    Route::post('/classes/score', [ClasseController::class, 'score'])->name('classes.score');
    Route::post('/classes/setScore', [ClasseController::class, 'setScore'])->name('classes.setScore');

    Route::post('/scheduleSt/Schedule', [StudentController::class, 'Schedule'])->name('scheduleSt.Schedule');
    Route::post('/scheduleTc/Schedule', [TeacherController::class, 'Schedule'])->name('scheduleTc.Schedule');


    Route::post('/posts/preview', [PostController::class, 'preview'])->name('posts.preview');
    Route::post('/posts/upload_image', [PostController::class, 'upload_image'])->name('posts.upload_image');


    Route::post('/exams/getSubject', [ExamController::class, 'getSubject'])->name('exams.getSubject');

    Route::post('/scores/updateLastScore', [ScoreController::class, 'updateLastScore'])->name('scores.updateLastScore');

    Route::post ('/listpoint/import', [ListPointController::class, 'import'])->name('listpoint.import');







