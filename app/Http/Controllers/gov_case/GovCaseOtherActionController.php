<?php

namespace App\Http\Controllers\gov_case;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Court;
use App\Models\Office;
use App\Models\User;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\GovCaseBadi;
use App\Models\gov_case\GovCaseBibadi;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Models\gov_case\GovCaseDivisionCategoryType;
use App\Models\gov_case\GovCaseHearing;
use App\Repositories\gov_case\AttachmentRepository;
use App\Repositories\gov_case\GovCaseBadiBibadiRepository;
use App\Repositories\gov_case\GovCaseLogRepository;
use App\Repositories\gov_case\GovCaseRegisterRepository;
use Illuminate\Support\Facades\DB;

class GovCaseOtherActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function againstGovCaseIndex()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $office = userInfo()->office_id;

        $query =  GovCaseRegister::with('bibadis')->where('in_favour_govt', 0)->whereNull('result_copy_asking_date')->orderby('id','DESC');

        /*if ($roleID == 32 || $roleID == 33) {
            $query->whereHas('bibadis',
                function ($query)use($officeID) {
                $query->where('selected_main_dept_id', $officeID);
               }
            );
        }
        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas('bibadis',
                function ($query)use($officeID) {
                    $query->where('selected_main_min_id', $officeID);
               }
            );
        }*/

        if($roleID != 27 && $roleID != 28){
            $query->orWhereHas('bibadis', function ($q) use ($office) {
                    $q->where('respondent_id', $office);
                });
        }


        $data['cases'] = $query->paginate(10);
        $data['page_title'] =   'সরকারের বিপক্ষে রায় হওয়া মামলার তালিকা';

        return view('gov_case.others_action.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function againstGovCaseEdit($id)
    {
        
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        if($roleID != 33){
            $data['depatments'] = Office::where('parent', $officeID)->get();
        }else{
            $data['depatments'] = Office::where('level', 12)->get();
        }
        $data['page_title'] =   'সরকারের বিপক্ষে রায় হওয়া মামলার তথ্য হালনাগাদ';
        // return $data;
        return view('gov_case.others_action.edit')->with($data);
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function againstGovCaseStore(Request $request)
    {
        // return $request;
        $id = $request->caseId;

        $request->validate(
            [
            'result_copy_asking_date' => 'required',
            'result_copy_reciving_date' => 'required',
            ]
        );

        if ($request->result_copy_asking_date != NULL && $request->result_copy_asking_date != '') {
            $result_copy_asking_date = date('Y-m-d',strtotime(str_replace('/', '-', $request->result_copy_asking_date)));        
        } else {
            $result_copy_asking_date = null;
        }
        if ($request->result_copy_reciving_date != NULL && $request->result_copy_reciving_date != '') {
            $result_copy_reciving_date = date('Y-m-d',strtotime(str_replace('/', '-', $request->result_copy_reciving_date)));        
        } else {
            $result_copy_reciving_date = null;
        }
        if ($request->appeal_requesting_date != NULL && $request->appeal_requesting_date != '') {
            $appeal_requesting_date = date('Y-m-d',strtotime(str_replace('/', '-', $request->appeal_requesting_date)));        
        } else {
            $appeal_requesting_date = null;
        }

        $data = [
           'result_copy_asking_date' => $result_copy_asking_date, 
           'result_copy_reciving_date' => $result_copy_reciving_date,
           'appeal_requesting_date' => $appeal_requesting_date, 
           'appeal_requesting_memorial' => $request->appeal_requesting_memorial, 
           'reason_of_not_appealing' =>$request->reason_of_not_appealing,  
        ];

        DB::table('gov_case_registers')
            ->where('id', $id)
            ->update($data);

        //========= Case Activity Log -  start ============
        $caseResOldData = GovCaseRegister::findOrFail($id);

        $caseOldData = [];
        $caseOldData = array_merge( $caseOldData, [
            ['case_register' => [
                   'result_copy_asking_date' => $result_copy_asking_date, 
                   'result_copy_reciving_date' => $result_copy_reciving_date,
                   'appeal_requesting_date' => $appeal_requesting_date, 
                   'appeal_requesting_memorial' => $request->appeal_requesting_memorial, 
                   'reason_of_not_appealing' =>$request->reason_of_not_appealing,
                ]
            ],
        ]);
        $caseNewData = [];
        $caseNewData = array_merge( $caseNewData, [
            ['case_register' => [$data]],
        ]);
        $cs_activity_data['case_register_id'] = $id;
        $cs_activity_data['activity_type'] = 'Update';
        $cs_activity_data['message'] = 'সরকারের বিপক্ষে রায় হওয়া মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে';
        $cs_activity_data['old_data'] =json_encode($caseOldData);
        $cs_activity_data['new_data'] = json_encode($caseNewData);
        gov_case_activity_logs($cs_activity_data);
        //========= Case Activity Log  End ==========

        return redirect()->route('cabinet.case.othersaction.againstgov')->with('success', 'সরকারের বিপক্ষে রায় হওয়া মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sentToSolCaseIndex()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query =  GovCaseRegister::whereNull('result_sending_date')->orderby('id','DESC');

        /*if ($roleID == 32 || $roleID == 33) {
            $query->whereHas('bibadis',
                function ($query)use($officeID) {
                $query->where('selected_main_dept_id', $officeID);
               }
            );
        }
        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas('bibadis',
                function ($query)use($officeID) {
            $query->where('selected_main_min_id', $officeID);
               }
            );
        }*/
        if($roleID != 27 && $roleID != 28){
            $query->orWhereHas('bibadis', function ($q) use ($officeID) {
                    $q->where('respondent_id', $officeID);
                });
        }
        $data['cases'] = $query->paginate(10);
        $data['page_title'] =   'সলিসিটর অনুবিভাগে জবাব প্রেরণের জন্য অপেক্ষমান মামলার তালিকা';

        return view('gov_case.others_action.sentToSolIndex')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sentToSolCaseEdit($id)
    {
        
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        if($roleID != 33){
            $data['depatments'] = Office::where('parent', $officeID)->get();
        }else{
            $data['depatments'] = Office::where('level', 12)->get();
        }
        $data['page_title'] =   'সলিসিটর অনুবিভাগে জবাব প্রেরণের জন্য অপেক্ষমান মামলার তথ্য হালনাগাদ';
        // return $data;
        return view('gov_case.others_action.sentToSolEdit')->with($data);
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sentToSolCaseStore(Request $request)
    {
        // return $request;
        $id = $request->caseId;

        $request->validate(
            [
            'result_sending_date' => 'required',
            'result_sending_memorial' => 'required',
            ]
        );

        if ($request->result_sending_date != NULL && $request->result_sending_date != '') {
            $result_sending_date = date('Y-m-d',strtotime(str_replace('/', '-', $request->result_sending_date)));        
        } else {
            $result_sending_date = null;
        }

        $data = [
           'result_sending_date' => $result_sending_date, 
           'result_sending_memorial' => $request->result_sending_memorial,
        ];

        DB::table('gov_case_registers')
            ->where('id', $id)
            ->update($data);

        //========= Case Activity Log -  start ============
        $caseResOldData = GovCaseRegister::findOrFail($id);

        $caseOldData = [];
        $caseOldData = array_merge( $caseOldData, [
            ['case_register' => [
                   'result_sending_date' => $result_sending_date, 
                   'result_sending_memorial' => $request->result_sending_memorial,
                ]
            ],
        ]);
        $caseNewData = [];
        $caseNewData = array_merge( $caseNewData, [
            ['case_register' => [$data]],
        ]);
        $cs_activity_data['case_register_id'] = $id;
        $cs_activity_data['activity_type'] = 'Update';
        $cs_activity_data['message'] = 'সলিসিটর অনুবিভাগে জবাব প্রেরণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে';
        $cs_activity_data['old_data'] =json_encode($caseOldData);
        $cs_activity_data['new_data'] = json_encode($caseNewData);
        gov_case_activity_logs($cs_activity_data);
        //========= Case Activity Log  End ==========

        return redirect()->route('cabinet.case.othersaction.senttosol')->with('success', 'সলিসিটর অনুবিভাগে জবাব প্রেরণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function sentToAgFromSolCaseIndex()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $query =  GovCaseRegister::whereNull('result_sending_date_solisitor_to_ag')->orderby('id','DESC');
        /*if ($roleID == 32 || $roleID == 33) {
            $query->whereHas('bibadis',
                function ($query)use($officeID) {
                $query->where('selected_main_dept_id', $officeID);
               }
            );
        }
        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas('bibadis',
                function ($query)use($officeID) {
            $query->where('selected_main_min_id', $officeID);
               }
            );
        }*/
        if($roleID != 27 && $roleID != 28){
            $query->orWhereHas('bibadis', function ($q) use ($officeID) {
                    $q->where('respondent_id', $officeID);
                });
        }
        $data['cases'] = $query->paginate(10);
        $data['page_title'] =   'সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের জন্য অপেক্ষমান মামলার তালিকা';
        // return $data;
        return view('gov_case.others_action.sentToAgFromSolIndex')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sentToAgFromSolCaseEdit($id)
    {
        
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        if($roleID != 33){
            $data['depatments'] = Office::where('parent', $officeID)->get();
        }else{
            $data['depatments'] = Office::where('level', 12)->get();
        }
        $data['page_title'] =   'সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের জন্য অপেক্ষমান মামলার তথ্য হালনাগাদ';
        // return $data;
        return view('gov_case.others_action.sentToAgFromSolEdit')->with($data);
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sentToAgFromSolCaseStore(Request $request)
    {
        // return $request;
        $id = $request->caseId;

        $request->validate(
            [
            'result_sending_date_solisitor_to_ag' => 'required',
            'result_sending_memorial_solisitor_to_ag' => 'required',
            ]
        );

        if ($request->result_sending_date_solisitor_to_ag != NULL && $request->result_sending_date_solisitor_to_ag != '') {
            $result_sending_date_solisitor_to_ag = date('Y-m-d',strtotime(str_replace('/', '-', $request->result_sending_date_solisitor_to_ag)));        
        } else {
            $result_sending_date_solisitor_to_ag = null;
        }

        $data = [
           'result_sending_date_solisitor_to_ag' => $result_sending_date_solisitor_to_ag, 
           'result_sending_memorial_solisitor_to_ag' => $request->result_sending_memorial_solisitor_to_ag,
        ];

        DB::table('gov_case_registers')
            ->where('id', $id)
            ->update($data);

        //========= Case Activity Log -  start ============
        $caseResOldData = GovCaseRegister::findOrFail($id);

        $caseOldData = [];
        $caseOldData = array_merge( $caseOldData, [
            ['case_register' => [
                   'result_sending_date_solisitor_to_ag' => $result_sending_date_solisitor_to_ag, 
                   'result_sending_memorial_solisitor_to_ag' => $request->result_sending_memorial_solisitor_to_ag,
                ]
            ],
        ]);
        $caseNewData = [];
        $caseNewData = array_merge( $caseNewData, [
            ['case_register' => [$data]],
        ]);
        $cs_activity_data['case_register_id'] = $id;
        $cs_activity_data['activity_type'] = 'Update';
        $cs_activity_data['message'] = 'সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে';
        $cs_activity_data['old_data'] =json_encode($caseOldData);
        $cs_activity_data['new_data'] = json_encode($caseNewData);
        gov_case_activity_logs($cs_activity_data);
        //========= Case Activity Log  End ==========

        return redirect()->route('cabinet.case.othersaction.senttoagfromsol')->with('success', 'সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে');

    }

    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function stepNotTakenAgainstPostpondOrderCaseIndex()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query =  GovCaseRegister::whereNull('appeal_against_postpond_interim_order')->orderby('id','DESC');

        /*if ($roleID == 32 || $roleID == 33) {
            $query->whereHas('bibadis',
                function ($query)use($officeID) {
                    $query->where('selected_main_dept_id', $officeID);
               }
            );
        }
        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas('bibadis',
                function ($query)use($officeID) {
                    $query->where('selected_main_min_id', $officeID);
               }
            );
        }*/
        if($roleID != 27 && $roleID != 28){
            $query->orWhereHas('bibadis', function ($q) use ($officeID) {
                    $q->where('respondent_id', $officeID);
                });
        }
        $data['cases'] = $query->paginate(10);
        $data['page_title'] =   'স্থগিতাদেশের বিপরীতে ব্যাবস্থা গ্রহণের জন্য অপেক্ষমান মামলার তালিকা';
        // return $data;
        return view('gov_case.others_action.stepNotTakenAgainstPostpondOrderIndex')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stepNotTakenAgainstPostpondOrderCaseEdit($id)
    {
        
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        if($roleID != 33){
            $data['depatments'] = Office::where('parent', $officeID)->get();
        }else{
            $data['depatments'] = Office::where('level', 12)->get();
        }
        $data['page_title'] =   'স্থগিতাদেশের বিপরীতে ব্যাবস্থা গ্রহণের জন্য অপেক্ষমান মামলার তথ্য হালনাগাদ';
        // return $data;
        return view('gov_case.others_action.stepNotTakenAgainstPostpondOrderEdit')->with($data);
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stepNotTakenAgainstPostpondOrderCaseStore(Request $request)
    {
        // return $request;
        $id = $request->caseId;

        $request->validate(
            [
            'appeal_against_postpond_interim_order_date' => 'required',
            'appeal_against_postpond_interim_order' => 'required',
            'appeal_against_postpond_interim_order_details' => 'required',
            ]
        );

        if ($request->appeal_against_postpond_interim_order_date != NULL && $request->appeal_against_postpond_interim_order_date != '') {
            $appeal_against_postpond_interim_order_date = date('Y-m-d',strtotime(str_replace('/', '-', $request->appeal_against_postpond_interim_order_date)));        
        } else {
            $appeal_against_postpond_interim_order_date = null;
        }

        $data = [
           'appeal_against_postpond_interim_order_date' => $appeal_against_postpond_interim_order_date, 
           'appeal_against_postpond_interim_order' => $request->appeal_against_postpond_interim_order,
           'appeal_against_postpond_interim_order_details' => $request->appeal_against_postpond_interim_order_details,
        ];

        DB::table('gov_case_registers')
            ->where('id', $id)
            ->update($data);

        //========= Case Activity Log -  start ============
        $caseResOldData = GovCaseRegister::findOrFail($id);

        $caseOldData = [];
        $caseOldData = array_merge( $caseOldData, [
            ['case_register' => [
                   'appeal_against_postpond_interim_order_date' => $appeal_against_postpond_interim_order_date, 
                   'appeal_against_postpond_interim_order' => $request->appeal_against_postpond_interim_order,
                   'appeal_against_postpond_interim_order_details' => $request->appeal_against_postpond_interim_order_details,
                ]
            ],
        ]);
        $caseNewData = [];
        $caseNewData = array_merge( $caseNewData, [
            ['case_register' => [$data]],
        ]);
        $cs_activity_data['case_register_id'] = $id;
        $cs_activity_data['activity_type'] = 'Update';
        $cs_activity_data['message'] = 'স্থগিতাদেশের বিপরীতে ব্যাবস্থা গ্রহণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে';
        $cs_activity_data['old_data'] =json_encode($caseOldData);
        $cs_activity_data['new_data'] = json_encode($caseNewData);
        gov_case_activity_logs($cs_activity_data);
        //========= Case Activity Log  End ==========

        return redirect()->route('cabinet.case.othersaction.stepnottakenAgainstpostpondorder')->with('success', 'স্থগিতাদেশের বিপরীতে ব্যাবস্থা গ্রহণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে');

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
        //
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
}
