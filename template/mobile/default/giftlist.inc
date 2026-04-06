
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <title>兑换中心-{$webname}</title>
    <link rel="stylesheet" type="text/css" href="{$skin}css/member_basic.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/member_total.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/gift.css" />
    {tpl style}
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <a href="javascript:void(0);" class="btn refresh" onclick="window.location.reload();"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">兑换中心</p>
    </div>
</nav>

<div id="main">

    <div class="container-fluid" style="padding:0px;">
		
   <div class="missing-list" id="divContent">
 
       <div class="exch-border exch-itemrow">
            <ul>
                    <li>
                        <a href="{:url('gift/product')}&card_id=2">
                            <img src="/images/gift/product2.png" alt="">
                            <cite>滴滴体验卡100元</cite>
                            <cite><span>100000</span></cite>
                            <i class="exch-border"></i>
                        </a>
                       <a class="ui-btn" href="{:url('gift/product')}&card_id=2">兑换</a>
                    </li>
                   
                    <li>
                        <a href="{:url('gift/product')}&card_id=4">
                            <img src="/images/gift/product4.png" alt="">
                            <cite>滴滴体验卡500元</cite>
                            <cite><span>500000</span></cite>
                            <i class="exch-border"></i>
                        </a>
                       <a class="ui-btn" href="{:url('gift/product')}&card_id=4">兑换</a>
                    </li>
                  
                    <li>
                        <a href="{:url('gift/product')}&card_id=6">
                            <img src="/images/gift/product6.png" alt="">
                            <cite>滴滴体验卡1000元</cite>
                            <cite><span>1000000</span></cite>
                            <i class="exch-border"></i>
                        </a>
                       <a class="ui-btn" href="{:url('gift/product')}&card_id=6">兑换</a>
                    </li>
                    <li>
                        <a href="{:url('gift/product')}&card_id=7">
                            <img src="/images/gift/product7.png" alt="">
                            <cite>滴滴体验卡5000元</cite>
                            <cite><span>5000000</span></cite>
                            <i class="exch-border"></i>
                        </a>
                       <a class="ui-btn" href="{:url('gift/product')}&card_id=7">兑换</a>
                    </li>
					
					 <li>
                        <a href="{:url('gift/product')}&card_id=8">
                            <img src="/images/gift/product8.png" alt="">
                            <cite>滴滴体验卡10000元</cite>
                            <cite><span>10000000</span></cite>
                            <i class="exch-border"></i>
                        </a>
                       <a class="ui-btn" href="{:url('gift/product')}&card_id=8">兑换</a>
                    </li>
					 <li>
                        <a href="{:url('gift/product')}&card_id=9">
                            <img src="/images/gift/product9.png" alt="">
                            <cite>滴滴体验卡50000元</cite>
                            <cite><span>50000000</span></cite>
                            <i class="exch-border"></i>
                        </a>
                       <a class="ui-btn" href="{:url('gift/product')}&card_id=9">兑换</a>
                    </li>
					
            </ul>
                        
      

        </div>
    </div>
		
		
    </div>
    
</div>


{tpl foot}
</body>
</html>