<?php 

require('./lib/init.php');


if(empty($_POST)) {
	include(ROOT . '/view/admin/catadd.html');
} else {
/*	//连接数据库
	$conn = mysql_connect('localhost' , 'root' , '');
	mysql_query('use 1224blog' , $conn);
	mysql_query('set names utf8');*/
	//检测栏目是否为空
	$cat['catname'] = trim($_POST['catname']);
	if(empty($cat['catname'])) {
		error('栏目不能为空');
		exit();
	}

	//检测栏目名是否已存在
	$sql = "select count(*) from cat where catname='$cat[catname]'";
	$rs = mQuery($sql);
	//var_dump(mysql_fetch_row($rs)[0]);exit();
	if(mysql_fetch_row($rs)[0] != 0) {
		echo '栏目已经存在';
		exit();
	}

	//将栏目写入栏目表
	//$sql = "insert into cat (catname) values ('$cat[catname]')";
	if(!mExec('cat' , $cat)) {
		//echo '栏目插入失败';
		echo mysql_error();
	} else {
		//echo '栏目插入成功';
		succ('栏目插入成功');
	}
	//print_r($_POST);
}

//var_dump($_POST);




?>