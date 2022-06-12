@extends('layout.dashboard')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3>Setting</h3>
        </div>
        <div class="card-body">
          <form action="{{ route('save_setting') }}" method="post">
            @csrf @method('put')
            <div class="mb-3">
              <label for="xenditApiKey" class="form-label">Xendit Api Key</label>
              <input type="text" name="xenditApiKey" id="xenditApiKey" class="form-control" value="{{ old('xenditApiKey') ?? $xenditApiKey }}">
            </div>
            <div class="mb-3">
              <button class="btn btn-primary form-control" type="submit">Save Change</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection