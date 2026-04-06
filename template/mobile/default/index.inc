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
        line-height: 30px;
        padding: 0 24px 0 12px;
        background-size: 24px 12px; }
</style>
    <title>{$webname}</title>
</head>
<body>

{tpl head}

<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="item active"><img src="{$skin}img/banner/03.png" alt=""></div>
        <div class="item"><img src="{$skin}img/banner/01.png" alt="网站直充"></div>
        <div class="item"><img src="{$skin}img/banner/02.png" alt="多方充值"></div>
    </div>
</div>

<div id="main_column" class="white_bg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pad0">
                <a href="./mobile.php?c=index&a=newslist">
                    <div class="news">
                        <div class="news_img"><img src="{$skin}img/icon/news1.png" class="img-responsive" alt=""></div>
                        <div class="news_img">
                            <h3>新闻公告</h3>
                        </div>
                        <div class="clearfix"></div>
                        <div class="news_text">关注最新公告，助你赢取大奖!</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pad0">
                <a href="#">
                    <div class="news">
                        <div class="news_img"><img src="{$skin}img/icon/news2.png" class="img-responsive" alt=""></div>
                        <div class="news_img">
                            <h3>最新活动</h3>
                        </div>
                        <div class="clearfix"></div>
                        <div class="news_text">内容丰富，惊喜不断!</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pad0">
                <?php if($is_weixin){?>
                <a href="http://wpa.qq.com/msgrd?v=3&uin=78271214&site=qq&menu=yes">
                <?php }else{?>
                <a href="mqqwpa://im/chat?chat_type=wpa&uin=78271214&version=1&src_type=web&web_src=oicqzone.com">
                <?php }?>
                    <div class="news">
                        <div class="news_img"><img src="{$skin}img/icon/news4.png" class="img-responsive" alt=""></div>
                        <div class="news_img">
                            <h3>联系客服</h3>
                        </div>
                        <div class="clearfix"></div>
                        <div class="news_text">更多疑难问题，请与客服QQ联系!</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>


<div class="side">
    <div class="zone news">
        <h1>新闻公告</h1>
        <ul class="zone-con area-list" style="padding: 0">
                {if $news_list}
                {loop $news_list $k $v}
                <li><a style="color:#000;" href="./mobile.php?c=index&a=news&id={$v.id}">{$v.title}</a> </li>
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
