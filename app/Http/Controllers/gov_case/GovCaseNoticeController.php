<?php

namespace App\Http\Controllers\gov_case;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\gov_case\GovCaseNotice;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;


class GovCaseNoticeController extends Controller
{
     public function index()
    {   
        $data['notices']= GovCaseNotice::orderBy('id','DESC')->paginate(10);
        $data['page_title'] = 'নোটিশের তালিকা';

        return view('gov_case.notice.index')->with($data);
    }

     public function create()
    {   

        $data['roles'] = Role::select('id', 'role_name')
        ->where('is_gov', 1)
        ->orderBy('sort_order', 'ASC')
        ->get(); 
        $data['notices']= GovCaseNotice::orderBy('id','DESC')->paginate(10);
        $data['page_title'] = 'নতুন নোটিশ এন্ট্রি ফর্ম';

        return view('gov_case.notice.add')->with($data);
    }


    public function store(Request $request)
    {
        // dd($request->all());
       $request->validate([
            'description' => 'required',
            'publish_date' => 'required',
            'expiry_date' => 'required',
            'role_id' => 'required',          
            ],
            [
            'description.required' => 'নোটিশের বিস্তারিত লিখুন',
            'publish_date.required' => 'নোটিশ প্রকাশের তারিখ',
            'role_id.required' => 'ভূমিকা নির্বাচন করুন',
            'expiry_date.required' => 'নোটিশের মেয়াদ শেষ হবার তারিখ',
            ]);
        $publish_date = date('Y-m-d',strtotime($request->publish_date));
        $expiry_date = date('Y-m-d',strtotime($request->expiry_date));

        DB::table('gov_case_notices')->insert([
            'description'=>$request->description,
            'publish_date' =>$publish_date,
            'expiry_date' =>$expiry_date,
            'notice_for' =>$request->role_id,
            'created_by' =>userInfo()->id,
            
       ]);

         return redirect()->route('cabinet.notice.list')->with('success','সাফল্যের সাথে নোটিশ জারি হয়েছে');
    }

     public function edit($id)
    {   

        $data['roles'] = Role::select('id', 'role_name')
        ->where('is_gov', 1)
        ->orderBy('sort_order', 'ASC')
        ->get(); 
        $data['notice']= GovCaseNotice::where('id',$id)->first();
        $data['page_title'] = 'নোটিশ সংশোধন ফর্ম';
        // return $data;
        return view('gov_case.notice.edit')->with($data);
    }


    public function update(Request $request)
    {
        // dd($request->all());
       $request->validate([
            'description' => 'required',
            'publish_date' => 'required',
            'expiry_date' => 'required',
            'role_id' => 'required',          
            ],
            [
            'description.required' => 'নোটিশের বিস্তারিত লিখুন',
            'publish_date.required' => 'নোটিশ প্রকাশের তারিখ',
            'role_id.required' => 'ভূমিকা নির্বাচন করুন',
            'expiry_date.required' => 'নোটিশের মেয়াদ শেষ হবার তারিখ',
            ]);
        $publish_date = date('Y-m-d',strtotime($request->publish_date));
        $expiry_date = date('Y-m-d',strtotime($request->expiry_date));

        DB::table('gov_case_notices')
        	->where('id',$request->case_id)
        	->update([
            'description'=>$request->description,
            'publish_date' =>$publish_date,
            'expiry_date' =>$expiry_date,
            'notice_for' =>$request->role_id,
            
       ]);

         return redirect()->route('cabinet.notice.list')->with('success','সাফল্যের সাথে নোটিশ হালনাগাদ করা হয়েছে');
    }

     public function show($id)
    {    
        $data['notice']= GovCaseNotice::where('id',$id)->first();
        $data['page_title'] = 'নোটিশের বিস্তারিত';

        return view('gov_case.notice.show')->with($data);
    }
}
