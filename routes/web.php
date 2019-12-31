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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test/pay','Test\PayController@pay');  //支付
Route::post('/test/alipay/notify','Test\AliController@notify');  //验签异步
Route::get('/test/alipay/return','Test\AliController@aliReturn');   //支付宝同步通知

