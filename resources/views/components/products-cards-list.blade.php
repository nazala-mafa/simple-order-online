<div class="container">
  <div class="row">
    @foreach ($products as $product)
      <div class="col-12 col-md-4 col-xl-3 col-xxl-3 mt-3 p-md-0 p-3">
        <div class="card h-100 m-md-2" style="box-shadow: 0 0 10px grey;">
          <div class="card-header">
            <h4>{{ $product->name }}</h4>
          </div>
          <div class="card-body">
            <img
              src="{{ asset(
                  'uploads/images/products/' .
                      $product->user_id .
                      '/' .
                      $product->images()->where('type', 'primary')->first()->filename,
              ) }}"
              alt="" class="w-100 mb-2">
            <h5>{{ $product->name }}</h5>
            <p>{{ $product->description }}</p>
            <div style="height: 1.5em"></div>
            <div class="mt-3 position-absolute" style="bottom:1em;left: 50%;transform: translateX(-50%);">
              <div class="btn-group mx-auto" role="group" data-id="{{ $product->id }}" data-product-name="{{ $product->name }}">
                <button type="button" class="btn btn-primary">Detail</button>
                <button type="button" class="btn btn-primary add-to-cart text-nowrap">Add to cart</button>
                <button type="button" class="btn btn-primary">Wishlist</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addToCartModal" tabindex="-1" role="dialog" aria-labelledby="addToCartModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addToCartModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('carts.store') }}" method="post">
          @csrf
          <input type="hidden" id="add-to-cart-product-id" name="product_id">
          <div class="input-group mb-3">
            <div class="w-25 input-group-prepend">
              <button class="w-100 btn btn-outline-primary subtract-btn" type="button">-</button>
            </div>
            <input type="number" class="form-control" min="0" placeholder="enter product quantity" id="add-to-cart-quantity" name="amount">
            <div class="w-25 input-group-append">
              <button class="w-100 btn btn-outline-primary add-btn" type="button">+</button>
            </div>
          </div>
          <div class="mb-3">
            <button class="w-100 btn btn-primary" id="add-to-cart" type="submit">Add to cart</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@section('script')
  <script>
    $('.add-to-cart').click(function() {
      if ({{ auth()->check() ? 'true' : 'false' }}) {
        $('#add-to-cart-product-id').val($(this).parent().data('id'))
        $('#addToCartModalLabel').text($(this).parent().data('product-name'))
        $('#addToCartModal').modal('show')
      } else {
        window.location = "{{ url()->to('/login') }}"
      }
    })

    $('.substract-btn').click(function(){ $(this).parent().next().val( parseInt($(this).parent().next().val())-1 ) })
    $('.add-btn').click(function(){ $(this).parent().prev().val( parseInt($(this).parent().prev().val())+1 ) })
  </script>
@endsection
