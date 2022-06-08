<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

    <li class="nav-item">
      <a href="{{ url()->to('/seller') }}"
        class="nav-link {{ url()->to('/seller') !== url()->current() ?: 'active' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
          Dashboard
        </p>
      </a>
    </li>

    <li
      class="nav-item {{ !in_array(url()->current(), [
          url()->to('/seller/products'),
          url()->to('/seller/products/create'),
          route('products-categories.index'),
          route('products-categories.create'),
      ]) ?:
          'menu-open' }}">
      <a href="#"
        class="nav-link {{ !in_array(url()->current(), [
            url()->to('/seller/products'),
            url()->to('/seller/products/create'),
            route('products-categories.index'),
            route('products-categories.create'),
        ]) ?:
            'active' }}">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Products
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url()->to('/seller/products') }}"
            class="nav-link {{ url()->to('/seller/products') !== url()->current() ?: 'active' }}">
            <i class="far fa-circle nav-icon"></i>
            <p>Products List</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url()->to('/seller/products/create') }}"
            class="nav-link {{ url()->to('/seller/products/create') !== url()->current() ?: 'active' }}">
            <i class="far fa-circle nav-icon"></i>
            <p>Add Product</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('products-categories.index') }}"
            class="nav-link {{ route('products-categories.index') !== url()->current() ?: 'active' }}">
            <i class="far fa-circle nav-icon"></i>
            <p>Product Categories</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('products-categories.create') }}"
            class="nav-link {{ route('products-categories.create') !== url()->current() ?: 'active' }}">
            <i class="far fa-circle nav-icon"></i>
            <p>Add Product Category</p>
          </a>
        </li>
      </ul>
    </li>

    <li class="nav-item">
      <a href="{{ route('table-order.index') }}"
        class="nav-link {{ route('table-order.index') !== url()->current() ?: 'active' }}">
        <i class="nav-icon fas fa-concierge-bell"></i>
        <p>
          Orders
        </p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ url()->to('/profile-setting') }}"
        class="nav-link {{ url()->to('/profile-setting') !== url()->current() ?: 'active' }}">
        <i class="nav-icon fas fa-user-edit"></i>
        <p>
          Profile
        </p>
      </a>
    </li>

  </ul>
</nav>
