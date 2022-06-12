<!-- Sidebar Menu -->
<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

    <li class="nav-item">
      <a href="{{ url()->to('/admin') }}"
        class="nav-link {{ url()->to('/admin') !== url()->current() ?: 'active' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
          Dashboard
        </p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ url()->to('/admin/table') }}"
        class="nav-link {{ url()->to('/admin/table') !== url()->current() ?: 'active' }}">
        <i class="nav-icon fas fa-concierge-bell"></i>
        <p>
          Tables
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

    <li class="nav-item">
      <a href="{{ url()->to('/admin/setting') }}"
        class="nav-link {{ url()->to('/admin/setting') !== url()->current() ?: 'active' }}">
        <i class="nav-icon fas fa-cog"></i>
        <p>
          Setting
        </p>
      </a>
    </li>

  </ul>
</nav>
