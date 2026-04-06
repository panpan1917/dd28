<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    {tpl style}
    <link rel="stylesheet" type="text/css" href="{$skin}css/js.css" />
    <script type="text/javascript" src="{$skin}js/vue.min.js"></script>
    <title>{$webname}</title>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header">
            <a href="{:url("game/game/id/$id")}" class="navbar-brand" onclick="javascript:void(0);"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#list_nav">
                <span class="glyphicon glyphicon-option-horizontal"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="list_nav">
            <ul class="nav navbar-nav navbar-right" style="display: none">
                <li class="active"><a href="auto.shtml">自定义投注模式</a></li>
                <li><a href="../member_mybank.shtml">小金库</a></li>
                <li><a href="trend.shtml">{$game_name}走势图</a></li>
            </ul>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-18px; font-size:16px;  text-shadow:2px 2px 3px #ccc;color: #ecc296;">{$game_name}</p>
    </div>

</nav>

<div id="main">
    <div class="container-fluid">
        <div class="row">
            <table class="table table-hover table-striped table-bordered text-center">
                <tr>
                    <th class="text-center">期号</th>
                    <th class="text-center">时间</th>
                    <th class="text-center">投注</th>
                    <th class="text-center">获取</th>
                    <th class="text-center">赢取</th>
                </tr>
                {loop $list $v}
                <tr class="item" rel="{$v.no}">
                    <td>{$v.no}</td>
                    <td>{$v.time}</td>
                    <td>{$v.points}</td>
                    <td>{$v.hdpoints}</td>
                    <td>{$v.hd}</td>
                </tr>
                {/loop}
            </table>
        </div>

    </div>
</div>
{tpl foot}
<script type="text/javascript">
    $(function () {
        $("#list").on('click','#press',function () {
            no=$(this).parents("a").attr("id");
            window.location="{:url("game/press/id/$id")}&no="+no;
            return false;
        });
        $("#main").on("click",".item",function () {
            no=$(this).attr("rel");
            window.location="{:url("game/recorddetail/id/$id")}&no="+no;
        })

    })
</script>
</body>
</html>