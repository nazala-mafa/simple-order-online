@extends('layout.dashboard')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <table class="table table-bordered table-striped" id="table">
          <thead>
            <tr>
              <th>No</th>
              <th>QrCode</th>
              <th>Table Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($tables as $idx => $table)
              <tr>
                <td>{{ $idx+1 }}</td>
                <td>
                  <img src="https://chart.googleapis.com/chart?chs=360x360&cht=qr&chl={{ urlencode(route('display', $table->id)) }}&choe=UTF-8" width="100" height="100" alt="table qr code">
                </td>
                <td class="text-capitalize">{{ $table->name }}</td>
                <td>
                  <button class="btn btn-primary btn-sm edit-btn" data-name="{{ $table->name }}" data-url="{{ route('table.update', $table->id) }}">edit</button>
                  <form action="{{ route('table.destroy', $table->id) }}" method="post" class="d-inline">
                    @csrf @method('delete')
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

<!-- Modal -->
<div class="modal fade" id="addTableModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('table.store') }}" method="post">
      @csrf 
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add New Table</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="text" class="form-control" placeholder="table name" name="name">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" >Add new table</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editTableModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="#" method="post" id="editTableForm">
      @csrf @method('put')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editTableModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="text" class="form-control" placeholder="table name" name="name" id="editTableModalInputName">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" >Edit table</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('footer')
  <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('#table').DataTable({
        paging: false,
        info: false,
        dom: 't',
        initComplete: function() {
          $('.edit-btn').click(function() {
            $('#editTableModalLabel').text('edit '+$(this).data('name')+' table')
            $('#editTableModalInputName').val($(this).data('name'))
            $('form#editTableForm').attr('action', $(this).data('url'))
            $('#editTableModal').modal('show')
          })
        }
      })

      $('#add-table-btn').click(function(){
        $('#addTableModal').modal('show')
      })
    })
  </script>
@endsection

@section('content-header-right')
  <button class="btn btn-primary btn-xl" id="add-table-btn">Add New Table</button>
@endsection