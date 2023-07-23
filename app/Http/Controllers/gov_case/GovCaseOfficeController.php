<?php

namespace App\Http\Controllers\gov_case;

use App\Http\Controllers\Controller;

use \Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseOfficeType;
use Validator, Redirect, Response;

class GovCaseOfficeController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:create_new_office', ['only' => ['create']]);
        $this->middleware('permission:create_new_office', ['only' => ['create']]);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());


        //
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        // dd($officeInfo);
        $data['page_title'] = 'অফিসের তালিকা';
        // $data['offices'] = GovCaseOffice::orderby('id','DESC')->paginate(10)->withQueryString();
        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();


        //Add Conditions 

        $query = GovCaseOffice::orderby('id', 'ASC');
        if (!empty($_GET['office_type'])) {
            $query->where('gov_case_office.level', '=', $_GET['office_type']);
        }
        if (!empty($_GET['office_name'])) {
            $query->where('gov_case_office.office_name_bn', 'LIKE', '%' . $_GET['office_name'] . '%');
        }


        $data['offices'] = $query->paginate(10)->withQueryString();
        // dd($data['offices']);
        // Dorpdown
        // $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();
        $data['upazilas'] = NULL;
        $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();

        if ($roleID == 5 || $roleID == 6 || $roleID == 7 || $roleID == 8 || $roleID == 13) {
            // dd(1);
            $data['upazilas'] = DB::table('upazila')->select('id', 'upazila_name_bn')->where('district_id', $officeInfo->district_id)->get();
        }
        return view('gov_case.office.index')
            ->with($data)
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }


    public function level_wise($level)
    {
        //
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        // dd($officeInfo);
        $data['page_title'] = 'অফিস সেটিং তালিকা';
        $query = DB::table('office')
            ->leftjoin('division', 'office.division_id', '=', 'division.id')
            ->leftjoin('district', 'office.district_id', '=', 'district.id')
            ->leftjoin('upazila', 'office.upazila_id', '=', 'upazila.id')
            // ->where('office.is_gov', 1)
            ->where('office.level', $level)
            ->select('office.*', 'upazila.upazila_name_bn', 'district.district_name_bn', 'division.division_name_bn');
        if ($roleID == 5 || $roleID == 6 || $roleID == 7 || $roleID == 8 || $roleID == 13) {
            $query->where('office.district_id', '=', $officeInfo->district_id);
        } elseif ($roleID == 9 || $roleID == 10 || $roleID == 11) {
            $query->where('office.upazila_id', '=', $officeInfo->upazila_id);
        }/*elseif($roleID == 12){
            $moujaIDs = $this->get_mouja_by_ulo_office_id(Auth::user()->office_id);
            // dd($moujaIDs);
            // print_r($moujaIDs); exit;
            $query->where('office.mouja_id', $moujaIDs);    
        }   */

        //Add Conditions 

        if (!empty($_GET['division'])) {
            $query->where('office.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('office.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('office.upazila_id', '=', $_GET['upazila']);
        }


        $data['offices'] = $query->paginate(10)->withQueryString();
        // dd($data['offices']);
        // Dorpdown
        // $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();
        $data['upazilas'] = NULL;
        $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();

        if ($roleID == 5 || $roleID == 6 || $roleID == 7 || $roleID == 8 || $roleID == 13) {
            // dd(1);
            $data['upazilas'] = DB::table('upazila')->select('id', 'upazila_name_bn')->where('district_id', $officeInfo->district_id)->get();
        }

        return view('gov_case.office.cabinet.index')
            ->with($data)
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }


    public function parent_wise($parent)
    {
        //
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        // dd($officeInfo);
        $data['page_title'] = 'অফিস সেটিং তালিকা';
        $query = DB::table('office')
            ->leftjoin('division', 'office.division_id', '=', 'division.id')
            ->leftjoin('district', 'office.district_id', '=', 'district.id')
            ->leftjoin('upazila', 'office.upazila_id', '=', 'upazila.id')
            // ->where('office.is_gov', 1)
            ->where('office.parent', $parent)
            ->select('office.*', 'upazila.upazila_name_bn', 'district.district_name_bn', 'division.division_name_bn');

        //Add Conditions 

        if (!empty($_GET['division'])) {
            $query->where('office.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('office.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('office.upazila_id', '=', $_GET['upazila']);
        }


        $data['offices'] = $query->paginate(10)->withQueryString();
        // dd($data['offices']);
        // Dorpdown
        // $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();
        $data['upazilas'] = NULL;
        $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();

        return view('gov_case.office.index')
            ->with($data)
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());


        //
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        //
            $data['offices'] = DB::table('gov_case_office')
                ->select('gov_case_office.*')
                // ->where('level', 1)
                ->get();

            $data['ministries'] = DB::table('gov_case_office')
                ->select('gov_case_office.*')
                ->where('level', 1)
                ->get();
        
            $data['divisions'] = DB::table('gov_case_office')
                ->select('gov_case_office.*')
                ->where('level', 3)
                ->get();

            $data['division'] = DB::table('division')
                ->select('division.*')
                ->get();
            $data['court_type'] = DB::table('court_type')
                ->select('court_type.*')
                ->get();
            $data['office_type'] = DB::table('gov_case_office_type')
                ->select('gov_case_office_type.*')
                ->get();
            $data['page_title'] = 'নতুন অফিস এন্ট্রি ফরম';
        // dd($data); 

        return view('gov_case.office.add')->with($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // dd($request->all());
        // return $request; 
        $roleID = Auth::user()->role_id;

        $validator = $request->validate([
            'office_name' => 'required',
            'status' => 'required'
        ]);
        if($request->level == 2){
            $parentID = $request->parentMinID;
        }elseif($request->level == 4){
            $parentID = $request->parentDivID;
        }else{
            $parentID = '';
        }
        DB::table('gov_case_office')->insert([
            'level' => $request->office_lavel,
            'office_name_bn' => $request->office_name,
            'status' => $request->status,
            'parent' => $parentID,
            'level' => $request->level,
        ]);
        return redirect()->route('cabinet.office')
            ->with('success', 'অফিস সফলভাবে সংরক্ষণ করা হয়েছে');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data['offices'] = DB::table('gov_case_office')
            ->select('gov_case_office.*')
            ->where('gov_case_office.id', $id)
            ->first();

        $data['office_type'] = DB::table('gov_case_office_type')
            ->select('gov_case_office_type.*')
            ->get();

        $data['page_title'] = 'অফিসের তথ্য হালনাগাদ ফরম';
        // return $data;
        return view('gov_case.office.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // return $request;
        $id = $request->office_id;
        $validator = $request->validate([
            'office_name' => 'required',
            'status' => 'required'
        ]);
        $data = [
            'level' => $request->office_lavel,
            'office_name_bn' => $request->office_name,
            'status' => $request->status,
            'level' => $request->level,
        ];

        DB::table('gov_case_office')
            ->where('id', $id)
            ->update($data);

        return redirect()->route('cabinet.office')
            ->with('success', 'অফিস সফলভাবে সংশোধন করা হয়েছে');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getDependentDistrict($id)
    {
        $subcategories = DB::table("district")->where("division_id", $id)->pluck("district_name_bn", "id");
        return json_encode($subcategories);
    }
    public function getDependentUpazila($id)
    {
        $subcategories = DB::table("upazila")->where("district_id", $id)->pluck("upazila_name_bn", "id");
        return json_encode($subcategories);
    }
    public function getDependentMouja($id)
    {
        $subcategories = DB::table("mouja")->where("upazila_id", $id)->pluck("mouja_name_bn", "id");
        return json_encode($subcategories);
    }
}
