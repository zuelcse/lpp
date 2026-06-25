@extends('layouts/contentNavbarLayout')

@section('title', 'Work Type')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Work Type') }}</span>
                    <!-- <a href="{{url('/setting/work-type/create')}}" class="btn btn-primary float-left" >
                        <box-icon type='solid' name='plus-square'></box-icon>
                        New
                    </a>  -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#workTypeModal">
                        <box-icon type='solid' name='plus-square'></box-icon>
                        New
                    </button>
                </div>


                <div class="card-body">
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table" class="font-size: 0.7em">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Name BN</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    
                                    @forelse($data as $key => $item)
                                        <tr>
                                            <td>{{ $data->firstItem() + $key }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->name_bn }}</td>
                                            <td class="flex items-center justify-content-between">
                                                <a class="btn btn-success" href="{{url('setting/subgroup/update/'.$item->id)}}" onclick="return confirm('Are you sure you want to update this Work Type?')">Edit</a>
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


    

<!-- <div class="modal fade" id="workTypeModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5>Add Work Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form action="{{ route('setting-work-type-create') }}">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control">
            </div>

            <div class="mb-3">
                <label>Name (Bangla)</label>
                <input type="text" name="name_bn" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Save</button>
        </form>
      </div>

    </div>
  </div>
</div> -->

@endsection
