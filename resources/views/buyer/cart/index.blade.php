@extends('layout.dashboard')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="text-capitalize">penjual: {{ $carts[0]['seller']->name }}</h4>
          </div>
          <div class="card-body">
            <table class="table table-bordered table-striped" id="cartTable">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Product Image</th>
                  <th>Name</th>
                  <th>Amount</th>
                  <th>Price</th>
                  <th>Total Price</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $total = 0; ?>
                {{-- Dummy --}}
                @foreach ($carts[0]['data'] as $idx => $cart)
                  <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>
                      <img
                        src="{{ asset(
                            '/uploads/images/products/' .
                                $cart->product->user_id .
                                '/' .
                                $cart->product->images()->where('type', 'primary')->first()->filename,
                        ) }}"
                        alt="" width="100">
                    </td>
                    <td> {{ $cart->product->name }} </td>
                    <td> {{ $cart->amount }} </td>
                    <td> Rp. {{ number_format($cart->price, 0, '', '.') }} </td>
                    <td> Rp. {{ number_format($cart->price_total, 0, '', '.') }} </td>
                    <td class="text-nowrap">
                      <button class="btn-sm btn btn-primary edit-btn" data-amount="{{ $cart->amount }}"
                        data-name="{{ $cart->product->name }}" data-id="{{ $cart->id }}">edit</button>
                      <form action="{{ route('carts.destroy', $cart->id) }}" method="post" class="d-inline">
                        @csrf @method('delete')
                        <button class="btn-sm btn btn-warning text-white">delete</button>
                      </form>
                    </td>
                  </tr>
                  <?php $total += (float) $cart->price_total ?>
                @endforeach
              </tbody>
              <tfoot>
                <?php $tax = 10000; $shipping = 25000; // dummy ?>
                @if ($tax)
                  <tr>
                    <td></td>
                    <td colspan="4" class="text-right"><b>Pajak:</b></td>
                    <td colspan="2">Rp. {{ number_format($tax, 0, '', '.') }}</td>
                  </tr>
                @endif
                @if ($shipping)
                  <tr>
                    <td></td>
                    <td colspan="4" class="text-right"><b>Biaya Pengiriman:</b></td>
                    <td colspan="2">Rp. {{ number_format($shipping, 0, '', '.') }}</td>
                  </tr>
                @endif
                <tr>
                  <td></td>
                  <td colspan="4" class="text-right"><b>Total:</b></td>
                  <td colspan="2">Rp. {{ number_format($total + $tax + $shipping, 0, '', '.') }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
    
    <div class="w-100 text-right">
      <form action="{{ route('payments.store') }}" method="post"> @csrf
        <input type="hidden" name="buyer_id" value="{{ auth()->user()->id }}">
        {{-- Dummy --}}
        <input type="hidden" name="seller_id" value="{{ $carts[0]['seller']->id }}">
        <button type="submit" class="btn btn-primary btn-lg px-5">Checkout</button>
      </form>
    </div>

  </div>


  <!-- Modal -->
  <div class="modal fade" id="cartEditModal" tabindex="-1" role="dialog" aria-labelledby="cartEditModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cartEditModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
            <label for="amount" class="form-label">Amount</label>
            <div class="input-group mb-3">
              <div class="w-25 input-group-prepend">
                <button class="w-100 btn btn-outline-primary substract-btn" type="button">-</button>
              </div>
              <input type="number" class="form-control" min="0" placeholder="enter product quantity" id="amount"
                name="amount">
              <div class="w-25 input-group-append">
                <button class="w-100 btn btn-outline-primary add-btn" type="button">+</button>
              </div>
            </div>
          </form>
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
      $('#cartTable').DataTable({
        paging: false,
        ordering: false,
        info: false,
        dom: 't',
        initComplete: function() {
          $('.edit-btn').click(function() {
            $('#cartEditModal form').attr('action', "{{ url()->to('/buyer/carts/') }}" + $(this).data(
              'id'))
            $('#amount').val($(this).data('amount'))
            $('#cartEditModalLabel').text($(this).data('name'))
            $('#cartEditModal').modal('show')
          })
        }
      })

      $('.substract-btn').click(function() {
        $(this).parent().next().val(parseInt($(this).parent().next().val()) - 1)
      })
      $('.add-btn').click(function() {
        $(this).parent().prev().val(parseInt($(this).parent().prev().val()) + 1)
      })
    })
  </script>
@endsection
