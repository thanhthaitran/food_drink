// add address
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
      errorMessage = Lang.get('user.user.shipping.create_not_success') +'<br/>'+ response.responseJSON.message +'<br/>';
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
  // edit address
  $(document).on('click', '.edit-address', function(event) {
    event.preventDefault();
    var id = $(this).attr('edit-id');
    action =$(this).attr('data-action');
    if(action == 'view') {
      $('#address' + id).replaceWith(function() {
        return '<input class="form-control" id="input'+ id +'" type="text" value ="' + $('#address'+id).text() +'" />';
      });
      $(this).attr('data-action', 'edit');
    } else {
      address = $('#input'+ id).val();
      $('.panel-body .alert-info').html('');
      $('.panel-body .alert-danger').html('');
      $.ajax({
        url: ' api/shippings/'+ id,
        type: 'PUT',
        headers: {
          "Accept": "application/json",
          "Authorization": "Bearer " + accessToken
        },
        data: {
            "address": address,
        },
        success: function (response){
          $('#input'+ id).replaceWith(function () {
              return '<p id="address'+ id +'">'+ response.data.address +'<p/>';
          });
          $('.panel-body .alert-info').html(Lang.get('user.user.shipping.edit_success'));
          $('.panel-body .alert-info').show();
        },
        error: function(response) {
          errorMessage = Lang.get('user.user.shipping.edit_not_success') +'<br/>'+ response.responseJSON.message +'<br/>';
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
      $(this).attr('data-action', 'view');
    }
  });

  // delete address
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
          $('.panel-body .alert-info').html('');          
          $('.panel-body .alert-danger').html('');
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
