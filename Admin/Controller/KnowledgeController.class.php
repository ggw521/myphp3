<?php
namespace Admin\Controller;
use Think\Controller;
class KnowledgeController extends Controller{
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
		$upload = new \Think\Upload($cfg);		
			//实例化上传类
		//上传
		$info = $upload -> uploadOne($_FILES['thumb']);//传递一个一维数组	
		//上传结果的判断
		if($info){
			//上传成功
			//给picture设置值
			$post['picture'] = UPLOAD_PATH . $info['savepath'] . $info['savename'];
			#实例化图像类
			//echo $post['picture'];die;
			$im= new \Think\Image();
			#打开一副图片 ，并且传图片的带盘符的路径
			$im ->open(WORKING_PATH.$post['picture']);
			#制作缩略图
			$im ->thumb(100,100);
			#保存图片 带盘符路径
			$im->save(WORKING_PATH . UPLOAD_PATH . $info['savepath'] . 'thumb_' . $info['savename']);
			$post['thumb'] = UPLOAD_PATH . $info['savepath'] . 'thumb_' . $info['savename'];

		}
		$model = M('Knowledge');
		$rst= $model->add($post);
		if($rst){
			$this->success('添加成功',U('showList'),3);
		}else{
			$this->error('添加失败',U('add'),3);
		}
	}
	#showList方法
	public function showList(){
		$model = M('Knowledge');

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
		
		//dump($data);die;
		$this->assign('data',$data);
		$this->assign('count',$count);
	       	$this->assign('show',$show);
		$this->display();
	}
	#del方法
	public function del(){
		$ids = I('get.ids');
		$model = M('Knowledge');
		$rst = $model ->delete($ids);
		if($rst){
			$this->success('删除成功',U('showList'),3);
		}else{
			$this->error('删除失败',U('showList'),3);
		}
	}
	#getContent方法
	public function getContent(){
		$id = I('get.id');
		$model = M('Knowledge');
		$data = $model->find($id);
		echo htmlspecialchars_decode($data['content']);
	}
	#edit方法
	public function edit(){
		$id =I('get.id');
		$model = M('Knowledge');
		$data = $model->find($id);
		$this->assign('data',$data);
		$this->display();
	}
	#editOk方法
	public function editOk(){
		$post =I('post.');
		//文件的上传
		$cfg = array(
				//保存根路径
				'rootPath'      =>  WORKING_PATH . UPLOAD_PATH, 
			);
		$upload = new \Think\Upload($cfg);		
			//实例化上传类
		//上传
		$info = $upload -> uploadOne($_FILES['thumb']);//传递一个一维数组	
		//上传结果的判断
		if($info){
			//上传成功
			//给picture设置值
			$post['picture'] = UPLOAD_PATH . $info['savepath'] . $info['savename'];
			#实例化图像类
			//echo $post['picture'];die;
			$im= new \Think\Image();
			#打开一副图片 ，并且传图片的带盘符的路径
			$im ->open(WORKING_PATH.$post['picture']);
			#制作缩略图
			$im ->thumb(100,100);
			#保存图片 带盘符路径
			$im->save(WORKING_PATH . UPLOAD_PATH . $info['savepath'] . 'thumb_' . $info['savename']);
			$post['thumb'] = UPLOAD_PATH . $info['savepath'] . 'thumb_' . $info['savename'];

		}
		$model = M('Knowledge');
		$rst = $model->save($post);
		if($rst!==false){
			$this->success('更新成功',U('showList'),3);
		}else{
			$this->error('更新失败',U('edit',array('id' => $post['id'])),3);
		}

	}
	#download方法
	public function download(){
		//on_clean();
		$id = I('get.id');
		#实例化模型
		$model = M('Knowledge');		
		#查询附件信息
		$data = $model -> find($id);
		//dump($data);die;
		#实现附件下载
		$file = WORKING_PATH . $data['thumb'];
		header("Content-type: application/octet-stream");
		header('Content-Disposition: attachment; filename="' . basename($file) . '"');
		header("Content-Length: ". filesize($file));
		readfile($file);
	}
}