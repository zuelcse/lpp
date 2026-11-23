@extends('layouts/contentNavbarLayout')

@section('title', 'Sub Group Update')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Sub Group Update') }}</span> 
                    <button class="btn btn-secondary float-left" onclick="history.back()">Go Back</button>
                </div>
                <div class="card-body">
                    <div class="row">
                    <div class="col-xxl">
                        <div class="card mb-12">
                        <div class="card-header d-flex align-items-center justify-content-between">
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{url('/setting/subgroup/update/'.$subgroup->id)}}">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="row mb-1">
                                            <label class="col-sm-3 col-form-label" for="main_group_id">Main Group</label>
                                            <div class="col-sm-9">
                                                <select type="text" id="main_group_id" class="form-control js-example-basic-single" name="main_group_id"  aria-label="Enter Group Name" aria-describedby="main_group_id">
                                                    <option>--Select Group--</option>
                                                    @foreach($allGroup as $key => $item)
                                                    <option value="{{$key}}" {{$key == $subgroup->main_group_id?'selected':''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row mb-1">
                                            <label class="col-sm-3 col-form-label" for="name">Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter EN Name" value="{{$subgroup->name}}" autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row mb-1">
                                            <label class="col-sm-3 col-form-label" for="is_active">Status</label>
                                            <div class="col-sm-9">
                                                <select type="text" id="is_active" class="form-control js-example-basic-single" name="is_active"  aria-label="Enter Group Name" aria-describedby="is_active">
                                                    <option value="1" {{$subgroup->is_active==1?'selected':''}}>Enabled</option>
                                                    <option value="0" {{$subgroup->is_active==0?'selected':''}}>Disabled</option>
                                                </select>
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

@endsection
