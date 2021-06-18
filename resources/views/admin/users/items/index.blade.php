@extends( 'admin.layouts.admin_app' )
@section( 'content' )
<style>
    .no-txt-transform {
        text-transform: none;
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">
<?php
$title = DB::table('categories')->where('id', $id)->first();
?>
<div class="app-title">

	<ul class="app-breadcrumb breadcrumb">
		<li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i>
		</li>
		<li class="breadcrumb-item"><a href="{{url('items/'.$id)}}">{{ $title->name }} </a>
		</li>
	</ul>
</div>

@if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
@endif



<div class="row">
	<div class="col-md-12">
		<div class="tile">
			<h3 class="tile-title">{{ $title->name }} 			
				<a href="{{url('add_item/'. $id)}}" class="btn btn-sm btn-primary pull-right cust_color"><i class="fa fa-plus" ></i> {{ __('Add New Item') }}</a>
			</h3>
			<div class="table-responsive">
				<table class="table" id="org-users">
					<thead class="back_blue">
						<tr>
							<th>{{ __('ID') }}</th>
							<th>{{ __('Age') }}</th>
							<th>{{ __('Name') }}</th>
							<th>{{ __('Image') }}</th>
							<th>{{ __('Sound') }}</th>
							<th class="text-center">{{ __('Actions') }}</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$count = 0;
						?>
						@if($data != null)
						@foreach($data as $row)
						<?php
						$count ++;
						?>
						<tr>
							<td class="no-txt-transform"> {{$count}} </td>
							<td class="no-txt-transform"> {{$row->age}}</td>
							<td class="no-txt-transform">{{$row->name}}</td>
							<td class="no-txt-transform">
								<img src="{{asset('assets/items/'.$row->image)}}" height="50" width="100"> 
							</td>
							<td class="no-txt-transform">
								<source src="{{asset('assets/items/sounds'.$row->sound)}}" height="50" width="100" type="audio/mp3">
									<audio controls>
										<source src="{{asset('assets/sound/'.$row->sound)}}" type="audio/mp3">
									</audio> 
							</td>
							<td class="text-center">
								<div class="actions-btns dule-btns">
									<a href="{{url('edit_item/' . $row->id)}}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
									<a href="javascript:void(0)" data-id="<?php echo $row->id; ?>" class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i></a>
								</div>
							</td>
						</tr>
						@endforeach
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.24.1/dist/sweetalert2.all.min.js"></script>

<script type="text/javascript">

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
  
  </script>

<script type="text/javascript">
    $("body").on( "click", ".delete", function () {
    var task_id = $( this ).attr( "data-id" );
    console.log(task_id);
    var form_data = {
    id: task_id
    };
	
    swal({
    title: "Do you want to delete this Item?",

    type: 'info',
    showCancelButton: true,
    confirmButtonColor: '#F79426',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes',
    showLoaderOnConfirm: true
    }).then( ( result ) => {
    if ( result.value == true ) {
    $.ajax( {
    type: 'POST',

	url: '<?php echo url("delete_item"); ?>', 
    data: form_data,
    success: function ( msg ) {
    swal( "@lang('Item Deleted')", '', 'success' )
    setTimeout( function () {
    location.reload();
    }, 500 );
    }
    } );
    }
    } );
    } );
  </script>

<style>
	.sweet-alert h2 {
		font-size: 1.3rem !important;
	}
	
	.sweet-alert .sa-icon {
		margin: 30px auto 35px !important;
	}
</style>

@endsection