var shipping_id = '';

function userInfo() {
  $.ajax({
    type: 'GET',
    url: '/api/profile',
    headers: { 'authorization': 'Bearer '+ localStorage.getItem('access_token') },
    success: function (response){
      var html = '';
      var user = response.data.user;
      response.data.user.shipping_addresses.forEach(shipping => {
        var select = '';
        if (shipping.is_default == 1) {
          select = 'selected';
        }
        html += '<option value="'+ shipping.id +'" '+ select +'>'+ shipping.address +'</option>'
      });
      $('.form-control').html(html);
      $('#name').text(user.name);
      $('#email').text(user.email);
      $('#home-address').text(user.user_info.address);
      $('#phone').text(user.user_info.phone);
    }
  });
}

function homeAddress() {
  $(document).on('click', '#home-address-shipping', function(event) {
    event.preventDefault();
    $('#address').show();
    var homeAddress = $('#home-address').text();
    $('#address').val(homeAddress);
  });
}

function newAddress() {
  $(document).on('click', '#new-address-shipping', function(event) {
    event.preventDefault();
    $('#address').show();
    $('#address').val('');
  });
}

function oldAdress() {
  $(document).on('click', '#old-address-shipping', function(event) {
    event.preventDefault();
    $('#address').show();
    var oldAddress = $("#address-shipping option:selected").text();
    shipping_id =  $("#address-shipping option:selected").val();
    $('#address').val(oldAddress);
  });
}

$(document).ready(function() {
	userInfo();
	homeAddress();
	newAddress();
	oldAdress();
});
