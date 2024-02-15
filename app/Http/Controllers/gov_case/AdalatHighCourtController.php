<?php

namespace App\Http\Controllers\gov_case;

use App\Http\Controllers\Controller;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseOfficeType;
use App\Models\gov_case\HighcourtAdalat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdalatHighCourtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        //Add Conditions
        $query = DB::table('highcourt_adalats')
            ->where('deleted_at', '=', null)
            ->orderBy('name');

        $data['users'] = $query->paginate(10)->withQueryString();

        $data['page_title'] = 'হাইকোর্ট আদালতের তালিকা';

        return view('gov_case.highcourt_adalat_manage.index')
            ->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();

        $role = array('1', '27');
        $data['roles'] = DB::table('roles')
            ->select('id', 'name')
            ->whereNotIn('id', $role)
            ->where('is_gov', 1)
            ->orderBy('sort_order', 'ASC')
            ->get();

        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();

        $query = DB::table('users')->orderBy('id', 'DESC')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.id')
            ->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')
            ->where('users.is_gov', 1);

        if (!empty($_GET['office_id'])) {
            $query->where('users.office_id', '=', $_GET['office_id']);
        }
        if (!empty($_GET['role'])) {
            $query->where('users.role_id', '=', $_GET['role']);
        }

        $data['users'] = $query->paginate(10)->withQueryString();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->whereNotIn('id', $role)->where('is_gov', 1)->orderBy('sort_order', 'ASC')->get();
        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();

        $data['page_title'] = 'নতুন আদালত এন্ট্রি ফরম';
        // dd($data);

        return view('gov_case.highcourt_adalat_manage.add')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'status' => 'nullable',
        ],
            [
                'name.required' => 'নাম লিখুন',
            ]);

        DB::table('highcourt_adalats')->insert([
            'name' => $request->name,
            'status' => $request->status,
        ]);
        return redirect()->route('cabinet.highcourt-maintain.index')->with('success', 'সাফল্যের সাথে নতুন আদালত তৈরি সম্পন্ন হয়েছে');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['adalatManagement'] = DB::table('highcourt_adalats')
            ->where('highcourt_adalats.id', $id)
            ->get()->first();

        $data['page_title'] = 'আদালতের তথ্য সংশোধন ফরম';
        return view('gov_case.highcourt_adalat_manage.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',

        ],
            [
                'name.required' => 'আদালতের নাম লিখুন',

            ]);

        $adalatUpdate = DB::table('highcourt_adalats')
            ->where('id', $id)
            ->update(['name' => $request->name,
                'status' => $request->status,
            ]);

        return redirect()->route('cabinet.highcourt-maintain.index')
            ->with('success', 'আদালতের তথ্য সফলভাবে আপডেট হয়েছে');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $highcourtAdalat = HighcourtAdalat::find($id);

        if (!$highcourtAdalat) {
            return redirect()->route('cabinet.highcourt-maintain.index')
                ->with('error', 'আদালতের তথ্য পাওয়া যায়নি');
        }

        $highcourtAdalat->delete();

        return redirect()->route('cabinet.highcourt-maintain.index')
            ->with('success', 'আদালতের তথ্য সফলভাবে মুছে ফেলা হয়েছে');

    }
}
