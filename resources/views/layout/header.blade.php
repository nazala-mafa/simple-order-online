<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Dashboard Admin' }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <script>
    const url = new URL(window.location.href)
    const dateTimeFormat = dateString => {
      const date = new Date(dateString)
      const days = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
      const month = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustur', 'september', 'oktober', 'november', 'desember'];
      return `${days[date.getDay()]}, ${date.getDate()} ${month[date.getMonth()]} ${date.getFullYear()}, ${date.getHours()}:${date.getMinutes()}`
    } 
  </script>
  {{-- Custom Head --}}
  @yield('head')
</head>