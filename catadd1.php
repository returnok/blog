<meta charset="utf8">
<?php 


//判断表单是否有post数据
if (empty($_POST)) {
	include('./view/admin/catadd.html');
} else {
	//如有POST，则判断catname是否为空
	$cat['catname'] = trim($_POST['catname']);
	if(empty($cat['catname'])){
		exit('栏目名称不能为空');
	}

	// 连接数据库
	$conn = mysql_connect('localhost','root','');
	mysql_query('use 1224blog',$conn);
	mysql_query('set names utf8',$conn);
	//ar_dump($conn);exit();

	//首先查询catname是否有重名 
	$sql = "select count(*) from cat where catname='$cat[catname]'";
	$rs = mysql_query($sql , $conn);
	var_dump($rs);exit();

}

	
?>
