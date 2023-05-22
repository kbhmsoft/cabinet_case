@extends('layouts.cabinet.cab_default')

@section('content')

<!--begin::Card-->
<div class="card card-custom">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
      </div>
   </div>
   <div class="card-body">
      @if ($message = Session::get('success'))
      <div class="alert alert-success">
         {{ $message }}
      </div>
      @endif
     
      <table class="table table-hover mb-6 font-size-h5">
         <thead class="thead-light font-size-h6">
            <tr>
               <th scope="col" width="30">ক্রমিক</th>
               <th scope="col">মামলা নং</th>
               <th scope="col">ক্যাটাগরি</th>
               <th scope="col">পিটিশনারের নাম ও ঠিকানা</th>
               <th scope="col" width="300">মামলার বিষয়বস্তু</th>
               <th scope="col" width="70">অ্যাকশন</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($cases as $key => $row)
          
            <tr>
               <td scope="row" class="tg-bn">{{ en2bn($key + $cases->firstItem()) }}.</td>
               <td>{{ $row->case_no }}</td>
               <td>{{ $row->case_category->name_bn ?? '-' }}</td>
               <td>{{ $row->badis->first()->name ?? '-' }},<br>{{ $row->badis->first()->address ?? '-' }} </td>

               <td>{{ $row->subject_matter ?? '-'}}</td>
               <td>
                    <div class="btn-group float-right">
                        <button class="btn btn-primary font-weight-bold btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">অ্যাকশন</button>
                        <div class="dropdown-menu">

                           @can('show_details_info')
                            <a class="dropdown-item" href="{{ route('cabinet.case.details', $row->id) }}">বিস্তারিত তথ্য</a>
                           @endcan
                            <a class="dropdown-item" href="{{ route('cabinet.case.othersaction.againstgovedit', $row->id) }}">হালনাগাদ</a>
                            @can('highcoutr_send_answer')
                            @if($row->action_user_role_id == userInfo()->role_id)
                            <a class="dropdown-item" href="{{ route('cabinet.case.action.details', $row->id) }}">জবাব প্রেরণ</a>
                            @endif
                            @endcan
                        </div>
                    </div>
               </td>
            </tr>
            @endforeach
         </tbody>
      </table>

      <div class="d-flex justify-content-center">
         {!! $cases->links() !!}
      </div>
   </div>
   <!--end::Card-->

   @endsection

   {{-- Includable CSS Related Page --}}
   @section('styles')
   <!-- <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" /> -->
   <!--end::Page Vendors Styles-->
   @endsection

   {{-- Scripts Section Related Page--}}
   @section('scripts')
   <!-- <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
   <script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
 -->


<!--end::Page Scripts-->
@endsection


