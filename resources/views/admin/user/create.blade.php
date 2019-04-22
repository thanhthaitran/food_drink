@extends('admin.layout.master')
@section('title', __('user.admin.create.title'))
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{__('user.admin.create.form_title')}}
        <small>{{__('user.admin.create.user')}}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fa fa-dashboard"></i>{{__('user.admin.create.create')}}</a></li>
        <li><a href="{{ route('user.index') }}">{{__('admin.manage_user')}}</a></li>
        <li class="active">{{__('user.admin.create.create_user')}}</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Create</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form method="POST" action="{{route('user.store')}}" enctype="multipart/form-data">
            {{csrf_field()}}
              <div class="box-body">
                <div class="form-group">
                  <label for="InputName">{{__('user.admin.create.name')}}<span class="text-danger"> *</span></label>
                  <input type="text" class="form-control" id="InputName" name="name" placeholder="{{__('user.admin.create.enter_name')}}">
                  @if($errors->first('name')) 
                    <span class="help-block">
                      <strong class="text-danger">{{ $errors->first('name') }}</strong>
                    </span>
                  @endif
                </div>
                <div class="form-group">
                  <label for="InputEmail">{{__('user.admin.create.email')}}<span class="text-danger"> *</span></label>
                  <input type="text" class="form-control" id="InputEmail" name="email" placeholder="{{__('user.admin.create.enter_email')}}">
                  @if($errors->first('email')) 
                    <span class="help-block">
                      <strong class="text-danger">{{ $errors->first('email') }}</strong>
                    </span>
                  @endif
                </div>
                <div class="form-group">
                  <label for="InputPassword">{{__('user.admin.create.password')}}<span class="text-danger"> *</span></label>
                  <input type="password" class="form-control" id="InputPassword" name="password" placeholder="{{__('user.admin.create.enter_password')}}">
                  @if($errors->first('password')) 
                    <span class="help-block">
                      <strong class="text-danger">{{ $errors->first('passwordư') }}</strong>
                    </span>
                  @endif
                </div>
                <div class="form-group">
                  <label for="InputAddress">{{__('user.admin.create.address')}}<span class="text-danger"> *</span></label>
                  <input type="text" class="form-control" id="InputAddress" name="address" placeholder="{{__('user.admin.create.enter_address')}}">
                  @if($errors->first('address')) 
                    <span class="help-block">
                      <strong class="text-danger">{{ $errors->first('address') }}</strong>
                    </span>
                  @endif
                </div>
                <div class="form-group">
                  <label for="InputPhone">{{__('user.admin.create.phone')}}<span class="text-danger"> *</span></label>
                  <input type="text" class="form-control" id="InputPhone" name="phone" placeholder="{{__('user.admin.create.enter_phone')}}">
                  @if($errors->first('phone')) 
                    <span class="help-block">
                      <strong class="text-danger">{{ $errors->first('phone') }}</strong>
                    </span>
                  @endif
                </div>
                <div class="form-group">
                  <label for="InputAvatar">{{__('user.admin.create.avatar')}}</label>
                  <input type="file" class="form-control" id="InputAvatar" name="avatar" placeholder="{{__('user.admin.create.enter_avatar')}}">
                </div>
                <!-- <div class="form-group">name
                  <label for="exampleInputFile">File input</label>
                  <input type="file" id="exampleInputFile">

                  <p class="help-block">Example block-level help text here.</p>
                </div> -->               
              </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary" name="submit">{{__('user.admin.create.submit')}}</button>
                {{-- @if(count($errors))
                  <div class="form-group">
                    <div class="alert alert-danger">
                      <ul>
                        @foreach($errors->all() as $error)
                          <li>{{$error}}</li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                @endif --}}
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
</div>
@endsection
