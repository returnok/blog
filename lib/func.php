<?php 

/**
* 成功的提示信息
*/

function succ($res) {
	$result = 'succ';
	require(ROOT . '/view/admin/info.html');
	exit();
}

/**
* 失败返回的报错信息
*/

function error($res) {
	$result = 'fail';
	require(ROOT . '/view/admin/info.html');
	exit();
}

/**
* 获取来访者的真实IP
*
*/

function getRealIp() {
	static $realip = null;
	if($realip !== null) {
		return $realip;
	}

	if(getenv('REMOTE_ADDR')) {
		$realip = getenv('REMOTE_ADDR');
	} else if(getenv('HTTP_CLIENT_IP')) {
		$realip = getenv('HTTP_CLIENT_IP');
	} else if (getenv('HTTP_X_FROWARD_FOR')) {
		$realip = getenv('HTTP_X_FROWARD_FOR');
	}

	return $realip;	
}

/**
* 生成分页代码
* @param int $num 文章总数
* @param int $curr 当前显示的页码数      $curr-2 $curr-1 $curr $curr+1 $curr+2
* @param int $cnt 每页显示的条数
*/

function getPage($num,$curr,$cnt) {
	//最大的页码数
	$max = ceil($num/$cnt);
	//最左侧页码
	$left = max(1 , $curr-2);

	//最右侧页码
	$right = min($left+4 , $max);

	$left = max(1 , $right-4);

/*	(1 [2] 3 4 5) 6 7 8 9 
	1 2 (3 4 [5] 6 7) 8 9
	1 2 3 4 (5 6 7 [8] 9)*/
	$page = array();
	for($i=$left;$i<=$right;$i++) {
		$_GET['page'] = $i;
 		$page[$i] = http_build_query($_GET);
	}

	return $page;
}

//print_r(getPage(100,5,10));

/**
* 生成随机字符串
* @param int $num 生成的随机字符串的个数
* @return str 生成的随机字符串
*/
function randStr($num=6) {
	$str = str_shuffle('abcedfghjkmnpqrstuvwxyzABCEDFGHJKMNPQRSTUVWXYZ23456789');
	return substr($str, 0 , $num);
}

//echo randStr();

/**
* 创建目录 ROOT.'/upload/2015/01/25/qwefas.jpg'
* 
*/
function createDir() {
	$path = '/upload/'.date('Y/m/d');
	$fpath = ROOT . $path;
	if(is_dir($fpath) || mkdir($fpath , 0777 , true)) {
		return $path;
	} else {
		return false;
	}
}

/**
* 获取文件后缀
* @param str $filename 文件名
* @return str 文件的后缀名,且带点.
*/
function getExt($filename) {
	return strrchr($filename, '.');
}


/**
* 生成缩略图
*
* @param str $oimg /upload/2016/01/25/asdfed.jpg
* @param int $sw 生成缩略图的宽
* @param int $sh 生成缩略图的高
* @return str 生成缩略图的路径 /upload/2016/01/25/asdfed.png
*/

function makeThumb($oimg , $sw=200 , $sh = 200) {
	//缩略图存放的路径的名称
	$simg = dirname($oimg) . '/' . randStr() . '.png';

	//获取大图和缩略图的绝对路径
	$opath = ROOT . $oimg;//原图的绝对路径
	$spath = ROOT . $simg;//最终生成的小图

	//创建小画布
	$spic = imagecreatetruecolor($sw, $sh);

	//创建白色
	$white = imagecolorallocate($spic, 255, 255, 255);
	imagefill($spic, 0, 0, $white);

	//获取大图信息
	list($bw , $bh ,$btype) = getimagesize($opath);
	//1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，6 = BMP，
	//7 = TIFF(intel byte order)，8 = TIFF(motorola byte order)，9 = JPC，10 = JP2，
	//11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，16 = XBM
	$map = array(
		1=>'imagecreatefromgif',
		2=>'imagecreatefromjpeg',
		3=>'imagecreatefrompng',
		15=>'imagecreatefromwbmp'
	);
	if(!isset($map[$btype])) {
		return false;
	}
	$opic = $map[$btype]($opath);//大图资源
	//imagecreatefromjpeg(filename)

	//计算缩略比
	$rate = min($sw/$bw , $sh/$bh);
	$zw = $bw * $rate;//最终返回的小图宽
	$zh = $bh * $rate;//最终返回的缩略小图高

	//imagecopyresampled(dst_image, src_image, dst_x, dst_y, 
		//src_x, src_y, dst_w, dst_h, src_w, src_h)
	//echo $rate ,  '<br>' , $zw , '<br>' , $zh ;exit();
	//imagecopyresampled($spic, $opic, 0, 0, 0, 0, $zw, $zh, $bw, $bh);

	imagecopyresampled($spic, $opic, ($sw-$zw)/2, ($sh-$zh)/2, 0, 0, $zw, $zh, $bw, $bh);

	imagepng($spic , $spath);

	imagedestroy($spic);
	imagedestroy($opic);

	return $simg;
}


/**
* 检测用户是否登录
*/

function acc() {
	if(!isset($_COOKIE['name']) || !isset($_COOKIE['ccode'])){
		return false;
	}
	return $_COOKIE['ccode'] === cCode($_COOKIE['name']);
}



/**
* 加密用户名
* @param str $name 用户登陆时输入的用户名
* @return str md5(用户名+salt)=>md5码
*/

function cCode($name) {
	$salt = require(ROOT . '/lib/config.php');
	return md5($name . '|' . $salt['salt']);
}






?>