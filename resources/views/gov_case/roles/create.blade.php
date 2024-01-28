@extends('layouts.cabinet.cab_default')

@section('content')


<!--begin::Card-->
<div class="card card-custom">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h2>ভূমিকা পরিচালনা </h2>
      </div>
      <div class="card-toolbar">
      @if(auth()->user()->can('create_role'))
         <button type="button" data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-sm btn-primary font-weight-bolder">
            <i class="la la-plus"></i>ভূমিকা তৈরি করুন
         </button>
         @else
         <button type="button" class="btn btn-sm btn-secondary font-weight-bolder">
            <i class="la la-plus"></i>ভূমিকা তৈরি করুন
         </button>
         @endif
      </div>
   </div>
   <div class="card-body">
      @if($errors->any())
      <div class="alert alert-danger">
          <ul>
             @foreach($errors->all() as $error)
               <li>{{ $error }}</li>
             @endforeach
          </ul>
      </div>
      @endif
      @if ($message = Session::get('success'))
      <div class="alert alert-success">
         <p>{{ $message }}</p>
      </div>
      @endif
      <table class="table table-hover mb-6 font-size-h6">
         <thead class="thead-light ">
            <tr>
               <th scope="col" width="30">#</th>
               <th scope="col">ভূমিকা নাম</th>
               <th scope="col">ভূমিকা নাম(ইংরেজি)</th>
               <th scope="col">অবস্থা</th>
               <th scope="col" width="150">অ্যাকশন</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $i = (($roles->currentPage() -1) * $roles->perPage() + 1);
            ?>
            @foreach ($roles as $role)
                <?php
                    $user = App\Models\User::find($role->created_by);
                ?>
            <tr>
               <th scope="row" class="tg-bn">{{ en2bn($i++) }}</th>
               <td>{{ $role->name_bn }}</td>
               <td>{{ $role->name }}</td>
               <td>
                  @if($role->status == 1)
                     <span class="badge badge-primary">সক্রিয়</span>
                  @else
                     <span class="badge badge-secondary">নিশক্রিয়</span>
                  @endif
               </td>
               <td>
                @if(auth()->user()->can('role_update'))
                  <button type="button" onclick="updateRoleModal({{$role->id}}, '{{$role->name_bn}}', '{{$role->name}}', '{{$role->status}}')" class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1 pb-1">সংশোধন</button>
                @else
                 <button type="button" class="btn btn-secondary btn-sm font-weight-bold pt-1 pb-1">সংশোধন</button>
                @endif

                <!-- {{-- @if(auth()->user()->can('role_delete'))
                  <a href="{{ route('cabinet.roleItemDelete', $role->id) }}" onclick="return confirm('আপনি কি নিশ্চিত ?')" class="btn btn-warning btn-shadow btn-sm font-weight-bold pt-1 pb-1">মুছে দিন</a>
                @else
                 <a href="#" class="btn btn-secondary btn-sm font-weight-bold pt-1 pb-1">মুছে দিন</a>
                @endif --}} -->
               </td>
            </tr>

            @endforeach

         </tbody>
      </table>
         {{ $roles->appends($_GET)->links() }}

   </div>
</div>
<!--end::Card-->



      <!-- update Modal -->
      <div class="modal fade" id="updateRoleItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">ভূমিকা সংশোধন করুন</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('cabinet.updateRole') }}" method="POST">
               @csrf
               <input type="hidden" name="role_id" id="roleID">
               <div class="modal-body">
                   <div class="card-body card-block">
                        <div class="form-group">
                            <label for="name" class=" form-control-label">ভূমিকা নাম <span class="text-danger">*</span></label>
                            <input type="text" id="update_name_bn" name="name_bn" class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group">
                            <label for="name" class=" form-control-label">ভূমিকা নাম(ইংরেজি) <span class="text-danger">*</span></label>
                            <input type="text" id="update_name" name="name" class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group">
                            <label for="name" class=" form-control-label">অবস্থা<span class="text-danger">*</span></label>
                             <select name="status" class="form-control">
                                <option class="status1" value="1">সক্রিয়</option>
                                <option class="status2" value="0">নিশক্রিয়</option>
                             </select>
                        </div>

                  </div>
               </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
              <button type="submit" class="btn btn-primary">সংশোধন করুন</button>
            </div>
         </form>
          </div>
        </div>
      </div>


<!-- update Modal -->


      <!-- <form action="{{ route('cabinet.storeRole') }}" method="POST"> -->


<!-- create Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">ভূমিকা তৈরি করুন</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('cabinet.storeRole') }}" method="POST">
         @csrf
         <div class="modal-body">
             <div class="card-body card-block">
                  <div class="form-group">
                      <label for="name" class=" form-control-label">ভূমিকা নাম <span class="text-danger">*</span></label>
                      <input type="text" id="name_bn" name="name_bn" placeholder="ভূমিকার নাম লিখুন" class="form-control form-control-sm" required>
                      <span style="color: red">
                        {{ $errors->first('name') }}
                     </span>
                  </div>
                  <div class="form-group">
                      <label for="name" class=" form-control-label">ভূমিকা নাম(ইংরেজি) <span class="text-danger">*</span></label>
                      <input type="text" id="name" name="name" placeholder="ভূমিকার নাম লিখুন" class="form-control form-control-sm" required>
                      <span style="color: red">
                        {{ $errors->first('name') }}
                     </span>
                  </div>

            </div>
         </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
        <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
      </div>
   </form>
    </div>
  </div>
</div>




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

   function updateRoleModal(id, name_bn, name, status){
       $('#updateRoleItem').modal().show();

       $('#roleID').val(id);
       $('#update_name_bn').val(name_bn);
       $('#update_name').val(name);

        $('.status2').attr('selected', false);
        $('.status2').attr('selected', false);

       var checkstatus = status;
       if(checkstatus == '0'){
            $('.status2').attr('selected','selected');
       }else{
          $('.status1').attr('selected','selected');
       }

   }
</script>


@endsection


