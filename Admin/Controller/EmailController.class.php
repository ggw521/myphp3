<?php
namespace Admin\Controller;
use Think\Controller;
class EmailController extends Controller{
	#send方法
	public function send(){
		#要显示名字 所以需要实例化User表
		$model = M('User');
		$data = $model ->select();
		$this->assign('data',$data);
		$this->display();
	}
	#sendOK方法
	public function sendOK(){
		$post = I('post.');
		$post['addtime'] = time();
		$post['isread']= 0;
		$post['from_id'] = session('uid');#发件人的用户id
		//文件的上传
		if($_FILES['file']['size'] > 0){
		$cfg = array(
				//保存根路径
				'rootPath'      =>  WORKING_PATH . UPLOAD_PATH, 
			);
		$upload = new \Think\Upload($cfg);	//实例化上传类
		//上传
		$info = $upload -> uploadOne($_FILES['file']);//传递一个一维数组
		//dump($info);die;
		//上传结果的判断
		if($info){
			//上传成功
			//给filepath设置值
			$post['file'] = UPLOAD_PATH . $info['savepath'] . $info['savename'];
			//给filename设置值
			$post['filename'] = $info['name'];
			//给hasfile设置值
			$post['hasfile'] = 1;
		}
	       }
	      //dump($post);die;
	       $model = M('Email');
	       $rst=$model->add($post);
	       if($rst){
	       	$this->success('发送成功',U('sendBox'),3);
	       }else{
	       	$this->error('发送失败',U('send'),3);
	       }

	}
	#sendOk方法
	public function sendBox(){
		$model = M('Email');
		#获取总记录数
	       	$count = $model->count();
	       	#实例化分页类 至少传递一个参数
	       	$page = new \Think\Page($count,2);     
	       	$page -> setConfig('prev','上一页');
		$page -> setConfig('next','下一页');
		$page -> setConfig('first','首页');
		$page -> setConfig('last','末页');
		$page -> rollPage = 3;		#每页显示的页码数，如想显示首页按钮，则该属性的值必须要小于总的页码数
		$page -> lastSuffix = false;	
	       	#使用show方法组转
	       	$show = $page->show();
	       	
	       	$data = $model->field('t1.*,t2.truename as to_name')->table('tp_email as t1,tp_user as t2')->where('t1.to_id = t2.id')->limit($page->firstRow,$page->listRows)->select();

	       	#$data = $model->limit($page->firstRow,$page->listRows)->select();
		#select t1.*,t2.truename as to_name from tp_email as t1,tp_user as t2 where t1.to_id = t2.id;
		//dump($data);DIE;
		
		$this->assign('data',$data);
		$this->assign('count',$count);
	       	$this->assign('show',$show);
		$this->display();

	}
	#download方法
	public function download(){
		$id = I('get.id');
		$model = M('Email');
		$data = $model->find($id);
		//dump($data);die;
                        #实现附件下载
		$file = WORKING_PATH . $data['file'];
		header("Content-type: application/octet-stream");
		header('Content-Disposition: attachment; filename="' . basename($file) . '"');
		header("Content-Length: ". filesize($file));
		readfile($file);

	}
	#del方法
	public function del(){
		$id = I('get.id');
		$model = M('Email');
		$rst= $model->delete($id);

		if($rst){
			$this->success('删除成功',U('sendBox'),3);
		}else{
			$this->error('删除失败',U('sendBox'),3);
		}
	}
	#recBox方法
	public function recBox(){
		$model = M('Email');

		#获取总记录数
	       	$count = $model->count();
	       	#实例化分页类 至少传递一个参数
	       	$page = new \Think\Page($count,2);     
	       	$page -> setConfig('prev','上一页');
		$page -> setConfig('next','下一页');
		$page -> setConfig('first','首页');
		$page -> setConfig('last','末页');
		$page -> rollPage = 3;		#每页显示的页码数，如想显示首页按钮，则该属性的值必须要小于总的页码数
		$page -> lastSuffix = false;	
	       	#使用show方法组转
	       	$show = $page->show();
		#select t1.*,t2.truename as from_name from tp_email as t1,tp_user as t2 where t1.from_id = t2.idand t1.to_id = uid;
		$data = $model->field('t1.*,t2.truename as from_name')
		                         ->table('tp_email as t1,tp_user as t2')
		                         ->where('t1.from_id = t2.id and t1.to_id =' . session('uid'))
		                         ->limit($page->firstRow,$page->listRows)
		                         ->select();
		                         //dump($data);die;
		                         $this->assign('count',$count);
	       	                          $this->assign('show',$show);
		                         $this->assign('data',$data);
		                         $this->display();
	}
	#getContent方法
	public function getContent(){
		$id = I('get.id');
		//dump($id);die;
		$model = M('Email');
		$data = $model->find($id);
                        if($data){
                                 $arr = array( 'id'=>$id,'isread'=>1);
                                 $model->save($arr);
                        }
		echo htmlspecialchars_decode($data['content']);
	}
	
	
}