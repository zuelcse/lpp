@extends('layouts/contentNavbarLayout')

@section('title', 'Ledger')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Ledger') }}</span>
                </div>


                <div class="card-body">
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table" class="font-size: 0.7em">
                                <thead>
                                    <tr>
                                        <th>DATE</th>
                                        <th>LEDGER NAME</th>
                                        <th>VOUCHER TYPE</th>
                                        <th>COST CENTER</th>
                                        <th>VOUCHER NO</th>
                                        <th>DUE ON</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    
                                    @forelse($orders as $key => $item)
                                    
                                        <tr>
                                            <td>{{ $item->date }}</td>
                                            <td>{{ $item->Ledger->name }}</td>
                                            <td>{{ $item->VoucherType->voucher_name }}</td>
                                            <td>{{ $item->CostCenter->name }}</td>
                                            <td>{{ $item->voucher_no }}</td>
                                            <td>{{ $item->due_on }}</td>
                                            <td></td>
                                        </tr>
                                        
                                    @empty
                                        <tr>
                                            No Item Found.
                                        </tr>
                                    @endforelse


                                </tbody>
                            </table>
                            <div>
                                {{ $orders->withQueryString()->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
