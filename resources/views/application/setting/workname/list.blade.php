@extends('layouts/contentNavbarLayout')

@section('title', 'Work Name')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Work Name') }}</span>
                    <a href="{{url('/setting/work-name/create')}}" class="btn btn-primary float-left">
                        <box-icon type='solid' name='plus-square'></box-icon>
                        New
                    </a> 
                </div>


                <div class="card-body">
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table" class="font-size: 0.7em">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Group</th>
                                        <th>Name</th>
                                        <th>Name BN</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    
                                    @forelse($data as $key => $item)
                                        <tr>
                                            <td>{{ $data->firstItem() + $key }}</td>
                                            <td>{{  $item->Ledger->name }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->name_bn }}</td>
                                            <td class="flex items-center justify-content-between">
                                                <a class="btn btn-success" href="{{url('setting/work-name/update/'.$item->id)}}" onclick="return confirm('Are you sure you want to update this Work Name?')">Edit</a>
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
                                {{ $data->withQueryString()->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
