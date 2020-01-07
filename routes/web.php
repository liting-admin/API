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
Route::get('phpinfo', function () {
   phpinfo();
});
Route::get('/test/pay','Test\PayController@pay');  //支付
Route::post('/test/alipay/notify','Test\AliController@notify');  //验签异步
Route::get('/test/alipay/return','Test\AliController@aliReturn');   //支付宝同步通知
Route::post('/test/index','Test\TestController@index'); 
Route::post('/test/user','Test\TestController@user'); 
Route::get('/test/userList','Test\TestController@userList')->middleware('UserLists');


Route::get('/test/jia','Test\OrdController@encryption');
Route::get('/test/jie','Test\OrdController@decode');
Route::get('/test/test','Test\JieController@index');
Route::get('/test/ins','Test\JieController@dds');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



Route::get('/user/addkey','Test\JieController@addkey');
Route::post('/user/addkeyy','Test\JieController@addkeys');

Route::get('/user/deckey','Test\JieController@deckey');
Route::post('/user/deckey','Test\JieController@deckeys');
