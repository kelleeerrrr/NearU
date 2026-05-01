<table border="1" cellpadding="10">

  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Status</th>
    <th>Action</th>
  </tr>

  @foreach($owners as $owner)
    <tr>
      <td>{{ $owner->name }}</td>
      <td>{{ $owner->email }}</td>

      <!-- FIXED -->
      <td>{{ $owner->verification_status }}</td>

      <td>
        <a href="{{ route('admin.owner-verifications.review', $owner->id) }}">
          Review
        </a>
      </td>
    </tr>
  @endforeach

</table>