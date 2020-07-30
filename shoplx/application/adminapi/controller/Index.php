<?php
namespace app\adminapi\controller;

use app\adminapi\controller\BaseApi;
use think\Db;
use tools\jwt\Token;

class Index extends BaseApi
{
    public function index()
    {
        /*echo encrypt_password('123456');die;
    	// 测试Token 工具类
        $token = Token::getToken(100);
        dump($token);
    	// 解析token 得到用户
    	$user_id = Token::getUserId($token);
    	dump($user_id);die;*/

    	// $goods = Db::name('goods')->find();
    	// $this -> responese(200,'success',$goods);

    	// $this -> ok($goods);
    	// $this -> fail('参数错误');
    	// return(json($goods));
        // 
        
        $yzm = captcha_check('hogm','2571165f1b8bc453d83');
        echo '返回的数据是:'.$yzm;
    }
}
