
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
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">推荐奖励</p>
    </div>
</nav>
<div id="main">
    {tpl info}
    <div class="container-fluid">
        <div class="row member_ex white_bg member_gift">
            <div class="media">
                <div class="media-left media-middle">
                    <span class="glyphicon glyphicon-xbt"></span>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">推荐奖励</h4>
                    <p>推荐奖励的明细记录</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row white_bg member_mycard">
            <table class="table table-hover table-striped table-bordered text-center">
                <thead>
                <tr>
                    <th>可领取乐豆</th>
                    <th>领取状态</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{$point}</td>
                    <td><?php if($point>0){?>
                            <?php if($id){ echo '已经领取过了';}else{?>
                                <input type='button' id='btnGet' class='btn btn-danger' value='领取'/>
                                <?php }}?></td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row white_bg member_mycard">
            <table class="table table-hover table-striped table-bordered text-center">
                <thead>
                <tr>
                    <th>时间</th>
                    <th>乐豆</th>
                    <th>状态</th>
                </tr>
                </thead>
                <tbody>
                {if $list}
                {loop $list $k $v}
                <tr>
                    <td>{$v.log_time}</td>
                    <td>{$v.amount}</td>
                    <td>已领取</td>
                </tr>
                {/loop}
                {else}
                <tr>
                    <td colspan="4">暂无推广收益。</td>
                </tr>
                {/if}
                </tbody>
            </table>
        </div>
    </div>
</div>
{tpl foot}

<script type="text/javascript">
$(function () {
    $("#btnGet").bind('click',function () {
    	$("#btnGet").attr("disabled",true);
        $.getJSON('?c=extension&a=money&g=1',function (data) {
            if(data.status==0){
                alert(data.message);
                $("#btnGet").attr("disabled",false);
                window.location='{:url('extension/money')}';//reload();
            }else{
                alert(data.message);
                $("#btnGet").attr("disabled",false);
            }
        });
    })
})



    
    function page_list(p) {
        $.getJSON("?c=extension&a=index&ajax=1&page="+p,function (data) {
            var html='';
            for (var i in data.list){
                if(typeof data.list[i].points=='undefined')data.list[i].points=0;
                html+='<tr><td>'+data.list[i].nickname+'</td><td>'+data.list[i].logintime+'</td><td>'+data.list[i].points+'</td></tr>';
            }
            $("#bod").html(html);
            $(".page").html(data.page);
        })
    }
</script>
</body>
</html>