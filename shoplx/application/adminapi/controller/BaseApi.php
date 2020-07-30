<?php

namespace app\adminapi\controller;
use think\Controller;

class BaseApi extends Controller {
	// 无需登录的请求数组
	protected static $no_login = ['login/captcha', 'login/login'];

	// 控制器的初始化代码
	protected function initialize() {
		parent::initialize();
		// 初始化代码
		// 允许的源域名
		header("Access-Control-Allow-Origin: *");
		// 允许请求头信息
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
		// 允许的请求头信息
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');

		// 进行登录检查

		// 获取当前请求控制器方法的名称
		$path = strtolower($this->request->controller()) . '/' . $this->request->action();
		if (!in_array($path, self::$no_login)) {
			// 需要做登录检查
			$user_id = \tools\jwt\Token::getUserid();
			if (empty($user_id)) {
				$this->fail('这是token的验证失败', 403);
			}
			// 得到的用户放到请求信息中
			$this->request->get('user_id', $user_id);
			$this->request->post('user_id', $user_id);
		}
	}

	// 通用响应
	/**
	 * @param int $code 状态码
	 * @param String $msg 状态信息
	 * @param array $data 数据内容
	 */
	protected function responese($code = 200, $msg = 'success', $data = []) {
		$res = [
			'code' => $code,
			'msg' => $msg,
			'data' => $data,
		];
		// 原生写法
		//echo json_encode($res,JSON_UNESCAPED_UNICODE);die;

		json($res)->send();
	}

	// 成功响应
	/**
	 * @param int $code 成功状态码
	 * @param String $msg 状态信息
	 * @param array $data 数据内容
	 */

	protected function ok($data = [], $code = 200, $msg = 'success') {
		$this->responese($code, $msg, $data);
	}

	// 失败响应
	/**
	 * @param int $code 失败状态码
	 * @param String $msg 状态信息
	 * @param array $data 数据内容
	 */

	protected function fail($msg, $code = 500, $data = []) {
		$this->responese($code, $msg, $data);
	}

}
