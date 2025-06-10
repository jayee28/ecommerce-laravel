<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Illuminate\Support\Facades\Session;


class AdminController extends Controller
{
    public function dashboard(){
        Session::put('page','dashboard');
        return view('admin.dashboard');
    }

    public function login(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>";
            // print_r($data);
            // die;

            // Check if email exists
            $admin = \App\Models\Admin::where('email', $data['email'])->first();
            if (!$admin) {
                return redirect()->back()->with('error_email', 'Email address not found.');
            }

            // Check if password is correct
            if (!Hash::check($data['password'], $admin->password)) {
                return redirect()->back()->with('error_password', 'Incorrect password.');
            }

            $rules = [
                'email'    => 'required|email|max:255',
                'password' => 'required|max:10',
            ];

            $customMessages = [
                'email.required'    => 'Email is required',
                'email.email'       => 'Please enter valid password',
                'password.required' => 'Password is required',
            ];

            $this->validate($request,$rules,$customMessages);

            if (Auth::guard('admin')->attempt(['email' => $data['email'],'password' => $data['password']])){
                return redirect('admin/dashboard');
            }else{
                return redirect()->back()->with('error_message','Login failed.');
            }
        }
        return view('admin.login');
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }

    public function updatePassword(Request $request){
        Session::put('page','update-password');
        if($request->isMethod('post')){
            $data = $request->all();
            //check if current password is correct
            if(Hash::check($data['current_password'],Auth::guard('admin')->user()->password)){
                //Check if new password and confirm password are matching
                if($data['new_password'] == $data['cnf_password']){
                    Admin::where('id',Auth::guard('admin')->user()->id)->update(['password'=>
                        bcrypt($data['new_password'])]);
                    return redirect()->back()->with('success_message','Password update successful.');

                }else{
                    return redirect()->back()->with('error_message','New password and confirm password didnot match.');

                }
            }else{
                return redirect()->back()->with('error_message','Your current password is incorrect.');
            }
        }
        return view('admin.update_password');
    }

    public function checkCurrentPassword(Request $request){
        $data = $request->all();
        if(Hash::check($data['current_password'],Auth::guard('admin')->user()->password)){
            return "true";
        }else{
            return "false";
        }
    }

    public function updateDetails(Request $request){
        Session::put('page','update-details');
         if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>";
            // print_r($data);
            // die;

            $rules = [
                'admin_name'   => 'required|regex:/^[\pL\s\-]+$/u|max:255',
                'admin_mobile' => 'required|numeric|digits:10',
                'admin_image'  => 'nullable|mimes:png,jpg,jpeg',
            ];

            $customMessages = [
                'admin_name.required'   => 'Name is required',
                'admin_name.regex'      => 'Please enter valid name',
                'admin_name.max'        => 'Please enter valid name',
                'admin_mobile.required' => 'Mobile is required',
                'admin_mobile.numeric'  => 'Please enter valid mobile number',
                'admin_mobile.digits'   => 'Mobile number should be 10 digits.',
                'admin_image.mimes'     => 'Only .jpg,.jpeg,.png extensions are allowed',
                
            ];

            $this->validate($request,$rules,$customMessages);

            //Upload admin image
            if($request->has('admin_image')){
                $file = $request->file('admin_image');
                $extension = $file->getClientOriginalExtension();

                $imageName = time().'.'.$extension;
                $path = 'admin/images/photos/';
                $file->move($path,$imageName);
            }else if(!empty($data['current_image'])){
                $imageName = $data['current_image'];
            }else{
                $imageName = "";
            }

            //Update admin details
            Admin::where('email',Auth::guard('admin')->user()->email)->update(['name' => $data['admin_name'],
            'mobile' => $data['admin_mobile'],'image' => $imageName]);
            return redirect()->back()->with('success_message','Admin details has been updated successfully.');

        }
        return view('admin.update_details');
    }
}
