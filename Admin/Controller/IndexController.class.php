<?php
#声明命名空间
namespace Admin\Controller;
#引入父类类元素
use Think\Controller;
#定义类并且继承父类
class IndexController extends Controller{

	#默认方法
	public function index(){
		#获取当前时间
		$date = date('Y-m-d H:i:s');
		#变量分配
		$this -> assign('date',$date);
		#展示模版（于当前请求方法名相同的模版文件）
		$this -> display();

		#展示模版（展示当前控制器下指定模版文件）
		#$this -> display('index2');

		#展示模版（展示View目录下指定目录名下的指定模版文件）
		#$this -> display('Test/index');
	}

}