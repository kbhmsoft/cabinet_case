@extends('layouts.cabinet.cab_default')


@section('content')

<!--begin::Card-->
<div class="card card-custom col-12">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
      </div>
         <a href="{{ route('cabinet.settings.office_type.list') }}" class="btn btn-primary btn-sm float-right" style="height: 30px;">তালিকা</a>
   </div>

   <form action="{{ route('cabinet.settings.office_type.update') }}" method="POST">
      @csrf

      <div class="card-body">
	      	@if ($message = Session::get('success'))
	      	<div class="alert alert-success">
	         <p>{{ $message }}</p>
	      	</div>
	      	@endif
         <div class="mb-12">
            <div class="form-group row">
                  <input type="hidden" name="office_type_id" class="form-control" placeholder="" value="{{ $office_type->id}}" />
               <div class="col-lg-4">
                  <label>বাংলা নামঃ</label>
                  <input type="text" name="type_name_bn" class="form-control" placeholder="" value="{{ $office_type->type_name_bn}}" />
               </div>
               <div class="col-lg-4">
                  <label>ইংরেজি নামঃ</label>
                  <input type="text" name="type_name" class="form-control" placeholder="" value="{{ $office_type->type_name}}" />
               </div>
            </div>
         </div>
      </div>

      <div class="card-footer">
         <div class="row">
            <div class="col-lg-12">
               <button type="submit" class="btn btn-primary font-weight-bold mr-2">সংরক্ষণ</button>
            </div>
         </div>
      </div>

   </form>
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


