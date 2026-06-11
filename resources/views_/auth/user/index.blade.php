@extends('layouts/contentNavbarLayout')

@section('title', 'User Management')
@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">User Management </span> 
  @can('add_user')
    <span class="text-muted fw-light pull-right">
      <a href="{{ route('acl-auth-register-basic')}}" class="btn btn-sm btn-success mr-3 font-weight-bolder">
            <box-icon name='plus' color="white"></box-icon>  Create Users
         </a>   
    </span>
  @endcan
</h4>
  <!--begin::Card-->
<div class="card">
   <div class="card-body">
      @include('alerts.alert')
      <table class="table table-hover table-bordered mb-6 font-size-h6">
         <thead class="thead-light ">
            <tr>
               <th width="30">Sl. No</th>
               <th>Name</th>
               <th class="text-center">Email</th>
               <th class="text-center">Mobile</th>
               <th class="text-center">Role</th>
               <th class="text-center">Action</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $i = (($users->currentPage() -1) * $users->perPage() + 1);
            ?>
            @foreach ($users as $user)
 
            
            <tr>
               <th scope="row" class="tg-bn">{{ ($i++) }}</th>
               <td>{{ $user->name }}</td>
               
               <td class="text-center">{{ $user->email }}</td>
               <td class="text-center">{{ $user->mobile }}</td>
               <td class="text-center"><span class="btn btn-success btn-xs">{{ $user->getRoleNames(); }}</span></td>
               
               <td class="text-center">
                  @can('edit_user')
                  <a href="{{ route('userItem', $user->id) }}" class="btn btn-info btn-shadow btn-sm font-weight-bold pt-1  pb-1">Stock Item Group</a>
                  <a href="{{ route('userLedger', $user->id) }}" class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1  pb-1">Ledger Group</a>
                  <button type="button" onclick="updateUserModal('{{$user->id}}','{{$user->name}}', '{{$user->email}}', '{{$user->mobile}}', '{{ !empty($user->roles->first()->id)?$user->roles->first()->id:0}}','{{$user->supervisors}}')" class="btn btn-secondary btn-shadow btn-sm font-weight-bold pt-1 pb-1">Update</button>
                  @endcan
                  @can('delete_user')
                  <a href="{{ route('userDelete', $user->id) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-shadow btn-sm font-weight-bold pt-1  pb-1">Delete</a>
                  @endcan
               </td>
            </tr>
            @endforeach
           
         </tbody>
      </table>      
        {{-- $users->links() --}}
   </div>
</div>
<!--end::Card-->

  <!-- update Modal -->
  <div class="modal fade" id="updateUserItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Update user</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('updateUser') }}" method="POST">
           @csrf
           <input type="hidden" name="id" id="userId">
           <div class="modal-body">
               <div class="card-body card-block">

                <div class="form-group mb-3">
                    <label for="name" class=" form-control-label">User Name<span class="text-danger">*</span></label>
                    <input type="text" id="update_name" name="name" class="form-control form-control-sm" required>
                </div>

                <div class="form-group mb-3">
                    <label for="name" class=" form-control-label">User Email<span class="text-danger"></span></label>
                    <input type="email" id="update_email" name="email" class="form-control form-control-sm" >
                </div>

                <div class="form-group mb-3">
                    <label for="name" class=" form-control-label">User Mobile<span class="text-danger">*</span></label>
                    <input type="number" id="update_mobile" name="mobile" class="form-control form-control-sm" required>
                </div>

                <div class="form-group mb-3">
                    <label for="name" class=" form-control-label">Supervisor<span class="text-danger"></span></label>
                    <select name="supervisor" id="update_supervisor" class="form-control" value="{{ old('supervisor') }}">
                        <option value="">--Select Supervisor--</option>
                        @foreach($users as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                        @endforeach
                        </select>
                </div>

                <div class="form-group mb-3">
                    <label for="name" class=" form-control-label">Status<span class="text-danger">*</span></label>
                    <select name="role" id="update_role" class="form-control" value="{{ old('role') }}" required>
                        <option value="">--Select Role--</option>
                        @foreach($roles as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                        @endforeach
                        </select>
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="form-control-label">{{ __('Password') }} <span class="text-danger"></span></label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                </div>
            
              </div>
           </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
     </form>
      </div>
    </div>
  </div>
<!-- update Modal -->

@endsection

<!-- {{-- Includable CSS Related Page --}} -->
@section('styles')
  <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Page Vendors Styles-->
@endsection     

<!-- {{-- Scripts Section Related Page--}} -->
@section('vendor-script')
  <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
  <script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
  <!--end::Page Scripts-->


  <script>
     
     function updateUserModal(id, name, email, mobile, role,supervisors){

         $('#updateUserItem').modal('show');

         $('#userId').val(id);
         $('#update_name').val(name);
         $('#update_email').val(email);
         $('#update_mobile').val(mobile);
         $('#update_role').val(role);
         $('#update_supervisor').val(supervisors);

     }
  </script>
@endsection


