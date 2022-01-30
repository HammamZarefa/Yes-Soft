@extends('layouts.admin')

@section('styles')
<style>
.picture-container {
  position: relative;
  cursor: pointer;
  text-align: center;
}
 .picture {
  width: 300px;
  height: 400px;
  background-color: #999999;
  border: 4px solid #CCCCCC;
  color: #FFFFFF;
  /* border-radius: 50%; */
  margin: 5px auto;
  overflow: hidden;
  transition: all 0.2s;
  -webkit-transition: all 0.2s;
}
.picture:hover {
  border-color: #2ca8ff;
}
.picture input[type="file"] {
  cursor: pointer;
  display: block;
  height: 100%;
  left: 0;
  opacity: 0 !important;
  position: absolute;
  top: 0;
  width: 100%;
}
.picture-src {
  width: 100%;
  height: 100%;
}
</style>

@endsection

@section('content')

@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<form action="{{ route('admin.service.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="container">
        
        <div class="form-group">
            <div class="picture-container">

                <div class="picture">

                    <img src="" class="picture-src" id="wizardPicturePreview" height="200px" width="400px" title=""/>

                    <input type="file" id="wizard-picture" name="icon" class="form-control {{$errors->first('icon') ? "is-invalid" : "" }} ">

                    <div class="invalid-feedback">
                    {{ $errors->first('icon') }}    
                    </div>  

                </div>
                <h6>Choose Photo</h6>
            </div>
        </div>

        {{-- <div class="form-group ml-5">

            <label for="icon" class="col-sm-2 col-form-label">Icon</label>

            <div class="col-sm-9">

                <input type="text" name='icon' class="form-control {{$errors->first('icon') ? "is-invalid" : "" }} " value="{{old('icon')}}" id="icon" placeholder="example: icofont-map">

                <div class="invalid-feedback">
                    {{ $errors->first('icon') }}    
                </div>   

            </div>
           
            <a href="https://icofont.com/icons" target="_blank" rel="noopener noreferrer">
           
                <span class="col-sm-2 col-form-label" style="color: blue">https://icofont.com/icons</span>
        
            </a>

        </div> --}}

        <div class="form-group ml-5">

            <label for="title" class="col-sm-2 col-form-label">Title</label>

            <div class="col-sm-9">

                <input type="text" name='title' class="form-control {{$errors->first('title') ? "is-invalid" : "" }} " value="{{old('title')}}" id="title" placeholder="Title">

                <div class="invalid-feedback">
                    {{ $errors->first('title') }}    
                </div>   

            </div>

        </div>

        <div class="form-group ml-5">

            <label for="quote" class="col-sm-2 col-form-label">Quote</label>

            <div class="col-sm-9">

                <input type="text" name='quote' class="form-control {{$errors->first('quote') ? "is-invalid" : "" }} " value="{{old('quote')}}" id="quote" placeholder="Quote">

                <div class="invalid-feedback">
                    {{ $errors->first('quote') }}    
                </div>   

            </div>

        </div>

        <div class="form-group ml-5">

            <label for="desc" class="col-sm-2 col-form-label">Desc</label>

            <div class="col-sm-9">

                {{-- <input type="text" class="form-control" id="title" placeholder="Title"> --}}

                <textarea name="desc" id="summernote" cols="40" rows="10"  class="form-control {{$errors->first('desc') ? "is-invalid" : "" }} ">{{old('desc')}}</textarea>

                <div class="invalid-feedback">
                    {{ $errors->first('desc') }}    
                </div> 

            </div>   
            
             
   
        </div>
   
        <div class="form-group ml-5">
   
            <div class="col-sm-3">
   
                <button type="submit" class="btn btn-primary">Create</button>
   
            </div>
   
        </div>

    </div>      

  </form>
@endsection

<script>
    // Prepare the preview for profile picture
    $("#wizard-picture").change(function(){
      readURL(this);
  });
  //Function to show image before upload
function readURL(input) {
  if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
          $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
      }
      reader.readAsDataURL(input.files[0]);
  }
}
</script>