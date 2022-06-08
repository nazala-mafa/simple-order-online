@extends('layout.dashboard')

@section('content')
  <div class="card">
    <div class="card-body">
      <form action="{{ url()->to('/profile-setting-attempt') }}" method="post">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" name="name" id="name" class="form-control" required value="{{ auth()->user()->name }}">
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" id="email" class="form-control" required value="{{ auth()->user()->email }}">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password (fill to update password)</label>
          <input type="password" name="password" id="password" class="form-control">
        </div>
        <div class="mb-3 text-center">
          <button type="submit" class="px-4 btn btn-primary">Update Profile</button>
        </div>
      </form>
    </div>
  </div>
@endsection
