function confirmDelete() {
  var result = confirm('Are you sure you want to delete?');
  if (result) {
    return true;
  } else {
    return false;
  }
}

// delete image in edit product
var delImg = [];
$('.delete').click(function() {
  var id = $(this).data("id");
  var token = $(this).data("token");
  msg = Lang.get('product.admin.edit.delete_confirm');
  if (confirm(msg)){
    delImg.push(id);
    document.getElementById('delImage').value = delImg;
    document.getElementById('delete'+id).remove();
    document.getElementById('deleteImage'+id).remove();  
  }
});

//change status order
$(document).ready(function () {
  var status = '';
  var id = '';
  $(document).on('change', '.status', function() {
    status = $(this).val();
    id = $(this).data("id");
    $('#note-change-order').modal('show');
  });
  $(document).on('click', '.list-notes', function() {
    $('#list-note').modal('show');
  });
  $(document).on('click', '.tracking-orders', function() {
    $('#list-tracking-order').modal('show');
  });
  $(document).on('click', '#note-change-order-submit', function(event) {
    $('.content .alert-danger').html('');
    $('.content .alert-info').html('');
    event.preventDefault();
    var content = $('#note').val();
    $.ajax({
      url: id+'/updateStatus',
      type: 'PUT',
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      dataType: 'json',
      data: {
        "status": status,
        "content": content,
      },
      success: function(response) {
        $('.content .alert-info').html(Lang.get('order.admin.show.change_status_success'));
        $('.content .alert-info').show();
        location.reload();
      },
      error: function(response) {
        errorMessage = Lang.get('order.admin.index.not_successfully') +'<br/>'+ response.responseJSON.message +'<br/>';
        if (response.responseJSON.errors) {
          errors = Object.keys(response.responseJSON.errors);
          errors.forEach(error => {
            errorMessage += response.responseJSON.errors[error] + '<br/>';
          });
        }
        $('.content .alert-danger').html(errorMessage);
        $('.content .alert-danger').show();
      }
    });
    $('#note-change-order .modal-body #note').val('');
    $('#note-change-order').modal('hide');
  });
})
