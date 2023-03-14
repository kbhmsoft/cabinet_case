<?php

namespace App\Http\Controllers\gov_case;
use App\Http\Controllers\Controller;

use App\Models\Action;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AtCaseRegister;
use App\Models\CaseHearing;
use App\Models\Role;
use App\Models\AtCaseSFlog;
use App\Models\AtCaseSF;
use App\Models\Attachment;
use App\Models\Division;
use App\Models\gov_case\GovCaseRegister;
use Illuminate\Support\Facades\Validator;
use App\Repositories\gov_case\GovCaseLogRepository;
use App\Repositories\gov_case\GovCaseRegisterRepository;
use App\Models\gov_case\GovCaseHearing;
use App\Repositories\gov_case\AttachmentRepository;

class GovCaseActionController extends Controller
{
    public function pdf_sf($id){
        $userID = userInfo()->id;
        $officeInfo = user_office_info();

        $data['info'] = DB::table('case_register')
        ->join('court', 'case_register.court_id', '=', 'court.id')
        ->join('upazila', 'case_register.upazila_id', '=', 'upazila.id')
        ->join('mouja', 'case_register.mouja_id', '=', 'mouja.id')
        // ->join('case_type', 'case_register.ct_id', '=', 'case_type.id') 'case_type.ct_name',
        ->join('case_status', 'case_register.cs_id', '=', 'case_status.id')
        // ->leftJoin('case_badi', 'case_register.id', '=', 'case_badi.case_id')
        // ->leftJoin('case_bibadi', 'case_register.id', '=', 'case_bibadi.case_id')
        ->select('case_register.*', 'court.court_name', 'upazila.upazila_name_bn', 'mouja.mouja_name_bn', 'case_status.status_name')
        ->where('case_register.id', '=', $id)
        ->first();

        //dd($data['info']);

        $data['badis'] = DB::table('case_badi')
        ->join('case_register', 'case_badi.case_id', '=', 'case_register.id')
        ->select('case_badi.*')
        ->where('case_badi.case_id', '=', $id)
        ->get();

        $data['bibadis'] = DB::table('case_bibadi')
        ->join('case_register', 'case_bibadi.case_id', '=', 'case_register.id')
        ->select('case_bibadi.*')
        ->where('case_bibadi.case_id', '=', $id)
        ->get();

        $data['surveys'] = DB::table('case_survey')
        ->join('case_register', 'case_survey.case_id', '=', 'case_register.id')
        ->join('survey_type', 'case_survey.st_id', '=', 'survey_type.id')
        ->join('land_type', 'case_survey.lt_id', '=', 'land_type.id')
        ->select('case_survey.*','survey_type.st_name','land_type.lt_name')
        ->where('case_survey.case_id', '=', $id)
        ->get();

        // Get User Sign
        /*$data['user'] = DB::table('users')
        ->select('signature')
        ->where('id', '=', $userID)
        ->first();*/
        // Get SF Details
        $data['sf'] = DB::table('case_sf')
        ->orderBy('id', 'DESC')
        ->select('case_sf.*')
        ->where('case_sf.case_id', '=', $id)
        ->first();

        // Get Upazila based Role Signature

        $data['upazila_signatures'] = DB::table('users')
        ->select('users.name', 'roles.role_name', 'office.office_name_bn', 'users.signature')
        ->join('roles', 'roles.id', '=', 'users.role_id')
        ->join('office', 'office.id', '=', 'users.office_id')
        ->where('office.upazila_id', '=', $data['info']->upazila_id)
        ->whereIn( 'users.role_id', [9, 10, 11, 12])
        // ->groupBy('users.id')
        ->get();
        // dd($data['upazila_signatures']);
        // Get SF Signature

       /* $data['sf_signatures'] = DB::table('case_sf_log')
        ->select('case_sf_log.user_id', 'users.name', 'roles.role_name', 'office.office_name_bn', 'users.signature')
        ->join('users', 'users.id', '=', 'case_sf_log.user_id')
        ->join('roles', 'roles.id', '=', 'users.role_id')
        ->join('office', 'office.id', '=', 'users.office_id')
        ->where('case_sf_log.case_id', '=', $id)
        ->groupBy('case_sf_log.user_id')
        ->get();*/
        // dd($data['info']);

        // Generate PDF
        $data['id'] = $id;
        $data['page_title'] = 'এস এফ প্রতিবেদন'; //exit;
        $html = view('action.pdf_sf')->with($data);
        // echo 'hello';

        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 12,
            'default_font'      => 'kalpurush'
            ]);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    public function details($id)
    {
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        $data['roles'] = Role::whereIn('id', [14, 27, 28, 29, 31, 32, 33, 36, 14])->orderby('sort_order')->get();
        $data['forward_map'] = DB::table('case_forward_map')
            ->where('case_forward_map.sender_role_id', '=', userInfo()->role_id)
            ->first();
        $data['page_title'] = 'মামলার বিস্তারিত তথ্য'; //exit;
        // return $data;
        return view('gov_case.action.case_details')->with($data);

        // $data['info'] = GovCaseRegister::findOrFail($id);
        // $data['roles'] = Role::all();
        // $roleID = userInfo()->role_id;
        // $data['forward_map'] = DB::table('case_forward_map')
        // ->where('case_forward_map.sender_role_id', '=', $roleID)
        // ->first();

        // $data['logs'] = RM_CaseLog::where('gov_case_id', $id)
        // ->orderBy('id', 'desc')
        // ->get();

        // $data['page_title'] = 'মামলার বিস্তারিত তথ্য'; //exit;
        // return view('gov_case.action.case_details')->with($data);
    }

    public function receive($statusID)
    {
        // return userInfo();
        $roleID = userInfo()->role_id;
        $officeInfo = user_office_info();
        $officeID = user_office_info()->office_id;

        // echo $roleID = userInfo()->role_id; exit;
        $query= GovCaseRegister::orderBy('id','DESC')
                ->where('status','!=', 3)
                ->where('action_user_role_id', $roleID)
                ->where('case_status_id', $statusID);
        if($roleID == 36 && $statusID == 39){
            $query->whereIn('action_user_role_id', [$roleID,14]);
        }elseif($roleID == 29 || $roleID == 31 && $statusID != 33){
            $query->where('action_user_role_id', $roleID)->where('selected_main_min_id', $officeID);
        }elseif($roleID == 32 || $roleID == 33 && $statusID != 33){
            $query->where('action_user_role_id', $roleID)->where('selected_main_dept_id', $officeID);
        }
        $data['cases'] = $query->get();

        $data['page_title'] = 'ভূমি রাজস্ব মামলার তালিকা';
        return view('gov_case.action.receive')->with($data);
    }


    public function get_mouja_by_ulo_office_id($officeID){
        return DB::table('mouja_ulo')->where('ulo_office_id', $officeID)->pluck('mouja_id');
        // return DB::table('mouja_ulo')->select('mouja_id')->where('ulo_office_id', $officeID)->get();
        // return DB::table('division')->select('id', 'division_name_bn')->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create_sf(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'sf_details' => 'required',
        ],
        [
            'sf_details.required'=> 'কারণ দর্শানো নোটিশের প্যারা ভিত্তিক জবাব লিখুন',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        // User Info
        $user = userInfo();

        // Inputs
        $caseID = $request->case_id;
        $sfDetails = $request->sf_details;

        //case activity log start
        // $old_case_data = CaseRegister::findOrFail($caseID);
        // $old_CaseSFlog = CaseSFlog::orderby('id', 'DESC')
        //     ->where('case_id', $caseID)
        //     ->where('user_id', $user->id)
        //     ->first();
        //case activity log End

        // Insert data into at_case_sf table
        $sf_data = [
            'at_case_id'       => $caseID,
            'sf_details'    => $sfDetails,
            'user_id'       => $user->id,
        ];
        AtCaseSF::insert($sf_data);

        // Insert data at_case_sf_log Table
        $sf_log_data = [
            'at_case_id'       => $caseID,
            'sf_log_details'=> $sfDetails,
            'user_id'       => $user->id,
        ];
        AtCaseSFlog::insert($sf_log_data);

        // Update Case Register (is_sf(1), status(2), updated_at) table
        // $case_data = [
        //     'is_sf'     => 1,
        //     //'status'       => 2,
        //     //'updated_at'    => date('Y-m-d H:i:s'),
        // ];
        // DB::table('case_register')->where('id', $caseID)->update($case_data);

        //========= Case Activity Log -  start ============
        // $caseOldData = [];
        // $caseOldData = array_merge( $caseOldData, [
        // ['case_datas' => [
        //     'is_sf'     => $old_case_data->is_sf
        //     ]],
        // ]);
        // $caseNewData = [];
        // $caseNewData = array_merge( $caseNewData, [
        //     ['sf_data' => [$sf_data]],
        //     ['sf_log_data' => [$sf_log_data]],
        //     ['case_data' => [$case_data]],
        // ]);
        // $cs_activity_data['case_register_id'] = $caseID;
        // $cs_activity_data['activity_type'] = 'Create';
        // $cs_activity_data['message'] = 'এস এফ ফাইল তৈরী করা হয়েছে';
        // $cs_activity_data['old_data'] =json_encode($caseOldData);
        // $cs_activity_data['new_data'] = json_encode($caseNewData);
        // case_activity_logs($cs_activity_data);
        //========= Case Activity Log  End ==========

        //========== return new sf for instant view========
        // $data['info'] = DB::table('case_register')
        // ->join('court', 'case_register.court_id', '=', 'court.id')
        // ->join('upazila', 'case_register.upazila_id', '=', 'upazila.id')
        // ->join('mouja', 'case_register.mouja_id', '=', 'mouja.id')
        // ->join('roles', 'case_register.action_user_group_id', '=', 'roles.id')
        // ->join('case_status', 'case_register.cs_id', '=', 'case_status.id')
        // ->select('case_register.*', 'court.court_name', 'upazila.upazila_name_bn', 'mouja.mouja_name_bn', 'roles.role_name', 'case_status.status_name')
        // ->where('case_register.id', '=', $caseID)
        // ->first();

        // $data['badis'] = DB::table('case_badi')
        // ->join('case_register', 'case_badi.case_id', '=', 'case_register.id')
        // ->select('case_badi.*')
        // ->where('case_badi.case_id', '=', $caseID)
        // ->get();

        // $data['bibadis'] = DB::table('case_bibadi')
        // ->join('case_register', 'case_bibadi.case_id', '=', 'case_register.id')
        // ->select('case_bibadi.*')
        // ->where('case_bibadi.case_id', '=', $caseID)
        // ->get();

        // $data['sf'] = DB::table('case_sf')
        // ->orderBy('id', 'DESC')
        // ->select('case_sf.*')
        // ->where('case_sf.case_id', '=', $caseID)
        // ->first();

        // $data['sf_signatures'] = DB::table('case_sf_log')
        // ->select('case_sf_log.user_id', 'users.name', 'roles.role_name', 'office.office_name_bn', 'users.signature')
        // ->join('users', 'users.id', '=', 'case_sf_log.user_id')
        // ->join('roles', 'roles.id', '=', 'users.role_id')
        // ->join('office', 'office.id', '=', 'users.office_id')
        // ->where('case_sf_log.case_id', '=', $caseID)
        // ->groupBy('case_sf_log.user_id')
        // ->get();
        // $data['numb'] = random_int(100, 999);
        // $returnHTML = view('action.inc_case_details._return_sf')->with($data)->render();
         //========== end return new sf for instant view========

        // return response()->json(['success'=>'Data is successfully added','sfdata'=>'Data is successfully added', 'html' => $returnHTML]);
        return response()->json(['success'=>'Data is successfully added','sfdata'=>'Data is successfully added', 'html' => 'success']);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function edit_sf(Request $request)
    {


        // return response()->json(['success'=>'Data is successfully updated','sfdata'=> 'SF Details', 'html' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'sf_details' => 'required',
        ],
        [
            'sf_details.required'=> 'কারণ দর্শানো নোটিশের প্যারা ভিত্তিক জবাব লিখুন',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        // User Info
        $user = userInfo();

        // // Inputs
        // $caseID = $request->case_id;
        // $sfID = $request->sf_id;
        // $sfDetails = $request->sf_details;

        // //Old Data
        // $oldCaseSF = CaseSF::findOrFail($sfID);
        // $old_case_data = CaseRegister::findOrFail($caseID);
        // $input = $request->all();
        // dd($sfDetails);

        // Get Previous SF Data
        // $sf_data = DB::table('case_sf')->select('case_sf.*')->where('case_sf.case_id', '=', $id)->first();

        // Update Case SF table


        // Insert data case_sf_log Table
        $sf_log_data = [
            'at_case_id'       => $request->case_id,
            'sf_log_details'=> $request->sf_details,
            'user_id'       => $user->id,
        ];
        AtCaseSFlog::insert($sf_log_data);


        //========= Case Activity Log -  start ============
        // $caseOldData = [];
        // $caseOldData = array_merge( $caseOldData, [
        //     ['case_datas' => [
        //         'is_sf'     => $old_case_data->is_sf
        //     ]],
        // ]);
        // $caseNewData = [];
        // $caseNewData = array_merge( $caseNewData, [
        //     ['sf_data' => [$sf_data]],
        //     ['sf_log_data' => [$sf_log_data]]
        // ]);
        // $cs_activity_data['case_register_id'] = $caseID;
        // $cs_activity_data['activity_type'] = 'Update';
        // $cs_activity_data['message'] = 'এস এফ ফাইল আপডেট করা হয়েছে';
        // $cs_activity_data['old_data'] =json_encode($caseOldData);
        // $cs_activity_data['new_data'] = json_encode($caseNewData);
        // case_activity_logs($cs_activity_data);
        //========= Case Activity Log  End ==========

        // $data['info'] = DB::table('case_register')
        // ->join('court', 'case_register.court_id', '=', 'court.id')
        // ->join('upazila', 'case_register.upazila_id', '=', 'upazila.id')
        // ->join('mouja', 'case_register.mouja_id', '=', 'mouja.id')
        // ->join('roles', 'case_register.action_user_group_id', '=', 'roles.id')
        // ->join('case_status', 'case_register.cs_id', '=', 'case_status.id')
        // ->select('case_register.*', 'court.court_name', 'upazila.upazila_name_bn', 'mouja.mouja_name_bn', 'roles.role_name', 'case_status.status_name')
        // ->where('case_register.id', '=', $caseID)
        // ->first();

        // $data['badis'] = DB::table('case_badi')
        // ->join('case_register', 'case_badi.case_id', '=', 'case_register.id')
        // ->select('case_badi.*')
        // ->where('case_badi.case_id', '=', $caseID)
        // ->get();

        // $data['bibadis'] = DB::table('case_bibadi')
        // ->join('case_register', 'case_bibadi.case_id', '=', 'case_register.id')
        // ->select('case_bibadi.*')
        // ->where('case_bibadi.case_id', '=', $caseID)
        // ->get();

        // $data['sf'] = DB::table('case_sf')
        // ->orderBy('id', 'DESC')
        // ->select('case_sf.*')
        // ->where('case_sf.case_id', '=', $caseID)
        // ->first();

        // $data['sf_signatures'] = DB::table('case_sf_log')
        // ->select('case_sf_log.user_id', 'users.name', 'roles.role_name', 'office.office_name_bn', 'users.signature')
        // ->join('users', 'users.id', '=', 'case_sf_log.user_id')
        // ->join('roles', 'roles.id', '=', 'users.role_id')
        // ->join('office', 'office.id', '=', 'users.office_id')
        // ->where('case_sf_log.case_id', '=', $caseID)
        // ->groupBy('case_sf_log.user_id')
        // ->get();
        // $data['numb'] = random_int(100, 999);
        // $returnHTML = view('action.inc_case_details._return_sf')->with($data)->render();
         //========== end return new sf for instant view========

        return response()->json(['success'=>'Data is successfully updated','sfdata'=> 'SF Details', 'html' => 'Success']);
    }

    /*
    public function hearing_add()
    {

        dd($request->all());


        $validator = \Validator::make($request->all(), [
            'hearing_date' => 'required',
            'hearing_comment' => 'required',
            'hearing_report' => 'required|mimes:pdf|max:10240',
            ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        // User Info
        $user = userInfo();

        // Inputs
        $caseID = $request->case_id;
        // $hearingDate = $request->hearing_date;
        // Convert DB date formate
        $hearingDate = str_replace('/', '-', $request->hearing_date);
        $hearingComment = $request->hearing_comment;
        // $input = $request->all();
        // dd($request->all());

        //File Upload
        if($request->file('hearing_report')){
            $fileName = $caseID.'_'.time().'.'.$request->hearing_report->getClientOriginalExtension();
            $request->hearing_report->move(public_path('uploads/hearing'), $fileName);
        }else{
            $fileName = NULL;
        }

        // Insert data into case_sf table
        $hearing_data = [
        'case_id'       => $caseID,
        'hearing_date'  => date("Y-m-d", strtotime($hearingDate)),
        'hearing_file' => $fileName,
        'hearing_comment' => $hearingComment,
        'user_id'       => $user->id,
        'created_at'    => date('Y-m-d H:i:s'),
        ];
        // dd($hearing_data);
        DB::table('case_hearing')->insert($hearing_data);
        // dd($sf_data);

        /*
        // Update Case Register (is_sf(1), status(2), updated_at) table
        $case_data = [
        'is_sf'     => 1,
        //'status'       => 2,
        //'updated_at'    => date('Y-m-d H:i:s'),
        ];
        DB::table('case_register')->where('id', $caseID)->update($case_data);*/

        /*
        return response()->json(['success'=>'Data is successfully added','sfdata'=>'Data is successfully added']);
    }
    */


    /**
     * Show the application .
     *
     * @return \Illuminate\Http\Response
     */
    public function hearing_store(Request $request)
    {
        dd('hello');
        dd($request->all());

        // User Info

        $validator = Validator::make($request->all(), [
            'hearing_date' => 'required',
            'hearing_report' => 'required|max:10240',
            ]);
        $userID = userInfo()->id;
        $caseID = $request->case_id;
        $caseHeringOldData = CaseHearing::orderby('id', 'DESC')->where('case_id', $caseID)->first()->toArray();

        dd($request->file('hearing_report'));

        if ($request->hearing_report != NULL) {
            // store file into document folder
            // $file = $request->file->store('public/documents');

            // store file into public folder with rename
            $fileName = $caseID.'_'.time().'.'.request()->hearing_report->getClientOriginalExtension();
            $request->hearing_report->move(public_path('uploads/hearing_report'), $fileName);
            // dd($fileName);

            // Update Case Register (hearing_report, updated_at) table
            $case_data = [
            'case_id'         => $caseID,
            'hearing_date'    => $request->hearing_date,
            'hearing_comment' => $request->hearing_comment,
            'hearing_file'    => $fileName,
            'user_id'         => $userID,
            'created_at'      => date('Y-m-d H:i:s'),
            ];
            // dd($case_data);
            DB::table('case_hearing')->insert($case_data);

            //========= Case Activity Log -  start ============
            $caseOldData = [];
            $caseOldData = array_merge( $caseOldData, [
                ['case_hearing' => [$caseHeringOldData]],
            ]);
            $caseNewData = [];
            $caseNewData = array_merge( $caseNewData, [
                ['case_hearing' => [$case_data]],
            ]);
            $cs_activity_data['case_register_id'] = $caseID;
            $cs_activity_data['activity_type'] = 'Create';
            $cs_activity_data['message'] = 'হিয়ারিং তৈরী করা হয়েছে';
            $cs_activity_data['old_data'] =json_encode($caseOldData);
            $cs_activity_data['new_data'] = json_encode($caseNewData);
            case_activity_logs($cs_activity_data);
            //========= Case Activity Log  End ==========
            return Response()->json(["success" => true]);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function result_update(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'result' => 'required',
            'result_date' => 'required',
            'condition_name' => 'required',
            'result_file' => 'required|mimes:pdf|max:10240',
            ],
            [
                'condition_name.required' => 'মামলার বর্তমান অবস্থা নির্বাচন করুন',
                'result_date.required' => 'রায় ঘোষণার তারিখ নির্বাচন করুন',
                'result_file.required' => 'ফলাফলের ফাইল নির্বাচন করুন',
                'result_file.mimes' => 'শুধু মাত্র পিডিএফ ফাইল নির্বাচন করুন',
                'result_file.max' => 'সর্বোচ্চ ফাইলের আকার: ১০২৪০ কে বি',
            ]
        );

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $caseID = $request->hide_case_id;
        $pathFileName = AttachmentRepository::storeSingleAttachment(
                            'uploads/gov_case/result_file',
                            request()->result_file,
                            $caseID
                        );
        $result_date_format = date('Y-m-d',strtotime(str_replace('/', '-', $request->result_date)));
        $resultCopyAsking_date_format = date('Y-m-d',strtotime(str_replace('/', '-', $request->result_copy_asking_date)));
        $resultCopyReciving_date_format = date('Y-m-d',strtotime(str_replace('/', '-', $request->result_copy_reciving_date)));
        $result = [
            'result_date'  => $result_date_format,
            'result'  => $request->result,
            'result_file'  => $pathFileName,
            'govt_lost_reason'  => $request->lost_reason,
            'status'  => $request->condition_name,
            'in_favour_govt'  => $request->result,
            'result_copy_asking_date'  => $resultCopyAsking_date_format,
            'result_copy_reciving_date'  => $resultCopyReciving_date_format,
        ];
        // dd($result);
        GovCaseRegister::whereId($caseID)->update($result);

        $data['case'] = GovCaseRegister::where('id', $caseID)->first();
        $returnHTML = view('gov_case.action.inc_case_details._case_result')->with($data)->render();

        return response()->json(['success'=>'মামলার ফলাফল আপডেট করা হয়েছে', 'html' => $returnHTML ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function file_store(Request $request)
    {
        // Validation
        request()->validate([
            'sf_report'  => 'required|mimes:pdf|max:10240',
        ]);

        $caseID = $request->hide_case_id;
        if ($files = $request->file('sf_report')) {
            // store file into public folder with rename
            $pathFileName = AttachmentRepository::storeSingleAttachment('uploads/gov_case/sf_report', request()->sf_report, $caseID);
            AttachmentRepository::storeSF_SingleAttachment($pathFileName, $caseID);

            $returnHTML = '<embed src="' . asset($pathFileName) .'" type="application/pdf" width="100%" height="600px" />';

            // return Response()->json(["success" => true, "html" => $returnHTML ]);
            return Response()->json(["success" => 'সফলভাবে এস এফের চুড়ান্ত প্রতিবেদন আপলোড করা হয়েছে', "html" => $returnHTML ]);

        }

        // return Response()->json(["success" => false]);
        return Response()->json(["error" => 'Something went wrong!', "html" => [] ]);

    }

    public function file_store_hearing(Request $request)
    {
        request()->validate([
            'hearing_report'  => 'required|mimes:pdf|max:10240',
        ]);
        $caseID = $request->hide_case_id;
        $userID = userInfo()->id;
        $hearing_date = str_replace('/', '-', $request->hearing_date);
        if ($request->file('hearing_report')) {
            $pathFileName = AttachmentRepository::storeSingleAttachment('uploads/gov_case/hearing', request()->hearing_report, $caseID);
            $gov_case_data = [
                'gov_case_id' => $caseID,
                'hearing_date' => date("Y-m-d", strtotime($hearing_date)),
                'comments' => $request->hearing_comment,
                'hearing_file' => $pathFileName,
                'user_id' => $userID,
            ];
            $hearing_id = GovCaseHearing::insertGetId($gov_case_data);
            $data['case'] = GovCaseRegister::findOrFail($caseID);
            $returnHTML = view('gov_case.action.inc_case_details._single_hearing_data')->with($data)->render();
            return Response()->json(["success" => 'শুনানির তারিখ ও অন্যান্য তথ্য সংরক্ষণ করা হয়েছে', "html" => $returnHTML ]);
        }

        return Response()->json(["success" => false]);
    }
    public function hearing_result_upload(Request $request)
    {
        // Validation
        request()->validate([
            'hearingResultFile'  => 'required|mimes:pdf|max:10240',
            ],
        [
           'hearingResultFile.required' => 'ফলাফলের ফাইল নির্বাচন করুন',
        ]);

        $hearing = GovCaseHearing::findOrFail($request->hide_hearing_id);
        $pathFileName = AttachmentRepository::storeSingleAttachment('uploads/gov_case/hearing_result', request()->hearingResultFile, $hearing->gov_case_id);
        $hearing->hearing_result_file = $pathFileName;
        $hearing->hearing_result_comments = $request->result_comment;

        if($hearing->save()){
            $data['case'] = GovCaseRegister::findOrFail($hearing->gov_case_id);
            $returnHTML = view('gov_case.action.inc_case_details._single_hearing_data')->with($data)->render();
            return Response()->json(["success" => 'শুনানির ফলাফল সংরক্ষণ করা হয়েছে', "html" => $returnHTML ]);
        }


        return Response()->json(["success" => false]);
    }

    public function file_save(Request $request)
    {
        /*
        $request->validate([
            'sf_report' => 'required|mimes:pdf|max:10240',
        ]);

        $title = time().'.'.request()->sf_report->getClientOriginalExtension();
        $request->sf_report->move(public_path('uploads'), $title);
        // $storeFile = new Post;
        // $storeFile->title = $title;
        // $storeFile->save();

        return response()->json(['success'=>'File Uploaded Successfully']);
        */

        // Validation
        request()->validate([
            'hearing_report'  => 'required|mimes:pdf|max:10240',
            ]);

        // $input = $request->all();
        // dd($request->hide_case_id);
        $caseID = $request->hide_case_id;

        if ($files = $request->file('hearing_report')) {
            // store file into document folder
            // $file = $request->file->store('public/documents');

            // store file into public folder with rename
            $fileName = $caseID.'_'.time().'.'.request()->hearing_report->getClientOriginalExtension();
            $request->hearing_report->move(public_path('uploads/hearing'), $fileName);
            // dd($fileName);

            // Update Case Register (sf_report, updated_at) table
            $case_data = [
            'hearing_report'     => $fileName,
            // 'updated_at'    => date('Y-m-d H:i:s'),
            ];
            DB::table('case_hearing')->insert($case_data);/*
            DB::table('case_register')->where('id', $caseID)->update($case_data);*/

            //========= Case Activity Log - start ============
            $caseRegisterData = [
                'hearing_report'     => '/uploads/hearing' . $fileName
            ];
            $cs_activity_data['case_register_id'] = $caseID;
            $cs_activity_data['activity_type'] = 'Update';
            $cs_activity_data['message'] = 'হিয়ারিং রিপোর্ট আপলোড করা হয়েছে';
            $cs_activity_data['old_data'] = null;
            $cs_activity_data['new_data'] = json_encode($caseRegisterData);
            case_activity_logs($cs_activity_data);
            // ========= Case Activity Log  End ==========

            return Response()->json(["success" => true]);

        }

        return Response()->json(["success" => false]);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDependentCaseStatus($id)
    {
       $case_status = DB::table('case_status')->whereRaw("find_in_set('".$id."',role_access)")->get();
       // $case_status = DB::table('case_status')->whereIn('role_access',[$id])->get();
       return Response()->json(['success' => true, 'case_status' => $case_status]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        // return $request;
        $validator = Validator::make($request->all(), [
            'group' => 'required',
            'status_id' => 'required',
            'comment' => 'required',
            ],[
                'status_id.required' => 'স্ট্যাটাস নির্বাচন করুন',
                'comment.required' => 'মন্তব্য প্রদান করুন',
            ]);
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        GovCaseLogRepository::storeFowardGovCaseLog($request);
        GovCaseRegisterRepository::updateGovCaseAsFoward($request);
        return response()->json(['success'=>'মামলাটি সফলভাবে প্রেরণ করা হয়েছে']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function show(Action $action)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function edit(Action $action)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Action $action)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function destroy(Action $action)
    {
        //
    }

    public function test_pdf(){

        $data['id'] = '007';
        $data['page_title'] = 'মামলার বিস্তারিত তথ্য'; //exit;
        $html = view('action.test')->with($data);
        // echo 'hello';

        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 12,
            'default_font'      => 'kalpurush'
            ]);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
    public function menu_1(){
        $categories = Division::all();
        return view('te', compact('categories'));
    }
}
