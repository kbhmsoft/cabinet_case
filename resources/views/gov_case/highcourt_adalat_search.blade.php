<form class="form-inline" id="searchForm" method="GET">
    <div class="col-lg-6 px-2">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="adalat_name" placeholder="আদালতের নাম">
            <div class="input-group-append">
                <button type="submit" class="input-group-text btn btn-success ">অনুসন্ধান করুন</button>
            </div>
        </div>
    </div>
</form>


@section('scripts')
    <script src="{{ asset('js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"></script>
    <script>
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
