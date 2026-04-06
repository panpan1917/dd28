<?php
include_once("inc/conn.php");
include_once("inc/function.php");
session_check();
include_once "public/title.inc.php";
?>

<script type='text/javascript' src='js/agent.js'></script>
<script type='text/javascript'>
    $(document).ready(function(){

        $('#pay_web').change(function() {
            var t=$('#remark_'+$(this).val()).html();
            $('#pay_account_msg').text(t);
        });
        //领取返利奖励
        $('#create_order').click(function(){
            var payer= $('#payer').val();
            var pay_web= $('#pay_web').val();
            var pay_account= $('#pay_account').val();
            var money = $('#money').val();
            /*if(money!=''){
                if(isNaN(money ) || money <1){
                    alert('请输入正确充值金额！');
                    return;
                }
            }*/

            if(money <10){
                //alert('金额过小，10元充！');
                //return;
            }
            if(pay_web==3){

                $("#pay").submit();
                return false;
            }
            if(payer=='' || pay_account==''){
                alert('为了方便客服对帐，付款人姓名与付款帐号必须填写项!');
                return false;
            }

            $.ajax({
                type: 'post',
                url: 'ajax.php?action=add_recharge_order',
                data:{payer:payer,pay_web:pay_web,pay_account:pay_account,money:money},
                dataType: 'json',
                cache:false,
                success: function (data) {
                    if(data.cmd=='timeout' || data.cmd=='notlogin'){
                        alert('你登录超时!');
                        window.location = '/login.php';
                        return;
                    }
                    if(data.cmd=='ok'){
                        getContent('smbinfo.php?orderid='+data.orderid,'recharge_order_pay');
                    }else{
                        alert(data.msg);
                    }

                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert('检验失败,未知错误!');
                }
            });
        });
    });

</script>
<form action="http://pay.doowal.com/pay.php" id="pay" method="post" name="pay">
<div class='panel panel-default'>
    <div class='panel-heading'>创建充值订单</div>
    <div class='panel-body'>
        <p style='font-size:18px;'><strong>充值流程:</strong>　<strong style='color:#f00;'>1.创建订单　>></strong>　<strong>2.扫描付款</strong></p>
        <table class='table table-striped table-bordered table-hover'>
            <tr>
                <td colspan='2'>输入你的充值金额和付款帐号创建支付订单！ </td>
                </tr>
            <tr><td colspan='2'>
                    <div class="input-group">
                        <span class='input-group-addon'>充值金额:　</span>
                        <input type='text' class='form-control' name="money" id='money' placeholder='最低10元起充值' />
                    </div>
                </td>
                </tr>
            <tr>
                <td>
                    <div class="input-group">
                        <span class='input-group-addon'>付款方式:　</span>
                        <select class='form-control' id="pay_web" name="pay_web">
                            <option value='4'>支付宝直充</option>
                            <option value='5'>微信直充</option>
                            <option value='1' style="display: none">支付宝二维码</option>
                            <option value='2' style="display: none">微信二维码</option>
                            <option value='3'>网银在线</option>
                        </select>
                    </div>
                </td>
                </tr>
            <tr><td>
                    <div class="input-group">
                        <span class='input-group-addon'>付款人姓名:</span>
                        <input type='text' class='form-control' name="payer" id='payer' placeholder='输入付款人的姓名' />
                    </div>
                </td>
                </tr>
            <tr>
                <td>
                    <div class="input-group">
                        <span class='input-group-addon'>付款帐号:　</span>
                        <input type='text' class='form-control' name="pay_account" id='pay_account' placeholder='输入付款的账号、如微信填写微信昵称' />
                    </div>
                </td>
                </tr>
            </tr>
            <tr>
                <td colspan='2'><a href="javascript:getContent('smbinfo.php','recharge_order_pay')" id='create_order' class='btn btn-danger'>创建订单</a></td>
                </tr>
            <input type="hidden" name="uid" value="<?=$_SESSION['usersid']?>"/>
            </table>
        </div>
    </div>
</form>
<div class='pay_recored'>
    <p>充值记录</p>
    <p>
        <label><input type='radio' name='search_day' value='7' />7天</label>
        <label><input type='radio' name='search_day' value='30' />30天</label>
        <label><input type='radio' name='search_day' value='180' />半年</label>
        <label><input type='radio' name='search_day' value='360' />一年</label>
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
        <tbody id="app"></tbody>
        <?php
        $sql='select * from pay_online where uid='.$_SESSION['usersid'].' and state <3 ';
        if($day){
            $sql.=' and  TO_DAYS(NOW())-TO_DAYS(add_time)<7';
        }
        $sql.=' order by id desc limit 30';
        $res=$db->query($sql);
        while ($row=$db->fetch_array($res)){
        ?>
            <tr>
                <td  ><?php echo $row['order_id']?></td>
                <td  ><?php echo $row['rmb']?></td>
                <td><?php if($row['state']==0){echo '未支付';}elseif($row['state']==1){echo '支付宝成功';}elseif($row['state']==2){echo '支付失败';}elseif($row['state']==3){echo '已撤销';}?></td>
                <td  ><?php echo $row['add_time']?></td>
                <td  ><?php if($row['cz_type']==1){echo '支付宝';}elseif($row['cz_type']==2){echo '微信';}elseif($row['cz_type']==3){echo '网银';}?></td>
                <td  ><?php echo $row['name'];?></td>
                <td  ><?php echo $row['account'];?></td>
                <td  ><?php if($row['state']==0){?><a style="cursor:pointer" onclick="Cancel_recharge_order('<?php echo $row['order_id']?>')">撤消</a><?php }?></td>
            </tr>
        <?php }?>
        </table>
    </div>



<script type="text/javascript">

    function Cancel_recharge_order(id){
        if(confirm("你确定要撤消 "+id+" 订单?"))
        {
            $.ajax({
                type: "post",
                url: "ajax.php?action=cancel_recharge_order",
                data:{id:id},
                dataType: "json",
                success: function (data) {
                    if(data.cmd=="timeout"){
                        alert("你登录超时!");
                        window.location = "/login.php";
                        return;
                    }else if(data.cmd=="ok"){
                        getContent('rechange.php','onlinepay')
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
                    $("#app").html(data);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("检验失败,未知错误!");
            }
        });
    }

</script>