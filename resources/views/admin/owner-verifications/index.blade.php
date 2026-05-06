@extends('layouts.admin')

@section('title', 'Owner Verifications — NearU')

@push('styles')
<style>
.admin-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.admin-header {
  background: linear-gradient(135deg, #2D7D4F, #1e5a3a);
  color: white;
  padding: 30px;
  border-radius: 12px;
  margin-bottom: 30px;
  text-align: center;
}

.admin-header h1 {
  font-size: 28px;
  font-weight: 800;
  margin-bottom: 10px;
}

.admin-header p {
  font-size: 16px;
  opacity: 0.9;
}

.verification-table {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.table-header {
  background: #f8f9fa;
  padding: 20px;
  border-bottom: 2px solid #e9ecef;
}

.table-header h2 {
  font-size: 20px;
  font-weight: 800;
  color: #2D7D4F;
  margin: 0;
}

.owner-grid {
  display: grid;
  gap: 20px;
  padding: 20px;
}

.owner-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  padding: 20px;
  transition: all 0.3s ease;
}

.owner-card:hover {
  border-color: #2D7D4F;
  box-shadow: 0 6px 20px rgba(45,125,79,0.15);
  transform: translateY(-2px);
}

.owner-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.owner-info h3 {
  font-size: 18px;
  font-weight: 800;
  color: #333;
  margin: 0 0 5px 0;
}

.owner-info p {
  color: #666;
  margin: 0;
  font-size: 14px;
}

.status-badge {
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 700;
  font-size: 14px;
  text-align: center;
}

.status-badge.not-verified {
  background: #fee;
  color: #d32f2f;
  border: 2px solid #fca5a5;
}

.status-badge.under-review {
  background: #fff3cd;
  color: #856404;
  border: 2px solid #ffeaa7;
}

.status-badge.approved {
  background: #e8f5e8;
  color: #2e7d32;
  border: 2px solid #a5d6a7;
}

.documents-section {
  margin: 15px 0;
}

.documents-title {
  font-weight: 700;
  color: #333;
  margin-bottom: 10px;
  font-size: 14px;
}

.document-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  background: #f8f9fa;
  border-radius: 8px;
  margin-bottom: 8px;
}

.document-name {
  font-weight: 600;
  color: #555;
  font-size: 14px;
}

.view-btn {
  background: #2D7D4F;
  color: white;
  text-decoration: none;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  transition: background 0.3s ease;
}

.view-btn:hover {
  background: #1e5a3a;
}

.actions-section {
  margin-top: 20px;
  display: flex;
  gap: 10px;
}

.review-btn {
  background: linear-gradient(135deg, #2D7D4F, #1e5a3a);
  color: white;
  text-decoration: none;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 14px;
  transition: all 0.3s ease;
  flex: 1;
  text-align: center;
}

.review-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(45,125,79,0.4);
}

.approve-btn {
  background: linear-gradient(135deg, #4CAF50, #45a049);
  color: white;
  text-decoration: none;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 14px;
  transition: all 0.3s ease;
  flex: 1;
  text-align: center;
}

.approve-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(76,175,80,0.4);
}

.reject-btn {
  background: linear-gradient(135deg, #f44336, #d32f2f);
  color: white;
  text-decoration: none;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 14px;
  transition: all 0.3s ease;
  flex: 1;
  text-align: center;
}

.reject-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(244,67,54,0.4);
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #666;
}

.empty-state h3 {
  font-size: 24px;
  margin-bottom: 10px;
  color: #333;
}

.empty-state p {
  font-size: 16px;
}
</style>
@endpush

@section('content')
<div class="admin-container">
  <div class="admin-header">
    <h1>🔐 Owner Verifications</h1>
    <p>Review and approve owner verification requests</p>
  </div>

  <div class="verification-table">
    <div class="table-header">
      <h2>Pending Verifications</h2>
    </div>

    @forelse($owners as $owner)
      <div class="owner-grid">
        <div class="owner-card">
          <div class="owner-header">
            <div class="owner-info">
              <h3>{{ $owner->name }}</h3>
              <p>{{ $owner->email }}</p>
            </div>
            <div class="status-badge {{ $owner->verification_status }}">
              @if($owner->verification_status === 'under_review')
                ⏳ Under Review
              @elseif($owner->verification_status === 'approved')
                ✅ Approved
              @else
                🔴 Not Verified
              @endif
            </div>
          </div>

          <div class="documents-section">
            <div class="documents-title">📄 Submitted Documents</div>
            @foreach($owner->verificationDocuments as $doc)
              <div class="document-item">
                <span class="document-name">{{ strtoupper($doc->type) }}</span>
                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="view-btn">
                  View File
                </a>
              </div>
            @endforeach
          </div>

          <div class="actions-section">
            @if($owner->verification_status !== 'approved')
              <form method="POST" action="{{ route('admin.owner-verifications.approve', $owner->id) }}" style="flex: 1;">
                @csrf
                <button type="submit" class="approve-btn" onclick="return confirm('Are you sure you want to approve this owner?')">
                  ✅ Approve
                </button>
              </form>
            @endif
            @if($owner->verification_status !== 'rejected')
              <form method="POST" action="{{ route('admin.owner-verifications.reject', $owner->id) }}" style="flex: 1;">
                @csrf
                <button type="submit" class="reject-btn" onclick="return confirm('Are you sure you want to reject this owner?')">
                  ❌ Reject
                </button>
              </form>
            @endif
          </div>
        </div>
      </div>
    @empty
      <div class="empty-state">
        <h3>📭 No Verification Requests</h3>
        <p>No owners are currently waiting for verification.</p>
      </div>
    @endforelse
  </div>
</div>
@endsection