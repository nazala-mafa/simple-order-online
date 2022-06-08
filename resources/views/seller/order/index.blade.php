@extends('layout.dashboard')

@section('content')
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Table</th>
        <th>On Behalf Of</th>
        <th>Name</th>
        <th>Amount</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($orders as $idx => $order)
        <tr>
          <td>{{ $idx+1 }}</td>
          <td class="text-capitalize">{{ $order->order->_table->name }}</td>
          <td class="text-capitalize">{{ $order->order->on_behalf_of }}</td>
          <td class="text-capitalize">{{ $order->product->name }}</td>
          <td class="text-capitalize">{{ $order->amount }}</td>
          <td>
            <form action="{{ route('table-order.update', $order->id) }}" method="post">
              @csrf @method('put')
              <?php 
                switch ($order->status) {
                  case 'pending':
                    $color = 'info';
                    break;
                  case 'proccess':
                    $color = 'primary';
                    break;
                  case 'ready':
                    $color = 'success';
                    break;
                  default:
                    $color = '';
                    break;
                }
              ?>
              <select name="status" class="form-control text-capitalize bg-{{ $color }}" onchange="this.parentElement.submit()">
                {{-- Order from table details status enum values --}}
                @foreach (['pending', 'proccess', 'ready'] as $status) 
                  <option value="{{ $status }}" {{ ($order->status !== $status) ?: 'selected' }} class="text-capitalize bg-white">{{ $status }}</option>
                @endforeach
              </select>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection