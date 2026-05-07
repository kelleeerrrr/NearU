@extends('layouts.app')

@section('title', 'System Information - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <div class="page-header">
        <div class="header-top">
          <h2>💻 System Information</h2>
          <a href="{{ route('admin.profile') }}" class="btn-back">← Back</a>
        </div>
      </div>

      <div class="info-grid">
        <!-- Application Info -->
        <div class="info-card">
          <h3>🚀 Application</h3>
          <div class="info-list">
            <div class="info-item">
              <span class="info-label">Application Name:</span>
              <span class="info-value">{{ config('app.name') }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Environment:</span>
              <span class="info-value">{{ config('app.env') }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Debug Mode:</span>
              <span class="info-value">{{ config('app.debug') ? 'Enabled' : 'Disabled' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Timezone:</span>
              <span class="info-value">{{ config('app.timezone') }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Laravel Version:</span>
              <span class="info-value">{{ app()->version() }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">PHP Version:</span>
              <span class="info-value">{{ PHP_VERSION }}</span>
            </div>
          </div>
        </div>

        <!-- Server Info -->
        <div class="info-card">
          <h3>🖥 Server</h3>
          <div class="info-list">
            <div class="info-item">
              <span class="info-label">Server Software:</span>
              <span class="info-value">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Server OS:</span>
              <span class="info-value">{{ PHP_OS }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Memory Limit:</span>
              <span class="info-value">{{ ini_get('memory_limit') }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Max Execution Time:</span>
              <span class="info-value">{{ ini_get('max_execution_time') }}s</span>
            </div>
            <div class="info-item">
              <span class="info-label">Max Upload Size:</span>
              <span class="info-value">{{ ini_get('upload_max_filesize') }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Max POST Size:</span>
              <span class="info-value">{{ ini_get('post_max_size') }}</span>
            </div>
          </div>
        </div>

        <!-- Database Info -->
        <div class="info-card">
          <h3>🗄️ Database</h3>
          <div class="info-list">
            <div class="info-item">
              <span class="info-label">Database Connection:</span>
              <span class="info-value">{{ config('database.default') }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Database Host:</span>
              <span class="info-value">{{ config('database.connections.pgsql.host') }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Database Port:</span>
              <span class="info-value">{{ config('database.connections.pgsql.port') }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Database Name:</span>
              <span class="info-value">{{ config('database.connections.pgsql.database') }}</span>
            </div>
          </div>
        </div>

              </div>
    </div>
  </div>

  @include('partials.footer')
</div>
@endsection

@push('styles')
<style>
.page-header {
  margin-bottom: 1.5rem;
}

.back-link {
  color: #2D7D4F;
  text-decoration: none;
  font-weight: 600;
  margin-bottom: 0.5rem;
  display: inline-block;
}

.back-link:hover {
  text-decoration: underline;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background: #2D7D4F;
  color: #fff;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 700;
  text-decoration: none;
  border: 2px solid #2D7D4F;
  box-shadow: 0 2px 8px rgba(45, 125, 79, 0.3);
  transition: all 0.2s;
  white-space: nowrap;
}

.btn-back:hover {
  background: #1f5c38;
  border-color: #1f5c38;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.4);
}

.page-header {
  margin-bottom: 1.5rem;
}

.header-top {
  display: flex;
  align-items: center;
  gap: 1rem;
  justify-content: space-between;
}

.header-top h2 {
  margin: 0;
  color: #374151;
  font-size: 1.5rem;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.info-card {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1);
}

.info-card h3 {
  margin: 0 0 1rem 0;
  color: #2D7D4F;
  font-size: 1.1rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.info-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.info-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0;
  border-bottom: 1px solid #f3f4f6;
}

.info-item:last-child {
  border-bottom: none;
}

.info-label {
  font-weight: 600;
  color: #6b7280;
  font-size: 0.9rem;
}

.info-value {
  font-weight: 600;
  color: #1f2937;
  font-size: 0.9rem;
  text-align: right;
}

.actions-section {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1);
}

.actions-section h3 {
  margin: 0 0 1rem 0;
  color: #2D7D4F;
  font-size: 1.1rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.action-buttons {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.btn {
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 0.9rem;
}

.btn-gray {
  background: #6b7280;
  color: white;
}

.btn-gray:hover {
  background: #4b5563;
}

.btn-blue {
  background: #2D7D4F;
  color: white;
}

.btn-blue:hover {
  background: #1f5c38;
}

.btn-green {
  background: #16a34a;
  color: white;
}

.btn-green:hover {
  background: #15803d;
}

@media (max-width: 768px) {
  .info-grid {
    grid-template-columns: 1fr;
  }
  
  .action-buttons {
    flex-direction: column;
  }
  
  .btn {
    width: 100%;
    text-align: center;
  }
}
</style>
@endpush
