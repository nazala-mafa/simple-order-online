@extends('layout.dashboard')

@section('content-header-right')
  <div class="text-right">
    <a href="javascript:history.back()" class="btn btn-primary">Back</a>
  </div>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <form action="{{ url()->to('/seller/products') }}" enctype="multipart/form-data" method="post">
          @csrf
          <div class="mb-3 row" id="images-wrapper">
            <div class="col-6 col-lg-4 col-xl-3 mt-2">
              <label for="image-1" class="form-label">
                <button type="button" class="btn-primary btn-sm" style="line-height: 1" id="add-img-input-btn">+</button>
                Gambar 1 (utama)
              </label>
              <div class="upload-image-input" style="background-image:url({{ asset('uploads/images/default-image.jpg') }})"></div>
              <input type="file" name="image_primary" id="image-1" accept="image/*" class="d-none upload-image-input-target">
            </div>
          </div>
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control">
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label for="categories" class="form-label">Categories</label>
            <select name="categories[]" id="categories" class="form-control" multiple>
              @foreach ($categories as $val)
                <option value="{{ $val->id }}">{{ $val->text }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <button class="btn btn-primary btn-block" type="submit">Save Product</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('footer')
<script>
  $('#add-img-input-btn').click(function(){
    const siblingCount = $(this).parent().parent().parent().children().length;
    $(this).parent().parent().parent().append(`
      <div class="col-6 col-lg-4 col-xl-3 mt-2">
        <label for="image-${siblingCount+1}" class="form-label">
          Gambar ${siblingCount+1}
        </label>
        <div class="upload-image-input" style="background-image:url({{ asset('uploads/images/default-image.jpg') }})"></div>
        <input type="file" name="images[]" id="image-${siblingCount+1}" accept="image/*" class="d-none upload-image-input-target">
      </div>
    `)
    addEventInputImage()
  })
  addEventInputImage()
  function addEventInputImage() {
    $('.upload-image-input-target').change(function(){
      $(this).prev().css('background-image',`url('${ URL.createObjectURL(this.files[0])}')`)
    })
    $('.upload-image-input').click(function(){
      $(this).next().click()
    })
  }
</script>
@endsection

@section('head')
<style>
  .upload-image-input {
    height: 150px;
    margin: 0 auto;
    background-size: cover;
    background-position: center;
    cursor: pointer;
  }
</style>
@endsection