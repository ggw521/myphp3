<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="/Public/Admin/css/base.css" />
<link rel="stylesheet" href="/Public/Admin/css/info-mgt.css" />
<link rel="stylesheet" href="/Public/Admin/css/WdatePicker.css" />
<title>移动办公自动化系统</title>
<style type='text/css'>
	table tr .id{ width:63px; text-align: center;}
	table tr .name{ width:118px; padding-left:17px;}
	table tr .nickname{ width:63px; padding-left:17px;}
	table tr .dept_id{ width:63px; padding-left:13px;}
	table tr .sex{ width:63px; padding-left:13px;}
	table tr .birthday{ width:80px; padding-left:13px;}
	table tr .tel{ width:113px; padding-left:13px;}
	table tr .email{ width:160px; padding-left:13px;}
	table tr .addtime{ width:160px; padding-left:13px;}
	table tr .operate{ padding-left:13px;}
</style>
</head>

<body>
<div class="title"><h2>知识管理</h2></div>
<div class="table-operate ue-clear">
	<a href="/index.php/Admin/Knowledge/add" class="add">添加</a>
    <a href="javascript:;"  id ="btndel" class="del">删除</a>
    <a href="javascript:;" id = "btnedit" class="edit">编辑</a>
    <a href="javascript:;" class="check">审核</a>
</div>
<div class="table-box">
	<table>
    	<thead>
        	<tr>
            	<th class="id">序号</th>
                <th class="name">标题</th>
				<th class="file">缩略图</th>
                <th class="description">描述</th>
                <th class="content">内容</th>
                <th class="content">作者</th>
				<th class="addtime">添加时间</th>
                <th class="operate">操作</th>
            </tr>
        </thead>
        <tbody>
        	<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            	<td class="id"><?php echo ($vo["id"]); ?></td>
                <td class="name"><?php echo ($vo["title"]); ?></td>
               <!-- <?php if(!empty($vo["thumb"])): ?><img src="<?php echo ($vo["thumb"]); ?>"/><?php endif; ?>-->
				<td class="file">
                <?php if($vo.thumb!==null): ?><img src="<?php echo ($vo["thumb"]); ?>"> 【<a href='javascript:;' class="download" data='<?php echo ($vo["id"]); ?>' >下载</a>】<?php endif; ?>
                </td>
                 <td class="description"><?php echo ($vo["description"]); ?></td>
                <td class="content"><?php echo (msubstr(htmlspecialchars_decode($vo["content"]),0,20)); ?></td>
                <td class="author"><?php echo ($vo["author"]); ?></td>
                <td class="addtime"><?php echo (date('Y-m-d H:i:s',$vo["addtime"])); ?></td>
                <td class="operate">
                	<a href ='javascript:;' class="show" data = "<?php echo ($vo["id"]); ?>" data-title = "<?php echo ($vo["title"]); ?>">查看</a> 
                    <input type ="checkbox" value="<?php echo ($vo["id"]); ?>"></input>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
</div>
<div class="pagination ue-clear">
	<div class="pagin-list">
		<?php echo ($show); ?>
	</div>
	<div class="pxofy">共 <?php echo ($count); ?> 条记录</div>
</div>
</body>
<script type="text/javascript" src="/Public/Admin/js/jquery.js"></script>
<script type="text/javascript" src="/Public/Admin/js/common.js"></script>
<script type="text/javascript" src="/Public/Admin/js/WdatePicker.js"></script>
<script type="text/javascript" src="/Public/Admin/plugin/layer/layer.js"></script>
<script type="text/javascript">
$(".select-title").on("click",function(){
	$(".select-list").hide();
	$(this).siblings($(".select-list")).show();
	return false;
})
$(".select-list").on("click","li",function(){
	var txt = $(this).text();
	$(this).parent($(".select-list")).siblings($(".select-title")).find("span").text(txt);
})

$("tbody").find("tr:odd").css("backgroundColor","#eff6fa");

showRemind('input[type=text], textarea','placeholder');
$(function(){
    $('#btndel').on('click',function(){

    var id = $(':checkbox:checked');
        var ids = '';       
        //遍历jQuery对象id变量
        for(var i = 0;i < id.length;i++){
            //组装ids
            ids = ids + id[i].value + ','; //1,2,3,
        }
        //去掉末尾的多余逗号
        ids = ids.substring(0,ids.length - 1);  //1,2,3,4
        //跳转到删除方法
        if(window.confirm('数据删除就没有了，Are you 确定？')){
        window.location.href = '/index.php/Admin/Knowledge/del/ids/' + ids;
    }
    })
    $('#btnedit').on('click',function(){
        var id = $(':checkbox:checked').val();
        window.location.href = "/index.php/Admin/Knowledge/edit/id/"+id;
    })

    $('.download').on('click',function(){
        var id = $(this).attr('data');
        window.location.href = '/index.php/Admin/Knowledge/download/id/' + id;
    })
    //给查看按钮添加点击事件
    $('.show').on('click',function(){
        //获取id
        var id = $(this).attr('data');
        //获取title
        var docTitle = $(this).attr('data-title');
        //使用layer弹出窗口
        layer.open({
            type: 2,
            title: docTitle,
            shadeClose: true,
            shade: 0,
            area: ['450px', '80%'],
            content: '/index.php/Admin/Knowledge/getContent/id/' + id //iframe的url
        }); 
    });
})
</script>
</html>