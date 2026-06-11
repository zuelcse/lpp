@extends('layouts/contentNavbarLayout')

@section('title', 'Ledger Update')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Ledger Update') }}</span>
                    <button class="btn btn-secondary float-left" onclick="history.back()">Go Back</button> 
                </div>
                <div class="card-body">
                    <div class="row">
                    <div class="col-xxl">
                        <div class="card mb-12">
                        <div class="card-header d-flex align-items-center justify-content-between">
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{url('/ledger/update/'.$ledger->id)}}">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label" for="main_group_id">Main Group</label>
                                            <div class="col-sm-9">
                                                <select type="text" id="main_group_id" class="form-control js-example-basic-single" name="main_group_id" placeholder="Enter Base units" aria-label="Enter Group Name" aria-describedby="main_group_id" onchange="subGroups()" required>
                                                    <option value="">--Select Group--</option>
                                                    @foreach($allGroup as $key => $item)
                                                    <option value="{{$key}}" {{$key == $ledger->main_group_id?'selected':''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label" for="sub_group_id">Sub Group</label>
                                            <div class="col-sm-9">
                                                <select type="text" id="sub_group_id" class="form-control js-example-basic-single" name="sub_group_id" placeholder="Enter Base units" aria-label="Enter Group Name" aria-describedby="sub_group_id" required>
                                                    <option value="">--Select Sub Group--</option>
                                                    @foreach($allSubGroup as $key => $item)
                                                    <option value="{{$key}}" {{$key == $ledger->sub_group_id?'selected':''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label" for="name">Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter EN Name" value="{{$ledger->name}}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label" for="name_bn">Name BN</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="name_bn" name="name_bn" placeholder="Enter BN Name" value="{{$ledger->name_bn}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label" for="region_id">Region</label>
                                            <div class="col-sm-9">
                                                <select type="text" id="region_id" class="form-control js-example-basic-single" name="region_id" placeholder="Enter Region" aria-label="Enter Region" aria-describedby="region_id" onchange="areas()">
                                                    <option value="">--Select Region--</option>
                                                    @foreach($regions as $key => $item)
                                                    <option value="{{$key}}" {{$key == $ledger->region_id?'selected':''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label" for="area_id">Area</label>
                                            <div class="col-sm-9">
                                                <select type="text" id="area_id" class="form-control js-example-basic-single" name="area_id" placeholder="Enter Area" aria-label="Enter Area" aria-describedby="area_id" onchange="terriitorys()">
                                                    <option value="">--Select Area--</option>
                                                    @foreach($areas as $key => $item)
                                                    <option value="{{$key}}" {{$key == $ledger->region_id?'selected':''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label" for="territory_id">Territory</label>
                                            <div class="col-sm-9">
                                                <select type="text" id="territory_id" class="form-control js-example-basic-single" name="territory_id" placeholder="Enter Territory" aria-label="Enter Territory" aria-describedby="territory_id">
                                                    <option value="">--Select Territory--</option>
                                                    @foreach($terriitorys as $key => $item)
                                                    <option value="{{$key}}" {{$key == $ledger->region_id?'selected':''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label" for="address">Address</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address EN" value="{{$ledger->address}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label" for="address_bn">Address BN</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="address_bn" name="address_bn" placeholder="Enter Address BN" value="{{$ledger->address_bn}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label" for="mobile">Mobile</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile" value="{{$ledger->mobile}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label" for="email">Email</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{$ledger->email}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="bank_account_holder">Bank Account Holder</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="bank_account_holder" name="bank_account_holder" placeholder="Enter EN Name" value="{{$ledger->bank_account_holder}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="bank_account_number">Bank Account Number</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" placeholder="Enter EN Name" value="{{$ledger->bank_account_number}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="bank_name">Bank Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Enter EN Name" value="{{$ledger->bank_name}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="bank_branch">Bank Branch</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="bank_branch" name="bank_branch" placeholder="Enter EN Name" value="{{$ledger->bank_branch}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="credit_limit">Credit Limit</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="credit_limit" class="form-control  " name="credit_limit" placeholder="Enter Credit Limit" aria-label="Enter Credit Limit" aria-describedby="credit_limit" value="{{$ledger->credit_limit}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
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
            $('#sub_group_id').empty().append('<option value="">-- Select Sub Group --</option>');
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
        $('#sub_group_id').empty().append('<option value="">-- Select Sub Group --</option>');

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
