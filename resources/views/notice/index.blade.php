@extends('layouts.cabinet.cab_default')
@section('title', 'নোটিশ তালিকা')

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        @import url('https://fonts.maateen.me/kalpurush/font.css');

        @media (max-width: 576px) {
            .table-responsive {
                overflow-x: auto;
            }
        }

        body {
            font-family: 'Kalpurush', Arial, sans-serif !important;
        }
    </style>
@endsection

@section('content')
    <div class="card slider-list">
        <div class="table-responsive card-body p-0 mb-3">
            <div class="card-header" style="display: flex; justify-content: space-between;">
                <div class="card-title">
                    <h2 class="text-dark font-weight-bolder">নোটিশ তালিকা</h2>
                </div>

                <div class="card-toolbar">
                    <a href="{{ route('notices.create') }}" class="btn btn-sm btn-primary font-weight-bolder">
                        <i class="la la-plus"></i>নতুন নোটিশ এন্ট্রি
                    </a>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>বিষয়</th>
                        <th> (পিডিএফ) ফাইল</th>
                        <th>তারিখ</th>
                        <th>স্ট্যাটাস</th>
                        <th class="text-center">প্রক্রিয়া</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $item)
                        <tr>

                            <td class="text-truncate" style="max-width: 200px;">{{ $item->title }}</td>
                            <td>
                                <a href="{{ Storage::url($item->notice_pdf) }}" target="_blank">পিডিএফ দেখুন</a>
                            </td>
                            <td>{{ $item->date }}</td>
                            <td>
                                <span class="p-2 mt-1 right badge badge-{{ $item->status ? 'success' : 'danger' }}">
                                    {{ $item->status ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                </span>
                            </td>

                            <td class="text-center d-flex justify-content-center align-items-center">
                                <a href="{{ route('notices.edit', $item) }}" class="btn btn-primary btn-sm"><i
                                        class="fas fa-edit"></i></a>

                                <form action="{{ route('notices.destroy', $item->id) }}" method="POST" id="deleteForm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm ml-2 btn-delete" onclick="confirmDelete('{{ $item->id }}')"><i
                                            class="fas fa-trash"></i></button>
                                </form> 
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center d-flex justify-content-center align-items-center">
                                তথ্য পাওয়া যাচ্ছে না!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mb-5 mr-4 d-flex justify-content-end align-items-center">
            {{ $data->render() }}
        </div>
    </div>

    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        function confirmDelete(id) {
            swal.fire({
                title: 'আপনি কি নিশ্চিতভাবে এই তথ্যটি মুছে ফেলতে চান?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'হ্যা, মুছে ফেলুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').action = '{{ route('notices.destroy', '') }}' + '/' + id;
                    document.getElementById('deleteForm').submit();
                }
            });
        }

        @if(session('status'))
            swal.fire({
                title: '{{ session('status') }}',
                icon: '{{ session('type') }}',
                showConfirmButton: false,
                timer: 1500
            });
        @endif
    </script>
@endsection
