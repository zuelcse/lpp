@extends('layouts/contentNavbarLayout')

@section('title', 'Stock Item')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Stock Item') }}</span>
                    <a href="{{url('/stockitem/create')}}" class="btn btn-primary float-left">
                        <box-icon type='solid' name='plus-square'></box-icon>
                        New
                    </a>  
                </div>


                <div class="card-body">
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Group</th>
                                    <th>Manufecturing</th>
                                    <th>Name</th>
                                    <th>Units</th>
                                    <th>Sales Rate</th>
                                    <!-- <th>Purchase Rate</th> -->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                
                                @forelse($stockItem as $key => $item)
                                <tr>
                                    <td>{{ $stockItem->firstItem() + $key }}</td>
                                    <td>{{$allGroup[$item->category]}}</td>
                                    <td>{{$allManufacturer[$item->manufacturer]}}</td>
                                    <td>{{$item->alias??''}}|{{$item->name}}</td>
                                    <td>{{$allUnits[$item->unit]}}</td>
                                    <td>{{$item->salesRate}}</td>
                                    <!-- <td>{{$item->purchaseRate}}</td> -->
                                    <td class="flex items-center justify-content-between">
                                        <a class="btn btn-success" href="{{url('stockitem/update/'.$item->id)}}" onclick="return confirm('Are you want to proceed?')">Edit</a>
                                        <!-- <button class="btn btn-info" onClick="syncStockItemToTally({{$item->id}})">Sync To Tally</button> -->
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
                        {{ $stockItem->withQueryString()->links() }}
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
