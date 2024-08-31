<div class="row justify-content-center mt-5">
  <div class="col-12">
    <div class="card shadow text-white bg-dark">
      <div class="card-header">Coding Challenge - Network connections</div>
      <div class="card-body">
        <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
          <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
          <label class="btn btn-outline-primary" for="btnradio1" id="get_suggestions_btn">Suggestions ({{ $data['suggestionsCount'] }} )</label>

          <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
          <label class="btn btn-outline-primary" for="btnradio2" id="get_sent_requests_btn">Sent Requests ({{ $data['sentRequestsCount'] }})</label>

          <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
          <label class="btn btn-outline-primary" for="btnradio3" id="get_received_requests_btn">Received Requests ({{ $data['receivedRequestsCount'] }})</label>

          <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off">
          <label class="btn btn-outline-primary" for="btnradio4" id="get_connections_btn">Connections ({{ $data['connectionsCount'] }})</label>
        </div>
        <hr>
        <div id="content">

          <!-- Suggestions -->
          <div id="suggestions_content" class="d-none">
            <x-suggestion :suggestions="$data['suggestions']" />
          </div>

          <!-- Sent Requests -->
          <div id="sent_requests_content" class="d-none">
            <x-request :mode="'send'" :data="$data" />
          </div>

          <!-- Received Requests -->
          <div id="received_requests_content" class="d-none">
            <x-request_receive :mode="'received'" :data="$data" />
          </div>

          <!-- Connections -->
          <div id="connections_content" class="d-none">
            <x-connection :data="$data"/>
          </div>
        </div>
        <div id="forappend">
        
        </div>

      </div>
    </div>
  </div>
</div>

<script>
 /* This Function is to get active tab name and display Read More Button According to
 *  The Selected Tab So we dont need to write a seprate function on Backend Side For 
 *  Every Tab In this way we can use Only One Controller Function and One Route On the Backend Side
 */ 
  $(document).ready(function() {
    function getActiveTabName() {
      var activeTab = $('input[name="btnradio"]:checked').attr('id');

      $('#forappend').empty(); 
      if (activeTab === 'btnradio1') {
        $('#forappend').append('<div class="d-flex justify-content-center mt-2 py-3" id="suggestions"><button class="btn btn-primary load-more" data-section="suggestions" data-page="1">Load More</button><div class="spinner-border text-primary ms-2 d-none" role="status" id="loading-spinner"><span class="visually-hidden">Loading...</span></div></div>');
      } else if (activeTab === 'btnradio2') {
        $('#forappend').append('<div class="d-flex justify-content-center mt-2 py-3" id="sentRequests"><button class="btn btn-primary load-more" data-section="sentRequests" data-page="1">Load More</button><div class="spinner-border text-primary ms-2 d-none" role="status" id="loading-spinner"><span class="visually-hidden">Loading...</span></div></div>');
      } else if (activeTab === 'btnradio3') {
        $('#forappend').append('<div class="d-flex justify-content-center mt-2 py-3" id="receivedRequests"><button class="btn btn-primary load-more" data-section="receivedRequests" data-page="1">Load More</button><div class="spinner-border text-primary ms-2 d-none" role="status" id="loading-spinner"><span class="visually-hidden">Loading...</span></div></div>');
      } else if (activeTab === 'btnradio4') {
        $('#forappend').append('<div class="d-flex justify-content-center mt-2 py-3" id="connectionRequest"><button class="btn btn-primary load-more" data-section="connectionRequest" data-page="1">Load More</button><div class="spinner-border text-primary ms-2 d-none" role="status" id="loading-spinner"><span class="visually-hidden">Loading...</span></div></div>');
      }

      return activeTab;
    }
    // Run when page load
    getActiveTabName();
    // Listen for changes in radio buttons
    $('input[name="btnradio"]').on('change', function() {
      getActiveTabName();
    });
  });
</script>


<script>
/* This Ajax request is handling all the load more request so if want to check
 * load more function this is the only function for all tabs load more function
 */
  $(document).on('click', '.load-more', function() {
    var button = $(this);
    var section = button.data('section');
    var page = button.data('page') + 1;

    $('#loading-spinner').removeClass('d-none');
    button.prop('disabled', true);

    $.ajax({
      url: '{{ route("network.loadMore") }}',
      type: 'GET',
      data: {
        section: section,
        page: page
      },
      success: function(data) {
            if (data.length === 0) {
                // disable button if no more records
                button.prop('disabled', true);
                button.text('No more records');
            } else {
                $.each(data, function(index, item) {
                    if(section === 'suggestions') {
                        $('#suggestions_content').append('<div class="my-2 shadow text-white bg-dark p-1" id="suggestion_'+item.id+'"><div class="d-flex justify-content-between"><table class="ms-1"><tr><td class="align-middle">'+item.name+'</td><td class="align-middle"> - </td><td class="align-middle">'+item.email+'</td></tr></table><div><button id="create_request_btn_'+item.id+'" class="btn btn-primary me-1 class-for-ajax">Connect</button></div></div></div>');
                    } else if (section === 'sentRequests') {
                        $('#sent_requests_content').append('<div class="my-2 shadow text-white bg-dark p-1" id="'+item.receiver.id+'"><div class="d-flex justify-content-between"><table class="ms-1"><td class="align-middle">'+item.receiver.name+'</td><td class="align-middle"> - </td><td class="align-middle">'+item.receiver.email+'</td><td class="align-middle"></table><div><button id="cancel_request_btn_'+item.receiver.id+'" class="btn btn-danger me-1 ajax-withraw-class" onclick="">Withdraw Request</button></div></div></div>');
                    } else if (section === 'receivedRequests') {
                        $('#received_requests_content').append('<div class="my-2 shadow text-white bg-dark p-1" id="'+item.sender.id+'"><div class="d-flex justify-content-between"><table class="ms-1"><td class="align-middle">'+item.sender.name+'</td><td class="align-middle"> - </td><td class="align-middle">'+item.sender.email+'</td><td class="align-middle"></table><div><button id="accept_request_btn_'+item.sender.id+'" class="btn btn-primary me-1 class-for-ajax-accept" onclick="">Accept</button></div></div></div>');
                    }
                });

                $.each(data.first, function(index_second, item_second) {
                    if(section === 'connectionRequest') {
                      $('#connections_content').append('<div class="my-2 shadow text-white bg-dark p-1" id="connection_'+item_second.pivot.connected_user_id+'"><div class="d-flex justify-content-between"><table class="ms-1"><td class="align-middle">'+item_second.name+'</td><td class="align-middle"> - </td><td class="align-middle">'+item_second.email+'</td><td class="align-middle"></table><div><button style="width: 220px" id="get_connections_in_common_" class="btn btn-primary" type="button"data-bs-toggle="collapse" data-bs-target="#collapse_" aria-expanded="false" aria-controls="collapseExample">Connections in common ('+data.second[index_second].common_connections_count+')</button><button id="create_request_btn_'+item_second.pivot.connected_user_id+'" class="btn btn-danger me-1 ajax-remove-connection-class">Remove Connection</button></div></div></div>');
                    }
                });

                button.data('page', page + 1);
                button.prop('disabled', false);
            }
        },
      complete: function() {
        $('#loading-spinner').addClass('d-none');
        button.prop('disabled', false);
      }
    });
  });
</script>
