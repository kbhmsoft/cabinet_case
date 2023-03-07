<?php

namespace App\Http\Controllers\gov_case;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SumpremCourtController extends Controller
{
    public function search_case()
    {
        $data['page_title'] = 'search_case';
        return view('gov_case.supreme_court.search_case')->with($data);
    }
    public function search_case_post_function(Request $request)
    {
        //dd($request);
        $div_id = $request->division_id;
        $case_type_id = $request->case_type_id;
        $case_number = $request->case_number;
        $year = $request->year;

        $url = 'https://supremecourt.gov.bd/web/case_history/case_history.php?div_id=' . $div_id . '&case_type_id=' . $case_type_id . '&case_number=' . $case_number . '&year=' . $year;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return response()->json([
            'success' => 'success',
            'html' => $response,
        ]);
    }
    public function supremecourt_causelist()
    {
        $data['page_title'] = 'causelist';
        return view('gov_case.supreme_court.causelist_supremecourt')->with($data);
    }

    public function supremecourt_causelist_pull_data(Request $request)
    {
        //dd($request);

        $div_id = $request->division_id;
        if ($div_id == 1) {
            $court_id = $request->court_no;
            $date1 = $request->case_date;
            $url = 'https://supremecourt.gov.bd/web/index.php?page=cause_list.php&menu=00&div_id=1&court_id=' . $court_id . '&date1=' . $date1;
        } else {
            $court_id = explode('+', $request->court_no)[0];
            $bench_id = explode('+', $request->court_no)[1];
            $date1 = $request->case_date;
            $url = 'https://supremecourt.gov.bd/web/index.php?page=cause_list.php&menu=00&div_id=2&court_id=' . $court_id . '&date1=' . $date1 . '&bench_id=' . $bench_id;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
          
        curl_close($curl);

        $html = '<style>

        .link_e10ru,.boxhead_234,.boxhead_212{
            display:none !important;
        }
        #date1{
            display:none !important;
        }
        .friend tr:first-child
        {
            display:none !important;
        }
           </style>';

        return response()->json([
            'success' => 'success',
            'html' => $html . $response,
        ]);
    }

    public function supremecourt_get_notification()
    {
        //dd(bn2en('1212'));
        $all_case = DB::table('gov_case_registers')->get();
        // dd($all_case);
        foreach ($all_case as $single_case) {
            $div_id = $single_case->case_division_id;
            $case_type_id = $single_case->case_type_id;
            $explode_the_case = explode('/', $single_case->case_no);
            $case_number = isset($explode_the_case[0]) ? (bn2en($explode_the_case[0])) : '';
            $year = isset($explode_the_case[1]) ? (bn2en($explode_the_case[1])) : '';

            $url = 'https://supremecourt.gov.bd/web/case_history/case_history.php?div_id=' . $div_id . '&case_type_id=' . $case_type_id . '&case_number=' . $case_number . '&year=' . $year;
            // dd($url);
            $this->insert_into_notification_table($url, $single_case->case_no);
        }

    }
    public function insert_into_notification_table($url, $case_number)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //dd($response );
        preg_match_all('/([0-9]?[0-9])[\.\-\/ ]+([0-1]?[0-9])[\.\-\/ ]+([0-9]{2,4})/', strip_tags($response), $complete);

        // dd($complete);

        $date = date('d/m/y');
        
        if (!empty($complete[0])) {
            if ($complete[0][array_key_last($complete[0])] == '27/02/23') {

                $notify_data = [
                    'case_no' => $case_number,
                    'url' => $url,
                    'date' => '27/02/23',
                ];
                DB::table('gov_case_notify_supre_court')->insert($notify_data);
            }
        }

    }

    public function supremecourt_show_notification(Request $request)
    {
        if(!empty($request->date))
        {
            // $exploded_date=explode('/',$request->date);
            // $date_year=$exploded_date[2]; //substr($str, 4);
            
            $date=$request->date;

        }
        else
        {
            $date='27/02/2023';  
        }
        
        $all_case = DB::table('gov_case_notify_supre_court')->where('date', $date)->get();
        $output = '';
        if (count($all_case) > 0) {
            $output .= '<table class="table table-striped table-sm text-left align-middle">
                <thead>
              <tr>
                <th>ক্রমিক নং</th>
                <th>মামলা নং</th>
                <th>তারিখ</th>
              </tr>
            </thead><tbody>';

            $i = 1;
            foreach ($all_case as $value) {
                $case_no = '<a href="#" class="text-decoration-none modal-url-show" data-url="'.$value->url.'">' . bn2en($value->case_no) . '</a>';

                $output .= '<tr>';
                $output .= '<td>' . $i++ . '</td>';
                $output .= '<td>' . $case_no . '</td>';
                $output .= '<td>' . $value->date . '</td>';
                $output .= '</tr>';

            }
            $output .= '</tbody></table>';
        } else {
            $output .= '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }
        if(!empty($request->date))
        {
            return response()->json([
                'success'=>'success',
                'output'=>$output,
                'date'=>$request->date
            ]);
        }
        $data['page_title'] = 'Notification';
        $data['output'] = $output;
        $data['date'] = $date;
        return view('gov_case.supreme_court.notification_supremecourt')->with($data);

    }

    public function modal_case_details_view(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $request->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
       
        return $response;
    }
}
