@extends('admin.layout.master')
@section('title', __('order.admin.show.title'))
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{__('order.admin.show.form_title')}}
        <small>{{__('order.admin.show.order')}}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('admin')}}"><i class="fa fa-dashboard"></i>{{__('admin.dashboard')}}</a></li>
        <li><a href="{{route('order.index')}}">{{__('admin.manage_order')}}</a></li>
        <li class="active">{{__('order.admin.show.form_title')}}</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
    <div class="alert-info" hidden></div>
    <div class="alert-danger" hidden></div>
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{__('order.admin.show.product_order')}}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>{{__('order.admin.show.email')}}</th>
                  <th>{{__('order.admin.show.address')}}</th>
                  <th>{{__('order.admin.show.total')}}</th>
                  <th>{{__('order.admin.show.status')}}</th>
                  <th>{{__('order.admin.show.note')}}</th>
                  <th>{{__('order.admin.show.tracking_orders')}}</th>
                </tr>
                <tr>
                  <td>{{ $order->user->email }}</td>
                  <td>{{ $order->address }}</td>
                  <td>{{ $order->total }} &dollar;</td>
                  <td>
                    <select class="form-control status" name="status" data-id="{{ $order->id }}">
                      <option value="{{ App\Order::PENDING }}" {{ $order->status == App\Order::PENDING ? 'selected="selected"' : '' }}>{{ __('order.admin.index.pending') }}</option>
                      <option value="{{ App\Order::ACCEPTED }}" {{ $order->status == App\Order::ACCEPTED ? 'selected="selected"' : '' }}>{{ __('order.admin.index.accepted') }}</option>
                      <option value="{{ App\Order::REJECTED }}" {{ $order->status == App\Order::REJECTED ? 'selected="selected"' : '' }}>{{ __('order.admin.index.rejected') }}</option>
                      <option value="{{ App\Order::RECEIVED }}" {{ $order->status == App\Order::RECEIVED ? 'selected="selected"' : '' }}>{{ __('order.admin.index.received') }}</option>
                    </select>
                  </td>
                  <td class="list-notes"><i class="fa fa-info"></i></td>
                  <td class="tracking-orders"><i class="fa fa-info"></i></td>
                </tr>
              </table>
            </div>
            <!-- form start -->
            <div class="box-header with-border list-product">
              <!-- <h3 class="box-title">{{__('order.admin.show.list_product')}}</h3> -->
            </div>
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>{{__('order.admin.show.name_product')}}</th>
                  <th>{{__('order.admin.show.quantity')}}</th>
                  <th>{{__('order.admin.show.total')}}</th>
                  <th>{{__('order.admin.show.image')}}</th>
                  <th>{{__('order.admin.show.date')}}</th>
                </tr>
                  @foreach ($order->orderDetails as $orderDetail)
                  <tr>
                    <td>{{ $orderDetail->name_product }}</td>
                    <td>{{ $orderDetail->quantity }}</td>
                    <td>{{ $orderDetail->price }} &dollar;</td>
                    <td><img src="{{ $orderDetail->image_url }}" alt="{{ $orderDetail->name_product }}" style="width:100px"></td>
                    <td>{{ $orderDetail->updated_at }}</td>
                  </tr>
                  @endforeach
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- content note -->
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

      <!-- list notes -->
      <div class="modal fade" id="list-note" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" hidden>
        <div class="modal-dialog note-cancel-order" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4>{{ __('order.admin.show.list_note') }}</h4>
            </div>
            <div class="modal-body">
              <p class="alert-info" hidden></p>
              <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                  <tr>
                    <th>{{ __('order.admin.show.name') }}</th>
                    <th>{{ __('order.admin.show.content') }}</th>
                    <th>{{ __('order.admin.show.action') }}</th>
                  </tr>
                    @foreach ($order->notes as $note)
                    <tr>
                      <td>{{ $note->user->name }}</td>
                      <td><p id="note{{ $note->id }}">{{ $note->content }}</p></td>
                      <td>
                        <a class="edit-note" data-action="view" edit-id="{{ $note->id }}"><i class="fa fa-edit"></i></a> |
                        <form method="POST" action="{{ route('note.destroy', ['note' => $note->id]) }}" class="form-trash" onsubmit="return confirmDelete()">
                          @csrf
                          {{ method_field('DELETE') }}
                          <button type="submit" class="but-trash"><i class="fa fa-trash"></i></button>
                        </form>
                      </td>
                    </tr>
                    @endforeach
                    <p class="alert-danger" hidden></p>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- show tracking orders -->
      <div class="modal fade" id="list-tracking-order" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" hidden>
        <div class="modal-dialog note-cancel-order" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4>{{ __('order.admin.show.list_tracking_order') }}</h4>
            </div>
            <div class="modal-body">
              <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                  <tr>
                    <th>{{ __('order.admin.show.old_status') }}</th>
                    <th>{{ __('order.admin.show.new_status') }}</th>
                    <th>{{ __('order.admin.show.time') }}</th>
                    <th>{{ __('order.admin.show.action') }}</th>
                  </tr>
                    @foreach ($order->trackingOrders as $trackingOrder)
                    <tr>
                      <td>
                        @if ($trackingOrder->old_status == App\Order::PENDING)
                          {{ __('order.admin.index.pending') }}
                        @elseif ($trackingOrder->old_status == App\Order::ACCEPTED)
                          {{ __('order.admin.index.accepted') }}
                        @elseif ($trackingOrder->old_status == App\Order::REJECTED)
                          {{ __('order.admin.index.rejected') }}
                        @elseif ($trackingOrder->old_status == App\Order::RECEIVED)
                          {{ __('order.admin.index.received') }}
                        @endif
                      </td>
                      <td>
                      @if ($trackingOrder->new_status == App\Order::PENDING)
                          {{ __('order.admin.index.pending') }}
                        @elseif ($trackingOrder->new_status == App\Order::ACCEPTED)
                          {{ __('order.admin.index.accepted') }}
                        @elseif ($trackingOrder->new_status == App\Order::REJECTED)
                          {{ __('order.admin.index.rejected') }}
                        @elseif ($trackingOrder->new_status == App\Order::RECEIVED)
                          {{ __('order.admin.index.received') }}
                        @endif
                      </td>
                      <td>{{ $trackingOrder->updated_at }}</td>
                      <td>
                        <form method="POST" action="{{ route('trackingOrder.destroy', ['trackingOrder' => $trackingOrder->id]) }}" class="form-trash" onsubmit="return confirmDelete()">
                          @csrf
                          {{ method_field('DELETE') }}
                          <button type="submit" class="but-trash"><i class="fa fa-trash"></i></button>
                        </form>
                      </td>
                    </tr>
                    @endforeach
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
@endsection
