<?php
namespace Admin\Controller;
use Think\Controller;
class DocController extends Controller{
	#add方法
	public function add(){
		$this->display();

	}
	#addOk方法
	public function addOk(){
		$post = I('post.');
		$post['addtime'] = time();
		//文件的上传
		$cfg = array(
				//保存根路径
				'rootPath'      =>  WORKING_PATH . UPLOAD_PATH, 
			);
		$upload = new \Think\Upload($cfg);	//实例化上传类
		//上传
		$info = $upload -> uploadOne($_FILES['file']);//传递一个一维数组
		#dump($info);die;
		//上传结果的判断
		if($info){
			//上传成功
			//给filepath设置值
			$post['filepath'] = UPLOAD_PATH . $info['savepath'] . $info['savename'];
			//给filename设置值
			$post['filename'] = $info['name'];
			//给hasfile设置值
			$post['hasfile'] = 1;
		}
		$model = M('Doc');
		$rst = $model -> add($post);
		if($rst){
			$this ->success('添加成功',U('showList'),3);
		}else{
			$this -> error('添加失败',U('add'),3);
		}
	}
	#showList方法
	public function showList(){
		$model = M('Doc');
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
	       	$data = $model -> select();
	       	$data = $model->limit($page->firstRow,$page->listRows)->select();
		
		$this->assign('data',$data);
		$this->assign('count',$count);
	       	$this->assign('show',$show);
		$this->display();
	}
	#del方法
	public function del(){
		$ids = I('get.ids');
		$model = M('Doc');
		$rst = $model->delete($ids);
		if($rst){
			$this->success('删除成功',U('showList'),3);
		}else{
			$this->error('删除失败',U('showList'),3);
		}
	}
	#edit方法
	public function edit(){
		$id=I('get.id');
		$model=M('Doc');
		$data = $model->find($id);
		$this->assign('data',$data);
		$this->display();
	}
	#editOk方法
	public function editOk(){
		$post = I('post.');
                        //文件的上传
		$cfg = array(
				//保存根路径
				'rootPath'      =>  WORKING_PATH . UPLOAD_PATH, 
			);
		$upload = new \Think\Upload($cfg);	//实例化上传类
		//上传
		$info = $upload -> uploadOne($_FILES['file']);//传递一个一维数组
		#dump($info);die;
		//上传结果的判断
		if($info){
			//上传成功
			//给filepath设置值
			$post['filepath'] = UPLOAD_PATH . $info['savepath'] . $info['savename'];
			//给filename设置值
			$post['filename'] = $info['name'];
			//给hasfile设置值
			$post['hasfile'] = 1;
		}

		//dump($post);die;
		$model = M('Doc');
		$rst = $model->save($post);
		if($rst!==false){
			$this->success('更新成功',U('showList'),3);
		}else{
			$this->error('更新失败',U('edit',array('id' => $post['id'])),3);
		}
	}
	
                 #download方法
	public function download(){
		$id = I('get.id');
		#实例化模型
		$model = M('Doc');
		#查询附件信息
		$data = $model -> find($id);
		//dump($data);die;
		#实现附件下载
		$file = WORKING_PATH . $data['filepath'];
		header("Content-type: application/octet-stream");
		header('Content-Disposition: attachment; filename="' . basename($file) . '"');
		header("Content-Length: ". filesize($file));
		readfile($file);
	}
	#getContent方法
	public function getContent(){
		$id = I('get.id');
		$model = M('Doc');
		$data = $model->find($id);
		echo htmlspecialchars_decode($data['content']);
	}
	
}