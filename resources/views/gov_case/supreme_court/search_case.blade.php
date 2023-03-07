@extends('layouts.cabinet.cab_default')

@section('content')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <div class="card card-custom">
        <div class="card-title">
            <h3 class="card-title h2 font-weight-bolder text-center mt-2">{{ $page_title }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <form action="" id="suprime_court_case_search_form">
                        <div class="form-group row">
                            <div class="col-md-4 text-center"><label for="exampleFormControlInput1"
                                    class="form-label"><b>Division</b></label>
                                <select class="form-control form-select required1" name="division_id" id="division_id" required>
                                    <option value="">- Select -</option>
                                    <option value="1">Appellate Division</option>
                                    <option value="2">High Court Division</option>
                                </select>
                            </div>
                            <div class="col-md-4 text-center"><label for="exampleFormControlInput1"
                                    class="form-label"><b>Case
                                        Category</b></label>
                                <select class="form-control form-select required1" name="nature_id" id="nature_id" required>
                                    <option value="">- Select -</option>
                                </select>
                            </div>
                            <div class="col-md-4 text-center"><label for="exampleFormControlInput1"
                                    class="form-label"><b>Case
                                        Type</b></label>
                                <select class="form-control form-select required1" name="case_type_id" id="case_type_id" required>
                                    <option value="">- Select -</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-4"><label for="exampleFormControlInput1" class="form-label"><b>Case
                                        Number</b></label>
                                <input type="text" class="form-control required1" name="case_number" id="case_number"
                                    placeholder="" required>
                            </div>
                            <div class="col-md-4"><label for="exampleFormControlInput1"
                                    class="form-label"><b>Year</b></label>
                                <input type="text" class="form-control required1" id="year" name="year"
                                    placeholder="" value="{{ date('Y') }}">
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </form>
                    <div class="col-md-3"><button class="btn btn-primary mt-4"
                            id="suprime_court_case_search_button">Search</button></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="search_result_supream_court">

                </div>
            </div>
        </div>

    </div>

    <style>
        #select2-case_type_id-container
        {
            line-height: 12px !important;
        }
        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            height: 36px;
            user-select: none;
            -webkit-user-select: none;
            /* font-size: 1.2rem; */
        }
    </style>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        //$('#division_id').select2();
        $('#division_id').on('change', function() {
            var division_id = $(this).find('option:selected').val();
            var options_12 = [{
                    value: 8,
                    text: 'Civil Cases'
                },
                {
                    value: 10,
                    text: 'Contempt Cases'
                },
                {
                    value: 9,
                    text: 'Jail Cases'
                },
            ]

            var options_21 = [{
                    value: 3,
                    text: 'Civil Appeal'
                },
                {
                    value: 4,
                    text: 'Civil Revision'
                },
                {
                    value: 1,
                    text: 'Original Cases'
                },
                {
                    value: 2,
                    text: 'Writ Cases'
                },
            ]


            if (division_id == 1) {
                $('#nature_id').empty();
                var option_html = '<option value="">- Select -</option>';
                $.each(options_12, function(i, item) {
                    option_html += '<option value="' + item.value + '">' + item.text + '</option>';
                });
            } else if (division_id == 2) {
                $('#nature_id').empty();
                var option_html = '<option value="">- Select -</option>';
                $.each(options_21, function(i, item) {
                    option_html += '<option value="' + item.value + '">' + item.text + '</option>';
                });
            } else {
                $('#nature_id').empty();
                var option_html = '<option value="">- Select -</option>';
            }
            $('#nature_id').append(option_html);
            //$('#nature_id').select2();
        })

        $('#nature_id').on('change', function() {
            var nature_id = $(this).find('option:selected').val();

            if (nature_id == 8) {
                $('#case_type_id').empty();
                var options_8 =
                    '<option value="">- Select -</option><option value="35">Civil Appeal</option><option value="36">Civil Petition</option><option value="37">Civil Misc Petition</option><option value="38">Civil Review Petition</option>';
                $('#case_type_id').append(options_8);
            } else if (nature_id == 10) {
                $('#case_type_id').empty();
                var options_10 =
                    '<option value="">- Select -</option><option value="45">Contempt Petition(A)</option>';
                $('#case_type_id').append(options_10);
            } else if (nature_id == 9) {
                $('#case_type_id').empty();
                var options_9 =
                    '<option value="">- Select -</option><option value="43">Jail Petition</option><option value="44">Jail Appeal(A)</option>';
                $('#case_type_id').append(options_9);
            } else if (nature_id == 3) {
                $('#case_type_id').empty();
                var options_3 =
                    ' <option value="">- Select -</option><option value="47">Election Petition</option><option value="1">First Appeal</option><option value="2">First Misc Appeal</option><option value="3">Cross Objection</option><option value="4">Transfer Appeal</option><option value="32">Second Appeal</option><option value="33">Second Misc Appeal</option><option value="48">First Appeal Tender</option><option value="49">First Misc Appeal Tender</option><option value="93">VC Civil Rule</option><option value="94">In re : VC F M A</option><option value="96">In re : VC F A</option><option value="102">VC First Appeal</option>';
                $('#case_type_id').append(options_3);
            } else if (nature_id == 4) {
                $('#case_type_id').empty();
                var options_4 =
                    '<option value="">- Select -</option><option value="5">Civil Rule</option><option value="6">Civil Revision</option><option value="7">Civil Misc</option><option value="8">Civil Order</option><option value="58">Contempt</option><option value="81">Civil Review</option><option value="89">Violation Misc</option><option value="100">In-re VC Voilation Misc</option><option value="83">Violation Case</option><option value="88">Violation Misc. Rule</option><option value="101">VC Contempt Petition (H)</option><option value="98">In re : VC Civil Revision</option><option value="99">VC Civil Revision</option>';
                $('#case_type_id').append(options_4);
            } else if (nature_id == 1) {
                $('#case_type_id').empty();
                var options_1 =
                    ' <option value="">- Select -</option><option value="66">Letter of Administration Suit</option><option value="70">Violation Misc</option><option value="67">Review Petition</option><option value="73">Suo-Muto Rule (Original)</option><option value="34">Execution Case</option><option value="22">Company Matter</option><option value="23">Contempt Matter</option><option value="24">Customs Appeal</option><option value="25">Divorce Suit</option><option value="26">Income Tax Reference</option><option value="27">Merchant Shipping</option><option value="28">Trademark Appeal</option><option value="29">Trademark Application</option><option value="30">VAT Appeal</option><option value="18">Admiralty Suit</option><option value="19">Arbitration Appeal</option><option value="20">Arbitration Application</option><option value="21">Cancellation and Design</option><option value="69">Contempt Petition</option><option value="64">Contempt Rule</option><option value="65">Summary Suit</option><option value="79">Arbitration Rule</option><option value="78">Bank Company Application</option><option value="80">Family Matter</option><option value="87">VAT Revision</option><option value="105">Financial Institution</option>';
                $('#case_type_id').append(options_1);
            } else if (nature_id == 2) {
                $('#case_type_id').empty();
                var options_2 =
                    '<option value="">- Select -</option><option value="61">In re : Writ Petition</option> <option value="13">Writ Petition</option><option value="14">Contempt Petition(H)</option><option value="15">Review Petition</option><option value="16">Suo-Muto Rule</option><option value="17">Transfer Petiion</option><option value="51">Reference Application</option><option value="59">I.T.R.</option><option value="60">Rule</option><option value="90">In re : VC Writ Petition</option><option value="86">Copy Right Appeal</option><option value="92">VC Writ Petition</option>';
                $('#case_type_id').append(options_2);
            } else {
                $('#case_type_id').empty();
                var option_html = '<option value="">- Select -</option>';
            }
            $('#case_type_id').append(option_html);
            $('#case_type_id').select2();
        })



        $('#suprime_court_case_search_button').on('click', function(e) {
            e.preventDefault();

            let passport = true;
            $('.required1').each(function() {
                if ($(this).val() == '') {
                    passport = false;
                }
            });
            //alert(passport);
            if (passport) {
                swal.showLoading();
                $.ajax({
                    url: '{{ route('supremecourt.case.search.post.value') }}',
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "division_id": $('#division_id').find('option:selected').val(),
                        "nature_id": $('#nature_id').find('option:selected').val(),
                        "case_type_id": $('#case_type_id').find('option:selected').val(),
                        "case_number": $('#case_number').val(),
                        "year": $('#year').val(),
                    },

                    success: function(response) {
                        swal.close();
                        if (response.success == 'success') {
                            //$('#search_result_supream_court').empty();
                            $('#search_result_supream_court').html(response.html)
                            var ahref = $('#search_result_supream_court a').attr('href');
                            if (ahref != undefined) {

                                var Nhref = 'https://supremecourt.gov.bd/web/' + ahref.replace('../',
                                    '');;
                                $('#search_result_supream_court a').attr("href", Nhref);
                                $('#search_result_supream_court a').addClass('text-decoration-none');
                            }
                        }
                    }

                });
            } else {
                Swal.fire('সব গুলো তথ্য দিন')
            }

        })

        // $('#suprime_court_case_search_form').on('submit', function(e) {
        //     e.preventDefault();
        //     swal.showLoading();
        //     $.ajax({
        //         url: '{{ route('supremecourt.case.search.post.value') }}',
        //         method: 'post',
        //         data: {
        //             "_token": "{{ csrf_token() }}",
        //             "division_id": $('#division_id').find('option:selected').val(),
        //             "nature_id": $('#nature_id').find('option:selected').val(),
        //             "case_type_id": $('#case_type_id').find('option:selected').val(),
        //             "case_number": $('#case_number').val(),
        //             "year": $('#year').val(),
        //         },

        //         success: function(response) {
        //             swal.close();
        //             if (response.success == 'success') {
        //                 //$('#search_result_supream_court').empty();
        //                 $('#search_result_supream_court').html(response.html)
        //                 var ahref = $('#search_result_supream_court a').attr('href');
        //                 if (ahref != undefined) {

        //                     var Nhref = 'https://supremecourt.gov.bd/web/' + ahref.replace('../', '');;
        //                     $('#search_result_supream_court a').attr("href", Nhref);
        //                     $('#search_result_supream_court a').addClass('text-decoration-none');
        //                 }
        //             }
        //         }

        //     });
        // })
    </script>
@endsection
