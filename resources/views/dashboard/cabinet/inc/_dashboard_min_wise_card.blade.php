<br>
    <div class="container card">
      <div class="card-body">
         <table class="table table-hover mb-6 font-size-h5">
            <thead class="thead-light font-size-h6">
               <tr>
                  <th scope="col" width="30">#</th>
                  <th scope="col">মন্ত্রণালয় নাম</th>
                  <th scope="col">চলমান মামলা</th>
                  <th scope="col">নিস্পত্তি মামলা</th>
                  <th scope="col">সরকারের পক্ষে</th>
                  <th scope="col">সরকারের বিপক্ষে</th>
               </tr>
            </thead>
            <tbody> 
               @foreach ($ministry_wise as $key => $row)
               <tr>
                  <td>{{ en2bn($key + $ministry_wise->firstItem()) }}</td>
                  <td><a href="{{ route('cabinet.case.ministry_wise_list', $row->id) }}">{{ $row->office_name_bn }}</a></td>
                  <td align="center">{{ en2bn($row->running_case) }}</td>
                  <td align="center">{{ en2bn($row->completed_case) }}</td>
                  <td align="center">{{ en2bn($row->against_gov) }}</td>
                  <td align="center">{{ en2bn($row->not_against_gov) }}</td>
               </tr>
               @endforeach
            </tbody>
         </table>

         <div class="d-flex justify-content-center">
            {!! $ministry_wise->links() !!}
         </div>
      </div>
   </div> 