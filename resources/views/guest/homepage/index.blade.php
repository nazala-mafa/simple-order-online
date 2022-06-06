@extends('layout.public')

@section('content')
  <x-products-cards-list display-status="available" />
@endsection


@section('footer')
  @yield('script')
@endsection
