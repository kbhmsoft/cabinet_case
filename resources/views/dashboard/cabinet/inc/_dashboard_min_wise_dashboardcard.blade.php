
<br>
    <div class="container card">
      <div class="card-body">
         <table class="table table-hover mb-6 font-size-h5">
            <thead class="thead-light font-size-h6">
               <tr>
                  <th scope="col" width="30">#</th>
                  <th scope="col"></th>
                  <th scope="col">হাইকোর্ট বিভাগে চলমান মামলা</th>
                  <th scope="col">আপিল বিভাগে চলমান মামলা</th>
                  <th scope="col">সরকারের বিপক্ষে আপিলের জন্য পেন্ডিং</th>
                  <th scope="col">জবাব পেন্ডিং</th>
                  <th scope="col">স্থগিতাদেশ অন্তর্বর্তীকালীন পেন্ডিং মামলা</th>
               </tr>
            </thead>
            <tbody>
                {{-- {{dd($ministry)}} --}}
                {{-- @foreach ($ministryData as $ministry) --}}
                @php
                $index=1;
                @endphp
                @foreach ($ministry as $key=>$val)
               <tr>
                  <td>{{ en2bn($key + $ministry->firstItem()) }}</td>
                  <td>{{ $val->office_name_bn }}</td>
                  <td align="center">{{ en2bn($val->highcourt_running_case) }}</td>
                  <td align="center">{{ en2bn($val->appeal_running_case) }}</td>
                  <td align="center">{{ en2bn($val->against_gov) }}</td>
                  <td align="center">{{ en2bn($val->result_sending_count) }}</td>
                  <td align="center">{{ en2bn($val->against_postponed_count) }}</td>
               </tr>
               @endforeach
            </tbody>
         </table>

         <div class="d-flex justify-content-center">
            {!! $ministry->links() !!}
         </div>
      </div>
   </div>

   <?php
//    $ministryCount = DB::table('gov_case_office')
//        ->select(
//            'gov_case_office.id',
//            'gov_case_office.office_name_bn',
//            'gov_case_office.office_name_en',
//            DB::raw('SUM(CASE WHEN agcr.is_final_order = "0" THEN 1 ELSE 0 END) AS appeal_running_case'),
//        )
//        ->leftJoin('appeal_gov_case_register as agcr', 'gov_case_office.id', '=', 'agcr.appeal_office_id')
//        ->where('gov_case_office.id', $val->id)
//        ->groupBy('gov_case_office.id')
//        ->count();

//    if($ministryCount){
//        $appealRunningCount = $ministryCount;
//    }
   ?>
