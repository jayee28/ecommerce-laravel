<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminsRole;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Session::put('page','cms-pages');
        $cms_pages = CmsPage::get()->toArray();
        //dd($cms_pages);

        //Set Admin/Subadmins permission for CMS pages
        $cmspagesModuleCount = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id,
        'module' => 'cms_pages'])->count();

        $pagesModule = array();

        if(Auth::guard('admin')->user()->type == 'admin'){
            $pagesModule['view_access'] = 1;
            $pagesModule['edit_access'] = 1;
            $pagesModule['full_access'] = 1;
        }elseif($cmspagesModuleCount == 0){
            $message = "This feature is restricted for you";
            return redirect('admin/dashboard')->with('error_message',$message);
        }else{
            $pagesModule = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id,
        'module' => 'cms_pages'])->first()->toArray();
        }


        return view('admin.pages.cms_pages')->with(compact('cms_pages','pagesModule'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CmsPage $cmsPage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,$id=null)
    {
        Session::put('page','cms-pages');
        if($id==""){
            $title = "Add CMS Page";
            $cmspage = new CmsPage();
            $message = "CMS Page added successfully.";
        }else{
            $title = "Edit CMS Page";
            $cmspage = CmsPage::find($id);
            $message = "CMS Page updated successfully.";
        }

        if($request->isMethod('POST')){
            $data = $request->all();
            // echo "<pre>";print_r($data);die;

            //CMS Pages Validation
            $rules = [
                'title'       => 'required',
                'url'         => 'required',
                'description' => 'required'
            ];

            $customMessages = [
                'title.required'        => 'Page Title is required',
                'url.required'         => 'Page URL is required',
                'description.required' => 'Page Description is required'
            ];

            $this->validate($request,$rules,$customMessages);

            $cmspage->title = $data['title'];
            $cmspage->url   = $data['url'];
            $cmspage->description = $data['description'];
            $cmspage->meta_title   = $data['meta_title'];
            $cmspage->meta_description   = $data['meta_description'];
            $cmspage->meta_keywords   = $data['meta_keywords'];
            $cmspage->status = 1;
            $cmspage->save();
            return redirect('admin/cms-pages')->with('success_message',$message);
        }

        return view('admin.pages.add_edit_cmspage')->with(compact('title','cmspage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();

            $newStatus = ($data['status'] == 'Active') ? 0 : 1;

            CmsPage::where('id', $data['page_id'])->update(['status' => $newStatus]);

            return response()->json([
                'status' => $newStatus,
                'page_id' => $data['page_id']
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //Delete CMS Page
        CmsPage::where('id',$id)->delete();
        return redirect()->back()->with('success_message','CMS page deleted successfully.');
    }
}
