>@extends('layouts.cabinet.cab_default')

@section('title', 'নোটিশ সম্পাদনা করুন')
@section('content-header', 'নোটিশ সম্পাদনা করুন')

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

    <div class="card">
        <div class="card-body">

            <form action="{{ route('notices.update', $data) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">বিষয় <span class="text-danger"> * </span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        id="title" placeholder="বিষয় লিখুন" value="{{ old('title', $data->title) }}"">
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
                        <label class="custom-file-label" for="notice_pdf">ফাইল নির্বাচন করুন</label>
                    </div>
                    @error('notice_pdf')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror


                    @if (!empty($data['notice_pdf']))
                        <div class="mt-3 px-5">
                            <table width="100%" class="border-0 px-5" style="border:1px solid #dcd8d8;">
                                <tr id="deleteFileContainer_{{ $data['id'] }}">
                                    <td>
                                        <div class="form-group mb-2">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button class="btn bg-success-o-75" type="button">ফাইল:</button>
                                                </div>
                                                <input readonly type="text" class="form-control" value="পিডিএফ ফাইল" />
                                                <div class="input-group-append">
                                                    <a href="{{ asset('storage/' . $data['notice_pdf']) }}" target="_blank"
                                                        class="btn btn-sm btn-success font-size-h5 float-left">
                                                        <i class="fa fas fa-file-pdf"></i> <b>দেখুন</b>
                                                    </a>
                                                </div>
                                                <div class="input-group-append">
                                                    <button type="button" id="deleteFileBtn_{{ $data['id'] }}"
                                                        class="btn btn-danger"
                                                        onclick="deleteUploadedFile('{{ $data['id'] }}', '{{ $data['notice_pdf'] }}')">
                                                        <i class="fas fa-trash-alt"></i> <b>মুছুন</b>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endif


                </div>








                <div class="form-group">
                    <label for="date">তারিখ <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                        id="date" placeholder="তারিখ লিখুন" value="{{ old('date', $data->date) }}">
                    @error('date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>



                <div class="form-group">
                    <label for="status">Status</label>
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
                    <button class="btn btn-primary" type="submit">হালনাগাদ করুন</button>

                </div>

            </form>
        </div>
    </div>
@endsection


<script>
    function deleteUploadedFile(rowId, fileName) {
        console.log(rowId);
        Swal.fire({
            title: 'আপনি কি এই ফাইলটি মুছে ফেলতে চান?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'হ্যাঁ',
            cancelButtonText: 'না'
        }).then((result) => {
            if (result.isConfirmed) {
                let btn = $('#deleteFileBtn_' + rowId);
                // btn.addClass('loadersmall'); // Show loader

                $.ajax({
                    url: '{{ url('/') }}/delete-notice-file',
                    type: "POST",
                    data: {
                        row_id: rowId,
                        file_name: fileName,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(response) {
                        Swal.fire('সফল!', response.message, 'success');
                        $('#deleteFileContainer_' + rowId).remove(); // Remove file row
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                        Swal.fire('ত্রুটি!', 'ফাইল মুছতে সমস্যা হয়েছে!', 'error');
                    },
                    complete: function() {
                        btn.removeClass(
                        'loadersmall'); // ✅ Remove loader in both success & error cases
                    }
                });
            }
        });
    }
</script>
