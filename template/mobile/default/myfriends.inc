<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    {tpl style}
    <link rel="stylesheet" type="text/css" href="{$skin}style/member_total.css" />
    <title>{$webname}</title>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <a href="javascript:void(0);" class="btn refresh" onclick="window.location.reload();"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #fff;">我的下线</p>
    </div>
</nav>
<div id="main">
    {tpl info}
    <div class="container-fluid">
        <div class="row member_ex white_bg member_gift">
            <div class="media">
                <div class="media-left media-middle">
                    <span class="glyphicon glyphicon-eject"></span>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">我的下线</h4>
                    <p>我的下线明细情况表</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row white_bg member_mycard">
            <table class="table table-hover table-striped table-bordered text-center">
                <thead>
                <tr>
                    <th>下线用户</th>
                    <th>登录时间</th>
                    <th>昨日输赢</th>
                </tr>
                </thead>
                <tbody>
                {if $user_list}
                {loop $user_list $v}
                <tr>

                    <td>{$v.nickname}</td>
                    <td>{$v.logintime}</td>
                    <td>{if $v.points}{$v.points}{else}0{/if}</td>
                </tr>
                {/loop}
                {else}
                <tr>
                    <td colspan="3">暂无下线数据</td>
                </tr>
                {/if}
                </tbody>
            </table>
        </div>
    </div>
</div>
{tpl foot}
</body>
</html>