<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\gov_case\GovCaseBibadi;
use App\Models\gov_case\GovCaseOfficeType;
use App\Models\gov_case\GovCaseRegister;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Gov_ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:govt_case_report', ['only' => ['caselist']]);
    }

    public function caselist()
    {
        // Dropdown List
        $data['courts'] = DB::table('court')->select('id', 'court_name')->get();
        $data['roles'] = DB::table('roles')->select('id', 'name')->where('in_action', 1)->get();
        $data['officeTypes'] = DB::table('gov_case_office_type')->select('id', 'type_name_bn')->get();
        $data['ministry'] = DB::table('gov_case_office')->select('id', 'office_name_bn')->get();

        $officeID = Auth::user()->office_id;
        $childOfficeIds = [];
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];
        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }

        $data['officeList'] = DB::table('gov_case_office')
            ->where('gov_case_office.parent', $finalOfficeIds)
            ->orwhere('id', $finalOfficeIds)
            ->get(['id', 'office_name_bn']);

        $data['getMonth'] = date('M', mktime(0, 0, 0));

        $data['page_title'] = 'সরকারি স্বার্থ সংশ্লিষ্ট মামলার রিপোর্ট ফরম';
        $roleID = Auth::user()->role_id;
        if ($roleID != 27) {
            return view('gov_report.ministryWiseCaselist')->with($data);
        }
        // return view('case.case_add', compact('page_title', 'case_type'));
        return view('gov_report.caselist')->with($data);
    }

    public function pdf_generate(Request $request)
    {
        //=========================Ministry Wise Case Report========================//
        if ($request->btnsubmit == 'pdf_num_office_wise') {
            $data['page_title'] = 'সরকারি মামলার তালিকা';
            if ($request->date_start || $request->date_end) {
                $data['date_start'] = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_start)));
                $data['date_end'] = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_end)));
            } else {
                $data['date_start'] = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_start)));
                $data['date_end'] = date('Y-m-d', strtotime(str_replace('/', '-', now())));
            }

            $request->validate(
                [
                    'office_type' => 'required',
                    // 'date_start' => 'required',
                    // 'date_end' => 'required'
                ],
                [
                    'office_type.required' => 'অফিসের ধরণ নির্বাচন করুন',
                    // 'date_start.required' => 'মামলা শুরুর তারিখ নির্বাচন করুন',
                    // 'date_end.required' => 'মামলা শেষের তারিখ নির্বাচন করুন'
                ]
            );

            $data['page_title'] = ' এর সরকারি স্বার্থ সংশ্লিষ্ট মামলার রিপোর্ট';
            $office_type = $request->office_type;
            $dept_id = $request->ministry;

            // return [$office_type,$dept_id];
            $data['type_name'] = GovCaseOfficeType::where('id', $office_type)->first()->type_name_bn;
            if ($office_type != null) {
                $data['offices'] = DB::table('gov_case_office')->select('id as office_id', 'office_name_bn')->where('id', $office_type)->get();
            }

            foreach ($data['offices'] as $key => $value) {

                $data['results'][$key]['ministry_name_bn'] = $value->office_name_bn;

                $data['results'][$key]['office_id'] = $value->office_id;
                if ($dept_id != null) {
                    $data['results'][$key]['doptor'] = DB::table('gov_case_office')->select('id as doptor_id', 'office_name_bn as doptor_name')->where('level', $office_type)->where('id', $dept_id)->get();
                } else {
                    $data['results'][$key]['doptor'] = DB::table('gov_case_office')->select('id as doptor_id', 'office_name_bn as doptor_name')->where('level', $office_type)->get();
                }

                foreach ($data['results'][$key]['doptor'] as $k => $val) {
                    $data['results'][$key]['doptor'][$k]->dateBetween = $this->case_count_by_dateBetween_highCourt($val->doptor_id, $data)->count();
                    $data['results'][$key]['doptor'][$k]->prevUndoneCase = $this->previous_undone_case_count_firstDate_highCourt($val->doptor_id, $data)->count();
                    $data['results'][$key]['doptor'][$k]->totalCase = $this->total_case_count_by_highCourt($val->doptor_id, $data)->count();
                    $data['results'][$key]['doptor'][$k]->doneCase = $this->done_case_count_by_dateBetween_highCourt($val->doptor_id, $data)->count();
                    $data['results'][$key]['doptor'][$k]->favouredGov = $this->done_favoured_gov_case_count_highCourt($val->doptor_id, $data)->count();
                    $data['results'][$key]['doptor'][$k]->againstGov = $this->done_against_gov_case_count_highCourt($val->doptor_id, $data)->count();
                    $data['results'][$key]['doptor'][$k]->lastWorkDay = $this->previous_undone_case_count_lastDate_highCourt($val->doptor_id, $data);
                    $data['results'][$key]['doptor'][$k]->importantCase = $this->imprtant_case_count_by_dateBetween_highCourt($val->doptor_id, $data)->count();
                }
            }

            $html = view('gov_report.pdf_num_ministry')->with($data);
            // Generate PDF
            $this->generatePDF($html);
            // return view('gov_report.pdf_num_ministry')->with($data);
        }

        if ($request->btnsubmit == 'pdf_num_ministry_office_wise') {
            $data['page_title'] = 'সরকারি মামলার তালিকা';
            if ($request->date_start || $request->date_end) {
                $data['date_start'] = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_start)));
                $data['date_end'] = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_end)));
            } else {
                $data['date_start'] = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_start)));
                $data['date_end'] = date('Y-m-d', strtotime(str_replace('/', '-', now())));
            }

            $data['page_title'] = ' এর সরকারি স্বার্থ সংশ্লিষ্ট মামলার রিপোর্ট';
            // $office_type = $request->office_type;
            // $dept_id = $request->ministry;

            // return [$office_type,$dept_id];
            // $data['type_name'] = GovCaseOfficeType::where('id', $office_type)->first()->type_name_bn;
            // if ($office_type != null) {
            //     $data['offices'] = DB::table('gov_case_office')->select('id as office_id', 'office_name_bn')->where('id', $office_type)->get();
            // }
            $officeID = Auth::user()->office_id;
            $childOfficeIds = [];
            $childOfficeQuery = DB::table('gov_case_office')
                ->select('id')
                ->where('parent', $officeID)->get();

            foreach ($childOfficeQuery as $childOffice) {
                $childOfficeIds[] = $childOffice->id;
            }

            $finalOfficeIds = [];
            if (empty($childOfficeIds)) {
                $finalOfficeIds[] = $officeID;
            } else {
                $finalOfficeIds[] = $officeID;
                $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
            }

            $data['ministryWiseData'] = DB::table('gov_case_office')
                ->where('gov_case_office.parent', $finalOfficeIds)
                ->orwhere('id', $finalOfficeIds)
                ->get(['id', 'office_name_bn']);

            $arrayd = [];
            foreach ($data['ministryWiseData'] as $key => $val) {
                $val->dateBetween = $this->case_count_by_dateBetween_highCourt($val->id, $data)->count();
                $val->prevUndoneCase = $this->previous_undone_case_count_firstDate_highCourt($val->id, $data)->count();
                $val->totalCase = $this->total_case_count_by_highCourt($val->id, $data)->count();
                $val->doneCase = $this->done_case_count_by_dateBetween_highCourt($val->id, $data)->count();
                $val->favouredGov = $this->done_favoured_gov_case_count_highCourt($val->id, $data)->count();
                $val->againstGov = $this->done_against_gov_case_count_highCourt($val->id, $data)->count();
                $val->lastWorkDay = $this->previous_undone_case_count_lastDate_highCourt($val->id, $data);
                $val->importantCase = $this->imprtant_case_count_by_dateBetween_highCourt($val->id, $data)->count();
                array_push($arrayd, $val);
            }
            $data['ministryWiseData'] = $arrayd;
            $roleID = Auth::user()->role_id;
            if ($roleID != 27) {
                $html = view('gov_report.pdf_num_ministry_wise_data')->with($data);
                $this->generatePDF($html);
                // return view('gov_report.ministryWiseCaselist')->with($data);
            } else {
                $html = view('gov_report.pdf_num_ministry')->with($data);
                $this->generatePDF($html);
            }
            // Generate PDF

            // return view('gov_report.pdf_num_ministry')->with($data);
        }

        //=======================//Ministry Wise Case Report========================//

        //=======================Important Wise Case Report========================//

        if ($request->btnsubmit == 'pdf_num_importance') {
            $data['page_title'] = 'সরকারি মামলার তালিকা'; //exit;
            $data['date_start'] = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_start)));
            $data['date_end'] = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_end)));

            $data['page_title'] = 'গুরুত্বপূর্ণ ভিত্তিক সরকারি স্বার্থ সংশ্লিষ্ট মামলার রিপোর্ট'; //exit;

            $data['importantCase'] = $this->imprtant_case_details();
            if (count($data['importantCase']) < 1) {
                $data['results'] = [];
            }
            // dd(1);
            foreach ($data['importantCase'] as $key => $value) {
                $data['results'][$key]['caseID'] = $value->id;
                $data['results'][$key]['caseNum'] = $value->case_no;
                $data['results'][$key]['courtID'] = $value->court_id;
                $data['results'][$key]['petitioner'] = $value->concern_user_id;
                $data['results'][$key]['content'] = $value->subject_matter;
                $data['results'][$key]['importaceReason'] = $value->important_cause;
                // $data['results'][$key]['mulBibadi']  = $this->imprtant_case_mul_bibadi($data['results'][$key]['caseID']);
                // $data['results'][$key]['otherBibadi']= $this->imprtant_case_other_bibadi($data['results'][$key]['caseID']);
                // $data['results'][$key]['lastStep']= $this->imprtant_case_last_taken_step($data['results'][$key]['caseID']);
                // $data['results'][$key]['courtName']= $this->imprtant_case_court_name($data['results'][$key]['courtID']);
                // $data['results'][$key]['petitionerName']= $this->imprtant_case_petitioner_name($data['results'][$key]['petitioner']);

                $data['results'][$key]['mulBibadi'] = $this->imprtant_case_mul_bibadi($data['results'][$key]['caseID']);
                $data['results'][$key]['otherBibadi'] = $this->imprtant_case_other_bibadi($data['results'][$key]['caseID']);
                $data['results'][$key]['lastStep'] = ' ';
                $data['results'][$key]['courtName'] = ' ';
                $data['results'][$key]['petitionerName'] = ' ';
            }

            // return $data;
            // return $data['results'];
            $html = view('gov_report.pdf_num_important')->with($data);
            // Generate PDF
            $this->generatePDF($html);
        }

        //=======================//Important Wise Case Report========================//

    }

    public function case_count_by_dateBetween_highCourt($id, $data = null)
    {
        $from = $data['date_start'];
        $to = $data['date_end'];

        // $officeID = Auth::user()->office_id;

        // $childOfficeIds = [];
        // $childOfficeQuery = DB::table('gov_case_office')
        //     ->select('id')
        //     ->where('parent', $officeID)->get();

        // foreach ($childOfficeQuery as $childOffice) {
        //     $childOfficeIds[] = $childOffice->id;
        // }

        // $finalOfficeIds = [];
        // if (empty($childOfficeIds)) {
        //     $finalOfficeIds[] = $officeID;
        // } else {
        //     $finalOfficeIds[] = $officeID;
        //     $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        // }
        // return $finalOfficeIds;
        $query = GovCaseRegister::whereBetween('date_issuing_rule_nishi', [$from, $to])->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {$query->where('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');})->get();

        return $query;
    }
    public function previous_undone_case_count_firstDate_highCourt($id, $data = null)
    {
        $from = $data['date_start'];
        $to = $data['date_end'];

        $query = GovCaseRegister::where('date_issuing_rule_nishi', '<=', $from)->where('result', null)->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {$query->where('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');})->get();

        return $query;
    }
    public function total_case_count_by_highCourt($id, $data = null)
    {
        $from = $data['date_start'];
        $to = $data['date_end'];

        $query = GovCaseRegister::orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {$query->where('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');})->get();

        return $query;
    }

    public function done_case_count_by_dateBetween_highCourt($id, $data = null)
    {
        $from = $data['date_start'];
        $to = $data['date_end'];

        $query = GovCaseRegister::whereBetween('date_issuing_rule_nishi', [$from, $to])->where('result', '!=', null)->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {$query->where('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');})->get();

        return $query;
    }

    public function done_favoured_gov_case_count_highCourt($id, $data = null)
    {
        $from = $data['date_start'];
        $to = $data['date_end'];

        $query = GovCaseRegister::whereBetween('date_issuing_rule_nishi', [$from, $to])->where('result', 1)->where('in_favour_govt', 1)->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {$query->where('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');})->get();

        return $query;
    }

    public function done_against_gov_case_count_highCourt($id, $data = null)
    {
        $from = $data['date_start'];
        $to = $data['date_end'];

        $query = GovCaseRegister::whereBetween('date_issuing_rule_nishi', [$from, $to])->where('result', 2)->where('in_favour_govt', 0)->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {$query->where('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');})->get();

        return $query;
    }

    public function previous_undone_case_count_lastDate_highCourt($id, $data = null)
    {
        $from = $data['date_start'];
        $to = $data['date_end'];

        // $query = GovCaseRegister::where('date_issuing_rule_nishi', '<=', $from)->where('result', null)->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {$query->where('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');})->get();

        $totalCase = GovCaseRegister::orderby('id', 'DESC')->whereHas('bibadis', function ($totalCase) use ($id) {$totalCase->where('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');})->count();

        $completeCase = GovCaseRegister::whereBetween('date_issuing_rule_nishi', [$from, $to])->where('result', '!=', null)->orderby('id', 'DESC')->whereHas('bibadis', function ($completeCase) use ($id) {$completeCase->where('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');})->count();

        return $totalCase - $completeCase;
    }

    public function imprtant_case_count_by_dateBetween_highCourt($id, $data = null)
    {
        $from = $data['date_start'];
        $to = $data['date_end'];

        $query = GovCaseRegister::whereBetween('date_issuing_rule_nishi', [$from, $to])->where('important_cause', '!=', null)->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {$query->where('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');})->get();

        return $query;
    }

    public function imprtant_case_details()
    {

        $query = GovCaseRegister::select('id', 'case_no', 'court_id', 'concern_user_id', 'subject_matter', 'important_cause')->where('most_important', '!=', null)->orderby('id', 'ASC')->get();

        return $query;
    }

    public function imprtant_case_mul_bibadi($caseID)
    {

        $query = GovCaseBibadi::select('respondent_id', 'department_id')->where('gov_case_id', $caseID)->where('is_main_bibadi', 1)->with(['ministry' => function ($query) {
            $query->select('id', 'office_name_bn');
        }])->with(['department' => function ($query) {
            $query->select('id', 'office_name_bn');
        }])->first();
        return $query;
    }

    public function imprtant_case_other_bibadi($caseID)
    {

        $query = GovCaseBibadi::select('respondent_id', 'department_id')->where('gov_case_id', $caseID)->where('is_main_bibadi', 0)->with(['ministry' => function ($query) {
            $query->select('id', 'office_name_bn');
        }])->with(['department' => function ($query) {
            $query->select('id', 'office_name_bn');
        }])->first();
        return $query;
    }

    public function imprtant_case_last_taken_step($caseID)
    {

        // $query = GovCaseLog::where('gov_case_id', $caseID)->first()->case_status->status_name;
        $query = GovCaseRegister::where('id', $caseID)->first();
        return $query;
    }

    public function imprtant_case_court_name($courtID)
    {

        $query = Court::where('id', $courtID)->first()->court_name;
        return $query;
    }

    public function imprtant_case_petitioner_name($petitionertID)
    {

        $query = User::where('id', $petitionertID)->first();
        $query = $query->name;
        return $query;
    }

    public function generatePDF($html)
    {
        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 12,
            'default_font' => 'kalpurush',
            'format' => 'A4-L',
            'orientation' => 'L',
        ]);
        $mpdf->AddPageByArray([
            'margin-left' => 5,
            'margin-right' => 5,
            'margin-top' => 5,
            'margin-bottom' => 5,
        ]);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        // $mpdf->shrink_tables_to_fit = 1;
        $mpdf->use_kwt = true;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        if ($request->btnsubmit == 'pdf_division') {
            $data['page_title'] = 'মন্ত্রণালয় ভিত্তিক সরকারি মামলার রিপোর্ট'; //exit;
            $html = view('gov_report.pdf_division')->with($data);
            // echo 'hello';

            $mpdf = new \Mpdf\Mpdf([
                'default_font_size' => 12,
                'default_font' => 'kalpurush',
            ]);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }

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
