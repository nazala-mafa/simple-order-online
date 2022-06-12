@extends('layout.public')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between mt-3">
    <h3>Orders List</h3>
    <button onclick="history.back()" class="btn btn-primary">Order Again</button>
  </div>
  <div class="row">
    <div class="col-12 pt-3">
      <div class="card">
        <div class="card-body" style="overflow-x: auto">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Order Id</th>
                <th>On behalf of</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($orders as  $idx => $order)
                <tr>
                  <td>{{ $idx+1 }}</td>
                  <td>{{ 'ordr'.date('Ymd', strtotime($order->created_at)).$order->id }}</td>
                  <td class="text-capitalize">{{ $order->on_behalf_of }}</td>
                  <td class="text-capitalize">{{ str_replace('_', ' ', $order->status) }}</td>
                  <td>
                    @if ($tableId == $order->table_id)
                      @if ($order->status == 'in_payment')
                          <a href="{{ $order->payment_url }}" class="btn btn-primary btn-sm">Pay</a>
                      @else
                        <button 
                          data-on-behalf-of="{{ $order->on_behalf_of }}"
                          data-detail='{{ $order->detail->map(function($row){
                            $row['name'] = $row->product()->pluck('name')[0]; 
                            return $row;
                          })->toJson() }}' 
                          data-table-id="{{ $order->table_id }}" 
                          data-sum-total="{{ $order->sum_total }}"
                          class="btn btn-sm btn-primary btn-detail"  
                        >detail</button>
                      @endif
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="orderDetailModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-capitalize" id="orderDetailModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Price</th>
              <th>Amount</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody id="orders-list"></tbody>
          <tfoot>
            <tr>
              <td colspan="3" class="text-right pr-3">Sum Total :</td>
              <td id="sum-total" class="text-nowrap text-right"></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('footer')
  <script>
    $('.btn-detail').click(function() {
      let ordersHtml = []
      const orders = $(this).data('detail')
      orders.map(function(order){
        ordersHtml.push(`
          <tr>
            <td>${order.name}</td>
            <td class="text-nowrap">Rp. ${order.price.toLocaleString('id')}</td>
            <td class="text-center">${order.amount}</td>
            <td class="text-nowrap text-right">Rp. ${order.total.toLocaleString('id')}</td>
          </tr>
        `)
      })
      $('#orders-list').html(ordersHtml.join(''))
      $('#sum-total').text('Rp. '+$(this).data('sum-total').toLocaleString('id'))
      $('#orderDetailModalLabel').text('order on behalf of ' + $(this).data('on-behalf-of'))
      $('#orderDetailModal').modal('show')
    })
  </script>
@endsection