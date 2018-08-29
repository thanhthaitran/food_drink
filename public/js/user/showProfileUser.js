// show user info
if (!localStorage.getItem('access_token')) {
  window.location.href = 'http://' + window.location.hostname + '/';  
}
function showProfileUser() {
  $.ajax({
    type: 'GET',
    url: '/api/profile',
    headers: { 'authorization': 'Bearer '+ localStorage.getItem('access_token') },
    success: function (response){
      var shippingAddresses = response.data.user.shipping_addresses;
      var html = '';
      var add = '';
      $("#user-avatar").attr('src', response.data.user.user_info.avatar_url);
      $("#user-name").html(response.data.user.name);
      $("#user-address").html(response.data.user.user_info.address);
      $("#user-phone").html(response.data.user.user_info.phone);
      $("#user-email").html(response.data.user.email);
      shippingAddresses.forEach(shipping => {
        html += '<div id="del'+ shipping.id +'">\
                  <p>'+ shipping.address +'</p>\
                  <button class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></button>\
                  <button delete-id="'+ shipping.id +'" class="btn btn-sm btn-danger delete-address"><i class="glyphicon glyphicon-remove"></i></button>\
                  <hr>\
                </div>'
      });
      $('#user-address-shipping').html(html);
      add = '<div>\
              <p class="new-address">'+ Lang.get('user.user.profile.new_address') +'</p>\
              <button class="btn btn-sm btn-success address-shipping"><i class="fa fa-plus-square"></i></button>\
            </div>';
      $('#user-address-shipping').append(add);
    }
  });
}
$(document).ready(function() {
  showProfileUser();
});
