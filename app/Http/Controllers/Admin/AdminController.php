<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\AdminsRole;
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
                
                //Remember Admin email and password with cookies
                if(isset($data['remember']) && !empty($data['remember'])){
                    setcookie('email',$data['email'],time()+3600);//for 1 hr
                    setcookie('password',$data['password'],time()+3600);//for 1 hr
                }else{
                    setcookie('email','');
                    setcookie('password','');
                }
                
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

    public function subadmins(){
        if(Auth::guard('admin')->user()->type == 'admin'){
            Session::put('page','subadmins');
            $subadmins = Admin::where('type','subadmin')->get();
            return view('admin.subadmins.subadmins')->with(compact('subadmins'));
        }else{
            $message = "Sorry,this feature is not allowed for you.";
            return redirect('admin/dashboard')->with('error_message',$message);
        }
        
    }

    public function updateSubadminStatus(Request $request){
        Session::put('page','subadmins');
        if ($request->ajax()) {
            $data = $request->all();

            $newStatus = ($data['status'] == 'Active') ? 0 : 1;

            Admin::where('id', $data['subadmin_id'])->update(['status' => $newStatus]);

            return response()->json([
                'status' => $newStatus,
                'subadmin_id' => $data['subadmin_id']
            ]);
        }
    }

    public function deleteSubadmin($id)
    {
        Session::put('page','subadmins');
        //Delete subadmin
        Admin::where('id',$id)->delete();
        return redirect()->back()->with('success_message','Subadmin deleted successfully.');
    }

    public function addEditSubadmin(Request $request,$id=null)
    {
        Session::put('page','subadmins');
        if($id==""){
            $title = "Add Subadmin";
            $subadmindata = new Admin();
            $message = "Subadmin added successfully.";
        }else{
            $title = "Edit Subadmin";
            $subadmindata = Admin::find($id);
            $message = "subadmin updated successfully.";
        }

        if($request->isMethod('POST')){
            $data = $request->all();
            // echo "<pre>";print_r($data);die;

            if($id==""){
                $subadminCount = Admin::where('email',$data['email'])->count();
                if($subadminCount>0){
                    return redirect()->back()->with('error_message','Subadmin already exist.');
                }
            }

            //Subadmin Validation
            $rules = [
                'name'   => 'required',
                'mobile' => ['required', 'regex:/^[6-9]\d{9}$/'],
                'image'  => 'image|mimes:jpeg,jpg,png'
            ];

            $customMessages = [
                'name.required'   => 'Name is required',
                'mobile.required' => 'Mobile number is required',
                'mobile.regex'    => 'Enter a valid 10-digit mobile number',
                'image.image'     => 'Valid image is required',
                'image.mimes'       => 'Image must be a file of type: jpeg, jpg, png'
            ];

            $this->validate($request,$rules,$customMessages);

            //Upload subadmin image
            if($request->has('image')){
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();

                $imageName = time().'.'.$extension;
                $path = 'admin/images/photos/';
                $file->move($path,$imageName);
            }else if(!empty($data['current_image'])){
                $imageName = $data['current_image'];
            }else{
                $imageName = "";
            }

            $subadmindata->image  = $imageName;
            $subadmindata->name   = $data['name'];
            $subadmindata->mobile = $data['mobile'];
            if($id==""){
                $subadmindata->email = $data['email'];
                $subadmindata->type = 'subadmin';
                $subadmindata->status = isset($data['status']) ? $data['status'] : 1;
                
            }
            if($data['password'] != ""){
                $subadmindata->password = bcrypt($data['password']);
            }
            $subadmindata->save();
            return redirect('admin/subadmins')->with('success_message',$message);
          
        }

        return view('admin.subadmins.add_edit_subadmin')->with(compact('title','subadmindata'));
    }

    public function updateRole($id,Request $request){
        
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>";print_r($data);die;

            // Delete all earlier roles
            AdminsRole::where('subadmin_id',$id)->delete();

            // Add new roles dynamically
            foreach($data as $key => $value){
                if(isset($value['view'])){
                    $view = $value['view'];
                }else{
                    $view = 0;
                }
                if(isset($value['edit'])){
                    $edit = $value['edit'];
                }else{
                    $edit = 0;
                }
                if(isset($value['full'])){
                    $full = $value['full'];
                }else{
                    $full = 0;
                }
            }

            // Add new roles
            if(isset($data['cms_pages']['view'])){
                $cms_pages_view = $data['cms_pages']['view'];
            }else{
                $cms_pages_view = 0;
            }

            if(isset($data['cms_pages']['edit'])){
                $cms_pages_edit = $data['cms_pages']['edit'];
            }else{
                $cms_pages_edit = 0;
            }

            if(isset($data['cms_pages']['full'])){
                $cms_pages_full = $data['cms_pages']['full'];
            }else{
                $cms_pages_full = 0;
            }
            $role = new AdminsRole();
            $role->subadmin_id = $id;
            $role->module      = $key;
            $role->view_access      = $view;
            $role->edit_access      = $edit;
            $role->full_access      = $full;
            $role->save();

            $message = "Subadmin Roles Updated successfully.";
            return redirect()->back()->with('success_message',$message);
        }

        $subadminRoles = AdminsRole::where('subadmin_id',$id)->get()->toArray();
        $subadminDetails = Admin::where('id',$id)->first()->toArray();
        $title = "Update ".$subadminDetails['name']." (subadmin) Roles/Permissions";
        // dd($subadminRoles);
        return view('admin.subadmins.update_roles')->with(compact('title','id','subadminRoles'));
    }

}
