@extends('layouts.app')

@section('title', 'Review Owner Verification - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <div class="page-header">
        <div class="header-left">
          <h2>🔍 Review Owner Verification</h2>
          <p>Review owner details and verification documents</p>
        </div>
        <div class="header-right">
          <a href="{{ route('admin.owner-verifications.index') }}" class="btn btn-back">
            ← Back to Verifications
          </a>
        </div>
      </div>

      <div class="review-card">
        <!-- Owner Info -->
        <div class="owner-info">
          <div class="owner-details">
            <h3>{{ $owner->name }}</h3>
            <p class="owner-email">{{ $owner->email }}</p>
            <p class="owner-phone">{{ $owner->phone ?? 'Not provided' }}</p>
            <p class="owner-address">{{ $owner->address ?? 'Not provided' }}</p>
          </div>
          
          <!-- Status Badge -->
          <div class="status-badge">
            @if($owner->verification_status === 'under_review')
              <span class="badge pending">⏳ Under Review</span>
            @elseif($owner->verification_status === 'approved')
              <span class="badge approved">✅ Approved</span>
            @else
              <span class="badge not-verified">❌ Not Verified</span>
            @endif
          </div>
        </div>

        <!-- Documents Section -->
        <div class="documents-section">
          <h4>📁 Verification Documents</h4>
          @if($owner->verificationDocuments->count() > 0)
            <div class="documents-grid">
              @foreach($owner->verificationDocuments as $doc)
                <div class="document-item">
                  <div class="doc-type">{{ strtoupper($doc->type) }}</div>
                  <button class="doc-btn" onclick="openDocumentModal('{{ asset('storage/' . $doc->file_path) }}', '{{ strtoupper($doc->type) }}')">
                    View Document
                  </button>
                </div>
              @endforeach
            </div>
          @else
            <div class="no-documents">
              <p>No verification documents uploaded.</p>
            </div>
          @endif
        </div>

        <!-- Additional Info -->
        <div class="additional-info">
          <h4>📋 Additional Information</h4>
          <div class="info-grid">
            <div class="info-item">
              <span class="info-label">User Type:</span>
              <span class="info-value">{{ ucfirst($owner->user_type) }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Created At:</span>
              <span class="info-value">{{ $owner->created_at->format('M d, Y') }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Last Updated:</span>
              <span class="info-value">{{ $owner->updated_at->format('M d, Y') }}</span>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="actions-section">
          <div class="action-buttons">
            @if($owner->verification_status === 'under_review')
              <form method="POST" action="{{ route('admin.owner-verifications.approve', $owner->id) }}" class="inline-form">
                @csrf
                <button type="submit" class="btn btn-green" onclick="return confirm('Are you sure you want to approve this owner?')">
                  ✅ Approve
                </button>
              </form>
              
              <form method="POST" action="{{ route('admin.owner-verifications.reject', $owner->id) }}" class="inline-form">
                @csrf
                <button type="submit" class="btn btn-red" onclick="return confirm('Are you sure you want to reject this owner?')">
                  ❌ Reject
                </button>
              </form>
            @elseif($owner->verification_status === 'approved')
              <form method="POST" action="{{ route('admin.owner-verifications.reject', $owner->id) }}" class="inline-form">
                @csrf
                <button type="submit" class="btn btn-red" onclick="return confirm('Are you sure you want to reject this approved owner?')">
                  ❌ Reject
                </button>
              </form>
            @else
              <form method="POST" action="{{ route('admin.owner-verifications.approve', $owner->id) }}" class="inline-form">
                @csrf
                <button type="submit" class="btn btn-green" onclick="return confirm('Are you sure you want to approve this owner?')">
                  ✅ Approve
                </button>
              </form>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bottom spacing for floating nav -->
  <div class="bottom-spacer"></div>

  <!-- Document Modal -->
  <div id="documentModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="modalTitle">Document Viewer</h3>
        <button class="modal-close" onclick="closeDocumentModal()">&times;</button>
      </div>
      <div class="modal-body">
        <img id="modalImage" src="" alt="Document" style="max-width: 100%; max-height: 65vh; object-fit: contain; display: block; margin: 0 auto;">
      </div>
    </div>
  </div>

  @include('partials.footer')
</div>
@endsection

@push('scripts')
<script>
function openDocumentModal(imageSrc, docType) {
  const modal = document.getElementById('documentModal');
  const modalImage = document.getElementById('modalImage');
  const modalTitle = document.getElementById('modalTitle');
  
  modalImage.src = imageSrc;
  modalTitle.textContent = docType + ' Document';
  modal.classList.add('show');
}

function closeDocumentModal() {
  const modal = document.getElementById('documentModal');
  if (modal) {
    modal.classList.remove('show');
  }
}

// Close modal when clicking outside or pressing ESC
window.onclick = function(event) {
  const modal = document.getElementById('documentModal');
  if (event.target === modal) {
    closeDocumentModal();
  }
}

// ESC key support
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    closeDocumentModal();
  }
});
</script>
@endpush

@push('styles')
<style>
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1rem;
  gap: 1rem;
}

.header-left h2 {
  font-family: 'Syne', sans-serif;
  font-size: 1.35rem;
  font-weight: 800;
  color: var(--t1);
  margin-bottom: 0.25rem;
}

.header-left p {
  color: var(--t2);
  font-size: 0.8rem;
  margin: 0;
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
  text-decoration: none;
  color: #fff;
}

.review-card {
  background: #fff;
  border: 2px solid #2D7D4F;
  border-radius: 18px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}

.review-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(45, 125, 79, 0.15);
}

.owner-info {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid rgba(45, 125, 79, 0.1);
}

.owner-details h3 {
  font-family: 'Syne', sans-serif;
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--t1);
  margin-bottom: 0.5rem;
}

.owner-details p {
  color: var(--t2);
  font-size: 0.9rem;
  margin: 0.25rem 0;
}

.status-badge .badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border-radius: 25px;
  font-size: 0.8rem;
  font-weight: 700;
  border: 2px solid;
}

.badge.pending {
  background: #F2B705;
  color: #1F2933;
  border-color: #F2B705;
  box-shadow: 0 2px 8px rgba(242, 183, 5, 0.3);
}

.badge.approved {
  background: #2D7D4F;
  color: #fff;
  border-color: #2D7D4F;
  box-shadow: 0 2px 8px rgba(45, 125, 79, 0.3);
}

.badge.not-verified {
  background: #C8102E;
  color: #fff;
  border-color: #C8102E;
  box-shadow: 0 2px 8px rgba(200, 16, 46, 0.3);
}

.documents-section {
  margin-bottom: 1.5rem;
}

.documents-section h4 {
  font-size: 1rem;
  font-weight: 700;
  color: var(--t1);
  margin-bottom: 1rem;
}

.documents-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.document-item {
  background: #fff;
  border: 2px solid #2D7D4F;
  border-radius: 12px;
  padding: 1rem;
  text-align: center;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
}

.doc-type {
  font-size: 0.9rem;
  font-weight: 700;
  color: #F2B705;
  margin-bottom: 0.75rem;
}

.doc-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  color: #2D7D4F;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.8rem;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  background: rgba(45, 125, 79, 0.1);
  transition: background 0.2s;
  border: none;
  cursor: pointer;
}

.doc-btn:hover {
  background: rgba(45, 125, 79, 0.2);
  text-decoration: none;
  color: #2D7D4F;
}

.no-documents {
  text-align: center;
  padding: 2rem;
  color: var(--t2);
  background: rgba(45, 125, 79, 0.05);
  border-radius: 12px;
  border: 1px dashed rgba(45, 125, 79, 0.3);
}

.additional-info {
  margin-bottom: 1.5rem;
}

.additional-info h4 {
  font-size: 1rem;
  font-weight: 700;
  color: var(--t1);
  margin-bottom: 1rem;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.info-item {
  display: flex;
  justify-content: space-between;
  padding: 0.75rem;
  background: rgba(45, 125, 79, 0.05);
  border-radius: 8px;
  border: 1px solid rgba(45, 125, 79, 0.1);
}

.info-label {
  font-weight: 600;
  color: var(--t2);
  font-size: 0.9rem;
}

.info-value {
  font-weight: 600;
  color: var(--t1);
  font-size: 0.9rem;
}

.actions-section {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid rgba(45, 125, 79, 0.1);
}

.action-buttons {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
}

.action-buttons .btn,
.action-buttons form {
  display: inline-flex;
  align-items: center;
  vertical-align: middle;
  height: fit-content;
}

.inline-form {
  display: inline-flex;
  align-items: center;
  vertical-align: middle;
  margin: 0;
  padding: 0;
  border: none;
  background: none;
  height: 40px;
  box-sizing: border-box;
  line-height: 0;
  font-size: 0;
}

.action-buttons form .btn {
  margin: 0;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border-radius: 25px;
  font-size: 0.85rem;
  font-weight: 700;
  text-decoration: none;
  border: 2px solid transparent;
  cursor: pointer;
  transition: all 0.2s;
  text-align: center;
  box-shadow: 0 2px 8px rgba(45, 125, 79, 0.2);
  height: 40px;
  line-height: 1;
  white-space: nowrap;
  box-sizing: border-box;
  vertical-align: middle;
  min-width: 120px;
}

/* Ensure a.btn and button.btn are identical */
a.btn, button.btn {
  margin: 0;
  font-family: inherit;
  font-size: 0.85rem;
  font-weight: 700;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  height: 40px;
  box-sizing: border-box;
  vertical-align: middle;
  min-width: 120px;
  text-align: center;
}

a.btn {
  text-decoration: none;
  padding: 0.75rem 1.5rem;
}

button.btn {
  border: none;
  padding: 0.75rem 1.5rem;
}

.btn:active {
  transform: scale(0.97);
}

.btn-green {
  background: linear-gradient(135deg, #2D7D4F, #1f5c38);
  color: #fff;
  border-color: #2D7D4F;
}

.btn-green:hover {
  background: linear-gradient(135deg, #1f5c38, #2D7D4F);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.3);
}

.btn-red {
  background: linear-gradient(135deg, #C8102E, #a00c24);
  color: #fff;
  border-color: #C8102E;
}

.btn-red:hover {
  background: linear-gradient(135deg, #a00c24, #C8102E);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(200, 16, 46, 0.3);
}

.bottom-spacer {
  height: 100px;
  width: 100%;
}

/* Modal Styles */
.modal {
  display: none;
  position: fixed;
  z-index: 99999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8);
  align-items: center;
  justify-content: center;
}

.modal.show {
  display: flex;
}

.modal-content {
  background: linear-gradient(to bottom, #e8f7ee 0%, #f0f9e8 30%, #fff9e6 70%, #fef3d2 100%);
  border: 2px solid transparent;
  background-image: linear-gradient(to bottom, #e8f7ee 0%, #f0f9e8 30%, #fff9e6 70%, #fef3d2 100%), 
                    linear-gradient(to bottom, #2D7D4F 0%, #4a9d6a 20%, #8bc34a 40%, #cddc39 60%, #f2b705 80%, #fef3d2 100%);
  background-origin: border-box;
  background-clip: padding-box, border-box;
  border-radius: 18px;
  padding: 0;
  max-width: 95%;
  max-height: 95vh;
  box-shadow: 0 8px 32px rgba(45, 125, 79, 0.3);
  overflow: hidden;
  margin: auto;
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: stretch;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid rgba(242, 183, 5, 0.2);
  background: rgba(255, 255, 255, 0.5);
}

.modal-header h3 {
  font-family: 'Syne', sans-serif;
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--t1);
  margin: 0;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: var(--t2);
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 50%;
  transition: all 0.2s;
}

.modal-close:hover {
  background: rgba(45, 125, 79, 0.1);
  color: #2D7D4F;
}

.modal-body {
  padding: 1.5rem;
  text-align: center;
  background: rgba(255, 255, 255, 0.3);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

@media (max-width: 768px) {
  .modal-content {
    max-width: 95%;
    margin: 1rem;
  }
  
  .modal-header {
    padding: 0.75rem 1rem;
  }
  
  .modal-header h3 {
    font-size: 1rem;
  }
  
  .modal-body {
    padding: 1rem;
  }
  
  .owner-info {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
    position: relative;
  }
  
  .status-badge {
    position: absolute;
    top: 0;
    right: 0;
  }
  
  .actions-section {
    flex-direction: column;
    align-items: stretch;
  }
  
  .action-buttons {
    justify-content: center;
  }
  
  .documents-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .info-grid {
    grid-template-columns: 1fr;
  }
}
</style>
@endpush
