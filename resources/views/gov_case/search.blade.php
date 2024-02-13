<?php
$roleID = Auth::user()->role_id;
$officeInfo = user_office_info();
$caseNo = '';
$caseCategoryType = '';

if (!empty($_GET['case_no']) && !empty($_GET['case_category_type'])) {
    $caseNo = $_GET['case_no'];
    $caseCategoryType = $_GET['case_category_type'];
}

?>
<form class="form-inline" id="searchForm" method="GET">
    <div class="row">
        <div class="col-lg-6 mb-5 px-2">
            <select name="case_category_type" class="w-100 form-control">
                <option value="">মামলার শ্রেণী/কেস-টাইপ</option>
                @php
                    $addedCaseNames = [];
                    $selectedValue = old('case_category_type');
                @endphp
                @foreach ($gov_case_division_category_type as $value)
                    @foreach ($cases as $key => $row)
                        @if ($row->case_type_id == $value->id && !in_array($value->name_bn, $addedCaseNames))
                            @php
                                $addedCaseNames[] = $value->name_bn;
                            @endphp
                            <option value="{{ $value->id }}" {{ $selectedValue == $value->id ? 'selected' : '' }}>
                                {{ $value->name_bn }}
                            </option>
                        @endif
                    @endforeach
                @endforeach
            </select>
        </div>


        <div class="col-lg-6 px-2">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="case_no" placeholder="মামলা নং"
                    value="{{ $caseNo }}">
                <div class="input-group-append">
                    <button type="submit" class="input-group-text btn btn-success ">অনুসন্ধান করুন</button>
                </div>
            </div>
        </div>
    </div>
</form>



@section('scripts')
    <script src="{{ asset('js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"></script>
    <script>
        // common datepicker
        $('.common_datepicker').datepicker({
            format: "dd/mm/yyyy",
            todayHighlight: true,
            orientation: "bottom left"
        });
    </script>
    <script type="text/javascript"></script>
@endsection


<script>
    // Reset the form fields when the page is loaded
    window.addEventListener('load', function() {
        document.getElementById('searchForm').reset();
    });
</script>
