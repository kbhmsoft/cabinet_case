@extends('layouts.cabinet.cab_default')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />

<style>
    .password-container {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
    }
</style>



@section('content')
    <!-- <div class="container"> -->
    <!-- <div class="row justify-content-center"> -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <div class="container">
                    <div class="row">
                        <div class="col-10">
                            <h3 class="card-title h2 font-weight-bolder">ইউজার পাসওয়ার্ড পরিবর্তন করুন</h3>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('update.password') }}">
                    @csrf

                    @foreach ($errors->all() as $error)
                        <p class="text-danger">{{ $error }}</p>
                    @endforeach

                    <div class="form-group ">
                        <label for="password password-container" class="col-md-4 col-form-label text-md-left">বর্তমান
                            পাসওয়ার্ড</label>

                        <div class="col-md-4">
                            <input id="password" type="password" class="form-control" placeholder="বর্তমান পাসওয়ার্ড"
                                name="current_password" autocomplete="current-password" />
                            <span toggle="#password" class="fa fa-fw fa-eye field_icon toggle-password"
                                onclick="myFunction()"></span>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="password" class="col-md-4 col-form-label text-md-left">নতুন পাসওয়ার্ড</label>
                        <div class="col-md-4">
                            <input id="new_password" type="password" class="form-control" placeholder="নতুন পাসওয়ার্ড"
                                name="new_password" autocomplete="current-password" onkeyup="CheckPassword(this)">
                            <span toggle="#password" class="fa fa-fw fa-eye field_icon toggle-password"
                                onclick="myFunctionNew()"></span>
                        </div>
                        <div id="passwordValidation" style="color:red">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="password" class="col-md-4 col-form-label text-md-left">নতুন কনফার্ম পাসওয়ার্ড</label>

                        <div class="col-md-4">
                            <input id="new_confirm_password" type="password" class="form-control"
                                placeholder="নতুন কনফার্ম পাসওয়ার্ড" name="new_confirm_password"
                                autocomplete="current-password">

                            <span toggle="#password" class="fa fa-fw fa-eye field_icon toggle-password"
                                onclick="myFunctionConfirm()"></span>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 mt-10 offset-md-1">
                            <button type="submit" class="btn btn-primary">
                                পাসওয়ার্ড হালনাগাদ
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

    function myFunction() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
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
