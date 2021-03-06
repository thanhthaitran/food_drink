@extends('admin.layout.master')
@section('title', __('post.index.title') )
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{__('post.index.list_post')}}</h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fa fa-dashboard"></i>{{ __('admin.dashboard') }}</a></li>
        <li class="active">{{__('post.index.posts')}}</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">{{__('post.index.show_list_post')}}</h3>
              <div class="box-tools">
                <form class="input-group input-group-sm" style="width: 150px;" action="" method="GET">
                  <input type="text" name="search" class="form-control pull-right" placeholder="{{__('user.admin.index.search')}}">
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
                  <th>@sortablelink('id', __('post.index.id'))</th>
                  <th>@sortablelink('user.name', __('post.index.user_name'))</th>
                  <th>@sortablelink('product.name', __('post.index.product_name'))</th>
                  <th>@sortablelink('content', __('post.index.review'))</th>
                  <th>@sortablelink('rate', __('post.index.rate'))</th>
                  <th>{{__('post.index.type')}}</th>
                  <th>{{__('post.index.status')}}</th>
                  <th>{{__('post.index.action')}}</th>
                </tr>
                @foreach($posts as $post)
                  <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->user->name }}</td>
                    <td>{{ $post->product->name }}</td>
                    <td>{{ $post->content }}</td>
                    <td>{{ $post->rate }}</td>
                    @if($post->type == App\Post::COMMENT)
                      <td>{{ __('post.index.comment') }}</td>
                    @elseif($post->type == App\Post::REVIEW)
                      <td>{{ __('post.index.review') }}</td>
                    @endif
                    <td>
                    @if($post->status == $status['enable'])
                      <a href="{{ route('admin.post.active',['id'=> $post->id]) }}" id="{{ $post->id }}">
                        <img src="{{ asset('images/posts/icons/accept.png') }}" alt="" />
                      </a>
                    @elseif($post->status == $status['disable'])
                      <a href="{{ route('admin.post.active',['id'=> $post->id]) }}" id="{{ $post->id }}">
                        <img src="{{ asset('images/posts/icons/exclamation.png') }}" alt="" />
                      </a>
                    @endif
                    </td>
                    <td>
                      <form method="post" action="{{ route('admin.post.delete', $post->id) }}">
                        @csrf
                        @method('DELETE')
                        <button id="post_{{ $post->id }}" type="submit" class="but-trash"name="submit">
                          <i class="fa fa-trash"></i>
                         </button>
                       </form>
                    </td>
                </tr>
                @endforeach
              </table>
              <div class="text-center">
                {{ $posts->appends(\Request::except('page'))->render() }}
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
@endsection
@section('status')
<script>
  $(document).on('click','table tr td a',function (event) {
    event.preventDefault();
    var url = $(this).attr('href');
    var this_button = $(this);
    $.ajax({
      url: url,
      type: 'PUT', 
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      dataType: 'json',
    })
    .done(function(data) {
      if(data.status == {{$status['enable']}}) {
        this_button.find('img').attr('src','/images/posts/icons/accept.png');
      }
      else{
        this_button.find('img').attr('src','/images/posts/icons/exclamation.png');
      }
    })     
  })
</script>
@endsection
