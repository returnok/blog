<?php 


require('./lib/init.php');
$art_id = $_GET['art_id'] + 0;

//判断地址栏传来的art_id 是否合法
/*if(!is_numeric($art_id)) {
	header('Location: index.php');
}*/

//如果没有这篇文章 跳转到首页去
/*$sql = "select * from art where art_id=$art_id";
if(!mGetRow($sql)) {
	header('Location: index.php');
}*/

//查询文章
$sql = "select title,content,pubtime,catname,comm,pic from art inner join cat on art.cat_id=cat.cat_id where art_id=$art_id";
$art = mGetRow($sql);
//print_r($art);exit();

//查询所有的留言
$sql = "select * from comment where art_id=$art_id";
$comms = mGetAll($sql);


//post 非空 代表有留言
if(!empty($_POST)) {
	$comm['nick'] = trim($_POST['nick']);
	$comm['email'] = trim($_POST['email']);
	$comm['content'] = htmlspecialchars(trim($_POST['content']));
	$comm['pubtime'] = time();
	$comm['art_id'] = $art_id;
	$comm['ip'] = sprintf('%u' , ip2long(getRealIp()));
	$rs = mExec('comment' , $comm);
	if($rs) {
		//评论发布成功 将art表的comm+1
		$sql = "update art set comm=comm+1 where art_id=$art_id";
		mQuery($sql);

		//跳转到上个页面
		$ref = $_SERVER['HTTP_REFERER'];
		header("Location: $ref");
	}
}


require(ROOT . '/view/front/art.html');




?>