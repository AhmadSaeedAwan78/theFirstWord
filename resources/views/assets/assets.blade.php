@extends (($user_type == 'admin')?('admin.layouts.admin_app'):('admin.client.client_app'))

@section('content')
@if(isset($data))
    <div class="row" style="margin-left:10px;">
      <div class="col-md-12">
        <div class="tile">
            <div class="table-responsive cust-table-width">
                @if(Session::has('message'))
                    <p class="alert alert-info">{{ Session::get('message') }}</p>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <h3 class="tile-title">{{ __('Assets Edit') }}</h3>
           </div>
        </div>
      </div>
    </div>
    <div class="container">
        <!-- <form action="{{route('update_asset')}}" method="POST" enctype="multipart/form-data"> -->
        <form action="{{route('update_asset')}}" onsubmit="return get_location_assetsz();" method="POST" enctype="multipart/form-data" id="add_asset_locz">

        {{ csrf_field() }}
     <div class="form-group">
          <label for="sel1">{{ __('Asset type') }}<span class="red">*</span></label>
          <select class="form-control" name="asset_typez" required id="sel1">
            <option value="{{$data->asset_type}}">{{$data->asset_type}}</option>
            <option>{{ __('Application') }}</option>
            <option>{{ __('Database') }}</option>
            <option>{{ __('Physical Storage') }}</option>
            <option>{{ __('Website') }}</option>
            <option>{{ __('Other') }}</option>
          </select>
     </div>
     <input type="hidden" name="id" value="{{$data->id}}">
     <div class="form-group">
        <label>{{ __('Name') }}<span class="red">*</span></label>
        <input type="text" name="namez" value="{{$data->name}}" class="form-control" required disabled>
     </div>


     <!-- <div class="form-group">
        <label>{{ __('Hosting Type') }}<span class="red">*</span></label>
        <input type="text" name="hosting_typez" value="{{$data->hosting_type}}" class="form-control" required >
     </div> -->


      <div class="form-group">
        <div class='input-field'>
        <label>{{ __('Hosting Type') }}<span class="red">*</span></label>

            <select  class="form-control" required name='hosting_typez'>
               <option value="Cloud">{{ __('Cloud') }}</option>
               <option value="On-Premise">{{ __('On-Premise') }}</option>
               <option value="Not Sure">{{ __('Not Sure') }}</option>
               <option value="Hybrid">{{ __('Hybrid') }}</option>

            </select>
        </div>
     </div>



     <div class="form-group">
        <label>{{ __('Hosting Provider') }} </label>
        <input type="text" name="hosting_providerz" value="{{$data->hosting_provider}}" class="form-control" required >
     </div>
     <div class="form-group">
        <div class='input-field'>
            <label for='country'>{{ __('Country') }}<span class="red">*</span></label>
            <select id='country_selectz' class="form-control" required name='countryz'>
                @if(isset($cont[0]->country_name))
                    <option value="{{$cont[0]->country_name}}">{{$cont[0]->country_name}}</option>
                @endif
                @foreach($countries as $country)
                    <option>{{$country->country_name}}</option>
                @endforeach
            </select>
        </div>
     </div>
     <div class="form-group">
        <label>{{ __('City') }} </label>
        <input type="text" id="citiz" name="cityzz" value="{{$data->city}}" class="form-control"  >
     </div>
     <div class="form-group">
        <label>{{ __('State') }}/{{ __('Province') }} </label>
        <input type="text" name="statez" value="{{$data->state}}" class="form-control"  >
     </div>
          <input type="hidden" id="latituedez" name="latz" class="form-control">
          <input type="hidden" id="langutitudez" name="lngz" class="form-control">
     <div class="update_btn text-right">
         <input class="btn btn-primary mb-3" type="submit" name="submit" value="Update">
     </div>
    </form>
    </div>
@else
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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <h3 class="tile-title">{{ __('Assets') }}</h3>
            <div  class="d-flex justify-content-end mb-2 mr-4">
                <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">{{ __('Add More') }}</button>
                <!-- <div class="col col-md-4">
                    <input type="text" id="asset" class="form form-control">
                </div>
                <button id="add" class="btn btn-primary">Add</button> -->
            </div>                

            <table class="table" id="assets-table">
                <thead class="back_blue">
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">{{ __('Asset type') }}</th>
                    <th scope="col">{{ __('Asset Name') }}</th>
                    <th scope="col">{{ __('Hosting Type') }}</th>
                    <th scope="col">{{ __('Hosting Provider') }}</th>
                    <th scope="col">{{ __('Country') }}</th>
                    <th scope="col">{{ __('City') }}</th>
                    <th scope="col">{{ __('State') }}</th>
                    <?php if (Auth::user()->role == '1'): ?>
                    <th scope="col">{{ __('Actions') }}</th>
                    <?php endif; ?>
                    <?php if (Auth::user()->role != '1'): ?>
                    <th scope="col">{{ __('Actions') }}</th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
  	            <?php foreach ($asset_list as $key => $asset): ?>
                <tr>
                    <td>{{ $key + 1 }}</td>
                    
                    <td  class='spocNames'>{{ $asset->asset_type}}</td>
                    <td  class='spocNames'>{{ $asset->name }}</td>
                    <td  class='spocNames'>{{ $asset->hosting_type }}</td>
                    <td  class='spocNames'>{{ $asset->hosting_provider }}</td>
                    <td  class='spocNames'>{{ $asset->country }}</td>
                    <td  class='spocNames'>{{ $asset->city }}</td>
                    <td  class='spocNames'>{{ $asset->state }}</td>
                    <td>
                    <!-- <td><a class="delete-asset" href=""  asset-id="{{$asset->id}}"><i class="fas fa-trash-alt"></i> Delete</a> -->
                        <!-- <a class="delete_data"  onclick="return confirm('Are you sure, You want to Delete this record..!')" href="{{url('asset_delete/' . $asset->id)}}"><i class="fas fa-trash"></i>Delete</a> -->
                        <a href="{{url('asset_edit/' . $asset->id)}}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0)" data-id="{{$asset->id}}" class="btn btn-sm btn-danger removePartner"><i class="fa fa-trash"></i></a>
                        <!-- &nbsp;&nbsp;<a ><i class="fas fa-edit"></i>Edit</a> -->
                        <!-- &nbsp;&nbsp;<a class="edit_data" href="" value="my_name" id="{{ $asset->name }}" onclick='update_asset(this.id)' asset-id="{{}}"  data-toggle="modal" data-target="#edit_modal"  ><i class="fas fa-edit"></i> Edit</a></td> -->
                </tr>
	            <?php endforeach; ?>
                </tbody>
            </table>
       </div>
    </div>
  </div>
</div>



<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">{{ __('Add Asset') }}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        {{--  @if(Session::has('message'))
          <p class="alert alert-success">{{ Session::get('message') }}</p>
        @endif  --}}
        <form action="{{route('asset_add')}}" onsubmit="return get_location_assets();" method="POST" enctype="multipart/form-data" id="add_asset_loc">
        <!-- <form onsubmit="get_location_assets()" method="POST" enctype="multipart/form-data"> -->
            {{ csrf_field() }}
            @foreach ($errors->all() as $error)
              <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
         <!-- <div class="form-group">
            <label>{{ __('Asset type') }}<span class="red">*</span></label>
            <input type="file" name="image" class="form-control" style="padding: 3px;">
         </div> -->
         <div class="form-group">
          <label for="sel1">{{ __('Asset type') }}<span class="red">*</span></label>
          <select class="form-control" required id="sel1" name="asset_type">
            <option>{{ __('Application') }}</option>
            <option>{{ __('Database') }}</option>
            <option>{{ __('Physical Storage') }}</option>
            <option>{{ __('Website') }}</option>
            <option>{{ __('Other') }}</option>
          </select>
        </div>
         <div class="form-group">
            <label>{{ __('Name') }}<span class="red">*</span></label>
            <input type="text" id="name1" name="name" class="form-control" required >
            @if($errors->has('name'))
        @endif
         </div>
         <!-- <div class="form-group">
            <label>{{ __('Hosting Type') }}<span class="red">*</span></label>
            <input type="text" id="hosting_type1" name="hosting_type" class="form-control" required >
         </div> -->

          <div class="form-group">
        <div class='input-field'>
        <label>{{ __('Hosting Type') }}<span class="red">*</span></label>

            <select  class="form-control" required id="hosting_type1" name='hosting_type'>
               <option value="Cloud">{{ __('Cloud') }}</option>
               <option value="On-Premise">{{ __('On-Premise') }}</option>
               <option value="Not Sure">{{ __('Not Sure') }}</option>
               <option value="Hybrid">{{ __('Hybrid') }}</option>

            </select>
        </div>
     </div>



         <div class="form-group">
            <label>{{ __('Hosting Provider') }} </label>
            <input type="text" id="hosting_provider1" name="hosting_provider" class="form-control" required >
            @if($errors->has('hosting_provider'))
        @endif
         </div>
         <div class="form-group">
            <div class='input-field'>
                <label for='country'>{{ __('Country') }}<span class="red">*</span></label>
              <select id='country_select' class="form-control" required name='country'>
                @foreach($countries as $country)
                <option value="{{$country->country_name}}">{{$country->country_name}}</option>
                @endforeach
              </select>
            </div>
         </div>
         <div class="form-group">
            <label>{{ __('City') }} </label>
            <input type="text" id="city1" name="city" class="form-control"  >
         </div>
         <div class="form-group">
            <label>{{ __('State') }}/{{ __('Province') }} </label>
            <input type="text" id="state1" name="state" class="form-control"  >
         </div>
            <input type="hidden" id="latituede" name="lat" class="form-control">
            <input type="hidden" id="langutitude" name="lng" class="form-control">
         <div>
             <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button> 
             <input class="btn btn-primary" type="submit" name="submit" value="Add">
         </div>
        </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> <input class="btn btn-primary" type="submit" name="submit" value="Add"> -->
      </div>

    </div>
  </div>
</div>


<div id="edit_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <form action="{{route('asset_update')}}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('Assets Edit') }}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label>{{ __('Asset Name') }}</label>
            <input type="text" class="form-control" name="name" id="get_name">
         </div>
      </div>
      <input type="hidden" id="first_name" name="first_name">
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
        <input type="submit" class="btn btn-primary" value="Update">
      </div>
    </div>
    </form>

  </div>
</div>



<script>

    function update_asset(value){
        document.getElementById('get_name').value = value;
        document.getElementById('first_name').value = value;
    }

    $( "body" ).on( "click", ".removePartner", function () {
            var task_id = $( this ).attr( "data-id" );
            var form_data = {
                id: task_id
            };
            swal( {
                    title: "Delete Asset",
                    text: "This operation can not be reversed",
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#F79426',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    showLoaderOnConfirm: true
                },
                function () {
                    $.ajax( {
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                        },
                        url: '<?php echo url("delete_asset"); ?>',
                        data: form_data,
                        success: function ( msg ) {
                            swal( "@lang('users.success_delete')", '', 'success' )
                            setTimeout( function () {
                                location.reload();
                            }, 2500 );
                        }
                    } );
                } );
    
        } );

    $(document).ready(() => {
        
        $('#assets-table').DataTable();

        <?php if (Auth::user()->role == '1'): ?>
        
        <?php endif; ?>                    
        
        // $('#add').click(()=>{
        //     var asset = $('#asset').val();
            
        //     $.ajax({
        //         data:    {'asset':asset},
        //         url:    '{{route('add_asset')}}',
        //         method: 'GET',
        //         success: (response) => {
        //             $('#asset').val('');
        //             console.log(response.status);
        //             if (response.status)
        //             {
        //                 swal({
        //                   title:               response.title,
        //                   text:                response.msg,
        //                   type:                response.status,
        //                   showCancelButton:    false,
        //                   confirmButtonClass: "btn-primary",
        //                   confirmButtonText:  "OK",
        //                   closeOnConfirm:      true
        //                 },
        //                 function(){
        //                     if (response.status == 'success') {
        //                         // location.reload();
        //                     }
        //                 });                        
        //             }
        //             else
        //             {
        //                 swal('Something went wrong', 'The asset could not be added due to some error', 'error');
        //             }
                    
        //         }
                
        //     });            
      
        // });
        

    })
</script>
<script type="text/javascript">

function get_location_assets(){

    var lng;
    get_location(lng);

    var hosting_provider = document.getElementById('hosting_provider1').value;

    return false;

}

function get_location(lng){
    // alert('well');
    window.locationData = [];
    var country_select = document.getElementById('country_select').value;
    var city1 = document.getElementById('city1').value;

    $.ajax({
        url: "https://maps.googleapis.com/maps/api/geocode/json?address="+country_select+"+"+city1+"&key=AIzaSyDaCml5EZAy3vVRySTNP7_GophMR8Niqmg",
        method: "GET",
        success: function (response) {
            window.locationData = response;
            var lat = locationData.results[0].geometry.location.lat;
            var lng = locationData.results[0].geometry.location.lng;
            document.getElementById("latituede").value = lat;
            document.getElementById("langutitude").value = lng;

            document.getElementById("add_asset_loc").removeAttribute("onsubmit");

            $.ajax({
              url: document.getElementById("add_asset_loc").getAttribute("action"),
              method: "POST",
              data: {
                "asset_type": document.getElementById("add_asset_loc").asset_type.value,
                "name": document.getElementById("add_asset_loc").name.value,
                "hosting_type": document.getElementById("add_asset_loc").hosting_type.value,
                "hosting_provider": document.getElementById("add_asset_loc").hosting_provider.value,
                "country": document.getElementById("add_asset_loc").country.value,
                "city": document.getElementById("add_asset_loc").city.value,
                "state": document.getElementById("add_asset_loc").state.value,
                "lat": document.getElementById("add_asset_loc").lat.value,
                "lng": document.getElementById("add_asset_loc").lng.value,
                "_token": document.getElementById("add_asset_loc")._token.value
              },
              success: function ( msg ) {
                            console.log(msg);
                            if(msg.status == 'success'){
                            swal( "New Asset Added Successfully..!" , 'success' )
                          }
                          else{
                             swal( "Asset already exists" , 'error' )
                          }
                            setTimeout( function () {
                                window.location.replace("https://dev.d3grc.com/assets");
                            }, 2500 );
                        }
              
          });

            return(lng);

        }
    })
}
</script>
@endif
<script type="text/javascript">
  function get_location_assetsz(){
    var lng;
    get_locationz(lng);

    return false;

}

function get_locationz(lng){
    // alert('well');
    window.locationData = [];
    var country_selectz = document.getElementById('country_selectz').value;
    var cityz = document.getElementById('citiz').value;

    $.ajax({
        url: "https://maps.googleapis.com/maps/api/geocode/json?address="+country_selectz+"+"+cityz+"&key=AIzaSyDaCml5EZAy3vVRySTNP7_GophMR8Niqmg",
        method: "GET",
        success: function (response) {
            window.locationData = response;
            var lat = locationData.results[0].geometry.location.lat;
            var lng = locationData.results[0].geometry.location.lng;
            document.getElementById("latituedez").value = lat;
            document.getElementById("langutitudez").value = lng;

            document.getElementById("add_asset_locz").removeAttribute("onsubmit");

            $.ajax({

              url: document.getElementById("add_asset_locz").getAttribute("action"),
              method: "POST",
              data: {
                "id": document.getElementById("add_asset_locz").id.value,
                "asset_type": document.getElementById("add_asset_locz").asset_typez.value,
                "name": document.getElementById("add_asset_locz").namez.value,
                "hosting_type": document.getElementById("add_asset_locz").hosting_typez.value,
                "hosting_provider": document.getElementById("add_asset_locz").hosting_providerz.value,
                "country": document.getElementById("add_asset_locz").countryz.value,
                "city": document.getElementById("add_asset_locz").cityzz.value,
                "state": document.getElementById("add_asset_locz").statez.value,
                "lat": document.getElementById("add_asset_locz").latz.value,
                "lng": document.getElementById("add_asset_locz").lngz.value,
                "_token": document.getElementById("add_asset_locz")._token.value
              },
              success: function ( msg ) {
                            swal( "Update Successfully..!" )
                            setTimeout( function () {
                                window.location.replace("https://dev.d3grc.com/assets");
                            }, 2500 );
                        }
              // success: function (response) {

              //     setTimeout( function () {

              //               window.location.replace("https://dev.d3grc.com/assets");

              //           }, 100 );

              // }
          });

        }
    })
}
</script>
@endsection