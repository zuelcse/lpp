@extends('layouts/contentNavbarLayout')

@section('title', 'Sub Group')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Sub Group') }}</span>
                    <a href="{{url('/setting/subgroup/create')}}" class="btn btn-primary float-left">
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
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    
                                    @forelse($subgroups as $key => $item)
                                        <tr>
                                            <td>{{ $subgroups->firstItem() + $key }}</td>
                                            <td>{{ $item->Group->alias.'-'.$item->Group->name }}</td>
                                            <td>{{ $item->alias }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td class="flex items-center justify-content-between">
                                                <a class="btn btn-sm btn-primary" href="{{url('setting/subgroup/update/'.$item->id)}}" onclick="return confirm('Are you sure you want to update this Sub Group?')">Edit</a>
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
                                {{ $subgroups->withQueryString()->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
