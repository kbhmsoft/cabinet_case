@extends('layouts.cabinet.cab_default')

@section('content')

<!--begin::Card-->
<div class="card card-custom mx-5">

   <form action="{{ route('cabinet.storeUpdateUserPermissionAll') }}" method="POST">
      @csrf

   <input type="hidden" name="role_id" value="{{ $role->id }}">
    
   <div class="card-header flex-wrap py-5 mt-5">
      <div class="card-title mb-0 mt-2" >
         <h2 style="display: contents">অনুমতি প্রদান পরিচালনা করুন </h2>
         <h3 style="display: contents">(ভুমিকা: <strong>{{ $role->name}}</strong>)</h3>

            @if($role->name == 'SuperAdmin')
               @if(Auth::user()->type == 'developer' || Auth::user()->role->name == 'SuperAdmin')
                  <button type="submit" class="btn btn-primary font-weight-bolder float-right" onclick="return confirm('আপনি কি নিশ্চিত অনুমতি বরাদ্দ সংশোধন করতে চান?')">
                     <i class="far fa-check-circle"></i>অনুমতি বরাদ্দ সংশোধন
                  </button>  
               @else
                  <button type="button" class="btn btn-secondary font-weight-bolder float-right">
                     <i class="far fa-check-circle"></i>অনুমতি বরাদ্দ সংশোধন
                  </button> 
               @endif
            @else
                  <button type="submit" class="btn btn-primary font-weight-bolder float-right" onclick="return confirm('আপনি কি নিশ্চিত অনুমতি বরাদ্দ সংশোধন করতে চান?')">
                  <i class="far fa-check-circle"></i>অনুমতি বরাদ্দ সংশোধন
               </button> 
            @endif
          
      </div>
      <div class="card-toolbar">        
      </div>
   </div>
   <div class="card-body">    
   <div class="row grid">
   @foreach($parentPermissions as $parentPermission )
      <div class="col-lg-4 col-md-4 mb-5 grid-item">
            <div class="card-bodys cardbody">
               <div class="cardheader" >
                     {{ $parentPermission->name }}
                </div>
               <div class="listPermission">
                  <ul>
                      
                     @foreach($parentPermission->permissions as $permission)
       
                     <li>
                        <input type="checkbox" name="permissionId[]" value="{{$permission->id}}" class="mr-1" @if(isset($permission->roleHasPermission($role->id)->permission_id) && $permission->roleHasPermission($role->id)->permission_id == $permission->id) checked @endif> 
                         <span>{{$permission->display_name}}</span>
                     </li>
                     @endforeach
                  </ul>
               </div>
            </div>
      </div>
   @endforeach 
  

 
   </div>

    
   </div>
</form>

</div>
<!--end::Card-->

 

@endsection

<!-- {{-- Includable CSS Related Page --}} -->
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Page Vendors Styles-->
@endsection     

<!-- {{-- Scripts Section Related Page--}} -->
@section('scripts')
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
<!--end::Page Scripts-->
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>

<script>
 
   // jQuery
$('.grid').masonry({
  
  itemSelector: '.grid-item'
});
    
</script>

 

@endsection


