@extends('layouts.cabinet.cab_default')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

@section('content')
    <style type="text/css">
        #appRowDiv td {
            padding: 5px;
            border-color: #ccc;
        }

        #appRowDiv th {
            padding: 5px;
            text-align: center;
            border-color: #ccc;
            color: black;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            width: 250px;
        }

        .select2-container .select2-selection--single {
            box-sizing: border-box;
            height: 41px;
            font-size: 1.2rem
        }

        .special-character {
            top: 10px;
        }
    </style>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <form method="POST" id="AdalatEntry" action="{{ route('cabinet.appeal-maintain.store') }}">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
                        </div>

                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <li class="alert alert-danger">{{ $error }}</li>
                            @endforeach
                        @endif
                        <div class="card-body card-block row">

                            <div class="form-group col-lg-8">
                                <label for="name" class=" form-control-label">আপিল আদালতের নাম <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" placeholder="আপিল আদালতের নাম লিখুন"
                                    class="form-control form-control-sm">
                                <span style="color: red">
                                    {{ $errors->first('name') }}
                                </span>
                            </div>

                            <div class="col-lg-2">
                                <label>স্ট্যাটাস <span class="text-danger">*</span></label>
                                <div class="radio-inline">
                                    <label class="radio">
                                        <input type="radio" name="status" value="1" checked="checke" />
                                        <span></span>এনাবল</label>
                                    <label class="radio">
                                        <input type="radio" name="status" value="0" />
                                        <span></span>ডিজেবল</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4">
                                <button type="submit" class="btn btn-success mr-2" onclick="return submitForm()">সংরক্ষণ করুন</button>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function submitForm() {
        Swal.fire({
            title: 'আপনি কি সংরক্ষণ করতে চান?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'হ্যা',
            cancelButtonText: 'না'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('AdalatEntry').submit();
            }
        });
        return false;
    }
</script>


@section('scripts')

@endSection
