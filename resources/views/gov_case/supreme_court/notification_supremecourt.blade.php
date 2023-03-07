@extends('layouts.cabinet.cab_default')

@section('content')
    <!-- Bootstrap CSS -->






    <div class="card card-custom">
        <div class="card-title">
            <h3 class="card-title h2 font-weight-bolder text-center mt-2">{{ $page_title }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8" id="search_result_supream_court">
                    <h3 class="text-center "><span class="date_show_hearing">{{ $date }}</span> তারিখের শুনানি</h3>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-8"> <label for="exampleFormControlInput1" class="form-label"><b>Date</b></label>
                            <input type="text" class="form-control required common_datepicker" autocomplete="off" name="case_date"
                                id="case_date" placeholder="" value="{{ $date }}">
                        </div>
                        <div class="col-md-4"><button class="btn btn-primary mt-4"
                                id="suprime_court_case_search_button">Search</button></div>
                    </div>


                </div>
            </div>

            <div class="row">
                <div class="col-md-12 py-5 table_goes_here">
                    @php echo $output; @endphp
                </div>
            </div>
        </div>

    </div>
    <style>
        .dataTables_paginate a {
            margin: 7px !important;
            cursor: pointer;
        }

        .dataTables_filter {
            margin-top: 20px;
        }

        .dataTables_filter input {
            margin-left: 10px
        }

       
    </style>
    <!-- Bootstrap Bundle with Popper -->
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"></script>
    <script>
        // common datepicker
        $('#case_date').datepicker({
            format: "dd/mm/yyyy",
            todayHighlight: true,
            orientation: "bottom left"
        });
    </script>
    <script type="text/javascript">
        $("table").DataTable({
            order: [0, 'asc']
        });

        $('#suprime_court_case_search_button').on('click', function(e) {
            e.preventDefault();
            swal.showLoading();
            $.ajax({
                url: '{{ route('show.notification.supremecourt') }}',
                method: 'get',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "date": $('#case_date').val()
                },

                success: function(response) {
                    swal.close();
                    if (response.success == 'success') {
                        $('.table_goes_here').empty();
                        $('.table_goes_here').html(response.output);
                        $('.date_show_hearing').html(response.date);
                    }
                }

            });

        })

        $(document).on('click', '.modal-url-show', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('modal.case.details.view') }}',
                method: 'get',
                data: {
                    url: $(this).data('url')
                },
                success: function(response) {
                    $("#modal_case_view").html(response);

                }
            });
            $('#staticBackdrop').modal('toggle');
        });
    </script>
@endsection
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-window-close" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container" id="modal_case_view">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</html>
