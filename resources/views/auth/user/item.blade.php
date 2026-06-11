@extends('layouts/contentNavbarLayout')

@section('title', 'Modify stock item group Permission Allocation')
@section('content')

<!--begin::Card-->
<div class="card">

   @include('alerts.alert')
   <form action="{{ route('storeUpdateUserItem') }}" method="POST">
      @csrf

   <input type="hidden" name="user_id" value="{{ $user->id }}">
    
   <div class="card-header flex-wrap py-5">
      <div class="card-title mb-0 mt-2" >
         <h3 style="display: contents">(Welcome to  <strong>{{ $user->name}}</strong>)</h3>
      </div>
      <div class="card-toolbar">        
      </div>
   </div>
   <div class="card-body">    
   <div class="row grid">
      <div class="col-lg-12 col-md-12 mb-12 grid-item">
            <div class="card-bodys cardbody">
               <div class="listPermission">
                  <div>
                    <div class="table-responsive text-nowrap">
                        <table class="table" class="font-size: 0.7em">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>Stock item Parents</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">  
                                @foreach($ledger as $item)
                                    <tr>
                                        <td><input type="checkbox" name="stock_item_group[]" value="{{$item->parent}}" class="mr-1" @if(in_array($item->parent,$ledgerPermission,true)) checked @endif></td>
                                        <td>{{ $item->parent }}</td>
                                    </tr>
                                @endforeach
                                
                            </tbody>
                        </table>
                  </div>
               </div>
            </div>
      </div>
  
        @can('edit_user')
            <button type="submit" class="btn btn-primary font-weight-bolder float-right mt-5" onclick="return confirm('Are you sure you want to modify ledger assignments?')">
               <i class="far fa-check-circle"></i>Modify stck item group allocation
            </button>  
        @endcan
 
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

@endsection


