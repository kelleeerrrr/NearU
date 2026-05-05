<h2>Owner Review</h2>

<!-- OWNER INFO -->
<p><strong>Name:</strong> {{ $owner->name }}</p>
<p><strong>Email:</strong> {{ $owner->email }}</p>

<!-- STATUS (IMPROVED) -->
<p>
  <strong>Status:</strong>

  @if($owner->verification_status === 'under_review')
      🟡 Under Review
  @elseif($owner->verification_status === 'approved')
      🟢 Approved
  @else
      🔴 Not Verified
  @endif
</p>

<hr>

<!-- DOCUMENTS -->
<h3>Verification Documents</h3>

@if($owner->verificationDocuments->count())

    @foreach($owner->verificationDocuments as $doc)

        <div style="margin-bottom:10px; padding:10px; border:1px solid #ddd;">

            <strong>{{ strtoupper($doc->type) }}</strong><br>

            <!-- VIEW FILE -->
            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank">
                View Document
            </a>

        </div>

    @endforeach

@else
    <p>No documents uploaded.</p>
@endif

<hr>

<!-- ACTIONS -->
<div style="display:flex; gap:10px;">

    <!-- APPROVE -->
    <form method="POST" action="{{ route('admin.owner-verifications.approve', $owner->id) }}">
        @csrf
        <button type="submit"
            style="background:green;color:white;padding:8px 12px;border:none;cursor:pointer;">
            Approve
        </button>
    </form>

    <!-- REJECT -->
    <form method="POST" action="{{ route('admin.owner-verifications.reject', $owner->id) }}">
        @csrf
        <button type="submit"
            style="background:red;color:white;padding:8px 12px;border:none;cursor:pointer;">
            Reject
        </button>
    </form>

</div>