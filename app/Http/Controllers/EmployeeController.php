<?php

namespace App\Http\Controllers;

use App\Models\Critical_rate;
use App\Models\Employee;
use App\Models\Employee_type;
use App\Models\Movie;
use App\Models\Movie_detail;
use App\Models\Movie_type;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function ManageEmp(){
        $emp = Employee::all();
        $empt = Employee_type::all();
        $detail = Movie_detail::all();
        $movies = Movie::all();
        return view('EmpManagement',compact('emp','empt','detail','movies'));
    }
    public function InsertEmpForm(){
        $emp = Employee::all();
        $empt = Employee_type::all();
        $detail = Movie_detail::all();
        $movie = Movie::all();
        $mtype = Movie_type::all();
        $ctr = Critical_rate::all();
        return view('InsertEmpForm',compact('emp','empt','detail','movie','mtype','ctr'));
    }
    public function EditEmpForm($id){
        $edit_emp = Employee::where('emp_id',$id)->get();
        $emp = Employee::all();
        $empt = Employee_type::all();
        $edit_detail = Movie_detail::where('emp_id',$id)->get();
        $detail = Movie_detail::all();
        $movie = Movie::all();
        $mtype = Movie_type::all();
        $ctr = Critical_rate::all();
        return view('editEmpForm',compact('emp','empt','detail','movie','mtype','ctr','edit_emp','edit_detail'));
    }

    public function AddEmp(Request $request) {
        // ตรวจสอบว่า ID และชื่อไม่มีการซ้ำในตาราง Employee
        $existingEmp = Employee::where('emp_id', $request->id)
            ->orWhere('emp_name', $request->name)
            ->count();

        if ($existingEmp > 0) {
            return redirect()->back()->with('error', 'ID หรือชื่อบุคคลซ้ำในระบบ');
        }
        $new_emp = new Employee;
        $new_emp->emp_id = $request->id;
        $new_emp->emp_name = $request->name;
        $new_emp->save();
        // ดึงค่า movie และ type ที่ถูกเลือกและบันทึกลงในตาราง movie_detail
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'movie_') === 0 && $value) {
                $movie_id = substr($key, 6); // เอาเฉพาะ ID ของหนัง
                $new_detail = new Movie_detail;
                $new_detail->emp_id = $request->id;
                $new_detail->movie_id = $movie_id;
                $new_detail->emp_type_id = $request->input('type_' . $movie_id);
                $new_detail->save();
            }
        }

        return redirect('/moviemanagementEmp');
    }
    public function EditEmp(Request $request){
        // ตรวจสอบว่าชื่อบุคคลไม่ซ้ำกับบุคคลอื่น
        $existingEmp = Employee::where('emp_name', $request->name)
            ->where('emp_id', '!=', $request->id)
            ->count();

        if ($existingEmp > 0) {
            return redirect()->back()->with('error', 'ชื่อบุคคลซ้ำในระบบ');
        }

        // อัปเดตชื่อของบุคคล
        Employee::where('emp_id', $request->id)->update([
            'emp_name' => $request->name
        ]);

        // รับรายการหนังที่ถูกเลือกมาจากฟอร์ม
        $selectedMovies = $request->input('movie_ids', []);

        // ตรวจสอบและลบ Movie_detail ที่ไม่อยู่ในรายการที่ถูกเลือก


        // อัปเดตหรือเพิ่มข้อมูล Movie_detail
        foreach ($selectedMovies as $movie_id) {
            // ตรวจสอบว่าหนังนี้ถูกเลือกหรือไม่
            if ($request->has('movie_' . $movie_id)) {
                $emp_type_id = $request->input('type_' . $movie_id);

                // ตรวจสอบว่า 'emp_type_id' ไม่เป็น null ก่อนที่จะบันทึกข้อมูล
                if (!is_null($emp_type_id)) {
                    $existingDetail = Movie_detail::where('emp_id', $request->id)
                        ->where('movie_id', $movie_id)
                        ->first();

                    if ($existingDetail) {
                        // อัปเดตข้อมูล Movie_detail
                        $existingDetail->emp_type_id = $emp_type_id;
                        $existingDetail->save();
                    } else {
                        // เพิ่มข้อมูล Movie_detail ใหม่
                        $new_detail = new Movie_detail;
                        $new_detail->emp_id = $request->id;
                        $new_detail->movie_id = $movie_id;
                        $new_detail->emp_type_id = $emp_type_id;
                        $new_detail->save();
                    }
                }
            }
            else{
                Movie_detail::where('emp_id', $request->id)
                ->where('movie_id', $movie_id)
                ->delete();
            }
        }

        return redirect('/moviemanagementEmp');
    }

    public function deleteEmployee($emp_id) {

        $employee = Employee::where('emp_id', $emp_id)->first();
        $delEmp = Employee::where('emp_id', $emp_id);

        if ($employee) {
            Movie_detail::where('emp_id', $emp_id)->forcedelete();


            $delEmp->delete();
            return redirect('/moviemanagementEmp')->with('success', 'ลบพนักงานเรียบร้อยแล้ว');
        } else {
            return redirect('/moviemanagementEmp')->with('error', 'ไม่พบพนักงานที่ต้องการลบ');
        }
    }

    public function filterEmp($id){
        $emp = Employee::all();
        $empt = Employee_type::all();
        $detail = Movie_detail::where('movie_id',$id)->get();
        $movies = Movie::where('movie_id',$id)->get();
        $allmovie = Movie::all();
        return view('EmpManagementFilter',compact('emp','empt','detail','movies','allmovie'));
    }


}
