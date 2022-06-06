{{-- Header --}}
@include('layout.header')

<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  @include('layout.navbar')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('layout.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="d-flex justify-content-between mb-2">
          <div>
            <h1 class="m-0">{{ $title ?? '' }}</h1>
          </div><!-- /.col -->
          <div>
            @yield('content-header-right')
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        @include('layout.message-block')
        @yield('content')
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  {{-- Space --}}
  <div class="space" style="height: 3em"></div>

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      {{ \Illuminate\Foundation\Inspiring::quote() }}
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; {{ date('Y') }} </strong> {{ env('APP_NAME', 'Simple E-Commerce') }}
  </footer>
</div>
<!-- ./wrapper -->

{{-- Footer --}}
@include('layout.footer')
