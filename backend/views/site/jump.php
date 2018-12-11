<?php
use backend\components\Func;
////$_GET['code'] = "http://hao.lianmeng.group/";
//$_GET['code'] = "http://weixintk.com/";
//$url_img = base64_decode($_GET['code']);
//if($_GET['c'] == 'c'){
////    $url_img = urldecode("http://t.cn/EzY7zW4");
//
//}else{
//    $url_img = urldecode("http://hao.lianmeng.group/1.jpg");
////    $url_img = urldecode("http://hao.lianmeng.group/2.png");
//}
if($flag){
$ip=Func::ipcode();   //随机ip
// 设置IP
$header = array(

    'X-FORWARDED-FOR:'.$ip
);
// test  msdkdev  msdktest

//生成第一个
$url_one = 'http://wx.17u.cn/wxinfo/openwxlink/redirectLink?id=1&url=http://wx.17u.cn/home/index.html/../../wxinfo/WxMember/RedirectBind?url='.$url_img;
$contents_one = Func::curls($url_one,$header);
preg_match("{openLink = '(.*?)';}s",$contents_one,$result);
$one_url = $result[1];
//var_dump($result);exit;
$url_two = 'http://wx.17u.cn/wxinfo/openwxlink/redirectLink?id=1&url=http://wx.17u.cn/home/index.html/../../wxinfo/WxMember/RedirectBind?url='.$one_url;
$contents_two = Func::curls($url_two,$header);

preg_match("{openLink = '(.*?)';}s",$contents_two,$results);
//echo $results[1];
//var_dump($results);exit;
$ticket = $results[1];
//exit;


///////////////////////////////////////////////

?>
<script>location.href="<?php echo $ticket;?>";</script>
<?php }else{ //pc端?>
<script>location.href="<?php echo $url_img_pc;?>";</script>
<?php };?>


