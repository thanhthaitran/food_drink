@extends('user.layout.master')
@section('title', __('user.user.profile.title'))
@section('content')
<div class="container">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 toppad" >
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">{{ __('user.user.profile.profile') }}</h3>
          <div class="edit-profile">
            <span class="pull-right">
              <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
            </span>
            <div class="clear-fix"></div>
          </div>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-3 col-lg-3 " align="center"> <img id="user-avatar" alt="User Pic" src="{{ asset('images/products/default-product.jpg') }}" class="img-circle img-responsive"> </div>
            <div class=" col-md-9 col-lg-9 ">
              <p class="alert-info" hidden></p>
              <table class="table table-user-information">
                <tbody class="info-user">
                  <tr>
                    <td>{{ __('user.user.profile.name') }}</td>
                    <td id="user-name"></td>
                  </tr>
                  <tr>
                    <td>{{ __('user.user.profile.email') }}</td>
                    <td id="user-email"></td>
                  </tr>
                  <tr>
                    <td>{{ __('user.user.profile.phone') }}</td>
                    <td id="user-phone"></td>
                  </tr>
                  <tr>
                    <td>{{ __('user.user.profile.home_address') }}</td>
                    <td id="user-address"></td>
                  </tr>
                  </tr>
                  <tr>
                    <td>{{ __('user.user.profile.shipping_address') }}</td>
                    <td id="user-address-shipping">
                    
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="alert-danger" hidden></div>
            </div>
          </div>
        </div>
        <div class="panel-footer">
          <br>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="address-shipping" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" hidden>
  <div class="modal-dialog note-cancel-order" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4>{{ __('user.user.shipping.write_address') }}</h4>
      </div>
      <div class="modal-body">
        <form id="demo-form2" class="form-horizontal form-label-left">
          <div class="form-group">
            <div class="col-md-12 col-sm-6 col-xs-12">
              <textarea rows="5" id="address" name="address" class="form-control col-md-7 col-xs-12"></textarea>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
              <button type="submit" id="add-address-shipping" class="btn btn-success">{{ __('user.user.shipping.add') }}</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
  <script type="text/javascript" src="{{ asset('js/user/showProfileUser.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/user/shippingAddress.js') }}"></script>
@endsection
