<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <!-- Bootstrap stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>

        @font-face {
            font-family: 'Nikosh';
        }

        body {
            font-family: 'Nikosh', sans-serif;
            padding-top: 70px;
        }

        footer {
            background-color: rgb(168, 220, 203);
            color: rgb(0, 0, 0);
            padding: 5%;
            text-align: center;
            position: relative;
            margin-top: 20px;
        }

        footer p {
            text-align: center;
        }

        .footer-text-color {
            color: green;
        }

        .main-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .left-section {
            flex: 1;
            padding-left: 5rem;
        }

        .right-section {
            padding-right: 5rem;
        }

        .footer-widget ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-widget ul li a {
            text-decoration: none;
        }


        @media (max-width: 768px) {
            .main-section {
                flex-direction: column;
            }

            .left-section,
            .right-section {
                flex: 1;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <header>
        <!-- ----------header Start---------- -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="images/logo.png" alt width="250" height="50"
                        class="d-inline-block align-text-top p-0 m-0">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
    </header>


    <div class="main-section">
        <div class="right-section">
            <div class="card shadow p-3 border-dark rounded" style="width: 200px; height: 200px;">
                <p style="font-size: 20px">উচ্চ আদালতের সরকারি স্বার্থ সংশ্লিষ্ট মামলা পরিচালনা সম্পর্কিত কার্যক্রম
                    ব্যবস্থাপনা সিস্টেম</p>
            </div>
        </div>

        <!-- Inside the "নোটিশ বোর্ড" div -->
        <div class="right-section">
            <div class="card shadow p-3 rounded border-dark"
                style="width: 400px; height: auto; background-color: #F3F3F3">
                <img src="{{ asset('uploads/IconeSCMS/bg_notice_board.png') }}" alt="Image"
                    style="position: absolute; top: 0; left: 0; width: 52px; height: 54px; z-index: 1;">
                <?php
                $latestNotices = \App\Models\Notice::latest()->take(5)->get();
                ?>
                @if ($latestNotices->isNotEmpty())
                    <h5 class="font-weight-bolder" style="margin-left: 3rem">নোটিশ বোর্ড </h5>
                    <ul style="list-style-type: none; padding-left: 0; margin-top: 10px">
                        @foreach ($latestNotices as $key => $notice)
                            <li style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <span style="color: green;">&#9658;</span>
                                <a href="{{ Storage::url($notice->notice_pdf) }}" target="_blank"
                                    style="text-decoration: none;">{{ $notice->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No notices available</p>
                @endif
            </div>
        </div>

        <div class="right-section">
            <div class="card shadow p-3 bg-white rounded" style="width: 300px; height: 300px; margin-top: 20px;">
                <small class="text-muted d-block mx-auto mb-3 mt-3" style="font-size: 20px">স্মার্ট কেস ম্যানেজমেন্ট
                    সিস্টেম</small>
                <a href="{{ route('doptor.login') }}" class="btn btn-success d-block mx-auto m-3">সাধারণ লগইন</a>
                <a href="{{ route('sso.login') }}" class="btn btn-success d-block mx-auto m-3">নথি লগইন</a>
            </div>
        </div>
    </div>

    <!------- Start of footer -------->
    <footer class="footer-section ">
        <div class="container">
            <div class="footer-content pt-5 pb-5">
                <div class="row justify-content-center">
                    <div class="col-xl-3 col-lg-3 mb-50">
                        <div class="footer-widget">
                            <div class="footer-widget-heading">
                                <h3 style="float: left;">পরিকল্পনা ও বাস্তবায়নে</h3>
                            </div>
                            <a href="https://minland.gov.bd/" target="_blank">
                                <img style="width: 100px; height: 50px; float: middle; margin-right: 28px;"
                                    src="images/logo1.png" alt="">
                            </a>

                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 mb-50">
                        <div class="footer-widget">
                            <div class="footer-widget-heading">
                                <h3 style="float: right;">কারিগরি সহায়তায়</h3>
                            </div>
                            <a href="http://mysoftheaven.com" target="_blank"><img
                                    style="width: 120px;height: auto;float: right;"
                                    src="https://ldtax.gov.bd/assets/images/auto.png" alt=""></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </footer>
    <!------- End footer ------->


    <!-- Bootstrap script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
