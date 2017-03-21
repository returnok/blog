<?php 


require('./lib/init.php');

//查询所有的栏目
$sql = "select cat_id,catname from cat";
$cats = mGetAll($sql);


//判断地址栏是否有cat_id
//$cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : '';
if(isset($_GET['cat_id'])) {
	$where = " and art.cat_id=$_GET[cat_id]";
} else {
	$where = '';
}

//分页代码
$sql = "select count(*) from art where 1" . $where;//获取总的文章数
$num = mGetOne($sql);//总的文章数
//getPage()
$curr = isset($_GET['page']) ? $_GET['page'] : 1;//当前页码数
$cnt = 2;//每页显示条数
$page = getPage($num , $curr, $cnt);
//print_r($page);

//查询所有的文章
$sql = "select art_id,title,content,pubtime,comm,catname,thumb from art inner 
join cat on art.cat_id=cat.cat_id where 1" . $where . ' order by art_id desc limit ' . ($curr-1)*$cnt . ',' . $cnt;
//echo $sql;exit();
$arts = mGetAll($sql);
//print_r($arts);exit();

//如果当前栏目下没有文章,跳转到首页去

require(ROOT . '/view/front/index.html');



?>