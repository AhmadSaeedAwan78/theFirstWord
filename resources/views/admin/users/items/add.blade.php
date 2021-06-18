@extends( 'admin.layouts.admin_app' )
@section( 'content' )

<style>
	
	/*
	label.cabinet{
	display: block;
	cursor: pointer;
	}

	label.cabinet input.file{
		position: relative;
		height: 100%;
		width: auto;
		opacity: 0;
		-moz-opacity: 0;
		  filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);
		  margin-top:-30px;
		}

#upload-demo{
    width: 100%;
    height: 265px;
    padding-bottom: 25px;
	}
	.img-thumbnail{
		/*background-color:#000 !important;*/
	}
	*/

	.uploadcare--jcrop-holder>div>div, #preview {
			  /*border-radius: 50%;*/
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
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.max-spec {
    font-size:14px;
}






</style>
<?php
$title = DB::table('categories')->where('id', $id)->first();
?>
<div class="app-title">
	<ul class="app-breadcrumb breadcrumb">
		<li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i>
		</li>
		<li class="breadcrumb-item"><a href="{{url('items/'.$id)}}">{{ $title->name }} </a>
		</li>
		<li class="breadcrumb-item"><a href="{{url('add_item/'.$id)}}">Add {{ $title->name }}</a>
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

<section class="forms">
	<div class="container-fluid">

		<div class="row">
			<div class="col-lg-12">

				<div class="card">
				    

					<div class="card-header d-flex align-items-center">
						<h3 class="h4"> Add {{ $title->name }} Item</h3>
					</div>
					<div class="card-body" id="org-form">
						<div class="card-body-form">
						<form class="form-horizontal" method="POST" action="{{ url('/store_item') }}" enctype="multipart/form-data" autocomplete="off">
						{{ csrf_field() }}

							<div class="form-group row">
								<label class="col-sm-4 form-control-label">{{ __('age') }}</label>
								<div class="col-sm-8">
									<input id="name" type="text" class="form-control" name="age" value="{{ old('age') }}" required autofocus> 
								</div>
							</div>
							<div class="line"></div>
							<div class="form-group row">
								<label class="col-sm-4 form-control-label">{{ __('Name') }}</label>
								<div class="col-sm-8">
									<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus> 
								</div>
							</div>
							<div class="line"></div>
							<div class="form-group row">
								<label class="col-sm-4 form-control-label">{{ __('Image') }}</label>
								<div class="col-sm-8">
									<input id="name" type="file" class="form-control" name="image" value="{{ old('image') }}" required autofocus> 
								</div>
							</div>
							<div class="line"></div>
							<div class="form-group row">
								<label class="col-sm-4 form-control-label">{{ __('Sound') }}</label>
								<div class="col-sm-8">
									<input id="name" type="file" class="form-control" name="sound" value="{{ old('sound') }}" required autofocus> 
								</div>
							</div>
							<div class="line"></div>

							<input type="hidden" name="category_id" value="{{ $id }}"required autofocus> 
							

							<div class="line"></div>

							<div class="line"></div>	
						
						</div>					
						<button type="submit" class="btn btn-sm btn-primary"> Save</button>
							
							</div>

							
							</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	


</section>
<script>

			// Start upload preview image
                        $(".gambar").attr("src", "<?php  echo  URL::to('/dummy.jpg');  ?>");
						var $uploadCrop,
						tempFilename,
						rawImg,
						imageId;
						function readFile(input) {
				 			if (input.files && input.files[0]) {
				              var reader = new FileReader();
					            reader.onload = function (e) {
									$('.upload-demo').addClass('ready');
									$('#cropImagePop').modal('show');
						            rawImg = e.target.result;
					            }
					            reader.readAsDataURL(input.files[0]);
					        }
					        else {
						        swal("Sorry - you're browser doesn't support the FileReader API");
						    }
						}

						$uploadCrop = $('#upload-demo').croppie({
							viewport: {
								width: 500,
								height: 700,
							},
							enforceBoundary: true,
							enableExif: true
						});
						$('#cropImagePop').on('shown.bs.modal', function(){
							// alert('Shown pop');
							$uploadCrop.croppie('bind', {
				        		url: rawImg
				        	}).then(function(){
				        		console.log('jQuery bind complete');
				        	});
						});

						$('.item-img').on('change', function () { imageId = $(this).data('id'); tempFilename = $(this).val();
							 $('#cancelCropBtn').data('id', imageId); readFile(this); });
						$('#cropImageBtn').on('click', function (ev) {
							$uploadCrop.croppie('result', {
								type: 'base64',
								format: 'jpeg',
								size: {width: 600, height: 800}
							}).then(function (resp) {
								$('#item-img-output').attr('src', resp);
								$('#cropImagePop').modal('hide');
							});
						});
				// End upload preview image
		</script>


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

<script>
	
	$('#add_images').click(function(){
	$('#images').click();
	});

function readURL(input) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#blah').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

$("#images").change(function() {
  readURL(this);
});


  $(document).ready(function() {
    const company = '{{ old('team') }}';
    
    if(company !== '') {
      $('#team').val(company);
    }
  });



</script>

@endsection