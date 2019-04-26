function checkLogin() {
  $.ajax({
    type: 'GET',
    url: '/api/checkAccessToken',
    headers: ({
      Accept: 'application/json',
      Authorization: 'Bearer ' + accessToken,
    }),
    success: function (response){
      $('.links .login').hide();
    },
    error: function () {
      window.localStorage.removeItem('access_token');
      localStorage.removeItem('data');
      $('.links .myaccount').hide();
      $('.links .logout').hide();
      window.location.href = 'http://' + window.location.hostname + '/';
    }
  });
}

function pagination(paginationID, response, getData, typePost) {
  if(response.data.last_page > 1) {
    $('#'+ paginationID).pagination({
      total: response.data.total,
      current: response.data.current_page,
      length: response.data.per_page,
      size: 2,
      // prev: 'Previous',
      // next: 'Next',
      click: function(options,$target) {
        urlItem = response.data.first_page_url.split('page', 1);
        urlPage = urlItem[0] +'page='+ options.current;
        if (typePost) {
          getData(urlPage, typePost);
        } else {
          getData(urlPage);
        }
      }
    });
  } else {
    $('#'+ paginationID).html('');
  }
}

// function pagination(response, prev, next, IDPagination) {
//   var a = '';
//   var urlPage = '';
//   var urlItem = '';
//   for(var i = 1; i <= response.data.last_page; i++) {
//     urlItem = response.data.first_page_url.split('page', 1);
//     urlPage = urlItem[0] +'page='+i;
//     if(response.data.current_page == 1) {
//       $('#'+ prev).addClass('disabled');
//     } else {
//       $('#'+ prev).removeClass('disabled');
//     }
//     if (response.data.current_page == response.data.last_page) {
//       $('#'+ next).addClass('disabled');
//     } else {
//       $('#'+ next).removeClass('disabled');
//     }
//     if(i == response.data.current_page) {
//       a = a + '<li>\
//               <a class="paginate-item current-page" href="'+ urlPage +'" style="color: #fff">'+ i +'</a>\
//             </li>';
//     } else{
//       a = a + '<li>\
//                 <a class="paginate-item" href="'+ urlPage +'" style="color: #fff">'+ i +'</a>\
//               </li>';
//     }
//   }
//   $('#'+ IDPagination).html(a);
// }

// function paginateItem(IDPagination, getAjax) {
//   var $main = $('#'+ IDPagination);
//   $main.on('click', '.paginate-item', function(event) {
//     event.preventDefault();
//     urlPaginate = $(this).attr('href');
//     getAjax(urlPaginate);
//   });
// }

$( document ).ready(function() {
  $('#product-search').on('submit', function (event) {
    event.preventDefault();
    query = $('#product-search').find('input[name="name"]').val();
    url =  "/products?name=" + query;
    window.location.href = url;
  });
  accessToken = localStorage.getItem('access_token');
  if (accessToken) {
    $('.links .login').hide();
    $('.links .register').hide();
    $('.please-login').hide();
    $('.actions .login').hide();
    checkLogin();
  } else {
    $('.language-currency-wrapper .myaccount').hide();
    $('.cart_navigation .checkout-btn').hide();
    $('.actions .add-order').hide();
    window.location.host;
  }
  //translate
  if (window.sessionStorage.getItem('locale')) {
    Lang.setLocale(window.sessionStorage.getItem('locale'));
  }

  $('.locale').on('click', function (event) {
    event.preventDefault();
    url = $(this).attr('href');
    $.ajax({
      url: url,
      type: 'GET',
      success: function(response) {
        window.sessionStorage.setItem('locale', response.locale);
        window.location.reload();
      }
    });
  });

  $('#logout').on('click', function (event) {
    event.preventDefault();
    if (accessToken) {
      $.ajax({
        url: '/api/logout',
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer ' + accessToken
        },
        type: 'POST',
        success: function (response) {
          localStorage.removeItem('access_token');
          localStorage.removeItem('data');
          window.location.href = 'http://' + window.location.hostname + '/';
        }
      });
    }
  });
});
