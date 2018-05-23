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
        <li><a href="#"><i class="fa fa-dashboard"></i>{{__('user.admin.create.create')}}</a></li>
        <li><a href="#">{{__('user.admin.create.form')}}</a></li>
        <li class="active">{{__('user.admin.create.user')}}</li>
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
            <form method="POST" action="{{route('user.store')}}">
            {{csrf_field()}}
              <div class="box-body">
                <div class="form-group">
                  <label for="InputName">Name</label>
                  <input type="text" class="form-control" id="InputName" name="name" placeholder="Enter name">
                </div>
                <div class="form-group">
                  <label for="InputEmail">Email</label>
                  <input type="text" class="form-control" id="InputEmail" name="email" placeholder="Enter name">
                </div>
                <div class="form-group">
                  <label for="InputPassword">Password</label>
                  <input type="password" class="form-control" id="InputPassword" name="password" placeholder="Password">
                </div>
                <!-- <div class="form-group">
                  <label for="exampleInputFile">File input</label>
                  <input type="file" id="exampleInputFile">

                  <p class="help-block">Example block-level help text here.</p>
                </div> -->               
              </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                @if(count($errors))
                  <div class="form-group">
                    <div class="alert alert-danger">
                      <ul>
                        @foreach($errors->all() as $error)
                          <li>{{$error}}</li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                @endif
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
</div>
@endsection