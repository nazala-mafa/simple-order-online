<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

    <li class="nav-item">
      <a href="{{ url()->to('/buyer') }}"
        class="nav-link {{ url()->to('/buyer') !== url()->current() ?: 'active' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
          Dashboard
        </p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ url()->to('/buyer/carts') }}"
        class="nav-link {{ url()->to('/buyer/carts') !== url()->current() ?: 'active' }}">
        <i class="nav-icon fas fa-shopping-cart"></i>
        <p>
          Cart
        </p>
      </a>
    </li>

  </ul>
</nav>
