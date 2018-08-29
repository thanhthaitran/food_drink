function addAddress() {
  var address = $('#address').val();
  $('.panel-body .alert-info').html('');
  $('.panel-body .alert-danger').html('');
  $.ajax({
    url: '/api/shippings',
    type: 'POST',
    headers: { 'authorization': 'Bearer '+ localStorage.getItem('access_token') },
    data: {
      address: address,
    },
    success: function(response) {
      showProfileUser();
      $('.panel-body .alert-info').html(Lang.get('user.user.shipping.create_success'));
      $('.panel-body .alert-info').show();
    },
    error: function(response) {
      errorMessage = Lang.get('user.user.shipping.not_success') +'<br/>'+ response.responseJSON.message +'<br/>';
      if (response.responseJSON.errors) {
        errors = Object.keys(response.responseJSON.errors);
        errors.forEach(error => {
          errorMessage += response.responseJSON.errors[error] + '<br/>';
        });
      }
      $('.panel-body .alert-danger').html(errorMessage);
      $('.panel-body .alert-danger').show();
    }
  });
}

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

  $(document).on('click', '.address-shipping' ,function (event) { 
    event.preventDefault();
    $('#address-shipping').modal('show');
  });

  $(document).on('click', '#add-address-shipping' ,function (event) { 
    event.preventDefault();
    addAddress();
    $('#address-shipping').modal('hide');
  });
});
