@extends('layouts.app')

@section('title', 'Edit Category - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <h2>✏️ Edit Category</h2>
      
      <div class="alert-card" style="background: #fffbeb; border-left: 4px solid #F2B705;">
        <div class="alert-icon">ℹ️</div>
        <div class="alert-content">
          <strong>Predefined Categories</strong>
          <p>The category system is predefined and cannot be modified. Please use existing categories for user classification.</p>
        </div>
      </div>
    </div>
  </div>

  @include('partials.footer')
</div>
@endsection

@push('styles')
<style>
.alert-card {
  background: white;
  border-radius: 12px;
  padding: 1rem;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
</style>
@endpush
