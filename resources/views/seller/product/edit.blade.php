@extends('layout.dashboard')

@section('content-header-right')
  <div class="text-right">
    <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form action="{{ url()->to('/seller/products/'.$product->id) }}" enctype="multipart/form-data" method="post">
            @csrf @method('put')
            <div class="mb-3 row" id="images-wrapper">

              <div class="col-6 col-lg-4 col-xl-3 mt-2">
                <label for="image-1" class="form-label">
                  <button type="button" class="btn-primary btn-sm" style="line-height: 1" id="add-img-input-btn">+</button>
                  Gambar 1 (utama)
                </label>
                <div class="upload-image-input" style="background-image:url('{{ asset( 'uploads/images/products/' . auth()->user()->id . '/' . $product->images->filter()->where('type', 'primary')->first()->filename ) }}')"></div>
                <input type="file" name="image_primary" id="image-1" accept="image/*" class="d-none upload-image-input-target">
              </div>

              @foreach ($product->images->filter()->where('type', 'secondary')->all() as $key => $val)
                <div class="col-6 col-lg-4 col-xl-3 mt-2">
                  <div class="d-flex justify-content-between">
                    <label for="image-{{ $key+2 }}" class="form-label">
                      Gambar {{ $key+2 }}
                    </label>
                    <div>
                      <a href="{{ url()->to('seller/products-image/'.$val->id) }}" class="btn-danger btn-sm delete-img-btn" style="line-height: 1"><i class="fas fa-trash"></i></a>
                    </div>
                  </div>
                  <div class="upload-image-input" style="background-image:url('{{ asset( 'uploads/images/products/' . auth()->user()->id . '/' . $val->filename ) }}')"></div>
                  <input type="file" name="images[{{$val->id}}]" id="image-{{ $key+2 }}" accept="image/*" class="d-none upload-image-input-target">
                </div>
              @endforeach

            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" name="name" id="name" class="form-control" value="{{ old('name') ?? $product->name }}">
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea name="description" id="description" class="form-control">{{ old('description') ?? $product->description }}</textarea>
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Price</label>
              <input type="number" name="price" id="price" class="form-control" value="{{ old('price') ?? $product->price }}">
            </div>
            <div class="mb-3">
              <label for="categories" class="form-label">Categories</label>
              <select name="categories[]" id="categories" class="form-control" multiple>
                @foreach ($categories as $val)
                  <option value="{{ $val->id }}" {{ ( !in_array($val->id, $product->categories->pluck('id')->toArray()) ) ?: 'selected' }} >{{ $val->text }}</option>
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
    $('.delete-img-btn').click(()=>{
      if(!confirm('are you sure to delete these image?')) return false;
    })
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