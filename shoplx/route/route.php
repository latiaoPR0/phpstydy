<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/*Route::get('think', function () {
return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

 */

// 后台接口的路由
Route::domain('adminapi', function () {
	// 访问首页
	Route::get('/', 'adminapi/index/index');

	//Route::resource('goods','adminapi/goods');
	Route::get('yzm/:id', '\\think\\captcha\\CaptchaController@index'); // 访问图片用的

	// 获取验证码接口
	Route::get('yzm', 'adminapi/login/captcha');

	// 登录接口
	Route::post('login', 'adminapi/login/login');

	// 退出接口
	Route::get('logout', 'adminapi/login/logout');
});

return [
];