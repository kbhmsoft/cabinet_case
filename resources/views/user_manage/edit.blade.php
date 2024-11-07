>@extends('layouts.cabinet.cab_default')


@section('content')

    <!--begin::Card-->
    <div class="row">
        <div class="card card-custom col-12">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
                </div>
                @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 6)
                    <div class="card-toolbar">
                        <a href="{{ url('user-management') }}" class="btn btn-sm btn-primary font-weight-bolder">
                            <i class="la la-list"></i> ব্যবহারকারীর তালিকা
                        </a>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <form action="{{ route('user-management.update', $userManagement->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <fieldset>
                        <legend>ব্যবহারকারীর তথ্য</legend>
                        <div class=" col-12 row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="name" class=" form-control-label">পুরো নাম <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" placeholder="পুরো নাম লিখুন"
                                        class="form-control form-control-sm"value="{{ $userManagement->name }}">
                                    <span style="color: red">
                                        {{ $errors->first('name') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="username" class=" form-control-label">ইউজারনেম <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="username" name="username"
                                        placeholder="ব্যবহারকারীর নাম লিখুন" class="form-control form-control-sm"
                                        value="{{ $userManagement->username }}" readonly="readonly">
                                    <span style="color: red">
                                        {{ $errors->first('username') }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="mobile_no" class=" form-control-label">মোবাইল নাম্বার </label>
                                    <input type="text" name="mobile_no" id="mobile_no" placeholder="মোবাইল নাম্বার লিখুন"
                                        class="form-control form-control-sm" value="{{ $userManagement->mobile_no }}">
                                </div>
                            </div>
                        </div>
                        <div class=" col-12 row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>ইমেইল এড্রেসঃ</label>
                                    <input type="text" name="email" class="form-control" placeholder=""
                                        value="{{ $userManagement->email }}" />
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="role_id" class=" form-control-label">ইউজার রোল <span
                                            class="text-danger">*</span></label>
                                    <select name="role_id" id="role_id" class="form-control-sm form-control">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        @foreach ($roles as $value)
                                            <option value="{{ $value->id }}"
                                                {{ $value->id == $userManagement->role_id ? 'selected' : '' }}>
                                                {{ $value->name_bn }} </option>
                                        @endforeach
                                    </select>
                                    <span style="color: red">
                                        {{ $errors->first('role_id') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="office_id" class=" form-control-label">অফিস <span
                                            class="text-danger">*</span></label>
                                    <select name="office_id" id="office_id" class="form-control-sm form-control">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        @foreach ($offices as $value)
                                            <option
                                                value="{{ $value->id }}"{{ $value->id == $userManagement->office_id ? 'selected' : '' }}>
                                                {{ $value->office_name_bn }}, {{ $value->upazila_name_bn }},
                                                {{ $value->district_name_bn }} </option>
                                        @endforeach
                                    </select>
                                    <span style="color: red">
                                        {{ $errors->first('office_id') }}
                                    </span>
                                </div>
                            </div>


                            <div class="form-group ">
                                <label for="password" class="col-md-4 col-form-label text-md-left">নতুন পাসওয়ার্ড</label>
                                <div class="col-md-4">
                                    <input id="new_password" type="password" class="form-control"
                                        placeholder="নতুন পাসওয়ার্ড" name="new_password" autocomplete="current-password"
                                        onkeyup="CheckPassword(this)">
                                    <span toggle="#password" class="fa fa-fw fa-eye field_icon toggle-password"
                                        onclick="myFunctionNew()"></span>
                                </div>
                                <div id="passwordValidation" style="color:red">
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="password" class="col-md-4 col-form-label text-md-left">নতুন কনফার্ম
                                    পাসওয়ার্ড</label>

                                <div class="col-md-4">
                                    <input id="new_confirm_password" type="password" class="form-control"
                                        placeholder="নতুন কনফার্ম পাসওয়ার্ড" name="new_confirm_password"
                                        autocomplete="current-password">

                                    <span toggle="#password" class="fa fa-fw fa-eye field_icon toggle-password"
                                        onclick="myFunctionConfirm()"></span>
                                </div>
                            </div>


                        </div>

                        <div class="col-lg-12 mb-5">
                            <div class="form-group row">
                                <div class="col-lg-6 mb-5">
                                    <label class=" form-control-label">স্বাক্ষরের স্ক্যান কপি সংযুক্তি <span
                                            class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <label></label>
                                        <div></div>
                                        <div class="custom-file">
                                            <input type="file" name="signature" class="custom-file-input"
                                                id="customFile" />
                                            <label class="custom-file-label" for="customFile">ফাইল নির্বাচন করুন</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-5">
                                    <label class=" form-control-label">প্রোফাইল ইমেজ সংযুক্তি <span
                                            class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <label></label>
                                        <div></div>
                                        <div class="custom-file">
                                            <input type="file" name="pro_pic" class="custom-file-input"
                                                id="customFile" />
                                            <label class="custom-file-label" for="customFile">ফাইল নির্বাচন করুন</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold mr-2">সংরক্ষণ করুন</button>
                            </div>
                        </div>
                    </div>

            </form>
        </div>
    </div>
    <!--end::Card-->

@endsection

{{-- Includable CSS Related Page --}}
@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors Styles-->
@endsection

{{-- Scripts Section Related Page --}}
@section('scripts')
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
    <!--end::Page Scripts-->
@endsection

<script>
        function CheckPassword(inputtxt) {
        var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{7,20}$/;
        var message = [];

        if (!inputtxt.value.match(passw)) {
            if (!inputtxt.value.match(/^(?=.*\d)/)) {
                message.push("কমপক্ষে একটি সংখ্যাসূচক থাকতে হবে");
            }
            if (!inputtxt.value.match(/^(?=.*[a-z])/)) {
                message.push("কমপক্ষে একটি ছোট হাতের অক্ষর থাকতে হবে");
            }
            if (!inputtxt.value.match(/^(?=.*[A-Z])/)) {
                message.push("কমপক্ষে একটি বড় হাতের অক্ষর থাকতে হবে");
            }
            if (!inputtxt.value.match(/^(?=.*[^a-zA-Z0-9])/)) {
                message.push("কমপক্ষে একটি বিশেষ ক্যারেক্টার থাকতে হবে");
            }
            if (inputtxt.value.length < 8 || inputtxt.value.length > 20) {
                message.push("পাসওয়ার্ডের দৈর্ঘ্য 8 থেকে 20 অক্ষরের মধ্যে হওয়া উচিত");
            }

            $("#passwordValidation").html(message.join(', <br>'));
            return false;
        } else {
            $("#passwordValidation").html("");
            return true;
        }
    }

    function myFunctionNew() {
        var x = document.getElementById("new_password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

    function myFunctionConfirm() {
        var x = document.getElementById("new_confirm_password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
