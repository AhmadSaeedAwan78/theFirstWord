@endsection@extends (($user_type == 'admin')?('admin.layouts.admin_app'):('admin.client.client_app'))

@section('content')
<?php if ($user_type == 'admin'): ?>
<div class="app-title">
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i>
        </li>
        <li class="breadcrumb-item"><a href="{{route('asset_list')}}">{{ __('Assets') }}</a>
        </li>
    </ul>
</div>
<?php endif; ?>
<div class="row" style="margin-left:10px;">
  <div class="col-md-12">
    <div class="tile">
        <div class="table-responsive cust-table-width">
            @if(Session::has('message'))
                <p class="alert alert-info">{{ Session::get('message') }}</p>
            @endif
            <h3 class="tile-title">{{ __('Assets') }}</h3>
       </div>
    </div>
  </div>
</div>


<h1>{{ __('well') }}</h1>



@endsection