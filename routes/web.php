<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware' => 'admin'],function () {
    Route::get('',[
        'uses'=>'HomeController@index',
        'as'=>'admin'
    ]);
    Route::resource('user', 'UsersController');
    Route::resource('product', 'ProductsController');
    Route::resource('category', 'CategoriesController');
    Route::group(['prefix'=>'post'],function (){
        Route::get('',[
            'uses' => 'PostsController@index',
            'as' => 'admin.post.index'
        ]);
        Route::delete('{post}',[
            'uses' => 'PostsController@destroy',
            'as' => 'admin.post.delete'
        ]);
        Route::put('{id}/active',[
            'uses' => 'PostsController@active',
            'as' => 'admin.post.active'
        ]);
    });
    Route::resource('order', 'OrdersController')->only([
        'index', 'show'
    ]);
    Route::put('order/{order}/updateStatus', 'OrdersController@updateStatus');
    Route::resource('note', 'NotesController');
    Route::resource('trackingOrder', 'TrackingOrdersController');
});
Route::group(['prefix' => 'admin'],function (){
    Route::get('login', [
        'uses' => 'Auth\LoginController@showLoginForm',
        'as' => 'admin.login'
        ]);
        Route::post('login',[
        'uses' => 'Auth\LoginController@login',
    ]);
    Route::post('logout',[
        'uses' => 'Auth\LoginController@logout',
        'as' => 'admin.logout'
    ]);
});
Route::get('/api-docs', function () {
    return view('api-docs');
});
Route::get('/api-doc-builders', function () {
    return view('api-docs-builders.index');
});
Route::middleware('locale')->group(function (){
    //forget password
    Route::group(['namespace' => 'Home', 'prefix' => 'password'], function () {
        Route::get('reset', [
            'uses' => 'ForgotPasswordController@index',
            'as' => 'password.forgot'
        ]);
        Route::get('reset/{token}', [
            'uses' => 'ResetPasswordController@index',
            'as' => 'password.reset'
        ]);
    });
    //login user
    Route::group(['namespace' => 'Home','prefix' => 'user'], function (){
        Route::get('login', [
            'uses' => 'LoginController@index',
            'as' => 'user.login'
        ]);
        Route::resource('register', 'RegisterController')->only([
            'index'
        ]);
    });
    //frontend
    Route::group(['namespace'=>'User','prefix'=>'/'],function () {
        Route::get('products/compare', 'ProductController@compare')->name('product.compare');        
        Route::get('',[
            'uses'=>'HomeController@index',
            'as'=>'user'
        ]);
        Route::resource('profile', 'UserController')->only([
            'index'
        ]);
        Route::get('profile/edit', 'UserController@edit')->name('profile.edit');
        Route::resource('products', 'ProductController')->only([
            'index', 'show'
        ]);
        Route::resource('cart', 'CartController')->only([
            'index'
        ]);
        Route::resource('orders', 'OrderController')->only([
            'index', 'create', 'show', 'edit'
        ]);
        Route::get('/locale/{locale}', function ($locale) {
            session(['locale' => $locale]);
            return response()->json(['locale' => session('locale')], 200);
        })->name('locale');
        Route::get('redirect/{social}', 'SocialAuthController@redirect')->name('redirect.social');
        Route::get('callback/{social}', 'SocialAuthController@callback')->name('callback.social');
    });
});
