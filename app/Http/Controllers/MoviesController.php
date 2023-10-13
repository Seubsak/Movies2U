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
use App\Models\favorite_movie;
use App\Models\reply;

class MoviesController extends Controller
{
    public function home(){
        $action = Movie_type::where('type_id',"MT01")->get();
        $comedy = Movie_type::where('type_id',"MT11")->get();
        $movie = Movie::all();
        $emp = Employee::all();
        $mtype = Movie_type::all();
        $ctr = Critical_rate::all();
        $favoriteMovies = favorite_movie::all();
        $totalLikesByMovie = favorite_movie::select('movie_id', favorite_movie::raw('SUM(`like`) as total_likes'))
            ->groupBy('movie_id')
            ->orderByDesc('total_likes') // เรียงตาม total_likes จากมากไปน้อย
            ->take(4) // แสดงเฉพาะ 4 อันดับแรก
            ->get();
        return view('movie2u.Home',compact('movie','mtype','emp','mtype','ctr','action','comedy', 'totalLikesByMovie', 'favoriteMovies'));
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
        $allmovie = Movie::all();
        $reviews = review::where('movie_id',$movieId)->get();
        $replies = reply::all();
        $user = User::all();
        $detail = Movie_detail::all();
        $emp = Employee::all();
        $empt = Employee_type::all();
        $mtype = Movie_type::all();
        $ctr = Critical_rate::all();
        return view('MovieDetail', compact('movie', 'emp', 'mtype', 'ctr','empt','detail','reviews','user','replies','allmovie'));
    }

    public function insertMovie(Request $request){
        $new_movie = new Movie;
        if ($request->score < 0 || $request->score > 10) {
            return redirect()->back()->with('error', 'Please input score 0-10')->withInput();
        }
        if($request->type == "" || $request->rate == "" || $request->time <= 0 || $request->info == ""){
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

        if ($request->type == "" || $request->rate == "" || $request->info == "") {
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
        //ตรวจสอบผู้ใช้ได้ล็อกอินมั้ย
        if (Auth::check()) {
            // ดึงข้อมูลผู้ใช้ที่ล็อกอินอยู่เก็บไว้ในตัวแปร $user
            $user = Auth::user();

            // ตรวจสอบว่าผู้ใช้ล็อกอินหรือยังหากล็อกอินแล้วทำต่อ
            if ($user) {
                // ตรวจสอบว่า movieId นี้มีอยู่ใน watchlist ของผู้ใช้หรือไม่
                $checkWatchlist = Watchlist::where('user_id', $user->id)->where('movie_id', $movieId)->first();

                if ($checkWatchlist) {
                    // ถ้าหากมีอยู่แล้ว โชว์ alert
                    return redirect()->back()->with('alert', 'This movie is already in your watchlist.');
                }

                // หากไม่พบ MovieId ใน watchlist ให้สร้างเพิ่มใน Watchlist object และกำหนดค่า 'user_id' และ 'movie_id'
                $add_watchlist = new Watchlist();
                $add_watchlist->user_id = $user->id;
                $add_watchlist->movie_id = $movieId;

                // บันทึกลงในตาราง watchlist
                $add_watchlist->save();

                //บันทึกเสร็จพร้อมโชว์ alert
                return redirect()->back()->with('success', 'Movie added to watchlist successfully.');
            }
        }
    }

    public function show_allwatchlist(){
        //ดึง user ID ของผู้ใช้ที่ล็อกอินอยู่
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
                $watchlistItem->delete(); // ใช้ SoftDelete

            return redirect('/MyWatchlist');
        } else {
            return redirect('/MyWatchlist');
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

    public function EditReview(Request $request){
        review::where('id', $request->id)->update([
            'review_info' => $request->info,
        ]);
        return redirect()->back();
    }

    public function DelReview($Id){
        $delcomment = review::where('id',$Id)->first();
        $delcomment->delete();
        return redirect()->back();
    }

    public function addReply(Request $request){
        $user = Auth::user();
            $add_reply = new reply;

            $add_reply->user_id = $user->id;
            $add_reply->review_id = $request->review;
            $add_reply->reply_info = $request->reply;

            $add_reply->save();
            return redirect()->back();
    }

    public function EditReply(Request $request){
        reply::where('id', $request->id)->update([
            'reply_info' => $request->info,
        ]);
        return redirect()->back();
    }
    public function DelReply($id){
        $Reply = reply::where('id', $id);
        $Reply->delete();
        return redirect()->back();
    }

    public function Favpage()
    {
        $user_id = auth()->user()->id;
        $favoriteMovies = favorite_movie::where('user_id', $user_id)->get();

        return view('movie2u.favmovies', compact('user_id', 'favoriteMovies'));
    }

    public function AddFav($movieId)
    {
        $user_id = auth()->user()->id;

        // ตรวจสอบว่าคู่ user_id และ movie_id นี้มีอยู่แล้วหรือไม่
        $existingFavorite = favorite_movie::where('user_id', $user_id)
            ->where('movie_id', $movieId)
            ->first();

        if (!$existingFavorite) {
            // ถ้ายังไม่มีให้เพิ่มเข้าไปในตาราง favorite_movies
            $favorite = new favorite_movie();
            $favorite->user_id = $user_id;
            $favorite->movie_id = $movieId;
            $favorite->like = 1; // กำหนดค่า like เป็น 1
            $favorite->save();
        }

        return redirect()->back(); // หลังจากเพิ่มเสร็จให้กลับไปยังหน้าก่อนหน้า
    }

    public function delFav($id)
    {
        $user_id = auth()->user()->id;

        // ใช้ forcedelete เพื่อลบรายการที่ตรงกันจากตาราง favorite_movies
        favorite_movie::where('user_id', $user_id)
            ->where('movie_id', $id)
            ->delete();

        return redirect()->back();
    }

    public function like()
    {
        // รวมยอดไลค์ตาม movie_id สำหรับทุก user
        $totalLikesByMovie = favorite_movie::select('movie_id', favorite_movie::raw('SUM(`like`) as total_likes'))
            ->groupBy('movie_id')
            ->get();

        // ดึงข้อมูลหนังโปรดของทุก user
        $favoriteMovies = favorite_movie::all();

        return view('movie2u.favmovies', compact('totalLikesByMovie', 'favoriteMovies'));
    }
}
