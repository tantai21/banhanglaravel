<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator; 
use App\User;
session_start();
class AdminController extends Controller
{
    public function index(){
       return view('admin_login');
    }
    public function showDashboard(){
       return view('admin.dashboard');
    }
    public function dashboard(Request $request){
      $admin_email = $request->admin_email;
      $admin_password = md5($request->admin_password);
      $result = DB::table('tbl_admin')->where('admin_email',$admin_email)->where('admin_password',$admin_password)->first();
      if($result){
         Session::put('admin_name',$result->admin_name);
         Session::put('admin_id',$result->admin_id);
         return Redirect::to('/dashboard');   
      }
         else{
            Session::put('message','Mật khẩu hoặc tài khoản bị sai vui lòng nhập lại !');
            return Redirect::to('/admin');
         }   
    }
    public function logout(){
      return Redirect::to('/admin');
    }
    public function signUp(){
       return view('admin_signup');
    }

    public function getDangKy(){
      return view(admin_signup);
    }
    public function postSignUp(Request $request){
      $validator = Validator::make($request->all(),[
         'name'=>'required|max:30|alpha',
         'email'=>'required|email',
         'phone'=>'required|numeric|min:11',
         'password'=>'required|confirmed|min:6|max:16',

      ]);
      if($validator->fails()){
         return redirect()->back()
         ->withErrors($validator)
         ->withInput();
      }
      $user = DB::table('tbl_admin')->where('admin_email',$request->email)->first();
      if(!$user){
         $newUser = new User();
         $newUser->admin_name = $request->name;
         $newUser->admin_email = $request->email;
         $newUser->admin_phone = $request->phone;
         $newUser->admin_password = $request->name;
         $newUser->save();
         // return redirect()->route('admin_signup')->with('message','Bạn đã tạo thành công tài khoản');
         return redirect('sighup')->with('message','Bạn đã tạo thành công tài khoản');
      }
      // else{
      //    return redirect()->route('admin_login')->with('message','Tài Khoản đã tồn tại');

      // }
    }
}
