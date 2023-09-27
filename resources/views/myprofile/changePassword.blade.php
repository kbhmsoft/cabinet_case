@extends('layouts.cabinet.cab_default')
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
                        <label for="password" class="col-md-4 col-form-label text-md-left">বর্তমান পাসওয়ার্ড</label>

                        <div class="col-md-4">
                            <input id="password" type="password" class="form-control" placeholder="বর্তমান পাসওয়ার্ড"
                                name="current_password" autocomplete="current-password">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="password" class="col-md-4 col-form-label text-md-left">নতুন পাসওয়ার্ড</label>

                        <div class="col-md-4">
                            <input id="new_password" type="password" class="form-control" placeholder="নতুন পাসওয়ার্ড"
                                name="new_password" autocomplete="current-password" onkeyup="CheckPassword(this)">
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
    <!-- </div> -->
    <!-- </div> -->
@endsection



<script>
    function CheckPassword(inputtxt) {
        var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{7,15}$/;
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
            if (inputtxt.value.length < 8 || inputtxt.value.length > 15) {
                message.push("পাসওয়ার্ডের দৈর্ঘ্য 8 থেকে 20 অক্ষরের মধ্যে হওয়া উচিত");
            }

            $("#passwordValidation").html(message.join(', <br>'));
            return false;
        } else {
            $("#passwordValidation").html("");
            return true;
        }
    }
</script>
