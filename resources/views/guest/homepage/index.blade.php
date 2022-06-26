@extends('layout.public')

@section('content')
  <div class="container">
    <div class="row">
      @foreach ($tables as $table)
        <div class="col-6 col-sm-4 col-md-3">
          <a class="card m-4 bg-secondary" href="{{ route('display', $table->id) }}">
            <div class="card-body text-white text-capitalize text-center text-decoration-none text-dark">
              <h3>{{ $table->name }}</h3>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
@endsection
