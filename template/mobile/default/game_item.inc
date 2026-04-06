<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <link rel="stylesheet" type="text/css" href="{$skin}style/game_list.css" />
    {tpl style}
    <link rel="stylesheet" type="text/css" href="{$skin}style/game_list.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}css/js.css" />
    <title>欧乐28第750164期明细-{$webname}</title>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-55px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">第{$no}期明细</p>
    </div>
</nav>



<div id="main">
    <div class="container-fluid">
        <div class="row white_bg detailed_coin">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0">
                <p>¥: <span class="red" id="money">{$info.points}</span></p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0">
                <p>银行: ¥<span class="red" id="bank">{$info.bankpoints}</span></p>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row white_bg detailed_coin">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0">
                <p>开奖时间: <span>03-25 13:20</span></p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0">
                <p>开奖结果: <span class="red">{$jg}</span></p>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row white_bg">
            <table class="table table-hover table-striped text-center">
                <thead>
                <tr>
                    <th>号码</th>
                    <th>赔率</th>
                    <th>我的投注额</th>
                    <th>获取金蛋</th>
                </tr>
                </thead>
                <tbody>
                {loop $Nums $k $v}
                <tr>
                    <td><span><img src="{$skin}/img/jg/number_{$v}.gif" alt="" class="img-responsive"></span></td>
                    <td>{$arrtzpl[$k]}</td>
                    <td><span>{$arrNewtz[$v]}</span></td>
                    <td><span {if $arrNewhdPoints[$v]} class="red"{/if}>{$arrNewhdPoints[$v]}</span></td>
                </tr>
                {/loop}

                </tbody>
            </table>
        </div>
    </div>
</div>
{tpl foot}
</body>
</html>