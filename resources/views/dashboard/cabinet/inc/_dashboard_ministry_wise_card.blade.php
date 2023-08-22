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
                {{-- @foreach ($ministryData as $ministry) --}}
                @php
                $index=1;
                @endphp
                @foreach ($ministry_wise as $key=>$val)
                {{-- @dd($val->id) --}}

              <tr>
                  <td>{{ en2bn($key + $ministry_wise->firstItem()) }}</td>
                  <td><h4>{{ $val->office_name_bn }}</h4></td>
                  <td align="center">{{ en2bn($val->highcourt_running_case) }}</td>
                  <td align="center">{{ en2bn($val->appeal_running_case) }}</td>
                  <td align="center">{{ en2bn($val->against_gov) }}</td>
                  <td align="center">{{ en2bn($val->result_sending_count) }}</td>
                  <td align="center">{{ en2bn($val->against_postponed_count) }}</td>
                  {{-- <td><h4>{{ $key->office_name_bn }}</h4></td>
                  <td align="center">{{ en2bn($key->highcourt_running_case) }}</td>
                  <td align="center">{{ en2bn($key->appeal_running_case) }}</td>
                  <td align="center">{{ en2bn($key->against_gov) }}</td>
                  <td align="center">{{ en2bn($key->result_sending_count) }}</td>
                  <td align="center">{{ en2bn($key->against_postponed_count) }}</td> --}}
               </tr>
               @endforeach
            </tbody>
         </table>

         <div class="d-flex justify-content-center">
            {!! $ministry_wise->links() !!}
         </div>
      </div>
   </div>
