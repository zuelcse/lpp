@extends('layouts/contentNavbarLayout')

@section('title', 'Ledger')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Ledger') }}</span>
                    <a href="{{url('/ledger/create')}}" class="btn btn-primary float-left">
                        <box-icon type='solid' name='plus-square'></box-icon>
                        New
                    </a> 
                </div>


                <div class="card-body">
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table" style="font-size: 0.8em">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <!-- <th>Opening Balance</th> -->
                                        <!-- <th>Credit Limit</th> -->
                                        <th>Address</th>
                                        <!-- <th>Mobile</th> -->
                                        <!-- <th>Closing Balance</th> -->
                                        <th>Group</th>
                                        <th>Sub Group</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    
                                    @forelse($ledgers as $key => $item)
                                        <tr>
                                            <td>{{ $ledgers->firstItem() + $key }}</td>
                                            <td>{{ $item->alias }}</td>
                                            <td>{{ $item->name }}</td>
                                            <!-- <td>{{ $item->opening_balance }}</td> -->
                                            <!-- <td>{{ $item->credit_limit }}</td> -->
                                            <td>{{ $item->address }}</td>
                                            <!-- <td>{{ $item->mobile }}</td> -->
                                            <!-- <td>{{ $item->closing_balance }}</td> -->
                                            <td>{{ $item->Group->alias.'-'.$item->Group->name }}</td>
                                            <td>{{ $item->Subgroups?->alias.'-'.$item->Subgroups?->name }}</td>
                                            <td class="flex items-center justify-content-between">
                                                <a class="btn btn-sm btn-primary" href="{{url('ledger/update/'.$item->id)}}" onclick="return confirm('Are you sure you want to update this Ledger?')">Edit</a>
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
                                {{ $ledgers->withQueryString()->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
