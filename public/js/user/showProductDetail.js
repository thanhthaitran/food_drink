var url = '/api'+ window.location.pathname;
var cartProduct = [];
var n = 0;
var recommend = [];

function appendHtml(response) {
  var avg_rate = 0;
  if (response.data.avg_rate != null) {
    avg_rate = response.data.avg_rate;
  }
  item = {
    category: response.data.category_id,
    rate: avg_rate,
  };
  recommend.push(item);
  localStorage.setItem('recommend', JSON.stringify(recommend));
  var rateStar = '';
  img_url = '../images/products/default-product.jpg';
  if (typeof response.data.images[0] !== 'undefined') {
    img_url = response.data.images[0].image_url;
  }
  rate = Math.round(avg_rate);
  var rate = Math.round(avg_rate);
  for (i = 1; i <= 5 ; i++) {
    if (i <= rate) {
      rateStar += '<i class="fa fa-star"></i>';
    } else {
      rateStar += '<i class="fa fa-star-o"></i>';
    }
  }

  var html = '<div class="col-xs-12 col-sm-7 col-lg-7 col-md-7 product-details-area">\
                <div class="product-name">\
                  <h1>'+response.data.name+'</h1>\
                </div>\
                <div class="price-box">\
                  <p class="special-price"><span class="price">'+Lang.get('product.user.money') + response.data.price+' </span> </p>\
                </div>\
                <div class="ratings">\
                  <div class="rating">'
                    + rateStar +
                    '<span>('+ avg_rate +')</span>\
                  </div>\
                  <p class="rating-links"> <span class="separator">'+ Lang.get('product.user.product.rate') +'</span> </p>\
                </div>\
                <div class="short-description">\
                  <h2>'+ Lang.get('product.user.product.quick_view') +'</h2>\
                  <p>'+response.data.preview+'</p>\
                </div>\
                <div class="product-variation">\
                  <form action="#" method="post">\
                    <div class="cart-plus-minus">\
                      <label for="qty">'+Lang.get('product.user.product.quantity')+'</label>\
                      <div class="numbers-row">\
                      <input type="number" class="qty" title="Qty" value="1" id="qty" name="qty" min="1" max="'+response.data.quantity+'">\
                    </div>\
                    <button class="button pro-add-to-cart" onclick="addCart('+ response.data.id +', \''+response.data.name +'\', \''+response.data.price +'\', '+response.data.quantity +', \''+ img_url +'\')" type="button">\
                      <span><i class="fa fa-shopping-cart"></i> '+ Lang.get('product.user.add_to_cart') +'</span>\
                    </button>\
                  </form>\
                </div>\
              </div>';
  $('.product-view-area').append(html);
}
function appendDescription(response) {
  var html = '<div class="tab-pane fade in active" id="description">\
                <div class="std">\
                  <p>'+response.data.description+'</p>\
                </div>\
              </div>';
  $('#description').html(html);
}
$(document).ready(function () {
  $.get(url, function(response) {
      appendHtml(response);
      appendDescription(response);
  });
  viewCart();
});
function numberItem() {
  if (localStorage.count) {
    document.getElementById('number-item').innerHTML = localStorage.count;
  }
}
numberItem();

function addCart(idProduct, nameProduct, priceProduct, quantityProduct, imgProduct) {
  var exists = false;
  item = {
    id: idProduct,
    name: nameProduct,
    price: priceProduct,
    quantity: quantityProduct,
    img_url: imgProduct,
    count: $('#qty').val(),
  };
  if (localStorage.carts) {
    cartProduct = JSON.parse(localStorage.carts);      
    $.each(cartProduct, function(index, value) {
      if(value.id === item.id) {
        value.count = parseInt(value.count) + parseInt(item.count);
        exists = true;
        return false;
      }
    });
  }    
  if(!exists) {
    cartProduct.push(item);      
    localStorage.setItem('carts', JSON.stringify(cartProduct));
    n = cartProduct.length;
    localStorage.setItem('count', n);
  }
  numberItem();
  localStorage.setItem('carts', JSON.stringify(cartProduct));  
  viewCart();
}

function viewCart() {
  if (typeof(Storage) !== 'undefined') {
    if (localStorage.carts) {
      cartProduct = JSON.parse(localStorage.carts);
      var html = '';
      var subTotal = 0;
      var total = 0;
      $.each(cartProduct, function(index, value) {
        if (cartProduct[index]['count'] > cartProduct[index]['quantity']) {
          $('.top-cart-content .quantity-stock').show();
        }
        total = value.count*value.price;
        subTotal = subTotal + total;
        html += '<li class="item">\
                  <a href="products/'+ value.id +'" title="'+ value.name +'" class="product-image"><img src="'+ value.img_url +'" alt="'+ value.name +'" width="65"></a>\
                  <div class="product-details">\
                    <p class="product-name"><a href="products/'+ value.id +'">'+ value.name +'</a> </p>\
                    <strong>'+ value.count +'</strong> x <span class="price">'+ Lang.get('product.user.money')+ value.price +'</span>\
                  </div>\
                </li>';
      });
      $('#cart-sidebar').html(html);
      $('.top-subtotal .sub-total').text(subTotal);
    }
  }
}
