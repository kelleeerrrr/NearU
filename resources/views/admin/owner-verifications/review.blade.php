<h2>Owner Review</h2>

<p>Name: {{ $owner->name }}</p>
<p>Email: {{ $owner->email }}</p>

<!-- FIXED: use verification_status -->
<p>Status: {{ $owner->verification_status }}</p>

<!-- APPROVE -->
<form method="POST" action="{{ route('admin.owner-verifications.approve', $owner->id) }}">
  @csrf
  <button type="submit" style="background:green;color:white;padding:8px 12px;border:none;">
    Approve
  </button>
</form>

<!-- REJECT -->
<form method="POST" action="{{ route('admin.owner-verifications.reject', $owner->id) }}">
  @csrf
  <button type="submit" style="background:red;color:white;padding:8px 12px;border:none;">
    Reject
  </button>
</form>