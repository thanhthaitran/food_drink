@extends('admin.layout.master')
@section('title', __('order.admin.index.title') )
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{ __('order.admin.index.list_order') }}</h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fa fa-dashboard"></i> {{ __('admin.dashboard') }}</a></li>
        <li class="active">{{ __('admin.manage_order') }}</li>
      </ol>
    </section>

    @include('admin.layout.message')
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">{{ __('order.admin.index.show_order') }}</h3>
              <div class="box-tools">
                <form class="input-group input-group-sm" style="width: 150px;" action="{{ route('order.index') }}" method="GET">
                  <input type="text" name="search" class="form-control pull-right" placeholder="{{ __('order.admin.index.search') }}">
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </form>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th class="col-md-1">@sortablelink('id', __('order.admin.index.id'))</th>
                  <th class="col-md-2">@sortablelink('user.name', __('order.admin.index.name_user'))</th>
                  <th class="col-md-2">@sortablelink('total', __('order.admin.index.total'))</th>
                  <th class="col-md-2">{{ __('order.admin.index.status') }}</th>
                  <th class="col-md-2">@sortablelink('updated_at', __('order.admin.index.date'))</th>
                  <th class="col-md-1">{{ __('order.admin.index.action') }}</th>
                </tr>
                  @foreach ($orders as $order)
                  <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->total }} {{ __('order.admin.index.money') }}</td>
                    <td>
                      @if ($order->status == App\Order::PENDING)
                        {{ __('order.admin.index.pending') }}
                      @elseif ($order->status == App\Order::ACCEPTED)
                        {{ __('order.admin.index.accepted') }}
                      @elseif ($order->status == App\Order::REJECTED)
                        {{ __('order.admin.index.rejected') }}
                      @elseif ($order->status == App\Order::RECEIVED)
                        {{ __('order.admin.index.received') }}
                      @endif
                    </td>
                    <td>{{ $order->updated_at }}</td>
                    <td>
                      <a href="{{ route('order.show', ['order' => $order->id] )}}"><i class="fa fa-info"></i></a>
                    </td>
                  </tr>
                  @endforeach
              </table>
              {{ $orders->appends(\Request::except('page'))->render() }}
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <div class="modal fade" id="note-change-order" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" hidden>
        <div class="modal-dialog note-cancel-order" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4>{{ __('order.admin.index.write_reason') }}</h4>
            </div>
            <div class="modal-body">
              <form id="demo-form2" class="form-horizontal form-label-left">
                <div class="form-group">
                  <div class="col-md-12 col-sm-6 col-xs-12">
                    <textarea rows="5" id="note" name="note" class="form-control col-md-7 col-xs-12"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                    <button type="submit" id="note-change-order-submit" class="btn btn-success">{{ __('order.admin.index.submit') }}</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
@endsection
