@extends('layouts.cabinet.cab_default')

@section('content')
<style type="text/css">
    #appRowDiv td{padding: 5px; border-color: #ccc;}
    #appRowDiv th{padding: 5px;text-align:center;border-color: #ccc; color: black;}
</style> 
<br>
<!-- <a href="" style="float: right;"> <button  class="btn btn-success">Back</button></a> -->
<div class="row">
	<div class="col-lg-12">
		<div class="card card-custom gutter-b example example-compact">
			<form method="POST" action="{{route('cabinet.notice.store')}}">	
				@csrf
				<div class="card">
				        <div class="card-header">
				        	<h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
				        </div>
				    <div class="card-body card-block row">
				        <div class="col-12">
				            <div class="form-group">
				                <label for="name" class=" form-control-label">নোটীশের বিস্তারিত <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control" id="description" rows="3" spellcheck="false">
                                    </textarea>
				            </div>
				        </div>
				        <div class="col-4">
				            <div class="form-group">
				                <label for="role_id" class=" form-control-label">ইউজার রোল <span class="text-danger">*</span></label>
				               <select name="role_id" id="role_id" class="form-control-sm form-control">
				               	<option value="">-- নির্বাচন করুন --</option>
		                                @foreach ($roles as $value)
		                                    <option value="{{ $value->id }}"> {{ $value->name }} </option>
		                                @endforeach
				               </select>
				               <span style="color: red">
	                				{{ $errors->first('role_id') }}
	                			</span>
				            </div>
				        </div>
			            <div class="col-4">
			               	<div class="form-group">
			                    <label for="mobile_no" class=" form-control-label">প্রকাশের তারিখ </label>
			                    <input type="text" name="publish_date" id="publish_date" placeholder="প্রকাশের তারিখন" class="form-control form-control-sm  common_datepicker">
			                </div>
			            </div>
			             <div class="col-4">
				            <div class="form-group">
				                <label for="email" class=" form-control-label">মেয়াদ শেষ হওয়ার তারিখ</label>
				                <input type="text" id="expiry_date" name="expiry_date" placeholder="মেয়াদ শেষ হওয়ার তারিখ" class="form-control form-control-sm common_datepicker">
				            </div>
				        </div>
				    </div>
			    </div>
				<div class="card-footer">
		            <div class="row">
		                <div class="col-lg-4"></div>
		               	<div class="col-lg-4">
		                    <button type="submit" class="btn btn-success mr-2"onclick="return confirm('আপনি কি সংরক্ষণ করতে চান?')">সংরক্ষণ করুন</button>
		                </div>
		            </div>
		        </div>

			   
			</form>
		</div>
	</div>	
</div>

@endsection
@section('scripts')
<script>
function myFunction() {
  confirm("আপনি কি সংরক্ষণ করতে চান?");
}

$('document').ready(function(){
	$('#preview').on('click',function(){
		var name = $('#name').val();
		var username = $('#username').val();
		var email = $('#email').val();
		var mobile_no = $('#mobile_no').val();
		var role_id = $('#role_id option:selected').text();
		var office_id = $('#office_id option:selected').text();
		$('#previewName').html(name);
		$('#previewUsername').html(username);
		$('#previewEmail').html(email);
		$('#previewMobile_no').html(mobile_no);
		$('#previewRole_id').html(role_id);
		$('#previewOffice_id').html(office_id);
	});
});
        // common datepicker
        $('.common_datepicker').datepicker({
            orientation: "bottom left",
            format: "dd/mm/yyyy",
            todayHighlight: true,
            viewMode: 'years',
        });


</script>
@endsection
