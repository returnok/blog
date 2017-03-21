<meta charset="utf8">
<?php 



$cat_id = $_GET['cat_id'];
//连接数据库
$conn = mysql_connect('localhost' , 'root' , '');
mysql_query('use 1224blog' , $conn);
mysql_query('set names utf8');

//检测 栏目id 是否为数字
//var_dump($cat_id);
if(!is_numeric($cat_id)) {
	echo '栏目不合法';
	exit();
}

//检测 栏目是否存在
$sql = "select count(*) from cat where cat_id=$cat_id";
$rs = mysql_query($sql);
if(mysql_fetch_row($rs)[0] == 0) {
	echo '栏目不存在';
	exit();
}

if(empty($_POST)){
	$sql = "select catname from cat where cat_id=$cat_id";
	$rs = mysql_query($sql);
	$cat = mysql_fetch_assoc($rs);
	require('./view/admin/catedit.html');
} else {
	$sql = "update cat set catname='$_POST[catname]' where cat_id=$cat_id";
	if(!mysql_query($sql)){
		echo '栏目修改失败';
	} else {
		echo '栏目修改成功';
	}
}


?>