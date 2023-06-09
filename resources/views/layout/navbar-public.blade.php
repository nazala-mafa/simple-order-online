<style>
  #cart-count {
    position: relative;
  }

  #cart-count span {
    position: absolute;
    top: -5px;
    font-size: 10px;
    left: 10px;
    background: var(--blue);
    color: white;
  }

  #user-dropdown {
    cursor: pointer;
  }

  #user-dropdown::after {
    display: none;
  }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">{{ env('APP_NAME') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          {{-- <a class="nav-link" href="{{ url()->to('/products') }}">Products <span class="sr-only">(current)</span></a> --}}
        </li>
      </ul>

      <span class="navbar-text">
        @if (auth()->check())
          <div class="btn-group">
            <i id="user-dropdown" class="fas fa-user dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false"></i>
            <div class="dropdown-menu">
              <a class="dropdown-item text-dark" href="{{ url()->to('/' . auth()->user()->role) }}"><i
                  class="fas fa-cog"></i> Setting</a>
              <a class="dropdown-item text-dark" href="{{ url()->to('/logout') }}"><i class="fas fa-sign-out-alt"></i>
                Logout</a>
            </div>
          </div>
        @else
          <a href="{{ url()->to('/login') }}">Login</a>
        @endif
      </span>

    </div>
  </div>
</nav>
