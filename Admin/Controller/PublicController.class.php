<?php
#定义命名空间
namespace Admin\Controller;
#引入父类类元素
use Think\Controller;
#定义控制器并且继承父类
class PublicController extends Controller{

	#login方法，展示登录页面的模版
	public function login(){
		#展示模版
		$this -> display();
	}
	#生产验证码的方法
             public function captcha(){
             	#配置
                  $cfg = array(
                  	'fontSize'  =>  13,              // 验证码字体大小(px)
                        'useCurve'  =>  false,            // 是否画混淆曲线
                      'useNoise'  =>  false,            // 是否添加杂点	
                       'imageH'    =>  40,               // 验证码图片高度
                        'imageW'    => 90,               // 验证码图片宽度
                          'length'    =>  4,               // 验证码位数
                          'fontttf'   =>  '4.ttf',              // 验证码字体，不设置随机获取
                  	);
                  #实例化验证码类
                  $verify = new \Think\Verify($cfg);
                  #输出
                  $verify->entry();
             }
             #index方法
             public function index(){
                   $post = I('post.');
                    #实例化验证码
                    $verify = new \Think\Verify();
                    $rst = $verify->check($post['captcha']);
                    if($rst){
                    	#实例化模型
                    	$model = M('User');
                    	#剔除验证码元素
                    	unset($post['captcha']);
                    	$data = $model->where($post)->find();
                    	if($data){
                    		#用户信息持久化
                    		session('uid',$data['id']);
                    		session('uname',$data['username']);
                    		session('role_id',$data['role_id']);
                    		$this->success('登录成功',U('Index/index'),3);
                    	}else{
                    		$this -> error('用户名或密码错误',U('login'),3);
                    	}

                    }else{
                    	$this->error('验证码错误',U('login'),3);
                    }
             }

             #退出方法
	public function logout(){
		#退出
		session(null);
		#跳转到登录页面
		$this -> success('退出成功',U('login'),3);
	}
}