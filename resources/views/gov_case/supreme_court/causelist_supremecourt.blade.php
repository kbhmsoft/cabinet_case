@extends('layouts.cabinet.cab_default')

@section('content')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet" />

    <div class="card card-custom">
        <div class="card-title">
            <h3 class="card-title h2 font-weight-bolder text-center mt-2">{{ $page_title }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div class="row mt-5">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label"><b>Division</b></label>
                                <select class="form-control required1" name="division_id" id="division_id" required>
                                    <option value="">- Select -</option>
                                    <option value="1">Appellate Division</option>
                                    <option value="2">High Court Division</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label"><b>Court
                                        No</b></label>
                                <select class="form-control required1" name="court_no" id="court_no" required>
                                    <option value="">- Select -</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4"><label for="exampleFormControlInput1" class="form-label"><b>Date</b></label>
                            <input type="text" class="form-control required" name="case_date" id="case_date" placeholder=""  required>
                        </div>
                        <div class="col-md-4"><button class="btn btn-primary mt-4"
                                id="suprime_court_case_search_button">Search</button></div>




                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="search_result_supream_court">

                </div>
            </div>
        </div>

    </div>

    <style>
        #select2-court_no-container {
            line-height: 12px !important;
        }
        .card {
            position: relative;
        }

        .class-causlist-show {
            position: absolute;
            left: -11px;

        }
        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            height: 41px;
            user-select: none;
            -webkit-user-select: none;
            padding-top: 6px;
            font-size:1.2rem 
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>


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
        $('#division_id').on('change', function() {
            var division_id = $(this).find('option:selected').val();
            var options_21 =
                '<option value="">- Select -</option><option value="36+6351">Annex Building Court No. 12</option><option value="50+6352">Annex Building Court No. 26</option><option value="25+7083">Main Building Court No. 15</option><option value="35+6797">Annex Building Court No. 11</option><option value="9+6360">Main Building Court No. 7</option><option value="93+6945">Bijoy 71 Building Court No. 28 (9th Floor)</option><option value="40+6783">Annex Building Court No. 16</option><option value="42+6363">Annex Building Court No. 18</option><option value="58+6946">Annex Building Court No. 34</option><option value="43+6947">Annex Building Court No. 19</option><option value="36+6351">Annex Building Court No. 13</option><option value="74+6367">Main Building Court No. 25</option><option value="105+6948">Bijoy 71 Building Court No. 9 (5th Floor)</option><option value="103+6949">Bijoy 71 Building Chamber No. 16 (6th Floor)</option><option value="119+7194">Bijoy 71 Building Court No. 17 (7th Floor)</option><option value="108+6785">Bijoy 71 Building Court No. 3 (3rd Floor)</option><option value="94+6729">Bijoy 71 Building Court No. 26 (9th Floor)</option><option value="17+6828">Annex Building Court No. 6</option><option value="116+7164">Bijoy 71 Building Court No. 7 (4th Floor)</option><option value="55+6950">Annex Building Court No. 31</option><option value="39+6382">Annex Building Court No. 15</option>	<option value="13+6823">Annex Building Court No. 2</option><option value="51+6951">Annex Building Court No. 27</option><option value="112+6794">Bijoy 71 Building Court No. 1 (3rd Floor)</option><option value="95+6829">Bijoy 71 Building Court No. 32 (10th Floor)</option><option value="21+6790">Main Building Court No. 11</option><option value="87+7142">Annex Building Court No. 35</option><option value="33+6952">Main Building Court No. 23</option><option value="121+6953">Bijoy 71 Building Court No. 23 (8th Floor)</option><option value="22+7185">Main Building Court No. 12</option><option value="104+6798">Bijoy 71 Building Court No. 13 (6th Floor)</option><option value="97+6802">Bijoy 71 Building Court No. 12 (5th Floor)</option><option value="98+6789">Bijoy 71 Building Court No. 21 (8th Floor)</option><option value="23+7143">Main Building Court No. 13</option><option value="19+7086">Annex Building Court No. 9</option><option value="41+7144">Annex Building Court No. 17</option><option value="56+7145">Annex Building Court No. 32</option><option value="99+7155">Bijoy 71 Building Court No. 14 (6th Floor)</option><option value="111+6808">Bijoy 71 Building Court No. 18 (7th Floor)</option><option value="113+6955">Bijoy 71 Building Court No. 2 (3rd Floor)</option><option value="96+7141">Bijoy 71 Building Court No. 20 (7th Floor)</option><option value="100+7156">Bijoy 71 Building Court No. 11 (5th Floor)</option><option value="49+6787">Annex Building Court No. 25</option><option value="118+6788">Bijoy 71 Building Court No. 15 (6th Floor)</option><option value="45+6806">Annex Building Court No. 21</option>	<option value="29+7263">Main Building Court No. 19</option><option value="102+6622">Bijoy 71 Building Court No. 29 (10th Floor)</option><option value="115+7146">Bijoy 71 Building Court No. 6 (4th Floor)</option><option value="18+7275">Annex Building Court No. 8</option><option value="52+7274">Annex Building Court No. 28</option> <option value="40+7262">Annex Building Court No. 16</option> <option value="79+5510">Blank List 1</option><option value="80+2234">Blank List 2</option><option value="81+2236">Lawazima Court</option>';

            var options_11 =
                '<option value="">- Select -</option><option value="1">COURT NO. 1</option><option value="4">COURT NO. 2</option><option value="5">COURT NO. 3</option><option value="89">Chamber Court</option>';

            if (division_id == 2) {
                $('#court_no').empty();
                $('#court_no').append(options_21);
            } else if (division_id == 1) {
                $('#court_no').empty();
                $('#court_no').append(options_11);
            } else {
                $('#court_no').empty();
                var option_html = '<option value="">- Select -</option>';
                $('#court_no').append(option_html);
            }

            $('#court_no').select2();
        })


        $('#suprime_court_case_search_button').on('click', function(e) {
            e.preventDefault();
            let passport = true;
            $('.required1').each(function() {
                if ($(this).val() == '') {
                    passport = false;
                }
            });
            if (passport) {
                swal.showLoading();
                $.ajax({
                    url: '{{ route('supremecourt.cause.list.pull.data') }}',
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "division_id": $('#division_id').find('option:selected').val(),
                        "court_no": $('#court_no').find('option:selected').val(),
                        "case_date": $('#case_date').val(),
                    },

                    success: function(response) {
                        swal.close();
                        if (response.success == 'success') {
                            //$('#search_result_supream_court').empty();
                            $('#search_result_supream_court').html(response.html)
                            // var ahref = $('a').attr('href');
                            // if (ahref != undefined) {

                            //     var Nhref = 'https://supremecourt.gov.bd/web/' + ahref.replace('../', '');;
                            //     $('a').attr("href", Nhref);
                            //     $('a').addClass('text-decoration-none')
                            // }
                        }
                    }

                });
            } else {
                Swal.fire('সব গুলো তথ্য দিন')
            }

        })

        $('#suprime_court_case_search_form').on('submit', function(e) {
            e.preventDefault();
            swal.showLoading();
            $.ajax({
                url: '{{ route('supremecourt.cause.list.pull.data') }}',
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "division_id": $('#division_id').find('option:selected').val(),
                    "court_no": $('#court_no').find('option:selected').val(),
                    "case_date": $('#case_date').val(),
                },

                success: function(response) {
                    swal.close();
                    if (response.success == 'success') {
                        //$('#search_result_supream_court').empty();
                        $('#search_result_supream_court').html(response.html)
                        // var ahref = $('a').attr('href');
                        // if (ahref != undefined) {

                        //     var Nhref = 'https://supremecourt.gov.bd/web/' + ahref.replace('../', '');;
                        //     $('a').attr("href", Nhref);
                        //     $('a').addClass('text-decoration-none')
                        // }
                    }
                }

            });
        })
        
        window.onbeforeunload = function(e) {
            
            return 'Are you sure you want to leave?';
        };
    </script>
@endsection
