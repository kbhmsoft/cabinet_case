<br>
<div class="container card">
    <div class="card-body">
        <div class="card-title">
            <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
        </div>
        <table class="table table-hover mb-6 font-size-h5">
            <thead class="thead-light font-size-h6">
                <tr>
                    <th scope="col" width="30">#</th>
                    <th scope="col"></th>
                    <th scope="col">হাইকোর্ট বিভাগে চলমান মামলা</th>
                </tr>
            </thead>
            <tbody>

                @php
                    $index = 1;
                @endphp
                @foreach ($ministry as $key => $val)
                    <tr>
                        <td>{{ en2bn($key + $ministry->firstItem()) }}</td>
                        <td>
                            <h4><a
                                    href="{{ route('cabinet.case.ministryWiseData', $val->id) }}">{{ $val->office_name_bn }}</a>
                            </h4>
                        </td>
                        <td align="center">{{ en2bn($val->total_running_appeal_case) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {!! $ministry->links() !!}
        </div>
    </div>
</div>
