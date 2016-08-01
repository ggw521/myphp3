<?php
namespace Admin\Controller;
use Think\Controller;
class DeptController extends Controller
{
	#方法 
	public function add(){
		#实例化模型
		$model = M('Dept');
		#查询
		$data = $model-> select();
		#发送
		$this->assign('data',$data);
	            $this->display();
	}
	#addOk方法ff
	public function addOk(){
	    #接受数据
	    $post = I('post.');
	    #实例化
	    $model = M('Dept');
	    $rt = $model->add($post);
	    #判断返回结果
	    if($rt){
	    	$this -> success('添加成功',U('showList') , 3 );
	    }else{
	    	$this -> error('添加失败', U('add') , 3 );
	    }
	}
	#showList 方法
	public function showList(){
		$model = M('Dept');
		$data = $model->select();//查询
              
		//dump($data);die;
		#$info = $model->where('id='.$data[3]['pid'])->find();
		#dump($info);
		#文件载入
		load('@/tree');
		#无限极分类
		$data = getTree($data);
		#分页
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

	       	   foreach ($data as $key => $val) {
			if($val['pid']>0){
                                       $info = $model->find($val['pid']);
                                       $data[$key]['pname'] = $info['name'];
			}

		}
	       	//dump($data);die;
                          $this->assign('count',$count);
                         $this->assign('info',$info);
                         $this->assign('show',$show);
		$this->assign('data',$data);//传递到魔板
		$this->display();//展示
	}
	#del方法
	public function del(){
		$ids = I('get.ids');
		$model = M('Dept');
		$rst= $model->delete($ids);
                        if($rst){
                        	$this->success('删除成功',U('showList'),3);
                        }else{
                        	$this->error('删除失败',U('showList'),3);
                        }
	}
	//edit方法
	public function edit(){
		$id = I('get.id');
		$model = M('Dept');
		$data = $model->find($id);
		$info = $model->where('id != ' . $id)->select();
		$this->assign('info',$info);
		$this->assign('data',$data);
		$this->display();
	}
	//editOk方法
	public function editOk(){
		$post = I('post.');
		$model = M('Dept');
		$rst = $model->save($post);
		if($rst !==false){
			$this->success('编辑成功',U('showList'),3);
		}else{
			$this->error('编辑失败',U('edit',array('id' => $post['id'])),3);
		}
	}
	
}