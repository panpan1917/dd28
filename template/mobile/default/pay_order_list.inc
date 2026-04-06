
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <title>会员中心-{$webname}</title>
    <link rel="stylesheet" type="text/css" href="{$skin}css/member_basic.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/member_total.css" />
    {tpl style}
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <a href="javascript:void(0);" class="btn refresh" onclick="window.location.reload();"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">充值记录</p>
    </div>
</nav>
<div id="main">
    {tpl info}
    <div class="container-fluid">
        <div class="row member_ex white_bg member_gift">
            <div class="media">
                <div class="media-left media-middle">
                    <span class="glyphicon glyphicon-folder-open"></span>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">充值记录</h4>
                    <p>记录。</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row white_bg member_mycard">
            <table class="table table-striped table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th>金额</th>
                    <th>充值时间</th>
                    <th>付款时间</th>
                    <th>状态</th>
                    <th>方式</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                {loop $list $v}
                <tr>
                    <td>{$v.rmb}</td>
                    <td>{$v.add_time}</td>
                    <td>{$v.pay_time}</td>
                    <td>{if $v.state==0}待付款{elseif $v.state==2}失败{elseif $v.state==1}成功{/if}</td>
                    <td>{$v.type}</td>
                    <td>{if $v.state==0}<a href="#" onclick="cancelOrder('{$v.order_id}');">撤销</a>{/if}</td>
                </tr>
                {/loop}

                </tbody>

            </table>
        </div>
    </div>
</div>


<script type="text/javascript">
	function cancelOrder(order_id){
        $.ajax({
            type: 'post',
            url: '{:url('users/cancelorder')}',
            data: {order_id: order_id},
            dataType: 'json',
            cache:false,
            success: function (data) {
                if(data.status==0){
                    alert(data.message);
                    window.location='{:url('users/pay_order_list')}';//reload();
                    return false;
                }else{
                    alert(data.message);
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('撤销失败,未知错误!');
            }
        });
	}
</script>


{tpl foot}
</body>
</html>