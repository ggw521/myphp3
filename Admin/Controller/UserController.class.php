<?php 
namespace Admin\Controller;
use Think\Controller;
/**
* 
*/
class UserController extends Controller
{
	       #showList方法
	       public function showList(){
	       	$model = M('User');
	       	#获取总记录数
	       	$count = $model->count();
	       	#实例化分页类 至少传递一个参数
	       	$page = new \Think\Page($count,1);     
	       	$page -> setConfig('prev','上一页');
		$page -> setConfig('next','下一页');
		$page -> setConfig('first','首页');
		$page -> setConfig('last','末页');
		$page -> rollPage = 3;		#每页显示的页码数，如想显示首页按钮，则该属性的值必须要小于总的页码数
		$page -> lastSuffix = false;	
	       	#使用show方法组转
	       	$show = $page->show();
	       	$data = $model->limit($page->firstRow,$page->listRows)->select();
	       	$this->assign('data',$data);
	       	$this->assign('count',$count);
	       	$this->assign('show',$show);
	       	$this->display();
	       }
	       #add方法
	       public function add(){
	       	$model = M('Dept');
	       	$data = $model->select();
	       	$this->assign('data',$data);
	       	$this->display();
	       }
	       #addOk方法
	       public function addOk(){
	       	$post = I('post.');
	       	$model = M('User');
	       	$rst = $model->add($post);
	       	if($rst){
	       		$this->success('添加成功',U('showList'),3);
	       	}else{
	       		$this->error('添加失败',U('add'),3);
	       	}
	       }
	       #成员删除的方法
	       public function del(){
	       	$ids = I('get.ids');
	       	$model = M('User');
	       	$rst = $model->delete($ids);
	       	if($rst){
	       		$this->success('删除成功',U('showList'),3);
	       	}else{
	       		$this->error('删除失败',U('showList'),3);
	       	}
	       }
                 #成员的编辑方法
                 public function edit(){
                 	$id = I('get.id');
                 	$model = M('User');
                 	$dept = M('Dept');
                 	$info = $dept->select();
                 	$data = $model->find($id);
                 	$this->assign('data',$data);
                 	$this->assign('info',$info);
                 	$this->display();
                 }
                 #editOk方法
                 public function editOk(){
                 	$post = I('post.');
                 	if(empty($post['password'])){
			#剔除密码元素
			unset($post['password']);
		}
                 	$model = M('User');
                 	$rst = $model->save($post);
                 	if($rst){
                 		$this->success('编辑成功',U('showList'),3);
                 	}else{
                 		$this->error('编辑失败',U('edit',array('id' => $post['id'])),3);
                 	}
                 }
                 #lianbiao方法
                 public function lianbiao(){
                 	#select t1.*,t2.name as name from tp_user as t1,tp_dept as t2 where t1.dept_id = t2.id;
                 	$model = M('User');
                 	$model->field('t1.*,t2.name as name')
                 	            ->table('tp_user as t1,tp_dept as t2')
                 	            ->where('t1.dept_id = t2.id')
                 	            ->select();
                 }

	
}