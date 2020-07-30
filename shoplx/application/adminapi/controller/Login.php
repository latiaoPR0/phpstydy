<?php
namespace app\adminapi\controller;

use app\adminapi\controller\BaseApi;
use app\common\model\Admin;
use tools\jwt\Token;

class Login extends BaseApi {
	/**
	 * 验证码接口
	 */

	public function captcha() {
		// 验证码唯一的标识
		$uniqid = uniqid(mt_rand(100000, 999999));
		// 生成验证码的地址
		$src = captcha_src($uniqid);
		$res = [
			'src' => $src,
			'uniqid' => $uniqid,
		];
		// 返回数据
		$this->ok($res);
	}

	/**
	 * 登录接口
	 */
	public function login() {
		// 接收参数
		$params = input();

		// 参数检测 (表单验证)
		$validate = $this->validate($params, [
			'username|用户名' => 'require',
			'password|密码' => 'require',
			'code|验证码' => 'require',
			// 'code|验证码' => 'require|captcha', // 验证码自动校验
			'uniqid|验证码标识' => 'require',
		]);
		if ($validate !== true) {
			// 参数验证失败
			$this->fail($validate, 401);
		}
		// 校验验证码 手动校验
		// 从缓存中根据uniqid获取session_id,设置session_id, 用于验证码校验
		session_id(cache('session_id_' . $params['uniqid']));
		// echo '验证码是'.$params['code'].'验证码标识是'.$params['uniqid'].'<br />';
		// echo('内容是'.captcha_check($params['code'],$params['uniqid']).'<br />');
		if (!captcha_check($params['code'], $params['uniqid'])) {
			// 验证码错误
			$this->fail('验证码错误', 402);die;
		}

		// 查询用户表进行验证
		$password = encrypt_password($params['password']);
		$info = Admin::where('username', $params['username'])->where('password', $password)->find();
		if (empty($info)) {
			// 用户名或者密码错误
			$this->fail('用户名或者密码错误', 403);die;
		}
		// 生成token 令牌
		$token = \tools\jwt\Token::getToken($info['id']);
		// 返回数据
		$data = [
			'token' => $token,
			'user_id' => $info['id'],
			'username' => $info['username'],
			'nickname' => $info['nickname'],
			'email' => $info['email'],
		];
		$this->ok($data);
	}

	/**
	 * 退出
	 */
	public function logout() {
		// 记录token 退出
		// 获取当前请求中的token
		$token = Token::getRequestToken();
		// 从缓存中取出 注销token数组
		$delete_token = cache('delete_token') ?: [];
		// 将当前的token 加入到数组中
		$delete_token[] = $token;
		// 将新的数组 重新放到缓存中 缓存一天
		cache('delete_token', $delete_token, 86400);
		// 返回数据
		$this->ok();
	}
}
