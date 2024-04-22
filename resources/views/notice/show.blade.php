@extends('layouts.app')
@section('title', 'নোটিশ তালিকা')

@section('head')
<link rel="icon" href="favicon.ico" type="image/x-icon">
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var link = document.querySelector("link[rel~='icon']");
        if (!link) {
            link = document.createElement('link');
            link.rel = 'icon';
            document.getElementsByTagName('head')[0].appendChild(link);
        }
        link.href = 'images/govlogo.png';
    })
</script>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        @media (max-width: 767px) {
            .content-header h1 {
                text-align: center;
            }
        }
    </style>

@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <header>
                    <!-- ----------header Start---------- -->
                    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow">
                        <div class="container">
                            <a class="navbar-brand" href="#">
                                <img src="{{ asset('images/logo.png') }}" alt width="250" height="50"
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
                <div class="card card-custom gutter-b example example-compact mt-5">
                    <div class="font-weight-bolde p-0 mb-3 border-0 text-dark text-center mt-3"
                        style="font-family: Nikosh; font-size: 2rem;">নোটিশ তালিকা</div>

                    <div class="card-body">
                        <table class="table table-bordered" style="font-family: Nikosh;">
                            <thead>
                                <tr>
                                    <th style="border-color: #000000; font-size: 1.2rem;">ক্রমিক</th>
                                    <th class="text-center" style="border-color: #000000; font-size: 1.2rem;">শিরোনাম</th>
                                    <th style="border-color: #000000; font-size: 1.2rem;">প্রকাশের তারিখ</th>
                                    <th style="border-color: #000000; font-size: 1.2rem; text-align: center">পিডিএফ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    function toBangla($number)
                                    {
                                        $bengali_numerals = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
                                        $result = '';
                                        foreach (str_split((string) $number) as $digit) {
                                            $result .= $bengali_numerals[$digit];
                                        }
                                        return $result;
                                    }
                                @endphp
                                @foreach ($notices as $index => $notice)
                                    <tr>
                                        <td style="border-color: #D8D8D8; font-size: 1rem; text-align: center">
                                            {{ toBangla($index + 1) }}</td>

                                        <td style="border-color: #D8D8D8; font-size: 1.1rem">{{ $notice->title }}</td>

                                        <td style="border-color: #D8D8D8; font-size: 1.1rem">
                                            {{ convertToBanglaDate($notice->date) }}</td>

                                        <td style="border-color: #D8D8D8;"><a href="{{ Storage::url($notice->notice_pdf) }}"
                                                target="_blank"><img src="{{ asset('uploads/IconeSCMS/pdf.png') }}"
                                                    alt="পিডিএফ দেখুন" width="width" height="height"
                                                    class="d-inline-block align-text-top p-0 m-0 ml-4"></a></td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $notices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
