<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('media/logos/favicon.ico') }}" />
    <title>স্মার্ট কেস ম্যানেজমেন্ট সিস্টেম</title>
    <!-- Favicon -->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- Bootstrap stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* ---------------- Fonts ---------------- */
        @font-face {
            font-family: 'Nikosh';
            src: url('path/to/nikosh-font.woff2') format('woff2');
            font-display: swap;
        }

        /* ---------------- CSS Variables ---------------- */
        :root {
            --primary-color: #898989;
            --primary-hover: #f0f0f0;
            --footer-bg: rgb(168, 220, 203);
            --footer-text: #000;
            --transition-speed: 0.3s;
            --card-bg: #F3F3F3;
            --card-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            --card-radius: 8px;
            --section-gap: 1.5rem;
            --notice-icon-size: 52px;
            --navbar-height: 80px; /* Increased navbar height */
        }

        /* ---------------- Base Styles ---------------- */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            scroll-padding-top: 80px;
            font-family: 'Nikosh', sans-serif;
            line-height: 1.6;
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: var(--navbar-height);
        }

        /* ---------------- Taller Navbar ---------------- */
        .navbar {
            background-color: #f8f9fa;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            height: var(--navbar-height);
            display: flex;
            align-items: center;
        }

        .navbar-brand {
            height: 100%;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            height: 50px; /* Adjusted logo size */
            width: auto;
            max-width: 250px;
        }

        /* ---------------- Main Content ---------------- */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem 0;
        }

        .main-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            justify-content: center;
            align-items: center;
            gap: var(--section-gap);
            padding: 2rem;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .card-container {
            display: flex;
            justify-content: center;
        }

        /* ---------------- Cards ---------------- */
        .card {
            background-color: var(--card-bg);
            border-radius: var(--card-radius);
            box-shadow: var(--card-shadow);
            width: 100%;
            min-height: 320px;
            padding: 1.5rem;
            position: relative;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        /* Info Card */
        .info-card {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .info-card p {
            font-size: 1.25rem;
            margin: 0;
            color: #333;
            padding: 1rem;
        }

        /* Notice Board Card */
        .notice {
            min-height: 320px;
        }

        .notice-icon {
            position: absolute;
            top: 0;
            left: 0;
            width: var(--notice-icon-size);
            height: var(--notice-icon-size);
            z-index: 1;
        }

        .notice h5 {
            padding: 1.5rem 0;
            margin: 0;
            font-weight: bold;
            position: relative;
            z-index: 2;
        }

        .notice ul {
            list-style-type: none;
            padding: 0;
            margin: 1rem 0;
            height: 180px;
            overflow-y: auto;
        }

        .notice li {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.75rem;
            padding-left: 1rem;
            text-indent: -1rem;
        }

        .notice li span {
            color: green;
            margin-right: 0.5rem;
        }

        .notice a {
            text-decoration: none;
            color: #333;
            transition: color 0.2s ease;
        }

        .notice a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .no-notice {
            margin: 3rem 0 0 3rem;
            color: #666;
        }

        /* Login Card */
        .login-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .login-title {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .login-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding: 0 1.25rem;
        }

        .login-btn {
            width: 100%;
            padding: 0.75rem;
            font-weight: bold;
        }

        /* ---------------- Buttons ---------------- */
        .all-button {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 2px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            transition: all var(--transition-speed) ease;
            position: absolute;
            bottom: 15px;
            right: 15px;
        }

        .all-button:hover {
            background-color: var(--primary-hover);
            color: #000;
            transform: translateY(-1px);
            text-decoration: none;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        /* ---------------- Footer ---------------- */
        footer {
            background-color: var(--footer-bg);
            color: var(--footer-text);
            padding: 0.75rem 1rem;
            text-align: center;
            width: 100%;
            margin-top: auto;
            flex-shrink: 0;
        }

        .footer-content {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0.5rem;
            gap: 3rem;
        }

        .footer-widget {
            flex: 0 1 auto;
            margin: 0;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
        }

        .footer-widget-heading {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .footer-widget img {
            max-height: 40px;
            width: auto;
            object-fit: contain;
        }

        /* ---------------- Responsive Adjustments ---------------- */
        @media (max-width: 1200px) {
            .main-section {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                max-width: 900px;
            }
        }

        @media (max-width: 992px) {
            :root {
                --navbar-height: 70px;
            }
            .main-section {
                gap: 1.5rem;
                max-width: 800px;
            }
        }

        @media (max-width: 768px) {
            :root {
                --navbar-height: 65px;
            }
            .navbar-brand img {
                height: 40px;
            }
            .main-section {
                grid-template-columns: 1fr;
                padding: 1.5rem;
                max-width: 600px;
            }

            .card {
                max-width: 500px;
                margin: 0 auto;
            }

            .notice ul {
                height: auto;
                max-height: 180px;
            }

            .notice li {
                white-space: normal;
            }

            .footer-content {
                gap: 2rem;
            }

            .footer-widget {
                padding: 0.4rem;
            }
        }

        @media (max-width: 576px) {
            :root {
                --navbar-height: 60px;
            }
            .navbar-brand img {
                height: 35px;
                width: 180px;
            }

            .main-section {
                padding: 1rem;
                max-width: 100%;
            }

            .card {
                min-height: 280px;
            }

            .info-card p {
                font-size: 1.1rem;
            }

            .login-title {
                font-size: 1.3rem;
            }

            .footer-content {
                gap: 1.5rem;
                flex-direction: row;
            }

            .footer-widget {
                padding: 0.3rem;
            }

            .footer-widget img {
                max-height: 35px;
            }
        }

        @media (max-width: 400px) {
            .main-section {
                padding: 0.5rem;
            }

            .card {
                padding: 1rem;
            }

            .info-card p {
                font-size: 1rem;
                padding: 0.5rem;
            }

            .login-title {
                font-size: 1.2rem;
                margin-bottom: 1rem;
            }

            .login-buttons {
                padding: 0;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var link = document.querySelector("link[rel~='icon']");
            if (!link) {
                link = document.createElement('link');
                link.rel = 'icon';
                document.getElementsByTagName('head')[0].appendChild(link);
            }
            link.href = 'images/govlogo.png';
        });
    </script>
</head>

<body>
    <header>
        <!-- ---------- Header Start ---------- -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="images/logo.png" alt="Logo" class="d-inline-block align-text-top p-0 m-0">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
        <!-- ---------- Header End ---------- -->
    </header>

    <main class="main-content">
        <div class="main-section">
            <div class="card-container">
                <div class="card shadow p-3 rounded info-card">
                    <p class="card-title">উচ্চ আদালতের সরকারি স্বার্থ সংশ্লিষ্ট মামলা পরিচালনা সম্পর্কিত কার্যক্রম ব্যবস্থাপনা সিস্টেম</p>
                </div>
            </div>

            <div class="card-container">
                <div class="card shadow p-3 rounded notice">
                    <img src="{{ asset('uploads/IconeSCMS/bg_notice_board.png') }}" alt="Notice Board Icon" class="notice-icon">
                    <?php
                    $latestNotices = \App\Models\Notice::latest()->take(5)->get();
                    ?>
                    @if ($latestNotices->isNotEmpty())
                        <h5 class="notice-heading">নোটিশ বোর্ড</h5>
                        <ul class="notice-list">
                            @foreach ($latestNotices as $key => $notice)
                                <li class="notice-item">
                                    <span class="notice-bullet">&#9658;</span>
                                    <a href="{{ Storage::url($notice->notice_pdf) }}" target="_blank" class="notice-link">{{ $notice->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="no-notice">কোন নোটিশ পাওয়া যায়নি</p>
                    @endif
                    <a href="{{ route('notices.details') }}" class="all-button">সকল</a>
                </div>
            </div>

            <div class="card-container">
                <div class="card shadow p-3 rounded login-card">
                    <h3 class="login-title">স্মার্ট কেস ম্যানেজমেন্ট সিস্টেম</h3>
                    <div class="login-buttons">
                        <a href="{{ route('doptor.login') }}" class="btn btn-success login-btn">সাধারণ লগইন</a>
                        <a href="{{ route('sso.login') }}" class="btn btn-success login-btn">নথি লগইন</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!------- Start of footer -------->
    <footer class="footer-section">
        <div class="container">
            <div class="footer-content">
                <div class="footer-widget">
                    <div class="footer-widget-heading">
                        <h5>পরিকল্পনা ও বাস্তবায়নে</h5>
                    </div>
                    <a href="https://cabinet.gov.bd/" target="_blank">
                        <img src="images/logo1.png" alt=""
                            style="width: 80px; height: 40px; margin-left: 5px; margin-bottom: 5px">
                    </a>
                </div>
                <div class="footer-widget">
                    <div class="footer-widget-heading">
                        <h5>কারিগরি সহায়তায়</h5>
                    </div>
                    <a href="http://mysoftheaven.com" target="_blank">
                        <img src="images/mysoftheaven-logo.png" alt="" style="width: 120px; height: auto;">
                    </a>
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