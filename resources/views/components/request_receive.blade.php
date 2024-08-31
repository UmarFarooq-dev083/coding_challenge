@foreach($data['receivedRequests'] as $receive)
<div class="my-2 shadow text-white bg-dark p-1" id="receiver_{{$receive->id}}">
  <div class="d-flex justify-content-between">
    <table class="ms-1">
      <td class="align-middle">{{$receive->sender->name}}</td>
      <td class="align-middle"> - </td>
      <td class="align-middle">{{$receive->sender->email}}</td>
      <td class="align-middle">
    </table>
    <div>
        <button id="accept_request_btn_{{$receive->id}}" class="btn btn-primary me-1 class-for-ajax-accept" onclick="">Accept</button>
    </div>
  </div>
</div>
@endforeach


<script src="{{ asset('public/js/main.js') }}"></script>

<script>
  $(document).ready(function () {
  $(document).on('click', '.class-for-ajax-accept', function () {
    var accept_request_id = $(this).attr('id').split('_').pop();
    var route_accept_request = '{{ route("accept.connect.request") }}';
    var csrfToken = '{{ csrf_token() }}';
    $.ajax({
      url: route_accept_request, 
      type: 'POST',
      data: {
        _token: csrfToken, // Use JavaScript variable for CSRF token
        id: accept_request_id, // Send the suggestion ID in the request
      },
      success: function (response) {
        // Remove the suggestion div if the request is successful
        $('#receiver_' + accept_request_id).remove();
      },
      error: function (xhr) {
        console.log('An error occurred:', xhr.responseText);
      },
    });
  });
});

</script>