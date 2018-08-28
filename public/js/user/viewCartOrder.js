cartProduct = JSON.parse(localStorage.carts);
var products = JSON.parse(localStorage.getItem('carts'));
var order = [];
var shipping_id = '';
products.forEach(function (product) {
  product_data = {};
  product_data.id = product.id;
  product_data.name_product = product.name;
  product_data.image = product.image;
  product_data.quantity = product.count;
  product_data.price = product.price;
  order.push(product_data);
});
function validation(cartProduct) {
  var count = 0;
  let show = '';
  $.each(cartProduct, function(index, value) {
    show += '<span id="'+count+'_error" class="help-block" hidden>\
    <strong class="text-danger"></strong>\
    </span>';
  count++;
  });
  $('#form-validation').append(show);
}

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
function itemCart(cartProduct) {
  var total = 0;
  var subTotal = 0;
  var html = '';
  $.each(cartProduct, function(index, value) {
    // if (cartProduct[index]['count'] > cartProduct[index]['quantity']) {
    //   window.history.go(-1);
    // }
    total = value.count * value.price;
    subTotal += total;
    html += '<tr>\
      <td class="cart_product"><img src="'+value.img_url+'" alt="Product"></td>\
      <td class="cart_description">'+value.name+'</td>\
      <td class="price">'+ Lang.get('product.user.money') +''+value.price+'</td>\
      <td class="qty">'+value.count+'</td>\
      <td class="total" value="'+total+'">'+ Lang.get('product.user.money') +''+total+'</td>\
    </tr>';
  })
  $('#show-cart').html(html);
  $('.sub-total').attr('value',subTotal);
  $('.sub-total').html(subTotal);
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
function addOrder() {
  $(document).on('click', '#add-order', function (event) {
    event.preventDefault();
    var address = $('#address').val().trim();
    $.ajax({
      type: 'POST',
      url: '/api/orders',
      headers: {
        Accept: 'application/json',
        Authorization: 'Bearer ' +localStorage.getItem('access_token'),
      },
      data: {
        total: $('.sub-total').attr('value'),
        address: address,
        shipping_id: shipping_id,
        product: order,
      },
      success: function() {
        localStorage.removeItem('carts');
        localStorage.removeItem('count');
        window.location.href='/';
      },
      error: function (response) {
        errors = Object.keys(response.responseJSON.errors);
        errors.forEach(error => {
            errorCheck = error.split('.');
            if (errorCheck[0] == 'product') {
              $('#'+ errorCheck[1] + '_error strong').html(response.responseJSON.errors[error]) ;
              $('#'+ errorCheck[1] + '_error' ).show();  
            } else {
              $('#'+ error + '_error strong').html(response.responseJSON.errors[error]) ;
              $('#'+ error + '_error' ).show();
            }
        });
    }
    })
  })
}

$(document).ready(function() {
  $('#address').hide();
  itemCart(cartProduct);
  userInfo();
  oldAdress();
  homeAddress();
  newAddress();
  addOrder();
  validation(cartProduct);
});
