// show user info
if (!localStorage.getItem('access_token')) {
  window.location.href = 'http://' + window.location.hostname + '/';  
}
const ADDRESS_DEFAULT = 1;
function showProfileUser() {
  $.ajax({
    type: 'GET',
    url: '/api/profile',
    headers: { 'authorization': 'Bearer '+ localStorage.getItem('access_token') },
    success: function (response){
      var shippingAddresses = response.data.user.shipping_addresses;
      var html = '';
      var add = '';
      var avatar_url = '../images/users/default-user-avatar.png';
      if (response.data.user.user_info.avatar != null) {
        avatar_url = response.data.user.user_info.avatar_url;
      }
      $("#user-avatar").attr('src', avatar_url);
      $("#user-name").html(response.data.user.name);
      $("#user-address").html(response.data.user.user_info.address);
      $("#user-phone").html(response.data.user.user_info.phone);
      $("#user-email").html(response.data.user.email);
      shippingAddresses.forEach(shipping => {
        var address_default = '';
        if (shipping.is_default == ADDRESS_DEFAULT) {
          address_default = '<span class="address-default">default</span>';
        }
        html += '<div id="del'+ shipping.id +'">\
                  <p id="address'+ shipping.id +'">'+ shipping.address +'</p>\
                  <button data-action="view" edit-id="'+ shipping.id +'" class="btn btn-sm btn-warning edit-address"><i class="glyphicon glyphicon-edit"></i></button>\
                  <button delete-id="'+ shipping.id +'" class="btn btn-sm btn-danger delete-address"><i class="glyphicon glyphicon-remove"></i></button>\
                  '+ address_default +'\
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
