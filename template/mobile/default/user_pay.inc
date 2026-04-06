
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <link rel="stylesheet" type="text/css" href="{$skin}css/member_basic.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/member_total.css" />
    {tpl style}
    <title>会员中心-{$webname}</title>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <a href="javascript:void(0);" class="btn refresh" onclick="window.location.reload();"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">在线充值</p>
    </div>
</nav>
<div id="main">
    {tpl info}
    <div class="container-fluid">
        <div class="row white_bg member_mycard">
            <form action="gwpay.php" id="gwpay" method="post" name="gwpay">
            	<div class="form-group member_coin">
            	充值金额和订单金额不一致者不予上分!  
            	</div>
                <div class="form-group member_coin">
                    <label for="desposit">充值金额:</label>
                    <input type="number" class="form-control" id="money" name="money" placeholder="银行卡最低{$min_recharge}元起,二维码50~200元" style="margin-bottom: 10px;">
                    <input type='hidden' name="money2" id="money2" value='' />
                    <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="add(50)">50</button>
                    <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="add(100)">100</button>
                    <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="add(500)">500</button>
                    <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="add(1000)">1000</button>
                    <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="add(5000)">5000</button>
                    <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="removes()">清除</button>
                </div>
                <div class="form-group member_coin">
                    <div class='' id="pay_web" name="pay_web">
					{loop $cz_list $k $v}
					<span class="btn btn-default {if $k==0}btn-success active{/if}" pf='{$v.id}' gp='{$v.grouptype}' style="margin-bottom: 5px;">{$v.name}{$v.marks}</span>
					{/loop}
					</div>
                </div>
                <div class="form-group member_coin">
                    <label for="desposit">付款人姓名:</label>
                    <input type="text" class="form-control" id="payer" name="payer" value="{$recv_cash_name}" placeholder="输入付款人的姓名" style="margin-bottom: 10px;">
                    <button type="button" onclick="getrecvname();" class='btn btn-danger' style='width:15%'>刷新</button>
                </div><div class="form-group member_coin">
                    <label for="desposit">付款人账户:</label>
                    <input type="text" class="form-control" id="pay_account" name="pay_account" placeholder="输入付款的账号" style="margin-bottom: 10px;">
                    <button type="button" onclick="getlastaccount();" class='btn btn-danger' style='width:15%'>刷新</button>
                </div>
                <button type="button" class="btn btn-danger btn-block set" id='create_order'>创建订单</button>
                
                <input type="hidden" name="uid" value="<?php echo $_SESSION['usersid']?>"/>
                <input type="hidden" id="orderid" name="orderid" value=""/>
            </form>

        </div>
    </div>
</div>
{tpl foot}
<script type="text/javascript">
function getrecvname(){
    $.ajax({
        type: "post",
        url: "ajax.php?action=getrecvname",
        data:{},
        dataType: "json",
        cache:false,
        success: function (data) {
            if(data.cmd=="ok"){
                if(data.data.recvname != ''){
            		$("#payer").val(data.data.recvname);
                }else{
                	alert('实名未认证');
                }
            }else{
                alert(data.msg);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert("获取失败,未知错误!");
        }
    });
}

function getlastaccount(){
    $.ajax({
        type: "post",
        url: "ajax.php?action=getlastaccount",
        data:{},
        dataType: "json",
        cache:false,
        success: function (data) {
            if(data.cmd=="ok"){
                //$("#payer").val(data.data.name);
                $("#pay_account").val(data.data.account);
            }else{
                alert(data.msg);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert("获取失败,未知错误!");
        }
    });
}


    $(function () {
    	/* $("#pay_web").bind('change',function () {
        	
    	}); */
    	
        $("#pay_web span").click(function () {
            $("#pay_web span").removeClass("btn-success");
            $("#pay_web span").removeClass("active");
            $(this).addClass("btn-success")
            $(this).addClass("active")
        });
        
        
        $("#create_order").bind('click',function () {
            var payer= $('#payer').val();
            //var pay_web= $('#pay_web').val();
            var pay_web= $('#pay_web .active').attr("pf");
            var grouptype= $('#pay_web .active').attr("gp");
            var pay_account= $('#pay_account').val();
            var money = parseInt($('#money').val());
			if(money!=''){
	             if(isNaN(money ) || money <1){
		             alert('请输入正确充值金额！');
		             return;
	             }
			}

			var arrayObj = new Array();
			<?php foreach($cz_list as $cz_item){?>
			<?php if($cz_item->is_pic == 0){?>
			arrayObj.push(<?php echo $cz_item->id;?>);
			<?php }?>
			<?php }?>


            	if (pay_web == '') {
                    alert('请选择付款方式!');
                    return false;
                }
                
                if (payer == '' || pay_account == '') {
                    alert('为了方便客服对帐，付款人姓名与付款帐号必须填写项!');
                    return false;
                }

                $('#create_order').attr("disabled",true); 
                $.ajax({
                    type: 'post',
                    url: 'ajax.php?action=add_recharge_order',
                    data: {payer: payer, pay_web: pay_web, pay_account: pay_account, money: money},
                    dataType: 'json',
                    cache:false,
                    success: function (data) {
                        if (data.cmd == 'timeout' || data.cmd == 'notlogin') {
                            alert('你登录超时!');
                            window.location = '/login.php';
                            return;
                        }
                        if (data.cmd == 'ok' && data.orderid != '') {

                        	//TODO
                            /* for(i=0; i<arrayObj.length;i++){
            					if(pay_web == arrayObj[i]){
            						$('#orderid').val(data.orderid);
            						$('#gwpay').submit();
            		           		return;
            					}
            				} */
                            
                        	window.location='{:url("users/pay_order")}&orderid='+data.orderid;
                        } else {
                            alert(data.msg);
                        }

                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert('检验失败,未知错误!');
                    }
                });

                $('#create_order').attr("disabled",false); 
        });


        $('#pay_web').change(function() {
			var cz_type = $(this).val();
	        $.ajax({
	            type: 'get',
	            url: 'mobile.php?c=users&a=getLastPayAccount',
	            data: {cz_type: cz_type},
	            dataType: 'json',
	            cache:false,
	            success: function (data) {
	                $("#payer").val(data.name);
	                $("#pay_account").val(data.account);
	            },
	            error: function (XMLHttpRequest, textStatus, errorThrown) {
	                ;//alert('检验失败,未知错误!');
	            }
	        });
    	});
        
    });

    function changegrouptype(grouptype){
	    $("#pay_web option").remove();
	    $("#pay_web").append("<option value=''>请选择付款方式</option>");
	    $.ajax({
                type: 'get',
                url: 'b.php?c=user&a=getPaymentList',
                data: {grouptype: grouptype},
                dataType: 'text',
                cache:false,
                success: function (data) {
                   if(data != ""){
               	   		$("#pay_web option").remove();
                  		$("#pay_web").append(data);
                  }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    ;
                }
	     });
    }

    function add(i) {
        var money=parseInt($("#money").val());
        var money2=parseInt($("#money2").val());
        if(isNaN(money))money=0;
        if(isNaN(money2))money2=0;
        $("#money").val(money+parseInt(i));
        $("#money2").val(money2+parseInt(i)+parseInt(10*Math.random()));//+parseInt(10*Math.random())
    }
    function removes() {
        $("#money").val("");
        $("#money2").val("");
    }
</script>
</body>
</html>