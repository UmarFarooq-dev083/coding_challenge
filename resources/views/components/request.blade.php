@foreach($data['sentRequests'] as $sent)
<div class="my-2 shadow text-white bg-dark p-1" id="withraw_{{$sent->id}}">
  <div class="d-flex justify-content-between">
    <table class="ms-1">
      <td class="align-middle">{{$sent->receiver->name}}</td>
      <td class="align-middle"> - </td>
      <td class="align-middle">{{$sent->receiver->email}}</td>
      <td class="align-middle">
    </table>
    <div>
      <button id="cancel_request_btn_{{$sent->id}}" class="btn btn-danger me-1 ajax-withraw-class" onclick="">Withdraw Request</button>
    </div>
  </div>
</div>
@endforeach


<script>
  $(document).ready(function () {
  $(document).on('click', '.ajax-withraw-class', function () {
    var withraw_request_id = $(this).attr('id').split('_').pop();
    var route_withraw_request = '{{ route("connect.request.withraw") }}';
    var csrfToken = '{{ csrf_token() }}';
    $.ajax({
      url: route_withraw_request, 
      type: 'POST',
      data: {
        _token: csrfToken, 
        id: withraw_request_id, 
      },
      success: function (response) {
        $('#withraw_' + withraw_request_id).remove();
      },
      error: function (xhr) {
        console.log('An error occurred:', xhr.responseText);
      },
    });
  });
});

</script>