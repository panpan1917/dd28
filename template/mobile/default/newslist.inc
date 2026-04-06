<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    {tpl style}
<style>
    .news{ background: #fff}
    .zone h1 {
        position: relative;
        padding-left: 16px;
        border-bottom: 1px solid #ccc;
        font-size: 16px;
        font-weight: 400;
        line-height: 40px;
    }.area-list a, .list-nolink {
         display: block;
         color:#000;
         line-height: 30px;
         padding-left: 14px;
         padding: 0 24px 0 12px;
         background-size: 24px 12px; }
</style>
    <title>{$webname}</title>
</head>
<body>

{tpl head}

<div class="side">
    <div class="zone news">
        <h1 style="margin-top: 60px;text-align:center;">新闻公告</h1>
        <ul class="zone-con area-list" style="padding: 0">
                {if $news_list}
                {loop $news_list $k $v}
                <li><a href="./mobile.php?c=index&a=news&id={$v.id}">{$v.title}</a> </li>
                {/loop}
                {/if}
         </ul>
    </div>
</div>


{tpl foot}

<div class="footer">
	<?php if($is_weixin){?>
	<p>客服QQ:78271214 <a href="http://wpa.qq.com/msgrd?v=3&uin=78271214&site=qq&menu=yes">在线客服</a></p>
	<?php }else{?>
	<p>客服QQ:78271214 <a href="mqqwpa://im/chat?chat_type=wpa&uin=78271214&version=1&src_type=web&web_src=oicqzone.com">在线客服</a></p>
	<?php }?>
</div>

</body>

</html>
