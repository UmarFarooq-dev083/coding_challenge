@foreach ($suggestions as $suggestion)
<div class="my-2 shadow text-white bg-dark p-1" id="suggestion_{{ $suggestion->id }}">
  <div class="d-flex justify-content-between">
    <table class="ms-1">
      <tr>
        <td class="align-middle">{{ $suggestion->name }}</td>
        <td class="align-middle"> - </td>
        <td class="align-middle">{{ $suggestion->email }}</td>
      </tr>
    </table>
    <div>
      <button id="create_request_btn_{{ $suggestion->id }}" class="btn btn-primary me-1 class-for-ajax">Connect</button>
    </div>
  </div>
</div>
@endforeach

<!-- Include your JavaScript file after the HTML elements -->

<script src="{{ asset('public/js/main.js') }}"></script>
<script>
  // Pass route and csrf token to JavaScript
  var routeCreateConnectRequest = '{{ route("create.connect.request") }}';
  var csrfToken = '{{ csrf_token() }}';
</script>


