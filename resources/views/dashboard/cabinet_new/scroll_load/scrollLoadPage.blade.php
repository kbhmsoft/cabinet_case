<div class="row mt-4">
    <!-- Card 7 -->
    <div class="col-md-12 mb-4 ">
        <table class="table table-hover mb-6 font-size-h5">
            <thead class="bg-light-primary font-size-h6">
                <tr>
                    <th scope="col">বিভাগ</th>
                    <th scope="col">হাইকোর্ট বিভাগে সরকারি স্বার্থ সংশ্লিষ্ট চলমান মামলা</th>
                    <th scope="col">আপিল বিভাগে সরকারি স্বার্থ সংশ্লিষ্ট চলমান মামলা</th>
                    <th scope="col">আপিলের জন্য পেন্ডিং</th>
                    <th scope="col">জবাব পেন্ডিং</th>
                    <th scope="col">স্থগিতাদেশ অন্তর্বর্তীকালীন পেন্ডিং মামলা</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['ministry'] as $val)
                    <tr>
                        <td class="font-weight-bolder">{{ $val->office_name_bn }}</td>
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
            {!! $data['ministry']->links() !!}
        </div>
    </div>
</div>
