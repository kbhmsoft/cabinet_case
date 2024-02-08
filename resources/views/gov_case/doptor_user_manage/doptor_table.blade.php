@foreach ($organoGram as $index => $row)
    <tr>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <td>{{ $index + 1 }}</td>
        <td>{{ $row->nameBn }}</td>
        <td>{{ $row->unitNameBn }}</td>
        <td>
            <div class="form-group mb-2">
                @php
                    $selectedRoleId = null;
                    foreach ($doptorUserManagement as $value) {
                        if ($value->organogram_id == $row->id) {
                            $selectedRoleId = $value->user_role;
                            break;
                        }
                    }
                @endphp
                <select name="role" class="form-control w-100"
                    onchange="updateUserRole(this, {{ $row->id }}, {{ $row->officeId }})">
                    <option value=''>-ইউজার রোল নির্বাচন করুন-</option>
                    @foreach ($user_role as $role)
                        <option value="{{ $role->id }}" {{ $role->id == $selectedRoleId ? 'selected' : '' }}>
                            {{ $role->name_bn }}
                        </option>
                    @endforeach
                </select>
            </div>
        </td>
        <td class="text-center">
            <strong>
                @if ($selectedRoleId)
                    <button id="role_yes_{{ $row->id }}" class="font-weight-bolder btn btn-success">
                        রোল দেয়া হয়েছে!
                    </button>
                @else
                    <button id="role_no_{{ $row->id }}" class="font-weight-bolder btn btn-danger">
                        কোনো রোল দেয়া হয় নি!
                    </button>
                @endif
            </strong>
        </td>
    </tr>
   
@endforeach


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    function updateUserRole(selectElement, organogramId, officeId) {
        // alert(selectElement.value);
        var selectedRole = selectElement.value;
        Swal.fire({
            title: "আপনি কি রোল প্রদান/পরিবর্তন করতে চান?",
            showDenyButton: true,
            confirmButtonText: "হ্যাঁ",
            denyButtonText: `না`
        }).then((result) => {

            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('doptor.updateUserRole') }}",
                    method: 'POST',
                    data: {
                        organogram_id: organogramId,
                        role_id: selectedRole,
                        office_id: officeId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "সফল!",
                            text: "ব্যবহারকারীর ভূমিকা সফলভাবে আপডেট হয়েছে।",
                            icon: "success"
                        }).then((result) => {
                            console.log(response.roleDetails.user_role);
                            // if (response.roleDetails == "" || response.roleDetails ==
                            //     null) {
                            //     $('#role_yes_' + response.id).html('কোনো রোল দেয়া হয় নি!');
                            //     $('#role_yes_' + response.id).addClass('btn-danger');
                            //     $('#role_yes_' + response.id).removeClass('btn-success');
                            // } else if (!response.roleDetails || response.roleDetails.user_role == 42){
                            //     $('#role_yes_' + response.id).html('কোনো রোল দেয়া হয় নি!');
                            //     $('#role_yes_' + response.id).addClass('btn-danger');
                            //     $('#role_yes_' + response.id).removeClass('btn-success');
                            // } else {
                            //     $('#role_yes_' + response.id).html('রোল দেয়া হয়েছে!');
                            //     $('#role_yes_' + response.id).removeClass('btn-danger');
                            //     $('#role_yes_' + response.id).addClass('btn-success');
                            //     $('#role_no_' + response.id).html('রোল দেয়া হয়েছে!');
                            //     $('#role_no_' + response.id).removeClass('btn-danger');
                            //     $('#role_no_' + response.id).addClass('btn-success');
                            // }

                            console.log(response.roleDetails.user_role);

                            if (response.roleDetails === null || response.roleDetails ===
                                "" || response.roleDetails.user_role == 42) {
                                $('#role_yes_' + response.id).html('কোনো রোল দেয়া হয় নি!');
                                $('#role_yes_' + response.id).addClass('btn-danger');
                                $('#role_yes_' + response.id).removeClass('btn-success');
                            } else {
                                $('#role_yes_' + response.id).html('রোল দেয়া হয়েছে!');
                                $('#role_yes_' + response.id).removeClass('btn-danger');
                                $('#role_yes_' + response.id).addClass('btn-success');
                                $('#role_no_' + response.id).html('রোল দেয়া হয়েছে!');
                                $('#role_no_' + response.id).removeClass('btn-danger');
                                $('#role_no_' + response.id).addClass('btn-success');
                            }

                        })
                    },
                    error: function(error) {
                        Swal.fire("দুঃখিত!", "ব্যবহারকারীর ভূমিকা আপডেট সমস্যা হয়েছে", "error");
                    }
                });
            }
        });


    }
</script>
