@extends('layout.public')

@section('content')
<div class="container">
  <div class="row">
    @foreach ($products as $product)
      <?php $imgSrc = asset( 'uploads/images/products/' . $product->user_id . '/' . $product->images()->where('type', 'primary')->first()->filename); ?>
      <div class="_card col-12 col-md-4 p-0" style="background-image: url('{{ $imgSrc }}');">
        <div class="_card_body">
          <h4>{{ $product->name }}</h4>
          <p>{{ $product->description }}</p>
        </div>
        <div class="_add_to_cart">
          <div class="edit" data-id="{{ $product->id }}">
            <div class="subtract">-</div>
            <div class="count">0</div>
            <div class="add">+</div>
          </div>
          <div class="clear">remove</div>
        </div>
      </div>
    @endforeach
  </div>
</div>
<div id="cart-wrapper">
  <div class="cart-body">
    <div class="mt-4 mx-4 d-flex justify-content-between">
      <h3 class="text-white text-capitalize">{{ $table->name }}</h3>
      <div>
        <i id="clear-cart-btn" class="fas fa-trash" style="font-size: 1.3em; cursor:pointer; margin-right: .5em"></i>
        <i id="hide-cart-btn" class="fas fa-chevron-circle-right" style="font-size: 1.5em; cursor:pointer"></i>
      </div>
    </div>
    <div class="p-4 pt-2">
      <table class="table">
        <thead>
          <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Jumlah</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody id="cart-table"></tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="text-right pr-3">Total Keseluruhan :</td>
            <td id="sumTotal" class="text-right"></td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div class="mx-4 text-right">
      <form action="{{ route('table-order.store') }}" method="post">
        @csrf <input type="hidden" name="table_id" value="{{ $table->id }}">
        <div id="table-order-form-input"></div>
        <input type="text" name="on_behalf_of" class="form-control mb-3" required placeholder="Order on behalf of">
        <div>
          <a href="{{ route('table-order.index', ['table_id' => $table->id]) }}" class="btn" style="color:white; background:tomato;">Orders List</a>
          <button class="btn" id="#" style="background: tomato; color: white" id="submit-cart">Order Now</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div id="cart-btn">
  <div id="cart">#</div>
  <i class="fas fa-shopping-cart"></i>
</div>
@endsection

@section('head')
  <link rel="stylesheet" href="{{ asset('css/pages/homepage/display.css') }}">
@endsection

@section('footer')
  <script>
    let cart = JSON.parse(localStorage.getItem('cart')) || {}
    let products = null;
    $.get(`{{ url()->to('/products') }}`, function(res){
      products = {}
      res.products.map(prod=>{
        products[prod.id] = prod
      })
      printToCart(cart)
    }) //get products obj

    //print cart to card in document first load
    if(cart) {
      for(let id of Object.keys(cart)) {
        $(`[data-id=${id}] .count`).text(cart[id]) 
      } //print to products card
      printToCart(cart)
    }

    function editCart(id, count) {
      cart[id] = count
      localStorage.setItem('cart', JSON.stringify(cart))
      printToCart(cart)
    }
    function printToCart(cart) {
      if(!products) return false;
      validCart = Object.keys(cart).filter(c=>cart[c]);
      let html = [];
      let tableHtml = [];
      let total = 0;
      let allTotal = 0;
      let no = 1;
      for(let c of validCart) {
        total = cart[c] * products[c].price;
        html.push(`
          <tr>
            <td>${no++}.</td>
            <td>${products[c].name}</td>
            <td>${cart[c]}</td>
            <td class="text-right">Rp. ${(total).toLocaleString('id')}</td>
          </tr>
        `)
        tableHtml.push(`
          <input type="hidden" name="products_amount[${c}]" value="${cart[c]}" />
        `)
        allTotal += total
      }
      let tableRowsHtml = (validCart.length) ? html.join('') : '<tr><td colspan="4" class="text-center">Empthy</td></tr>';
      (validCart.length) ? $('#cart').css('display', 'block') : $('#cart').css('display', 'none');
      $('#sumTotal').text(`Rp. ${allTotal.toLocaleString('id')}`)
      $('#cart-table').html( tableRowsHtml )
      $('#cart').text(validCart.length)

      tableHtml && $('#table-order-form-input').html(tableHtml.join(''))
    }

    $('.subtract').click(function() {
      let count = parseInt($(this).next().text())
      if(count>0) count--;
      $(this).next().text(count)
      editCart($(this).parent().data('id'), count)
    })
    $('.add').click(function() {
      let count = parseInt($(this).prev().text())
      count++;
      $(this).prev().text(count)
      editCart($(this).parent().data('id'), count)
    })
    $('.clear').click(function() {
      $(this).prev().children()[1].textContent = 0
      editCart($(this).prev().data('id'), 0)
    })
    $('#clear-cart-btn').click(function() {
      delete localStorage.cart
      cart = {}
      $(`.count`).text(0)
      printToCart(cart)
    })
    $('#hide-cart-btn').click(function() {
      $('#cart-wrapper').removeClass('active')
    })
    $('#cart-btn').click(function() {
      $('#cart-wrapper').addClass('active')
    })

    $('body').click(function(e) {
      if(!$(e.target).is('#cart-wrapper *, #cart-wrapper, #cart-btn *, #cart-btn')) {
        $('#cart-wrapper').removeClass('active')
      }
    })

    $('#order-now-btn').click(function() {
      delete localStorage.cart
      cart = {}
      $(`.count`).text(0)
      printToCart(cart)
    })
  </script>
@endsection