@extends( 'admin.layouts.admin_app' )

@section( 'content' )

<style>
	.uploadcare--jcrop-holder>div>div, #preview {

			}

	.uploadcare--menu__item_tab_facebook, .uploadcare--menu__item_tab_gdrive, .uploadcare--menu__item_tab_gphotos, .uploadcare--menu__item_tab_dropbox, .uploadcare--menu__item_tab_instagram, .uploadcare--menu__item_tab_evernote, .uploadcare--menu__item_tab_flickr, .uploadcare--menu__item_tab_onedrive, .uploadcare--dialog__powered-by {
	display: none !important;
}

	

.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

#size{
    margin-left: 8px;
    font-size: 18px;
    width: 168px;
    padding: 3px;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.size{
    margin-left: 58px;
    width: 168px;
    padding: 3px;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

#size{
    margin-left: 8px;
    font-size: 18px;
    width: 168px;
    padding: 3px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<?php
$cat_title = DB::table('categories')->where('id', $user->category_id)->first();
$title = DB::table('items')->where('id', $user->id)->first();
?>
<div class="app-title">
	<ul class="app-breadcrumb breadcrumb">
		<li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i>
		</li>
		<li class="breadcrumb-item"><a href="{{url('items/'.$user->category_id)}}">{{ $cat_title->name }} </a>
		</li>
		<li class="breadcrumb-item"><a href="{{url('/edit_item/'.$user->id)}}">Update {{ $cat_title->name }} Item</a>
		</li>		
	</ul>
</div>

@if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
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
<div class="row">
<?php
?>
	<div class="col-md-12">

		<form class="form-horizontal" method="POST" action="{{ url('update_item/'. $user->id) }}" autocomplete="off" enctype="multipart/form-data">

			{{ csrf_field() }}

			<div class="tile">

				<h3 class="tile-title">Update {{ $title->name }} </h3>

					<div class="row">

						<div class="col-sm-6 col-md-6">

							<div class="form-group">

								<label class="form-control-label">{{ __('Age') }}</label>

								<input type="text" class="form-control" name="age" value="{{ $user->age }}" required autofocus>

							</div>

						</div>
            <div class="col-sm-6 col-md-6">

							<div class="form-group">

								<label class="form-control-label">{{ __('Name') }}</label>

								<input type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>

							</div>

						</div>

            <div class="col-sm-6 col-md-6">

              <div class="form-group">
            
                <label class="form-control-label">{{ __('Image') }}</label>
            
                <input type="file" class="form-control" name="image" accept="image/x-png,image/gif,image/jpeg">
            
              </div>
            
            </div>

            <div class="col-sm-6 col-md-6">

              <div class="form-group">
            
                <label class="form-control-label">{{ __('Sound') }}</label>
            
                <input type="file" class="form-control" name="sound" value="{{ $user->sound }}" autofocus>
            
              </div>
            
            </div>


            @if($user->image)
          <div class="col-sm-6 col-md-6">
            <img src="{{asset('assets/items/'.$user->image)}}" height="125" width="125">
          </div>
            @endif

            @if($user->sound != null)
            <audio controls>
              <source src="{{asset('assets/sound/'.$user->sound)}}" type="audio/mp3">
            //	<source src="horse.mp3" type="audio/mpeg">
            </audio> 
          @endif
            
            

					<input id="file" type="hidden" class="form-control" name="category_id" value="{{$user->category_id}}">

					<div class="tile-footer col-sm-12 text-right">

						<a href="{{url('items/'.$user->category_id)}}" class="btn btn-default" style="border: solid 1px;">@lang('general.cancel')</a>

						<button type="submit" class="btn btn-primary">{{ __('Update') }}</button>

					</div>

				</form>

			</div>

		</form>

	</div>

</div>

<!-- croper model -->
	<div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">
						</h4>
					</div>
					<div class="modal-body">
						<div id="upload-demo" class="center-block"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
						<button type="button" id="cropImageBtn" onclick="abc()" class="btn btn-primary">{{ __('Crop') }}</button>
					</div>
				</div>
			</div>
		</div>





<script>
	// Getting an instance of the widget.
const widget = uploadcare.Widget('[role=uploadcare-uploader]');
// Selecting an image to be replaced with the uploaded one.
const preview = document.getElementById('preview');
// "onUploadComplete" lets you get file info once it has been uploaded.
// "cdnUrl" holds a URL of the uploaded file: to replace a preview with.
widget.onUploadComplete(fileInfo => {
  preview.src = fileInfo.cdnUrl;
  // alert(fileInfo.cdnUrl);

  const toDataURL = url => fetch(url)
  .then(response => response.blob())
  .then(blob => new Promise((resolve, reject) => {
    const reader = new FileReader()
    reader.onloadend = () => resolve(reader.result)
    reader.onerror = reject
    reader.readAsDataURL(blob)
  }))


toDataURL(fileInfo.cdnUrl)
  .then(dataUrl => {
    // console.log(dataUrl)
    preview.src = dataUrl
    $('#destination').val(dataUrl);
  })

})




</script>





@endsection