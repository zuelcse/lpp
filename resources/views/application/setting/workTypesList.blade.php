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
                                    <tr class="text-center">
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Name BN</th>
                                        <th>Variation</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    
                                    @forelse($data as $key => $item)
                                        <tr>
                                            <td>{{ $data->firstItem() + $key }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->name_bn }}</td>
                                            <td style="font-size:10px;">
                                                @foreach($item->sizes as $keys => $items)
                                                {{ $items->name_bn }} <br>
                                                @endforeach

                                                @foreach($item->colors as $keyc => $itemc)
                                                {{ $itemc->name_bn }} <br>
                                                @endforeach

                                                @foreach($item->weights as $keyw => $itemw)
                                                {{ $itemw->name_bn }} <br>
                                                @endforeach

                                                @foreach($item->papers as $keyp => $itemp)
                                                {{ $itemp->name_bn }} <br>
                                                @endforeach

                                                @foreach($item->laminations as $keyl => $iteml)
                                                {{ $iteml->name_bn }}<br>
                                                @endforeach
                                            </td>
                                            <td class="flex items-center text-center">
                                                <a class="btn btn-success" href="{{url('setting/work-types/edit?id='.$item->id)}}" onclick="return confirm('Are you sure you want to update this Work Type?')">
                                                    <box-icon type='solid' name='edit'></box-icon> 
                                                Assign
                                            </a>
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


    

<div class="modal fade" id="workTypeModal">
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

            <button type="submit" class="btn btn-success" onclick="return confirm('Are you want to proceed?')">Save</button>
        </form>
      </div>

    </div>
  </div>
</div>

@endsection


@push('scripts')
<script>
    console.log("JS Loaded ✅");
    $('#workTypeForm').on('submit', function(e){
    e.preventDefault();

    $.ajax({
        url: "{{ route('setting-work-type-create') }}",
        method: "POST",
        data: $(this).serialize(),
        success: function(res){
            alert(res.message);
            $('#workTypeModal').modal('hide');
            $('#workTypeForm')[0].reset();
        },
        error: function(err){
            console.log(err.responseJSON);
        }
    });
});
</script>
@endpush
