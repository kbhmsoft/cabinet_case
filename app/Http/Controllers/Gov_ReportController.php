<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\GovCaseLog;
use App\Models\gov_case\GovCaseBadi;
use App\Models\gov_case\GovCaseBibadi;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Models\gov_case\GovCaseHearing;
use App\Models\Court;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Gov_ReportController extends Controller
{
    public function caselist()
    {
          // Dropdown List
        $data['courts'] = DB::table('court')->select('id', 'court_name')->get();
        $data['roles'] = DB::table('role')->select('id', 'role_name')->where('in_action', 1)->get();
        $data['ministry'] = DB::table('office')->select('id', 'office_name_bn')->whereIn('level',[8,9])->get();

        $data['getMonth'] = date('M', mktime(0,0,0));

        $data['page_title'] = 'সরকারি স্বার্থ সংশ্লিষ্ট মামলার রিপোর্ট ফরম'; //exit;
        // return view('case.case_add', compact('page_title', 'case_type'));
        return view('gov_report.caselist')->with($data);
    }

    public function pdf_generate(Request $request)
    {
        //=========================Ministry Wise Case Report========================//

        if($request->btnsubmit == 'pdf_num_ministry'){
        // return $request;
            $data['page_title'] = 'সরকারি মামলার তালিকা'; //exit;
            $data['date_start'] = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_start)));
            $data['date_end'] = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_end)));

             // Validation
            $request->validate(
                [
                    'ministry' => 'required',
                    'date_start' => 'required',
                    'date_end' => 'required'
                ],
                [
                    'ministry.required' => 'মন্ত্রণালয় নির্বাচন করুন',
                    'date_start.required' => 'মামলা শুরুর তারিখ নির্বাচন করুন', 
                    'date_end.required' => 'মামলা শেষের তারিখ নির্বাচন করুন'
                ]
            );

            $data['page_title'] = ' এর সরকারি স্বার্থ সংশ্লিষ্ট মামলার রিপোর্ট'; //exit;
            $min_id = $request->ministry;
            $dept_id = $request->department;

             // Get ministry
            if($min_id != NULL){

                $data['ministry'] = DB::table('office')->select('id as min_id', 'office_name_bn')->where('id',$min_id)->get();
            }else{
                $data['ministry'] = DB::table('office')->select('id as min_id', 'office_name_bn')->whereIn('level',[8,9])->get();
            }

            foreach ($data['ministry'] as $key => $value) {
                $data['results'][$key]['ministry_name_bn'] = $value->office_name_bn;
                $data['results'][$key]['min_id'] = $value->min_id;
                if ($dept_id != NULL) {
                    $data['results'][$key]['doptor'] = DB::table('office')->select('id as doptor_id', 'office_name_bn as doptor_name')->whereIn('level',[10,11,12])->where('parent',$data['results'][$key]['min_id'])->where('id',$dept_id)->get();
                }else{
                    $data['results'][$key]['doptor'] = DB::table('office')->select('id as doptor_id', 'office_name_bn as doptor_name')->whereIn('level',[10,11,12])->where('parent',$data['results'][$key]['min_id'])->get();
                }

                foreach ($data['results'][$key]['doptor'] as $k => $val){
                    // echo $al->doptor_id; exit;
                    $data['results'][$key]['doptor'][$k]->dateBetween = $this->case_count_by_dateBetween_highCourt($val->doptor_id,$data)->count();
                    $data['results'][$key]['doptor'][$k]->prevUndoneCase = $this->previous_undone_case_count_firstDate_highCourt($val->doptor_id,$data)->count();
                    $data['results'][$key]['doptor'][$k]->totalCase = $this->total_case_count_by_highCourt($val->doptor_id,$data)->count();
                    $data['results'][$key]['doptor'][$k]->doneCase = $this->done_case_count_by_dateBetween_highCourt($val->doptor_id,$data)->count();
                    $data['results'][$key]['doptor'][$k]->againstGov = $this->done_against_gov_case_count_highCourt($val->doptor_id,$data)->count();
                    $data['results'][$key]['doptor'][$k]->appealCase = $this->appeal_case_count_by_dateBetween_highCourt($val->doptor_id,$data)->count();
                    $data['results'][$key]['doptor'][$k]->lastWorkDay = $this->previous_undone_case_count_lastDate_highCourt($val->doptor_id,$data)->count();
                    $data['results'][$key]['doptor'][$k]->importantCase = $this->imprtant_case_count_by_dateBetween_highCourt($val->doptor_id,$data)->count();
                }
            }
            // return $data['results'];
            $html = view('gov_report.pdf_num_ministry')->with($data);
             // Generate PDF
             $this->generatePDF($html);
              // return view('gov_report.pdf_num_ministry')->with($data);
        }

            //=======================//Ministry Wise Case Report========================//

            //=======================Important Wise Case Report========================//

        if($request->btnsubmit == 'pdf_num_importance'){
            $data['page_title'] = 'সরকারি মামলার তালিকা'; //exit;
            $data['date_start'] = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_start)));
            $data['date_end'] = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_end)));




            $data['page_title'] = 'গুরুত্বপূর্ণ ভিত্তিক সরকারি স্বার্থ সংশ্লিষ্ট মামলার রিপোর্ট'; //exit;

            $data['importantCase'] = $this->imprtant_case_details();
            foreach($data['importantCase'] as $key => $value){
                $data['results'][$key]['caseID']=$value->id;
                $data['results'][$key]['caseNum']=$value->case_no;
                $data['results'][$key]['courtID']=$value->court_id;
                $data['results'][$key]['petitioner']=$value->concern_user_id;
                $data['results'][$key]['content']=$value->subject_matter;
                $data['results'][$key]['importaceReason']=$value->important_cause;
                $data['results'][$key]['mulBibadi']  = $this->imprtant_case_mul_bibadi($data['results'][$key]['caseID']);
                $data['results'][$key]['otherBibadi']= $this->imprtant_case_other_bibadi($data['results'][$key]['caseID']);
                $data['results'][$key]['lastStep']= $this->imprtant_case_last_taken_step($data['results'][$key]['caseID']);
                $data['results'][$key]['courtName']= $this->imprtant_case_court_name($data['results'][$key]['courtID']);
                $data['results'][$key]['petitionerName']= $this->imprtant_case_petitioner_name($data['results'][$key]['petitioner']);
            }
            // return $data;
            // return $data['results'];
            $html = view('gov_report.pdf_num_important')->with($data);
             // Generate PDF
            $this->generatePDF($html);
        }

            //=======================//Important Wise Case Report========================//


    }



    public function case_count_by_dateBetween_highCourt($id, $data=NULL){
        $from = $data['date_start'];
        $to = $data['date_end'];

        $query = GovCaseRegister::whereBetween('date_issuing_rule_nishi', [$from, $to])->orderby('id', 'DESC')->whereHas( 'bibadis', function ($query)use($id) {$query->where('department_id', $id)->where('is_main_bibadi',1)->groupBy('gov_case_id');})->get();

        return $query;
    }
    public function previous_undone_case_count_firstDate_highCourt($id, $data=NULL){
        $from = $data['date_start'];
        $to = $data['date_end'];

        $query = GovCaseRegister::where('date_issuing_rule_nishi', '<=', $from)->where('result', null)->orderby('id', 'DESC')->whereHas( 'bibadis', function ($query)use($id) {$query->where('department_id', $id)->where('is_main_bibadi',1)->groupBy('gov_case_id');})->get();

        return $query;
    }
    public function total_case_count_by_highCourt($id, $data=NULL){
        $from = $data['date_start'];
        $to = $data['date_end'];

        $query = GovCaseRegister::orderby('id', 'DESC')->whereHas( 'bibadis', function ($query)use($id) {$query->where('department_id', $id)->where('is_main_bibadi',1)->groupBy('gov_case_id');})->get();

        return $query;
    }

    public function done_case_count_by_dateBetween_highCourt($id, $data=NULL){
        $from = $data['date_start'];
        $to = $data['date_end'];

        $query = GovCaseRegister::whereBetween('date_issuing_rule_nishi', [$from, $to])->where('result','!=',null)->orderby('id', 'DESC')->whereHas( 'bibadis', function ($query)use($id) {$query->where('department_id', $id)->where('is_main_bibadi',1)->groupBy('gov_case_id');})->get();

        return $query;
    }

    public function done_against_gov_case_count_highCourt($id, $data=NULL){
        $from = $data['date_start'];
        $to = $data['date_end'];

        $query = GovCaseRegister::whereBetween('date_issuing_rule_nishi', [$from, $to])->where('result','!=',null)->where('in_favour_govt', 0)->orderby('id', 'DESC')->whereHas( 'bibadis', function ($query)use($id) {$query->where('department_id', $id)->where('is_main_bibadi',1)->groupBy('gov_case_id');})->get();

        return $query;
    }

    public function appeal_case_count_by_dateBetween_highCourt($id, $data=NULL){
        $from = $data['date_start'];
        $to = $data['date_end'];

        $query = GovCaseRegister::where('status', 2)->where('in_favour_govt', 0)->orderby('id', 'DESC')->whereHas( 'bibadis', function ($query)use($id) {$query->where('department_id', $id)->where('is_main_bibadi',1)->groupBy('gov_case_id');})->get();

        return $query;
    }

    public function previous_undone_case_count_lastDate_highCourt($id, $data=NULL){
        $from = $data['date_start'];
        $to = $data['date_end'];

        $query = GovCaseRegister::where('date_issuing_rule_nishi', '<=', $from)->where('result', null)->orderby('id', 'DESC')->whereHas( 'bibadis', function ($query)use($id) {$query->where('department_id', $id)->where('is_main_bibadi',1)->groupBy('gov_case_id');})->get();

        return $query;
    }

    public function imprtant_case_count_by_dateBetween_highCourt($id, $data=NULL){
        $from = $data['date_start'];
        $to = $data['date_end'];

        $query = GovCaseRegister::whereBetween('date_issuing_rule_nishi', [$from, $to])->where('important_cause','!=',null)->orderby('id', 'DESC')->whereHas( 'bibadis', function ($query)use($id) {$query->where('department_id', $id)->where('is_main_bibadi',1)->groupBy('gov_case_id');})->get();

        return $query;
    }

    public function imprtant_case_details(){


        $query = GovCaseRegister::select('id','case_no','court_id','concern_user_id','subject_matter','important_cause')->where('important_cause','!=',null)->orderby('id', 'ASC')->get();

        return $query;
    }

    public function imprtant_case_mul_bibadi($caseID){

          $query = GovCaseBibadi::select('ministry_id','department_id')->where('gov_case_id', $caseID)->where('is_main_bibadi',1)->with(['ministry' => function($query){
            $query->select('id','office_name_bn');
        }])->with(['department' => function($query){
            $query->select('id','office_name_bn');
        }])->first();
        return $query;
    }

    public function imprtant_case_other_bibadi($caseID){

        $query = GovCaseBibadi::select('ministry_id','department_id')->where('gov_case_id', $caseID)->where('is_main_bibadi',0)->with(['ministry' => function($query){
            $query->select('id','office_name_bn');
        }])->with(['department' => function($query){
            $query->select('id','office_name_bn');
        }])->first();
        return $query;
    }

    public function imprtant_case_last_taken_step($caseID){

        // $query = GovCaseLog::where('gov_case_id', $caseID)->first()->case_status->status_name;
        $query = GovCaseRegister::where('id', $caseID)->first()->case_status->status_name;
        return $query;
    }

    public function imprtant_case_court_name($courtID){

        $query = Court::where('id', $courtID)->first()->court_name;
        return $query;
    }

    public function imprtant_case_petitioner_name($petitionertID){

        $query = User::where('id', $petitionertID)->first();
        $query = $query->name;
        return $query;
    }






   public function generatePDF($html){
    $mpdf = new \Mpdf\Mpdf([
     'default_font_size' => 10,
     'default_font'      => 'kalpurush',
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
    if($request->btnsubmit == 'pdf_division'){
         $data['page_title'] = 'মন্ত্রণালয় ভিত্তিক সরকারি মামলার রিপোর্ট'; //exit;
         $html = view('gov_report.pdf_division')->with($data);
         // echo 'hello';

         $mpdf = new \Mpdf\Mpdf([
          'default_font_size' => 12,
          'default_font'      => 'kalpurush'
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
