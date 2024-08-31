@foreach($data['connections'] as $key => $connection)
<div class="my-2 shadow text-white bg-dark p-1" id="connection_{{$connection['pivot']['connected_user_id']}}">
  <div class="d-flex justify-content-between">
    <table class="ms-1">
      <td class="align-middle">{{$connection->name}}</td>
      <td class="align-middle"> - </td>
      <td class="align-middle">{{$connection->email}}</td>
      <td class="align-middle">
    </table>
    <div>
      <button style="width: 220px" id="get_connections_in_common_" class="btn btn-primary" type="button"
        data-bs-toggle="collapse" data-bs-target="#collapse_" aria-expanded="false" aria-controls="collapseExample">
        Connections in common ({{$data['commonConnections'][$key]['common_connections_count'] }})
      </button>
      <button id="create_request_btn_{{$connection['pivot']['connected_user_id']}}" class="btn btn-danger me-1 ajax-remove-connection-class">Remove Connection</button>
    </div>
  </div>
</div>
@endforeach

<script>
  $(document).ready(function () {
  $(document).on('click', '.ajax-remove-connection-class', function () {
    var destroy_request_id = $(this).attr('id').split('_').pop();
    var route_destroy_request = '{{ route("connect.request.destroy") }}';
    var csrfToken = '{{ csrf_token() }}';
    $.ajax({
      url: route_destroy_request, 
      type: 'POST',
      data: {
        _token: csrfToken, 
        id: destroy_request_id, 
      },
      success: function (response) {
        $('#connection_' + destroy_request_id).remove();
      },
      error: function (xhr) {
        console.log('An error occurred:', xhr.responseText);
      },
    });
  });
});

</script>
