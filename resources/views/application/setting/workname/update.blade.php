@extends('layouts/contentNavbarLayout')

@section('title', 'New Work Name')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Create Work Name') }}</span> 
                    <button class="btn btn-secondary float-left" onclick="history.back()">Go Back</button>
                </div>
                <div class="card-body">
                    <div class="row">
                    <div class="col-xxl">
                        <div class="card mb-12">
                        <div class="card-header d-flex align-items-center justify-content-between">
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{url('/setting/work-name/update/'.$data->id)}}">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="row">
                                            <label class="col-sm-2 col-form-label" for="debit_head">Ledger</label>
                                            <div class="col-sm-9">
                                                <select type="text" id="debit_head" class="form-control js-example-basic-single" name="debit_head" placeholder="Enter Base units" aria-label="Enter Group Name" aria-describedby="debit_head">
                                                    <option>--Select Group--</option>
                                                    @foreach($ledgers as $key => $item)
                                                    <option value="{{$key}}" {{$key == $data->debit_head?'selected':''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label" for="name">Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter EN Name" value="{{$data->name}}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label" for="name_bn">Name BN</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="name_bn" name="name_bn" placeholder="Enter BN Name" value="{{$data->name_bn}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                
                                <div class="row">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you want to proceed?')">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function subGroups(){
        var main_group_id = $('#main_group_id').val();

        if(main_group_id){
            var url = "{{url('/sub-groups/')}}/"+main_group_id;
            $('#sub_group_id').empty().append('<option value="">-- Select Work Name --</option>');
            const data = fetch(url)
            .then(response => response.json())
            .then(data => {
                $.each(data, function(id, name){
                    $('#sub_group_id').append(`<option value="${id}">${name}</option>`);
                });
            })
            // .catch(error => console.error('Error:', error));
            // alert('G'+data);
        }
    }

    function areas(){
        var region_id = $('#region_id').val();

        if(region_id){
            var url = "{{url('/areas/')}}/"+region_id;
            $('#area_id').empty().append('<option value="">-- Select Area --</option>');
            const data = fetch(url)
            .then(response => response.json())
            .then(data => {
                $.each(data, function(id, name){
                    $('#area_id').append(`<option value="${id}">${name}</option>`);
                });
            })
        }
    }

    function terriitorys(){
        var area_id = $('#area_id').val();

        if(area_id){
            var url = "{{url('/terriitorys/')}}/"+area_id;
            $('#territory_id').empty().append('<option value="">-- Select Area --</option>');
            const data = fetch(url)
            .then(response => response.json())
            .then(data => {
                $.each(data, function(id, name){
                    $('#territory_id').append(`<option value="${id}">${name}</option>`);
                });
            })
        }
    }

$(document).ready(function(){
    // Country -> State
    $('#main_group_id').on('change', function(){
        alert('HI');
        let main_group_id = $(this).val();
        $('#sub_group_id').empty().append('<option value="">-- Select Work Name --</option>');

        if(main_group_id){
            $.get('/sub-groups/' + main_group_id, function(data){
                $.each(data, function(id, name){
                    $('#sub_group_id').append(`<option value="${id}">${name}</option>`);
                });
            });
        }
    });

    // State -> City
    $('#state').on('change', function(){
        let state_id = $(this).val();
        $('#city').empty().append('<option value="">-- Select City --</option>');

        if(state_id){
            $.get('/cities/' + state_id, function(data){
                $.each(data, function(id, name){
                    $('#city').append(`<option value="${id}">${name}</option>`);
                });
            });
        }
    });

});
</script>
@endsection
