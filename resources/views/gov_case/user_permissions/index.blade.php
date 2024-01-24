@extends('layouts.cabinet.cab_default')

@section('content')


<!--begin::Card-->
<div class="card card-custom">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h2>অনুমতি প্রদান পরিচালনা </h2>
      </div>
      <div class="card-toolbar">


      </div>
   </div>
   <div class="card-body">

      <table class="table table-hover mb-6 font-size-h6">
         <thead class="thead-light ">
            <tr>
               <th width="2%">#</th>
               <th width="">ভুমিকার নাম</th>
               <th width="">ভুমিকার নাম(ইংরেজি)</th>
               <th width="">অবস্থা</th>
               <th width="">প্রস্তুতকারক</th>
               <th width="">অ্যাকশন</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $i = 1;

            ?>
            @foreach ($roles as $role)
               <?php
                    $user = App\Models\User::find($role->created_by);
                ?>
            <tr>
               <th scope="row" class="tg-bn">{{ en2bn($i++) }}</th>
               <td>{{ $role->name_bn?? '' }}</td>
               <td>{{ $role->name?? '' }}</td>
               <td>
                  @if($role->status == 1)
                     <span class="badge badge-primary">সক্রিয়</span>
                  @else
                     <span class="badge badge-secondary">নিশক্রিয়</span>
                  @endif
               </td>
               <td>{{ $user->name?? '' }}</td>
               <td>
                @if(auth()->user()->can('manage_permission_details'))
                  <a href="{{ route('cabinet.userPermissionManage', $role->id) }}" class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1 pb-1">অনুমতি প্রদান পরিচালনা</a>
                @else
                 <a href="#" class="btn btn-secondary btn-sm font-weight-bold pt-1 pb-1">অনুমতি প্রদান পরিচালনা</a>
                @endif

               </td>
            </tr>
            @endforeach

         </tbody>
      </table>

   </div>
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


<script>


</script>





@endsection


