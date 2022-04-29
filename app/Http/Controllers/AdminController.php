<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator; 
use App\Models\User;
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
     $this->validate($request,[
        'name'=>'required|min:3',
        'email'=>'required|email|unique:users,email',
        'phone'=>'required|min:6',
        'password'=>'required|min:3|max:32',
     ],[
        'name.required'=>'Bạn chưa nhập Name',
        'name.min'=>'Tối thiểu 3 kí tự',
        'email.required'=>'Bạn chưa nhập Email',
        'email.email'=>'Bạn chưa định dạng email ',
        'email.unique'=>'email đã tồn tại ',
        'phone.required'=>'Bạn chưa nhập phone',
        'phone.min'=>'Tối thiểu 6 kí tự',
        'password.required'=>'Bạn chưa nhập password',
        'password.min'=>'Tối thiểu 3 kí tự',
        'password.max'=>'Tối đa 32 kí tự',
     ]);
         $newUser = new User();
         $newUser->name = $request->name;
         $newUser->email = $request->email;
         $newUser->phone = $request->phone;
         $newUser->password = $request->password;
         $newUser->save();     
         return redirect('sighup')->with('message','Bạn đã tạo thành công tài khoản');
     
   }

}
