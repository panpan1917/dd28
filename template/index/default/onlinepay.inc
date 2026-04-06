<script type='text/javascript' src='js/agent.js'></script>
<script type='text/javascript'>
    $(document).ready(function(){
        $(".ff").click(function () {
            var money=parseFloat($("#money").val());
            if(isNaN(money))money=0;

            var money2=parseFloat($("#money2").val());
            if(isNaN(money2))money2=0;
            
            var val=$(this).val();
            if(val=="清除"){
                $("#money").val('');
                
                $("#money2").val('');

                return false;
            }
            
            $("#money").val(parseFloat(val.replace('元',''))+money);

            $("#money2").val(parseFloat(val.replace('元',''))+money2+parseInt(10*Math.random()));//+parseInt(10*Math.random())
        });
        
        $("#pay_web span").click(function () {
            $("#pay_web span").removeClass("active");
            $("#pay_web span").removeClass("btn-success");
            $(this).addClass("btn-success")
            $(this).addClass("active")

        });
        
        $('#pay_web').change(function() {
            //var t=$('#remark_'+$(this).val()).html();
            //$('#pay_account_msg').text(t);
			var cz_type = $(this).val();
            $.ajax({
                type: 'get',
                url: 'b.php?c=user&a=getLastPayAccount',
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


    	/* $("#pay_web").bind('change',function () {
        	
    	}); */
        
        //充值订单
        $('#create_order').click(function(){
            var payer= $('#payer').val();
            //var pay_web= $('#pay_web').val();
            var pay_web= $('#pay_web .active').attr("pf");
            var grouptype= $('#pay_web .active').attr("gp");
            var pay_account= $('#pay_account').val();
            var money = parseInt($('#money').val());
            if(money!=''){
	             if(isNaN(money ) || money <1){
		             alert('请输入正确充值金额！');
		             return false;
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

                            
                            getContent('smbinfo.php?orderid=' + data.orderid, 'recharge_order_pay');
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
    
</script>

<form action="gwpay.php" id="gwpay" method="post" name="gwpay" target="_blank">
    <div class='panel panel-default'>
        <div class='panel-heading'>创建充值订单</div>
        <div class='panel-body'>
            <p style='font-size:18px;'><strong>充值流程:</strong>　<strong style='color:#f00;'>1.创建订单　>></strong>　<strong>2.扫描付款</strong></p>
            <table class='table table-striped table-bordered table-hover'>
                <tr>
                    <td colspan='2' style="font-size:16px;color:red;">充值金额和订单金额不一致者不予上分! </td>
                </tr>
                <tr>
                	<td colspan='2'>
                        <div class="input-group">
                            <span class='input-group-addon'>充值金额:　</span>
                            <input type='text' name="money" id="money" class='form-control' value='' style='margin:0 0 5px 0;' placeholder='银行卡最低{$min_recharge}元起,二维码50~200元' />
                            <input type='hidden' name="money2" id="money2" value='' />
                            <input type="button" class='btn btn-danger ff'  value='50元' />
                            <input type="button" class='btn btn-danger ff'  value='100元' /> 
                            <input type="button" class='btn btn-danger ff'  value='500元' /> 
                            <input type="button" class='btn btn-danger ff'  value='1000元' /> 
                            <input type="button"  value='5000元' class='btn btn-danger ff' /> 
                            <input type="button"  value='10000元' class='btn btn-danger ff' /> 
                            <input type="button"  value='30000元' class='btn btn-danger ff' /> 
                            <input type="button"  value='50000元' class='btn btn-danger ff' /> 
                            <input type="button"  value='清除' class='btn btn-danger ff' />
                            
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                            <div class='' id="pay_web" name="pay_web">
                                {loop $cz_list $k $v}
                                <span class="btn btn-default {if $k==0}btn-success active{/if}" pf='{$v.id}' gp='{$v.grouptype}' style="margin-bottom: 5px;">{$v.name}{$v.marks}</span>
                                {/loop}
                            </div>
                    </td>
                </tr>
                <tr>
                	<td>
                        <div class="input-group">
                            <span class='input-group-addon'>付款人姓名:</span>
                            <input type='text' class='form-control' name="payer" id='payer' value='{$recv_cash_name}' style='width:85%' placeholder='输入付款人的姓名' />
                            <button type="button" onclick="getrecvname();" class='btn btn-danger' style='width:15%'>获取姓名</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="input-group">
                            <span class='input-group-addon'>付款帐号:　</span>
                            <input type='text' class='form-control' name="pay_account" id='pay_account' style='width:85%' placeholder='输入付款的账号' />
                            <button type="button" onclick="getlastaccount();" class='btn btn-danger' style='width:15%'>获取账号</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'><a id='create_order' class='btn btn-danger'>创建订单</a> <a class='btn btn-danger' href="javascript:getContent('smbinfo.php','mydetail')">取消</a> </td>
                </tr>
                <input type="hidden" name="uid" value="<?=$_SESSION['usersid']?>"/>
                <input type="hidden" id="orderid" name="orderid" value=""/>
                
            </table>
        </div>
    </div>
</form>

<div class='pay_recored'>
    <p>充值记录</p>
    <p>
        <label><input type='radio' name='search_day' value='7' {if $day == 7}checked{/if} />7天</label>
        <label><input type='radio' name='search_day' value='30' {if $day == 30}checked{/if} />30天</label>
        <label><input type='radio' name='search_day' value='180' {if $day == 180}checked{/if} />半年</label>
        <label><input type='radio' name='search_day' value='365' {if $day == 365}checked{/if} />一年</label>
        <input type='button' value='查询' class='btn btn-danger' onClick="ajax_page_recharge_order_log(1);"/>
    </p>
    <table class='table table-striped table-hover table-bordered user_recharge_log_list' style="font-size:12px;">
        <tr>
            <th>订单号</th>
            <th>金额度</th>
            <th>状态</th>
            <th>时间</th>
            <th>支付方式</th>
            <th>支付帐号</th>
            <th>付款人</th>
            <th>操作</th>
        </tr>
       {loop $list $row}
            <tr>
                <td>{$row.order_id}</td>
                <td>{$row.rmb}</td>
                <td>{if $row.state==0}未支付{elseif $row.state==1}支付成功{elseif $row.state==2}支付失败{elseif $row.state==3}已撤销{/if}</td>
                <td>{$row.add_time}</td>
                <td>{$row.pay_type}</td>
                <td>{$row.account}</td>
                <td>{$row.name}</td>
                <td>{if $row.state==0}<a style="cursor:pointer" onclick="Cancel_recharge_order('{$row.order_id}')">撤消</a>{/if}</td>
            </tr>
        {/loop}
    </table>
</div>



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

    function Cancel_recharge_order(id){
        if(confirm("你确定要撤消 "+id+" 订单?"))
        {
            $.ajax({
                type: "post",
                url: "ajax.php?action=cancel_recharge_order",
                data:{id:id},
                dataType: "json",
                cache:false,
                success: function (data) {
                    if(data.cmd=="timeout"){
                        alert("你登录超时!");
                        window.location = "/login.php";
                        return;
                    }else if(data.cmd=="ok"){
                        getContents('user','onlinepay')
                    }else{
                        alert(data.msg);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert("检验失败,未知错误!");
                }
            });
        }
    }

    function ajax_page_recharge_order_log(page){
        var day= $('input[name="search_day"]:checked').val();
        $.ajax({
            type: "post",
            url: "ajax.php?action=get_recharge_order_log",
            data:{day:day,page:page},
            cache:false,
            success: function (data) {
                if(data=="timeout"){
                    alert("你登录超时!");
                    window.location = "/login.php";
                    return;
                }else{
                    $(".user_recharge_log_list tr:gt(1)").remove();
                    $(".user_recharge_log_list").append(data);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("检验失败,未知错误!");
            }
        });
    }

</script>