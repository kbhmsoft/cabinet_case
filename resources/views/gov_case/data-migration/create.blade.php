@extends('layouts.cabinet.cab_default')

@section('title', 'ডাটা মাইগ্রেশন')

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
            <form id="dataMigrationForm" action="{{ route('data-migration.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="data_migration_file">ফাইল <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" name="data_migration_file"
                            class="custom-file-input @error('data_migration_file') is-invalid @enderror"
                            id="data_migration_file">
                        <label class="custom-file-label" for="data_migration_file">
                            @if (isset($data['data_migration_file']) && $data['data_migration_file'])
                                {{ $data['data_migration_file'] }}
                            @else
                                ফাইল নির্বাচন করুন
                            @endif
                        </label>
                    </div>
                    @error('data_migration_file')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-5">
                    <a href="{{ route('data-migration.create') }}" class="btn btn-danger">বাতিল করুন</a>
                    <button class="btn btn-primary" type="submit">তৈরি করুন</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('dataMigrationForm').addEventListener('submit', function(e) {
        Swal.fire({
            html: `
                <div style="display: flex; flex-direction: column; align-items: center;">
                    <div class="clock-loader" style="width: 80px; height: 80px; position: relative;">
                        <div class="clock-face" style="width: 100%; height: 100%; border: 5px solid #007BFF; border-radius: 50%; position: relative;">
                            <div class="clock-hand" style="width: 40%; height: 3px; background-color: #007BFF; position: absolute; top: 50%; left: 50%; transform-origin: 0% 50%; transform: rotate(0deg); animation: tick 2s linear infinite;"></div>
                        </div>
                    </div>
                    <h2 style="margin-top: 20px; font-size: 1.5em;">মাইগ্রেশন চলছে...</h2>
                    <p>মাইগ্রেশন সম্পন্ন হওয়া পর্যন্ত অনুগ্রহ করে অপেক্ষা করুন।</p>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                popup: 'animated-popup',
            }
        });
    });

    // Add clock animation styles
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes tick {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .animated-popup {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    `;
    document.head.appendChild(style);
</script>


@endsection
