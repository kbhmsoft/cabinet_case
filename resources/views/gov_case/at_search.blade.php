<?php
$roleID = Auth::user()->role_id;
$officeInfo = user_office_info();
$caseNo = '';
$caseCategoryType = '';

if (!empty($_GET['case_no'])) {
    $caseNo = $_GET['case_no'];
    
}

?>
<form class="form-inline" id="searchForm" method="GET">
    <div class="row">
        

        <div class="col-lg-12 px-2">
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
