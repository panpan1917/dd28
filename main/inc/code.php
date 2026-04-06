<?php 
session_start(); 
$authnum=random(4);//验证码字符. 
$_SESSION["yan"]=$authnum; 
//生成验证码图片 
Header("Content-type: image/PNG"); 
$im = imagecreate(60,20); 
$red = ImageColorAllocate($im, 255,255,255); //设置背景颜色 
$white = ImageColorAllocate($im, 0,0,0);//设置文字颜色 
$gray = ImageColorAllocate($im, 0,0,0); //设置杂点颜色 

imagefill($im,60,20,$red); 

for ($i = 0; $i < strlen($authnum); $i++) 
{  
imagestring($im, 6, 13*$i+4, 1, substr($authnum,$i,1), $white); 
} 

for($i=0;$i<100;$i++) //加入干扰象素 
{ 
imagesetpixel($im, rand()%60 , rand()%20 , $gray); 
} 

ImagePNG($im); //以 PNG 格式将图像输出到浏览器或文件 
ImageDestroy($im);//销毁一图像 

//产生随机数函数 
function random($length) { 
$hash = ''; 
$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz'; 

$max = strlen($chars) - 1; 

for($i = 0; $i < $length; $i++) { 
$hash .= $chars[mt_rand(0, $max)]; 
} 
return $hash; 

} 
?>