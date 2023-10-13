<?php

namespace App\Http\Controllers;

use App\Models\favorite_movie;
use App\Models\review;
use App\Models\User;
use App\Models\watchlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AddUserController extends Controller
{
    public function adduserform(){
        $users = User::all();
        return view('auth.adduserform',compact('users'));
    }

    public function AddUser(Request $request){
        $new_user = new User;
        $new_user->name = $request->name;
        $new_user->email = $request->email;
        $new_user->password = Hash::make($request->password);
        $new_user->roles = $request->roles;
        $new_user->save();
        return redirect()->back()->with('Add user completed.');
    }

    public function DelUser($id){
        $del_user = User::find($id);
        if(Auth::user()->id == $id){
            return redirect()->back()->with('error','Can not delete this user.');
        }
        watchlist::where('user_id', $id)->forcedelete();
        favorite_movie::where('user_id', $id)->forcedelete();
        review::where('user_id', $id)->forcedelete();
        $del_user->delete();
        return redirect()->back()->with('Deleted user success.');
    }
}
