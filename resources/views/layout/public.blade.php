@include('layout.header')
<body>
  
<?php if(!isset($noHeader)): ?>
@include('layout.navbar-public')
<?php endif ?>

@yield('content')

@include('layout.footer')