<!-- Header -->
<header>
  <div class="header-container">
    <div class="header-top">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-sm-4 col-md-5 hidden-xs"> 
            <!-- Default Welcome Message -->
            <div class="welcome-msg ">{{ __('home.user.header.welcome') }}</div>
            <span class="phone hidden-sm">{{ __('home.user.header.call_us') }}</span>
          </div>
          
          <!-- top links -->
          <div class="headerlinkmenu col-lg-8 col-md-7 col-sm-8 col-xs-12">
            <div class="links">
              <div class="myaccount"><a title="My Account" href="{{ route('profile.index') }}"><i class="fa fa-user"></i><span class="hidden-xs">{{ __('home.user.header.my_account' ) }}</span></a></div>
              <div class="history-order"><a href="{{ route('orders.index') }}"><i class="fa fa-history"></i><span class="hidden-xs">{{ __('home.user.header.history_order' ) }}</span></a></div>
              <div class="login"><a href="{{ route('user.login') }}"><i class="fa fa-unlock-alt"></i><span class="hidden-xs">{{ __('home.user.header.log_in' ) }}</span></a></div>
              <div class="register"><a href="{{ route('register.index') }}"><i class="fa fa-user-plus"></i><span class="hidden-xs">{{ __('home.user.header.register' ) }}</span></a></div>              
              <div id="logout" class="logout"><a href=""><i class="fa fa-sign-out"></i><span class="hidden-xs">{{ __('home.user.header.log_out' ) }}</span></a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-sm-3 col-md-3 col-xs-12"> 
          <!-- Header Logo -->
          <div class="logo"><a title="e-commerce" href="{{ route('user') }}"><img alt="e-commerce" src="{{ asset('frontend/images/logo.png') }}"></a> </div>
          <!-- End Header Logo --> 
        </div>
        <div class="col-xs-9 col-sm-6 col-md-7"> 
          <!-- Search -->
          @if (!request()->is('user/login'))
          <div class="top-search">
            <div id="search">
              <form>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="{{ __('home.user.header.search') }}" name="search">
                  <button class="btn-search" type="button"><i class="fa fa-search"></i></button>
                </div>
              </form>
            </div>
          </div>
          @endif
          <!-- End Search --> 
        </div>
        <!-- top cart -->
        
        <div class="col-lg-2 col-xs-3 top-cart">
          <div class="top-cart-contain">
            <div class="mini-cart">
              <div data-toggle="dropdown" data-hover="dropdown" class="basket dropdown-toggle"> <a href="#">
                <div class="cart-icon"><i class="fa fa-shopping-cart"></i></div>
                <div class="shoppingcart-inner hidden-xs">
                  <span class="cart-title">{{ __('home.user.header.cart') }}</span>
                  <span id="number-item">0</span>
                  <span class="cart-total">{{ __('home.user.header.items') }}</span>
                </div>
                </a>
              </div>
              <div>
                <div class="top-cart-content">
                  <div class="block-subtitle hidden-xs">{{ __('home.user.header.recently_added') }}</div>
                    <ul id="cart-sidebar" class="mini-products-list">
                      
                    </ul>
                  <div class="top-subtotal">{{ __('home.user.header.subtotal') }}<span class="price">{{ __('product.user.money') }}<span class="sub-total">0</span></span></div>
                  <div class="actions">
                    <a href="{{ route('cart.index') }}" class="view-cart"><i class="fa fa-shopping-cart"></i> <span>{{ __('home.user.header.view_cart') }}</span></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
