@extends('layout.dashboard')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table class="table table-bordered table-striped" id="productCategoriesTable">
            <thead>
              <tr>
                <th>No</th>
                <th>Category</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($categories as $idx => $category)
                <tr>
                  <td>{{ $idx+1 }}.</td>
                  <td class="text-capitalize">{{ $category->category }}</td>
                  <td class="d-flex" style="gap: .3em">
                    <a href="{{ route('products-categories.edit', $category->id) }}" class="btn btn-primary btn-sm">edit</a>
                    <form action="{{ route('products-categories.destroy', $category->id) }}" method="post" onsubmit="return confirm('are you sure to delete these category?')"> @csrf @method('delete')
                      <button type="submit" class="btn btn-danger btn-sm">delete</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('footer')
  <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script>
  $(document).ready(function(){
    $('#productsTable').DataTable()
  })
  </script>
@endsection


@section('content-header-right')
  <div class="text-right">
    <a href="{{ route('products-categories.create') }}" class="btn btn-primary">Add Product Category</a>
  </div>
@endsection