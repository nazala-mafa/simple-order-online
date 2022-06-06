@extends('layout.dashboard')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('products-categories.update', $category->id) }}" method="post">
            @csrf @method('put')
            <div class="mb-3">
              <label for="category" class="form-label">Category Name</label>
              <input type="text" name="category" id="category" class="form-control" value="{{ old('category') ?? $category->category }}">
            </div>
            <div class="mb-3">
              <button class="btn btn-primary">Update Category</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection