<table border="1" cellpadding="10" width="100%">

  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Status</th>
    <th>Documents</th>
    <th>Action</th>
  </tr>

  @foreach($owners as $owner)
    <tr>

      <!-- NAME -->
      <td>{{ $owner->name }}</td>

      <!-- EMAIL -->
      <td>{{ $owner->email }}</td>

      <!-- STATUS (IMPROVED UI) -->
      <td>
        @if($owner->verification_status === 'under_review')
            🟡 Under Review
        @elseif($owner->verification_status === 'approved')
            🟢 Approved
        @else
            🔴 Not Verified
        @endif
      </td>

      <!-- DOCUMENTS -->
      <td>
        @foreach($owner->verificationDocuments as $doc)
            <div style="margin-bottom:6px;">
                <strong>{{ strtoupper($doc->type) }}</strong><br>

                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank">
                    View File
                </a>
            </div>
        @endforeach
      </td>

      <!-- ACTIONS -->
      <td>

        <!-- REVIEW PAGE -->
        <a href="{{ route('admin.owner-verifications.review', $owner->id) }}">
          Review
        </a>

        <br><br>

        <!-- APPROVE -->
        <form method="POST" action="{{ route('admin.owner-verifications.approve', $owner->id) }}">
          @csrf
          <button type="submit" style="background:green;color:white;">
            Approve
          </button>
        </form>

        <br>

        <!-- REJECT -->
        <form method="POST" action="{{ route('admin.owner-verifications.reject', $owner->id) }}">
          @csrf
          <button type="submit" style="background:red;color:white;">
            Reject
          </button>
        </form>

      </td>

    </tr>
  @endforeach

</table>