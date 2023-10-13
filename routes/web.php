<?php

use App\Http\Controllers\AddUserController;
use App\Http\Controllers\EmployeeController;
use App\Models\Critical_rate;
use App\Models\Employee;
use App\Models\Movie;
use App\Models\Movie_type;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoviesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $action = Movie_type::where('type_id',"MT01")->get();
    $comedy = Movie_type::where('type_id',"MT03")->get();
    $movie = Movie::all();
    $emp = Employee::all();
    $mtype = Movie_type::all();
    $ctr = Critical_rate::all();
    return view('welcome',compact('movie','mtype','emp','mtype','ctr','action','comedy'));
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
    $action = Movie_type::where('type_id',"MT01")->get();
    $comedy = Movie_type::where('type_id',"MT03")->get();
    $movie = Movie::all();
    $emp = Employee::all();
    $mtype = Movie_type::all();
    $ctr = Critical_rate::all();
    return view('welcome',compact('movie','mtype','emp','mtype','ctr','action','comedy'));

})->name('welcome');
    Route::post('/review',[MoviesController::class,'Addreview'])->name('Addreview');
    Route::post('/reviewUpdate',[MoviesController::class,'EditReview'])->name('reviewUpdate');
    Route::get('/delcomment/{Id}',[MoviesController::class,'DelReview'])->name('DelReview');
    Route::post('/reply',[MoviesController::class,'addReply'])->name('addReply');
    Route::post('/replyUpdate',[MoviesController::class,'EditReply'])->name('replyUpdate');
    Route::get('/delreply/{Id}',[MoviesController::class,'DelReply'])->name('DelReply');

    Route::middleware(['auth', 'admin:1'])->group(function () {
        Route::get('/addwatchlist/{movieId}', [MoviesController::class,'addwatchlist']);
        Route::get('/MyWatchlist', [MoviesController::class,'show_allwatchlist']);
        Route::get('/watchlist/delete/{id}', [MoviesController::class,'deletewatchlist']);
        Route::get('/favpage', [MoviesController::class, 'Favpage'])->name('favorite');
        Route::get('/addfav/{movieId}', [MoviesController::class, 'AddFav'])->name('addFav');
        Route::get('/delfav/{movieId}', [MoviesController::class, 'delFav'])->name('delFav');
        Route::get('/favpage', [MoviesController::class, 'like'])->name('likes');
    });
    Route::middleware(['auth', 'admin:2'])->group(function () {
        Route::get('/addUserForm',[AddUserController::class,'adduserform'])->name('adduserform');
        Route::post('/addUserForm/addUser',[AddUserController::class,'AddUser'])->name('AddUser');
        Route::get('/addUserForm/deleteUser/{id}',[AddUserController::class,'DelUser'])->name('DelUser');
        Route::get('/moviemanagement',[MoviesController::class,'manage'])->name('manage');
        Route::get('/moviemanagementEmp',[EmployeeController::class,'ManageEmp'])->name('ManageEmp');
        Route::get('/moviemanagement/type/{Id}', [MoviesController::class,'showTypefilter'])->name('showType');
        Route::get('/moviemanagementEmp/movie/{Id}', [EmployeeController::class,'filterEmp'])->name('filterEmp');
        Route::get('/moviemanagement/forminsertmovie',[MoviesController::class,'movieform'])->name('movieform');
        Route::get('/moviemanagementEmp/forminsertEmp',[EmployeeController::class,'InsertEmpForm'])->name('InsertEmpForm');
        Route::post('/moviemanagement/insert',[MoviesController::class,'insertMovie'])->name('insertMovie');
        Route::post('/moviemanagementEmp/insertEmp',[EmployeeController::class,'AddEmp'])->name('AddEmp');
        Route::get('/moviemanagement/delete/{id}', [MoviesController::class,'deleteMovie'])->name('deleteMovie');
        Route::get('/moviemanagementEmp/deleteEmp/{emp_id}',[EmployeeController::class,'deleteEmployee'])->name('deleteEmployee');
        Route::get('/moviemanagement/editForm/{id}', [MoviesController::class,'editForm'])->name('editForm');
        Route::get('/moviemanagementEmp/editEmpForm/{id}', [EmployeeController::class,'EditEmpForm'])->name('EditEmpForm');
        Route::post('/moviemanagement/update', [MoviesController::class,'update'])->name('update');
        Route::post('/moviemanagementEmp/updateEmp', [EmployeeController::class,'EditEmp'])->name('EditEmp');
    });
});

Route::get('/moviedetail/{movieId}', [MoviesController::class,'showMovieDetails']);
Route::get('/home',[MoviesController::class,'home']);
Route::get('/type/{Id}', [MoviesController::class,'showType']);
Route::get('/category', [MoviesController::class,'category']);


