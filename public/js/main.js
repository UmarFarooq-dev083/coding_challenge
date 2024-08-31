var skeletonId = 'skeleton';
var contentId = 'content';
var skipCounter = 0;
var takeAmount = 10;


function getRequests(mode) {
  // your code here...
}

function getMoreRequests(mode) {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getConnections() {
  // your code here...
}

function getMoreConnections() {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getConnectionsInCommon(userId, connectionId) {
  // your code here...
}

function getMoreConnectionsInCommon(userId, connectionId) {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getSuggestions() {
  // your code here...
}

function getMoreSuggestions() {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}
// Ajax Request Sent
$(document).ready(function () {
  $(document).on('click', '.class-for-ajax', function () {
    
    // Get the suggestion ID from the button's id attribute
    var suggestionId = $(this).attr('id').split('_').pop();
    
    // AJAX request to send the connection request to the backend
    $.ajax({
      url: routeCreateConnectRequest, // Use JavaScript variable for URL
      type: 'POST',
      data: {
        _token: csrfToken, // Use JavaScript variable for CSRF token
        id: suggestionId, // Send the suggestion ID in the request
      },
      success: function (response) {
        // Remove the suggestion div if the request is successful
        $('#suggestion_' + suggestionId).remove();
      },
      error: function (xhr) {
        console.log('An error occurred:', xhr.responseText);
      },
    });
  });
});


function deleteRequest(userId, requestId) {
  // your code here...
}

function acceptRequest(userId, requestId) {
  // your code here...
}

function removeConnection(userId, connectionId) {
  // your code here...
}

$(function () {
  //getSuggestions();
});



document.addEventListener('DOMContentLoaded', function () {
  const tabContents = {
      'btnradio1': ['suggestions_content'],
      'btnradio2': ['sent_requests_content'],
      'btnradio3': ['received_requests_content'],
      'btnradio4': ['connections_content']
  };

  const radioButtons = document.querySelectorAll('input[name="btnradio"]');

  radioButtons.forEach(radio => {
      radio.addEventListener('change', function () {
          // Hide all tab contents
          for (const key in tabContents) {
              tabContents[key].forEach(id => {
                  const element = document.getElementById(id);
                  if (element) { // Check if element exists
                      element.classList.add('d-none');
                  }
              });
          }

          // Show the selected tab content
          if (this.checked) {
              tabContents[this.id].forEach(id => {
                  const element = document.getElementById(id);
                  if (element) { // Check if element exists
                      element.classList.remove('d-none');
                  }
              });
          }
      });
  });

  // Trigger change event on page load to display the initial tab content
  document.querySelector('input[name="btnradio"]:checked').dispatchEvent(new Event('change'));
});
