@extends('user.layout.master')
@section('title', __('product.user.product.product_detail'))
@section('content')
<div class="main-container col1-layout">
  <div class="container">
    <div class="row">
      <div class="col-main">
        <div class="product-view-area">
          <div class="product-big-image col-xs-12 col-sm-5 col-lg-5 col-md-5">
            <div class="large-image"> 
              <a href="{{ asset('images/products/'.$product->images->first()['image']) }}" class="cloud-zoom" id="zoom1" rel="useWrapper: false, adjustY:0, adjustX:20"> 
                <img class="zoom-img" src="{{ asset('images/products/'.$product->images->first()['image']) }}" alt="{{ $product->name }}"> 
              </a> 
            </div>
            <div class="flexslider flexslider-thumb">
              <ul class="previews-list slides">
                @if(count($product->images) < 4)
                  @foreach($product->images as $image)
                    <li style="width: 100px; float: left; display: block;"><a href='{{ asset('images/products/'.$image->image) }}' class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: '{{ asset('images/products/'.$image->image) }}' "><img src="{{ asset('images/products/'.$image->image) }}" alt = "{{ $product->name }}"/></a></li>
                  @endforeach
                @else
                  @foreach($product->images as $image)
                    <li><a href='{{ asset('images/products/'.$image->image) }}' class='cloud-zoom-gallery' rel="useZoom: 'zoom1', smallImage: '{{ asset('images/products/'.$image->image) }}' "><img src="{{ asset('images/products/'.$image->image) }}" alt = "{{ $product->name }}"/></a></li>
                  @endforeach
                @endif
              </ul>
            </div>
            
            <!-- end: more-images --> 
            
          </div>
        </div>
      </div>
      <div class="product-overview-tab">
        <div class="container">
          <div class="row">
            <div class="col-xs-12">
              <div class="product-tab-inner"> 
                <ul id="product-detail-tab" class="nav nav-tabs product-tabs">
                  <li class="active"> <a href="#description" data-toggle="tab"> {{ __('product.user.detail.description') }} </a> </li>
                  <li> <a href="#reviews" data-toggle="tab">{{ __('product.user.detail.review.reviews') }}</a> </li>
                  <li><a href="#product_tags" data-toggle="tab">{{ __('product.user.detail.comment.comments') }}</a></li>
                </ul>
                <div id="productTabContent" class="tab-content">
                  <div class="tab-pane fade in active" id="description">
      
                  </div>
                  <!-- review -->
                  <div id="reviews" class="tab-pane fade">
                    <div class="col-sm-5 col-lg-5 col-md-5">
                      <div id="contain-posts2">

                      </div>
                      {{-- pagination --}}
                      <div class="home-inline"></div>
                      <ul id="pagination-post2" class="pagination-item">

                      </ul>
                      {{-- <div class="pagination-area"> --}}
                        {{-- <a id="next-post2" style="float: right;"><span>{{ __('product.user.next') }} <i class="fa fa-forward"></i></span></a> --}}

                        {{-- <a id="prev-post2" style="float: left;"><span> <i class="fa fa-backward"></i> {{ __('product.user.prev') }}</span></a> --}}
                      {{-- </div> --}}
                    </div>
                    <div class="col-sm-7 col-lg-7 col-md-7">
                      <div class="reviews-content-right">
                        <h2>{{ __('product.user.detail.review.write_your_review') }}</h2>
                        <form>
                          <div class="table-responsive reviews-table">
                            <table>
                              <tbody><tr>
                                <th>1 {{ __('product.user.detail.review.star') }}</th>
                                <th>2 {{ __('product.user.detail.review.stars') }}</th>
                                <th>3 {{ __('product.user.detail.review.stars') }}</th>
                                <th>4 {{ __('product.user.detail.review.stars') }}</th>
                                <th>5 {{ __('product.user.detail.review.stars') }}</th>
                              </tr>
                              <tr>
                                <td><input name="rate" value="1" type="radio"></td>
                                <td><input name="rate" value="2" type="radio"></td>
                                <td><input name="rate" value="3" type="radio"></td>
                                <td><input name="rate" value="4" type="radio"></td>
                                <td><input name="rate" value="5" type="radio"></td>
                              </tr>
                            </tbody></table>
                          </div>
                          <div class="form-area">
                            <div class="form-element">
                              <label>{{ __('product.user.detail.review.review') }} <em>*</em></label>
                              <textarea id="content-post2"></textarea>
                              <p class="alert-post2 alert-info" hidden>{{ __('product.user.detail.comment.comment_active') }}</p>
                              <div class="alert-danger danger2" hidden></div>
                            </div>
                            <div class="buttons-set">
                              <button class="button submit" id="add-review" title="Submit Review" type="submit"><span><i class="fa fa-thumbs-up"></i> &nbsp;{{ __('product.user.detail.review.review') }}</span></button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- comment -->
                  <div id="product_tags" class="tab-pane fade">
                    <div class="col-sm-5 col-lg-5 col-md-5">
                      <div id="contain-posts1">

                      </div>
                      {{-- pagination --}}
                      <div class="home-inline"></div>
                      <ul id="pagination-post1" class="pagination-item">

                      </ul>
                      {{-- <div class="pagination-area"> --}}
                        {{-- <a id="next-post1" style="float: right;"><span>{{ __('product.user.next') }} <i class="fa fa-forward"></i></span></a> --}}

                        {{-- <a id="prev-post1" style="float: left;"><span> <i class="fa fa-backward"></i> {{ __('product.user.prev') }}</span></a> --}}
                      {{-- </div> --}}
                    </div>
                    <div class="col-sm-7 col-lg-7 col-md-7">
                      <div class="reviews-content-right">
                        <h2>{{ __('product.user.detail.comment.write_your_comment') }}</h2>
                        <form>
                          <div class="form-area">
                            <div class="form-element">
                              <label>{{ __('product.user.detail.comment.comment') }} <em>*</em></label>
                              <textarea id="content-post1"></textarea>
                              <p class="alert-post1 alert-info" hidden>{{ __('product.user.detail.comment.comment_active') }}</p>
                              <div class="alert-danger danger1" hidden></div>
                            </div>
                            <div class="buttons-set">
                              <button class="button submit" id="add-comment" title="Submit Comment" type="submit"><span><i class="fa fa-thumbs-up"></i> &nbsp;{{ __('product.user.detail.comment.comment') }}</span></button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          {{-- <div class="shop-inner">
            <div class="page-title">
              <h2>{{ __('product.user.recommend.related_products') }}</h2>
            </div>
            <div class="product-grid-area">
              <ul id="list-recommend" class="products-grid">
                
              </ul>
            </div>
            <div class="home-inline"></div>
          </div> --}}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
  <script src="{{ asset('js/user/showProductDetail.js') }}"></script>
  <script src="{{ asset('js/user/showPostOfProduct.js') }}"></script>
  <script src="{{ asset('js/user/addPost.js') }}"></script>
  {{-- <script src="{{ asset('js/user/recommendProduct.js') }}"></script> --}}
@endsection
