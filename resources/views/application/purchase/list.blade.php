@extends('layouts/contentNavbarLayout')

@section('title', 'Purchase')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Purchase') }}</span>
                </div>

                
                <div class="card-body">
                    <div class="card">
                        <form method="GET" action="" enctype="multipart/form-data">
                            @csrf
        
                            <div class="py-3">
        
                                <div class="row mb-3">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="row">
                                            <label class="col-4 col-sm-4 col-xs-12  col-form-label"
                                                for="basic-default-ledger">Ledger
                                                Name</label>
                                            <div class="col-8 col-sm-8">
                                                <select type="text" class="form-control form-control-sm js-example-basic-single "
                                                    id="basic-default-ledger" name="ledger"
                                                    placeholder="Enter Ledger Type">
                                                    <option value="">Select Ledger</option>
                                                    @foreach ($ledger as $key => $item)
                                                        <option value="{{ $item->id }}" {{ optional($info)['ledger']==$item->id?"selected":""}}>{{ $item->alias .'-'.$item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-sm-12">
                                        <div class="row">
                                            <div class="col-4 col-sm-4">
                                                <input type="date" class="form-control form-control-sm " value="{{ optional($info)['s_date']?$info['s_date']:date('Y-m-d') }}" id="basic-default-date"
                                                    name="s_date" placeholder="Enter Date" />
                                            </div>
                                            <label class="col-2 col-sm-2 col-form-label" for="basic-default-date">To</label>
                                            <div class="col-4 col-sm-4">
                                                <input type="date" class="form-control form-control-sm " value="{{ optional($info)['e_date']?$info['e_date']:date('Y-m-d') }}" id="basic-default-date"
                                                    name="e_date" placeholder="Enter Date" />
                                            </div>
                                            <div class="col-2 col-sm-2">
                                                <button type="submit" class="btn btn-primary btn-sm btn-block">Go</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive text-nowrap" style="min-height: 500px;">
                            <table class="table table-bordered" class="font-size: 0.7em">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>VOUCHER NO</th>
                                        <th>LEDGER NAME</th>
                                        <th>DATE</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    
                                    @forelse($data as $key => $item)
                                    
                                        <tr>
                                            <td>{{ $data->firstItem() + $key }}</td>
                                            <td>{{ $item->voucher_no }}</td>
                                            <td>{{ $item->Ledger->name }}</td>
                                            <td>{{ $item->date }}</td>
                                            <td>{{ $item->total_amount }}</td>
                                            <td>{{ $item->paid_amount }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                                    <ul class="dropdown-menu">
                                                        <!-- @can('Sales Order Approve')
                                                            @if($item->is_tally_synced == 0)
                                                                <li><a class="dropdown-item" href="{{url('/purchase/status/'.$item->id)}}">Approve</a></li>
                                                            @endif
                                                        @endCan -->
                                                        <li><a class="dropdown-item" href="{{url('/purchase/details/'.$item->id)}}">Details</a></li>
                                                        @can('sales_order_delete')
                                                            @if($item->is_tally_synced != 2)
                                                                <li>
                                                                    <a class="dropdown-item" href="{{url('/purchase/edit/'.$item->id)}}">Update</a>
                                                                </li>
                                                                <!-- <li><a target="_blank" onclick="return confirm('Are you want to Delete?');" class="dropdown-item" href="{{url('/purchase/delete/'.$item->id)}}">Delete</a></li>  -->
                                                            @endif
                                                        @endCan
                                                    </ul>
                                                  </div>
                                            </td>
                                        </tr>
                                        
                                    @empty
                                        <tr>
                                            No Data Found.
                                        </tr>
                                    @endforelse


                                </tbody>
                            </table>
                            <div>
                                {{ $data->withQueryString()->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
