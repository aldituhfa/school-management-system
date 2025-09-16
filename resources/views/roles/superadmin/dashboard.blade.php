@extends('layouts.superadmin')

@section('title', 'Dashboard')

@section('content')
<div class="row">
  <div class="col-lg-3 col-sm-6 mb-3">
    <div class="card">
      <div class="card-body text-center">
        <i class="bx bx-user-circle bx-lg text-primary"></i>
        <h5 class="mt-2">5 Admin</h5>
        <p class="mb-0">Total Admin</p>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-sm-6 mb-3">
    <div class="card">
      <div class="card-body text-center">
        <i class="bx bx-book-open bx-lg text-success"></i>
        <h5 class="mt-2">12 Guru</h5>
        <p class="mb-0">Total Guru</p>
      </div>
    </div>
  </div>
</div>
@endsection
