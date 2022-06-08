@extends('layout.dashboard')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table class="table table-bordered table-striped" id="productsTable">
            <thead>
              <tr>
                <th>No</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                {{-- <th>Stock</th> --}}
                <th>Price</th>
                <th>Display Status</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

<!-- Modal -->
<div class="modal fade" id="stockEditModal" tabindex="-1" role="dialog" aria-labelledby="stockEditModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="stockEditModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="stock-product-id">
        <input type="number" min="0" class="form-control" id="stock-input">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button type="button" class="stok-edit-submit btn btn-warning text-white" data-dismiss="modal" data-type="subtract">Subtract ( - )</button>
        <button type="button" class="stok-edit-submit btn btn-primary" data-dismiss="modal" data-type="add">Add ( + )</button>
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
    $(document).ready(function() {
      let table = $('#productsTable').DataTable({
        ajax: {
          url: "{{ url()->to('/seller/products?datatables=1') }}",
          method: 'get'
        },
        columns: [{
            data: "",
            render: function(data, type, row, meta) {
              return meta.row + meta.settings._iDisplayStart + 1;
            }
          },
          {
            data: data => {
              return `<img width="100" src="${url.origin}/uploads/images/products/${data.user_id}/${data.images}" />`
            }
          },
          {
            data: 'name'
          },
          {
            data: data => data.categories.join(', ')
          },
          // {
          //   data: 'stock'
          // },
          {
            data: 'price'
          },
          {
            data: data => {
              let display_statuses = ['available', 'not available', 'preorder only', 'hide']
              return `
                <select class="form-control display-status-edit" data-id="${data.id}">
                  ${display_statuses.map(status => (
                    `<option class="text-capitalize" ${data.display_status===status && 'selected'} value="${status}">${status}</option>`
                  ) )}
                </select>
              `
            }
          },
          {
            data: data => {
              return `
                <div class="d-flex" style="gap:.3em">
                  <a class="btn btn-primary btn-sm" href="${url.origin}/seller/products/${data.id}/edit">edit</a>
                  <form class='delete-form' action="${url.origin+'/seller/products/'+data.id}" method='post'> @csrf @method('delete')
                    <button class="btn btn-danger btn-sm" data-id="${data.id}">delete</button>
                  </form>
                </div>
              `
              let stockBtn = `<button class="btn btn-info btn-sm edit-stock-btn w-100 mt-1" data-stock="${data.stock}" data-id="${data.id}" data-product-name="${data.name}">stock edit</button>`;
            }
          },
        ],
        initComplete: function() {
          $('#productsTable_filter').css('justify-content', 'end').css('display', 'flex')
          $('#productsTable_paginate .pagination').css('justify-content', 'end')
        },
        drawCallback: function() {
          $('.delete-form').click(function(e) {
            if (!confirm('are you sure to delete these product?')) return false;
          })
          $('.edit-stock-btn').click(function() {
            $('#stock-product-id').val($(this).data('id'))
            $('#stock-input').val(0)
            $('#stockEditModalLabel').text($(this).data('product-name'))
            $('#stockEditModal').modal('show')
          })
          $('.display-status-edit').change(function(){
            $.post("{{ url()->to('/seller/products-display-status') }}", {
              _token: "{{ csrf_token() }}",
              display_status: $(this).val(),
              product_id: $(this).data('id')
            })
          })
        }
      })

      $('.stok-edit-submit').click(function(){
        $.post("{{ url()->to('/seller/products-stock') }}", {
          _token: "{{ csrf_token() }}",
          type: $(this).data('type'),
          stock: $('#stock-input').val(),
          product_id: $('#stock-product-id').val()
        }, function(res) {
          if (res.success) {
            table.ajax.reload()
          } else {
            alert('Invalid stock reduction')
          }
        })
      })
    })
  </script>
@endsection

@section('head')
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('content-header-right')
  <div class="text-right">
    <a href="{{ url()->to('/seller/products/create') }}" class="btn btn-primary">Add Product</a>
  </div>
@endsection
