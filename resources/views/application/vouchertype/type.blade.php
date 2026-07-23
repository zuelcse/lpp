@extends('layouts/contentNavbarLayout')

@section('title', 'Voucher Type')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Voucher Type') }}</span>
                </div>


                <div class="card-body">
                    @include('alerts.alert')
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table" class="font-size: 0.7em">
                                <thead>
                                    <tr>
                                        <th>Voucher Name</th>
                                        <th>Voucher Type</th>
                                        <th>Voucher Prefix</th>
                                        <th>Start SL Number</th>
                                        <th>Goddown</th>
                                        <th>Sales Account</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    
                                    @forelse($voucherTypes as $key => $item)
                                        <tr>
                                            <td>{{ $item->voucher_name }}</td>
                                            <td>{{ $item->voucher_type == 1 ? 'Sales' : 'Recept' }}</td>
                                            <td>{{ $item->voucher_prefix }}</td>
                                            <td>{{ $item->start_number }}</td>
                                            <td>{{ $item->goddown }}</td>
                                            <td>{{ $item->sales_account }}</td>
                                            <td>
                                                <button type="button" onclick="updateVoucherTypeModal('{{$item->id}}','{{$item->voucher_prefix}}', '{{$item->start_number}}', '{{$item->goddown}}', '{{$item->sales_account}}')" class="btn btn-primary btn-shadow btn-sm font-weight-bold pt-1 pb-1">Edit</button>  
                                            </td>
                                        </tr>
                                        
                                    @empty
                                        <tr>
                                            No Item Found.
                                        </tr>
                                    @endforelse


                                </tbody>
                            </table>
                            <div>
                                {{ $voucherTypes->withQueryString()->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="voucherTypeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Voucher Type</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('updateVoucherType') }}" method="POST">
               @csrf
               <input type="hidden" name="id" id="Id">
               <div class="modal-body">
                   <div class="card-body card-block">

                    <div class="form-group mb-3">
                        <label for="voucher_prefix" class=" form-control-label">Voucher Prfix<span class="text-danger">*</span></label>
                        <input type="text" id="voucher_prefix" name="voucher_prefix" class="form-control form-control-sm" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="start_number" class=" form-control-label">Start Number<span class="text-danger">*</span></label>
                        <input type="Number" id="start_number" name="start_number" class="form-control form-control-sm" required="" >
                    </div>

                    <div class="form-group mb-3">
                        <label for="goddown" class=" form-control-label">Goddown<span class="text-danger">*</span></label>
                        <input type="text" id="goddown" name="goddown" class="form-control form-control-sm" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="sales_account" class=" form-control-label">Sales Account<span class="text-danger">*</span></label>
                        <input type="text" id="sales_account" name="sales_account" class="form-control form-control-sm" required>
                    </div>

                  </div>
               </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" onclick="return confirm('Are you want to proceed?')">Update</button>
            </div>
         </form>
          </div>
        </div>
    </div>
    <script>
     
     function updateVoucherTypeModal(id, voucher_prefix, start_number, goddown, sales_account){

         $('#voucherTypeModal').modal('show');

         $('#Id').val(id);
         $('#voucher_prefix').val(voucher_prefix);
         $('#start_number').val(start_number);
         $('#goddown').val(goddown);
         $('#sales_account').val(sales_account);

     }
  </script>

@endsection
