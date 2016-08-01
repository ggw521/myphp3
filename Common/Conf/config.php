<?php
return array(
		//'配置项'=>'配置值'
	'DEFAULT_MODULE'        =>  'Admin',  // 默认模块
	'TMPL_DENY_PHP'         =>  false, // 默认模板引擎是否禁用PHP原生代码

	'DB_TYPE'               =>  'mysql',     // 数据库类型,mysql oracle mssql db2 access
    'DB_HOST'               =>  'www.mytpkj.com', // 服务器地址
    'DB_NAME'               =>  'db_oa',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'tp_',    // 数据库表前缀
    'SHOW_PAGE_TRACE' => true,//显示跟踪信息，默认值是false
);