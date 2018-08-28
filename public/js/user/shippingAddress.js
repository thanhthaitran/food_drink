$(document).ready(function() {
  $(document).on('click', '.delete-address', function(event) {
    event.preventDefault();
    var id = $(this).attr('delete-id');
    msg = 'Are you sure delete this address?';
    if (confirm(msg)) {
      $.ajax({
        type: 'DELETE',
        url: '/api/shippings/'+ id,
        headers: {
          Accept: 'application/json',
          Authorization: 'Bearer ' + localStorage.getItem('access_token')
        },
        success: function (response){
          $('#del'+ id).hide();
        }
      });
    }
  });
});
