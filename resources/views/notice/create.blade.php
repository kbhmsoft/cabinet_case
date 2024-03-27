@extends('layouts.cabinet.cab_default')

@section('title', 'নোটিশ তৈরি করুন')

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <style>
        @media (max-width: 767px) {
            .content-header h1 {
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body mb-5">
            <form action="{{ route('notices.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="title">বিষয় <span class="text-danger"> * </span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        id="title" placeholder="বিষয় লিখুন" value="{{ old('title') }}">
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="notice_pdf">পিডিএফ ফাইল <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" name="notice_pdf" accept="application/pdf"
                            class="custom-file-input @error('notice_pdf') is-invalid @enderror" id="notice_pdf">
                        <label class="custom-file-label" for="notice_pdf">
                            @if (isset($data['notice_pdf']) && $data['notice_pdf'])
                                {{ $data['notice_pdf'] }}
                            @else
                                ফাইল নির্বাচন করুন
                            @endif
                        </label>
                    </div>
                    @error('notice_pdf')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date">তারিখ <span class="text-danger"> * </span></label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" id="date" placeholder="তারিখ লিখুন" value="{{ old('date') }}">
                    @error('date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                

                <div class="form-group">
                    <label for="status">স্ট্যাটাস</label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                        <option value="1" {{ old('status') === 1 ? 'selected' : '' }}>সক্রিয়</option>
                        <option value="0" {{ old('status') === 0 ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-5">
                    <a href="{{ route('notices.index') }}" class="btn btn-danger">বাতিল করুন</a>
                    <button class="btn btn-primary" type="submit">তৈরি করুন</button>
                </div>

            </form>
        </div>
    </div>
@endsection
