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
    <!-- Include Kalpurush font CSS -->
    {{-- <link rel="stylesheet" href="path/to/kalpurush-font.css"> --}}
    <style>
        /* body {
            font-family: 'Kalpurush', sans-serif;
            padding-top: 70px;
        } */
        @font-face {
            font-family: 'Nikosh';
            /* src: url('path/to/Nikosh.ttf'); */
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
        {{-- <div class="left-section m-5">
            <!-- Add your image, title, and content here -->
            <img src="images/book.png" alt="Your Image" class="img-fluid" width="300">
            <h2 class="m-3">স্মার্ট কেস ম্যানেজমেন্ট সিস্টেম</h2>
        </div> --}}
        <div class="right-section">
            <div class="card shadow p-3 border-dark rounded" style="width: 200px; height: 200px;">
                <p style="font-size: 20px">উচ্চ আদালতের সরকারি স্বার্থ সংশ্লিষ্ট মামলা পরিচালনা সম্পর্কিত কার্যক্রম
                    ব্যবস্থাপনা সিস্টেম’ টেক্সট
                    থাকবে</p>
            </div>
        </div>
        <div class="right-section">
            <div class="card shadow p-3 rounded border-dark" style="width: 400px; height: 200px;">
                <p>নোটিশ বোর্ড ________________________________</p>
                <p>মুক্ত সংস্থার সময়: {{ date('d-M-Y h:i:s A') }}</p>

                @if (isset($data['title']) && isset($data['notice_pdf']))
                    <p>শিরোনাম: {{ $data['title'] }}</p>
                    <p><a href="{{ $data['notice_pdf'] }}" target="_blank">পিডিএফ দেখুন</a></p>
                @endif
            </div>
        </div>

        {{-- <div class="right-section">
            <div class="card shadow p-3 rounded border-dark" style="width: 400px; height: 200px;">
                <h5>{{ $notice->title }}</h5>
                @if ($notice->notice_pdf)
                    <embed src="{{ Storage::url($notice->notice_pdf) }}" type="application/pdf" width="100%" height="100%">
                @else
                    <p>No PDF available</p>
                @endif
            </div>
        </div> --}}



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
