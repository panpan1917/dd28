
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
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">领取红包</p>
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
                    <h4 class="media-heading">领取红包</h4>
                    <p>领取红包的明细记录</p>
                </div>
            </div>
        </div>
    </div>
    <div id='bank'>

        <dl>
            <dd>
                <div class="input-group">
                    <span class='input-group-addon'>输入红包码:</span>
                    <input type='text' id='txtl' class='form-control' placeholder='输入红包码'/>
                </div>
            </dd>
            <dd class='text-center'>
                <input type='button' id='btnPack' class='btn btn-danger' value='获取红包'/>
            </dd>
        </dl>
    </div>

    <div class="container-fluid">
        <div class="row white_bg member_mycard">
            <table class="table table-hover table-striped table-bordered text-center">
                <thead>
                <tr>
                    <th>时间</th>
                    <th>金额</th>
                    <th>状态</th>
                </tr>
                </thead>
                <tbody>
                {if $list}
                {loop $list $k $v}
                <tr>
                    <td>{function=date('Y-m-d H:i:s',$v.time)}</td>
                    <td>{$v.ledou}</td>
                    <td>已领取</td>
                </tr>
                {/loop}
                {else}
                <tr>
                    <td colspan="4">暂无记录。</td>
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
        $("#btnPack").bind('click',function () {
            var l=$("#txtl").val();
            if(l == ""){
            	alert('请输入正确的红包号码');
                return;
            }
            
            /* var l=parseInt($("#txtl").val());
            if(isNaN(l)){
                alert('请输入正确的代码');
                return;
            } */

            $("#btnPack").attr("disabled",true);
            $.getJSON('?c=pack&a=index&num='+l,function (data) {
                alert(data.message);
                $("#btnPack").attr("disabled",false);
                if(data.status==0){
                    window.location.reload();
                    return false;
                }
            })
        })
    })
</script>
</body>
</html>