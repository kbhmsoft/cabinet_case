@extends('layouts.cabinet.cab_default')

@section('content')
 

<!--begin::Card-->
<div class="card card-custom">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h2 > {{ $page_title }} </h2>
      </div>
      <div class="card-toolbar">        
         <a href="{{ route('cabinet.notice.create') }}" class="btn btn-sm btn-primary font-weight-bolder">
            <i class="la la-plus"></i>নতুন নোটিশ জারি
         </a>                
      </div>
   </div>
   <div class="card-body">
      @if ($message = Session::get('success'))
      <div class="alert alert-success">
         <p>{{ $message }}</p>
      </div>
      @endif
      <table class="table table-hover mb-6 font-size-h6">
         <thead class="thead-light ">
            <tr>
               <th scope="col" width="30">#</th>
               <th scope="col">নোটিশ</th>
               <th scope="col">ইউজার রোল</th>
               <th scope="col">প্রকাশের তারিখ</th>
               <th scope="col">মেয়াদ শেষ হওয়ার তারিখ</th>
               <th scope="col" width="150">অ্যাকশন</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($notices as $key=>$row)
            <tr>
               <th scope="row" class="tg-bn">{{ en2bn($key+1) }}</th>
               <td>{{ $row->description }}</td>
               <td>{{ $row->role->role_name }}</td>
               <td>{{ en2bn($row->publish_date) }}</td>
               <td>{{ en2bn($row->expiry_date) }}</td>
               
               <td>
                  <a href="{{ route('cabinet.notice.show', $row->id) }}" class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1 pb-1">বিস্তারিত</a>
                  <a href="{{ route('cabinet.notice.edit', $row->id) }}" class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1 pb-1">সংশোধন</a>
               </td>
            </tr>
            @empty
            <tr><td colspan="4"><p class="font-weight-bold">কোন নোটিশ খুজে পাওয়া যায়নি</p></td></tr>
            @endforelse
         </tbody>
      </table>      
      {!! $notices->links() !!}  
   </div>
</div>
<!--end::Card-->

@endsection

{{-- Includable CSS Related Page --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Page Vendors Styles-->
@endsection     

{{-- Scripts Section Related Page--}}
@section('scripts')
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
<!--end::Page Scripts-->
@endsection


