@if(Session::has('error'))
  <div class="alert alert-danger alert-dismissible" role="alert">

    <i class="icon fa fa-ban"></i> {{ __('Alert!') }} :: 
    {{ __(Session::get('error')) }}

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(Session::has('info'))
  <div class="alert alert-info alert-dismissible" role="alert">

    <i class="icon fa fa-info"></i> {{ __('Alert!') }} :: 
    {{ __(Session::get('info')) }}

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(Session::has('warning'))
  <div class="alert alert-warning alert-dismissible">

    <i class="icon fa fa-exclamation-triangle"></i> {{ __('Alert!') }} :: 
    {{ __(Session::get('warning')) }}

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(Session::has('success'))
  <div class="alert alert-success alert-dismissible" role="alert">

    <i class="icon fa fa-check"></i> {{ __('Success!') }} :: 
    {{ __(Session::get('success')) }}
    
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div>
  @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif
</div>