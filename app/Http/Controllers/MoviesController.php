<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Employee_type;
use App\Models\EmployeeType;
use App\Models\review;
use App\Models\watchlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Movie_type;
use App\Models\Critical_rate;
use App\Models\Movie_detail;
use App\Models\User;

class MoviesController extends Controller
{
    public function home(){
        $action = Movie_type::where('type_id',"MT01")->get();
        $comedy = Movie_type::where('type_id',"MT03")->get();
        $movie = Movie::all();
        $emp = Employee::all();
        $mtype = Movie_type::all();
        $ctr = Critical_rate::all();
        return view('movie2u.Home',compact('movie','mtype','emp','mtype','ctr','action','comedy'));
    }
    public function category(){
        $mtype = Movie_type::all();
        $type = Movie_type::all();
        $movie = Movie::all();
        $ctr = Critical_rate::all();
        return view('movie2u.Category',compact('movie','mtype','ctr','type'));
    }

    public function showList($Id){
        $mtype = Movie_type::where('type_id',$Id)->get();
        $type = Movie_type::all();
        $movie = Movie::all();
        $ctr = Critical_rate::all();
        return view('movie2u.TypeList',compact('movie','mtype','ctr','type'));
    }
    public function manage(){
        $movie = Movie::all();
        $emp = Employee::all();
        $mtype = Movie_type::all();
        $ctr = Critical_rate::all();
        return view('MovieManagement',compact('movie','emp','mtype','ctr'));
    }
    public function showType($Id){
        $mtype = Movie_type::where('type_id',$Id)->get();
        $type = Movie_type::all();
        $movie = Movie::all();
        $ctr = Critical_rate::all();
        return view('movie2u.TypeList',compact('movie','mtype','ctr','type'));
    }
    public function showTypefilter($Id){
        $mtype = Movie_type::where('type_id',$Id)->get();
        $type = Movie_type::all();
        $movie = Movie::all();
        $ctr = Critical_rate::all();
        return view('moviemanagementfilter',compact('movie','mtype','ctr','type'));
    }

    public function showMovieDetails($movieId)
    {
        $movie = Movie::where('movie_id',$movieId)->get();
        if (!$movie) {
            return abort(404);
        }
        $reviews = review::where('movie_id',$movieId)->get();
        $user = User::all();
        $detail = Movie_detail::all();
        $emp = Employee::all();
        $empt = Employee_type::all();
        $mtype = Movie_type::all();
        $ctr = Critical_rate::all();
        return view('MovieDetail', compact('movie', 'emp', 'mtype', 'ctr','empt','detail','reviews','user'));
    }

    public function insertMovie(Request $request){
        $new_movie = new Movie;
        if ($request->score < 0 || $request->score > 10) {
            return redirect()->back()->with('error', 'Please input score 0-10')->withInput();
        }
        if($request->type == "" || $request->rate == "" || $request->time <= 0){
            return redirect()->back()->with('error', 'Add failed')->withInput();
        }
        if ($request->hasFile('img')) {
            $imgFile = $request->file('img');
            $imgFileName = $request->id . '.png'; // กำหนดนามสกุลเป็น .png
            if ($imgFile->getClientOriginalExtension() !== 'png') {
                return redirect()->back()->with('error', 'รูปภาพต้องเป็นไฟล์ .png เท่านั้น')->withInput();
            }
            $imgFile->move(public_path('Materials/Movies'), $imgFileName);
        }

        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $videoFileName = $request->id . '.mp4'; // กำหนดนามสกุลเป็น .mp4
            if ($videoFile->getClientOriginalExtension() !== 'mp4') {
                return redirect()->back()->with('error', 'วิดีโอต้องเป็นไฟล์ .mp4 เท่านั้น')->withInput();
            }
            $videoFile->move(public_path('Materials/Movies'), $videoFileName);
        }
        $new_movie->movie_id = $request->id;
        $new_movie->movie_name = $request->name;
        $new_movie->movie_time = $request->time;
        $new_movie->movie_year_on_air = $request->year;
        $new_movie->critical_rate = $request->rate;
        $new_movie->movie_score = $request->score;
        $new_movie->movie_type_id = $request->type;
        $new_movie->movie_info = $request->info;
        $new_movie->save();
        return redirect('/moviemanagement');
    }
    public function movieform(){
        $detail = Movie_detail::all();
        $emp = Employee::all();
        $empt = Employee_type::all();
        $movie = Movie::all();
        $mtype = Movie_type::all();
        $ctr = Critical_rate::all();
        return view('insertMovieForm',compact('movie','emp','mtype','ctr','empt','detail'));
    }

    public function deleteMovie($id) {
        $movie = Movie::where('movie_id',$id);
        $material = Movie::where('movie_id',$id)->first();
        if ($movie) {
            $imagePath = public_path('Materials/Movies/' . $material->movie_id . '.png');
            $videoPath = public_path('Materials/Movies/' . $material->movie_id . '.mp4');
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            if (file_exists($videoPath)) {
                unlink($videoPath);
            }
            Movie_detail::where('movie_id', $id)->forcedelete();
            watchlist::where('movie_id', $id)->forcedelete();
            review::where('movie_id', $id)->forcedelete();
            $movie->delete(); // Soft delete
            return redirect('/moviemanagement')->with('success', 'Movie deleted successfully.');
        } else {
            return redirect('/moviemanagement')->with('error', 'Movie not found.');
        }
    }

    public function editForm($id){
        $edit_movie = Movie::where('movie_id',$id)->get();
        $movie = Movie::all();
        $detail = Movie_detail::all();
        $emp = Employee::all();
        $empt = Employee_type::all();
        $mtype = Movie_type::all();
        $ctr = Critical_rate::all();
        return view('editMovieForm', compact('movie', 'emp', 'mtype', 'ctr','edit_movie','empt','detail'));
    }
    public function update(Request $request) {

        $edit_movie = Movie::where('movie_id', $request->id);

        if (!$edit_movie) {
            return redirect()->back()->with('error', 'Movie not found.')->withInput();
        }

        if ($request->score < 0 || $request->score > 10) {
            return redirect()->back()->with('error', 'Please input score 0-10')->withInput();
        }

        if ($request->type == "" || $request->rate == "") {
            return redirect()->back()->with('error', 'Please input type or rate.')->withInput();
        }

        if ($request->hasFile('img')) {
            $imgFile = $request->file('img');
            $imgFileName = $request->id . '.png'; // กำหนดนามสกุลเป็น .png

            // ตรวจสอบนามสกุลไฟล์
            if ($imgFile->getClientOriginalExtension() !== 'png') {
                return redirect()->back()->with('error', 'รูปภาพต้องเป็นไฟล์ .png เท่านั้น')->withInput();
            }

            // ลบรูปภาพเดิม (หากมี)
            if (file_exists(public_path('Materials/Movies/' . $imgFileName))) {
                unlink(public_path('Materials/Movies/' . $imgFileName));
            }

            // บันทึกรูปภาพใหม่
            $imgFile->move(public_path('Materials/Movies'), $imgFileName);
        }

        // อัปโหลดวิดีโอใหม่ (หากมีการเลือกไฟล์)
        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $videoFileName = $request->id . '.mp4'; // กำหนดนามสกุลเป็น .mp4

            // ตรวจสอบนามสกุลไฟล์
            if ($videoFile->getClientOriginalExtension() !== 'mp4') {
                return redirect()->back()->with('error', 'วิดีโอต้องเป็นไฟล์ .mp4 เท่านั้น')->withInput();
            }

            // ลบวิดีโอเดิม (หากมี)
            if (file_exists(public_path('Materials/Movies/' . $videoFileName))) {
                unlink(public_path('Materials/Movies/' . $videoFileName));
            }

            // บันทึกวิดีโอใหม่
            $videoFile->move(public_path('Materials/Movies'), $videoFileName);
        }

        // อัปเดตข้อมูลภาพยนต์
        Movie::where('movie_id', $request->id)->update([
            'movie_name' => $request->name,
            'movie_time' => $request->time,
            'movie_year_on_air' => $request->year,
            'critical_rate' => $request->rate,
            'movie_score' => $request->score,
            'movie_type_id' => $request->type,
            'movie_info' => $request->info
        ]);

        return redirect('/moviemanagement');
    }
    public function addwatchlist($movieId)
    {
        $user_id = Auth::id();

        // ตรวจสอบว่ามีการล็อกอินหรือไม่
        if (Auth::check()) {
            // ดึงข้อมูลผู้ใช้ที่ล็อกอินอยู่
            $user = Auth::user();

            // ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
            if ($user) {
                // สร้าง Watchlist object และกำหนดค่า 'user_id' และ 'movie_id'
                $add_watchlist = new Watchlist();
                $add_watchlist->user_id = $user->id;
                $add_watchlist->movie_id = $movieId;

                // บันทึกลงใน watchlist
                $add_watchlist->save();

        }
        return redirect()->back();
    }
}

public function show_allwatchlist(){
    $user_id = Auth::id();

    // ดึง movie_id ที่เกี่ยวข้องกับ user_id นี้จาก watchlist
    $watchlistMovies = watchlist::where('user_id', $user_id)->pluck('movie_id');

    // ดึงข้อมูลหนังที่มี movie_id ใน watchlist
    $moviesInWatchlist = Movie::whereIn('movie_id', $watchlistMovies)->get();

    return view('movie2u.Watchlist', compact('user_id', 'moviesInWatchlist'));
}

public function deletewatchlist($id) {
    $watchlistItem = watchlist::where('movie_id', $id)->first();

    if ($watchlistItem) {
            $watchlistItem->delete();

        return redirect('/MyWatchlist')->with('success', 'Movie deleted successfully.');
    } else {
        return redirect('/MyWatchlist')->with('error', 'Movie not found.');
    }
}

        public function Addreview(Request $request){
            $user = Auth::user();
            $add_review = new review;

            $add_review->user_id = $user->id;
            $add_review->movie_id = $request->movie;
            $add_review->review_info = $request->comment;

            $add_review->save();
            return redirect()->back();
    }

    public function Delcomment($Id){
        $delcomment = review::where('id',$Id)->first();
        $delcomment->forcedelete();
        return redirect()->back();
    }
}
