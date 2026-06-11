@extends('layouts/contentNavbarLayout')

@section('title', 'Voucher List')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Voucher List') }}</span>
                </div>

                <div class="card-body">
                    <div class="card">
                        @include('alerts.alert')
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
                                        <th>DATE</th>
                                        <th>Debit | Credit (Head)</th>
                                        <th>VOUCHER TYPE</th>
                                        <th>AMOUNT</th>
                                        <!-- <th>Attachment</th> -->
                                        <!-- <th>Status</th> -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    
                                    @forelse($vouchers as $key => $item)
                                        <tr>
                                            <td>{{ $vouchers->firstItem() + $key }}</td>
                                            <td>{{ $item->voucher_no }}</td>
                                            <td>{{ $item->date }}</td>
                                            <td>
                                                @foreach($item->MasterVoucher as $key1 => $item1)
                                                {{ $item1->DebitLedger->name }} |
                                                {{ $item1->CreditLedger->name }}
                                                <br>
                                                @endforeach
                                            </td>
                                            <td>{{ $item->VoucherType->voucher_name }}</td>
                                            <td>{{ $item->total_amount }}</td>
                                            <!-- <td>
                                                @if($item->attachment)
                                                <a target="_blank" href="/storage/{{$item->attachment}}">File</a>
                                                @endif
                                            </td> -->
                                            <!-- <td>
                                                @if($item->is_tally_synced == 0) <span class="badge bg-primary">Initialize</span>
                                                @elseif($item->is_tally_synced == 1) <span class="badge bg-info">Approved</span>
                                                @else($item->is_tally_synced == 2) <span class="badge bg-success">Updated</span>
                                                @endif
                                            </td> -->
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                                    <ul class="dropdown-menu">
                                                        
                                                            <li><a class="dropdown-item" href="{{url('/voucher/details/'.$item->id)}}">Details</a></li>
                                                       
                                                        <li><a class="dropdown-item d-none" href="javascript:void(0);">Edit</a></li>
                                                        @can('receipt_delete')
                                                            @if($item->is_tally_synced != 2)
                                                                <li>
                                                                    <a class="dropdown-item" href="{{url('/voucher/edit/'.$item->id)}}">Edit</a>
                                                                </li>
                                                                <li>
                                                                    <a target="_blank" onclick="return confirm('Are you want to Delete?');" class="dropdown-item" href="{{url('/voucher/delete/'.$item->id)}}">Delete</a>
                                                                </li>
                                                            @endif
                                                        @endCan
                                                    </ul>
                                                  </div>
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
                                {{ $vouchers->withQueryString()->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
