@extends('layout.dashboard')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('products-categories.store') }}" method="post">
            @csrf
            <div class="mb-3">
              <label for="category" class="form-label">Category Name</label>
              <input type="text" name="category" id="category" class="form-control" value="{{ old('category') }}">
            </div>
            <div class="mb-3">
              <button class="btn btn-primary">Add New Category</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection