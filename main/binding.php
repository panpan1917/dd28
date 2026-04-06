<?php
global $db;
$sql='select * from users where id='.$_SESSION['usersid'];
$info=$db->fetch_first($sql);

$list=get_account();
$id=intval($_GET['id'])?:0;

?>
<ul class='nav nav-tabs' style='margin:-1px 0 0 -1px;'>
<li><a href="javascript:getContent('smbinfo.php','Withdrawals')">我要提现</a></li>
<li class='active'><a href="javascript:getContent('smbinfo.php','binding')" style="color:#f00;font-weight:bold;font-size:14px;">绑定收款帐号</a></li>
</ul>

<div class='tab-content' style='margin:10px 0 0 0;'>
<div class='panel panel-default'>
<div class='panel-heading'>我的收款帐号</div>
<div class='panel-body'>
<table class='table table-hover table-bordered table-striped' style="font-size:12px;">
<tr>
<th>收款方式</th>
    <th>金额限制</th>
<th>收款人姓名</th>
<th>收款帐号</th>
<th>绑定时间</th>
<th>操作</th>
</tr>
    <?php
if (count($list)>0){
    $alipay=0;
    $bank=0;
    $array_drw=array();
    foreach ($list as $k=>$v){
        if($v['type']==1)$alipay=1;
        if($v['type']==3)$bank=1;
        if($v['type']==7)$weichat=1;
        $array_drw[$v['id']]=$v;
    ?>
    <tr>
        <td><?php echo cz_type($v['type']);?></td>
        <td><?php echo cz_type($v['type']);if($v['type']==3){echo '(推荐)';}?></td>
        <td><?php echo $info['recv_cash_name'];?></td>
        <td><?php echo $v['account'];?>(<?php echo $v['name'];?>)</td>
        <td><?php echo $v['add_time'];?></td>
        <td><?php if($v['type']!=2){?><a href="javascript:getContent('smbinfo.php?id=<?php echo $v['id'] ?>','binding')">修改</a><?php }?></td>
    </tr>
    <?php }}else{?>
<tr>
<td colspan='4'>暂无数据</td>
</tr>
    <?php }?>
</table>
</div>
</div>
    <?php if(!$bank || ($id && $array_drw[$id]['type']==3)){?>
        <?php
        if($array_drw[$id]['address']){
            $city=explode('|',$array_drw[$id]['address']);
        }
        ?>
        <div class='panel panel-default'>
            <div class='panel-heading'>绑定银行帐号</div>
            <div class='panel-body'>
                <dl class='bd_alipay'>
                    <dd>
                        <div class="input-group">
                            <span class='input-group-addon'>收款方式：</span>
                            <select class='form-control' id="type">
                                <option value="">选择收款方式</option>
                                <option value="中国工商银行" <?php if($array_drw[$id]['name']=='中国工商银行')echo 'selected';?>>中国工商银行</option>
                                <option value="中国农业银行" <?php if($array_drw[$id]['name']=='中国农业银行')echo 'selected';?>>中国农业银行</option>
                                <option value="中国建设银行" <?php if($array_drw[$id]['name']=='中国建设银行')echo 'selected';?>>中国建设银行</option>
                                <option value="中国银行" <?php if($array_drw[$id]['name']=='中国银行')echo 'selected';?>>中国银行</option>
                                <option value="中国民生银行" <?php if($array_drw[$id]['name']=='中国民生银行')echo 'selected';?>>中国民生银行</option>
                                <option value="招商银行" <?php if($array_drw[$id]['name']=='招商银行')echo 'selected';?>>招商银行</option>
                                <option value="兴业银行" <?php if($array_drw[$id]['name']=='兴业银行')echo 'selected';?>>兴业银行</option>
                                <option value="交通银行" <?php if($array_drw[$id]['name']=='交通银行')echo 'selected';?>>交通银行</option>
                                <option value="广发银行" <?php if($array_drw[$id]['name']=='广发银行')echo 'selected';?>>广发银行</option>
                                <option value="中信银行" <?php if($array_drw[$id]['name']=='中信银行')echo 'selected';?>>中信银行</option>
                                <option value="浦东发展银行" <?php if($array_drw[$id]['name']=='浦东发展银行')echo 'selected';?>>浦东发展银行</option>
                                <option value="邮政储蓄银行" <?php if($array_drw[$id]['name']=='邮政储蓄银行')echo 'selected';?>>邮政储蓄银行</option>
                                <option value="农村信用社" <?php if($array_drw[$id]['name']=='农村信用社')echo 'selected';?>>农村信用社</option>
                                <option value="平安银行" <?php if($array_drw[$id]['name']=='平安银行')echo 'selected';?>>平安银行</option>
                                <option value="光大银行" <?php if($array_drw[$id]['name']=='光大银行')echo 'selected';?>>光大银行</option>
                                <option value="华夏银行" <?php if($array_drw[$id]['name']=='华夏银行')echo 'selected';?>>华夏银行</option>
                                <option value="其他" <?php if($array_drw[$id]['name']=='其他')echo 'selected';?> style="color: #f00">其他请在卡号后加 银行名称</option>
                            </select>
                        </div></dd>
                    <dd>
                        <div class="input-group">
                            <span class='input-group-addon'>真实姓名：</span>
                            <input type='text' class='form-control' value='<?php echo $info[recv_cash_name];?>' placeholder='必须与你绑定的收款人名字一样' disabled='disabled'/>
                        </div></dd>
                    <dd>
                        <div class="input-group">
                            <span class='input-group-addon'>收款帐号：</span>
                            <input type='text' class='form-control' id="account" placeholder='请认真核对帐号' value="<?php echo $array_drw[$id]['account'];?>" />
                        </div></dd>
                    <dd>
                        <div class="input-group">
                            <span class='input-group-addon'>开户行省 ：</span>
                            <input type='text' class='form-control' id="province" placeholder='请开户所在省份' value="<?php echo $city[0];?>" />
                            <span class='input-group-addon'>-</span>
                            <input type='text' class='form-control' id="city" placeholder='请开户所在市'  value="<?php echo $city[1];?>"/>
                        </div></dd>
                    <dd>
                        <div class="input-group">
                            <span class='input-group-addon'>开户行名称：</span>
                            <input type='text' class='form-control' id="bank_name" placeholder='请认真填写开户网点' value="<?php echo $city[2];?>" />
                        </div></dd><input type="hidden" id="id" name="id" value="<?php echo $array_drw[$id]['id']?>">
                    <dd><input type='button' class='btn btn-danger' value='确认添加' onclick="add_bank()"/></dd>
                </dl>
            </div>
        </div>
    <?php }?>
    
    
<!--    
<?php if(!$alipay || ($id && $array_drw[$id]['type']==1)){?>
<div class='panel panel-default'>
<div class='panel-heading'>绑定支付宝帐号</div>
<div class='panel-body'>
<dl class='bd_alipay'>
<dd>
        <div class="input-group">
            <span class='input-group-addon'>真实姓名：</span>
            <input type='text' class='form-control' value='<?php echo $info[recv_cash_name];?>' disabled='disabled' />
            <span class='input-group-addon'>必须与你绑定的收款人名字一样</span>
        </div></dd>
<dd>
        <div class="input-group">
            <span class='input-group-addon'>收款帐号：</span>
            <input type='text' class='form-control' value="<?php echo $array_drw[$id]['account'];?>" placeholder='请认真核对帐号' id="alipay" />
        </div></dd>
    <input type="hidden" id="id" name="id" value="<?php echo $array_drw[$id]['id']?>">
<dd><input type='button' class='btn btn-danger' value='确认添加' onclick="add('alipay');" /></dd>
</dl>
</div>
</div>
<?php }?>


    <?php if(!$weichat || ($id && $array_drw[$id]['type']==7) ){?>
        <div class='panel panel-default'>
            <div class='panel-heading'>绑定借贷宝帐号</div>
            <div class='panel-body'>
                <dl class='bd_alipay'>
                    <dd>
                        <div class="input-group">
                            <span class='input-group-addon'>真实姓名：</span>
                            <input type='text' class='form-control' value='<?php echo $info[recv_cash_name];?>' disabled='disabled' />
                            <span class='input-group-addon'>必须与你绑定的收款人名字一样</span>
                        </div></dd>
                    <dd>
                        <div class="input-group">
                            <span class='input-group-addon'>收款帐号：</span>
                            <input type='text' class='form-control' value="<?php echo $array_drw[$id]['account'];?>" placeholder='请认真核对帐号' id="weichat" />
                        </div></dd>
                    <input type="hidden" id="id" name="id" value="<?php echo $array_drw[$id]['id']?>">
                    <dd><input type='button' class='btn btn-danger' value='确认添加' onclick="add('weichat');" /></dd>
                </dl>
            </div>
        </div>
    <?php }?>
-->  

</div>
<script type="text/javascript">
    function add(idx) {
        var acc=$("#"+idx).val();
        if(acc==''){
            alert("账户不能为空");
            return false;
        }
        var id=$("#id").val();
        $.ajax({
            type: 'post',
            url: 'ajax.php?action=add_account',
            data: {type: idx, acc: acc,id:id},
            cache:false,
            dataType: 'json',
            success: function (data) {
                if (data.status == 2 || data.status == 2) {
                    alert('你登录超时!');
                    window.location = '/login.php';
                    return;
                }
                if (data.status == 0) {
                    getContent('smbinfo.php?','Withdrawals');
                } else {
                    alert(data.message);
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('检验失败,未知错误!');
            }
        });

    }
    function add_bank() {
        var type=$("#type").val();
        if(type==0){
            alert("请选择银行");
            return ;
        }
        var id=$("#id").val();
        var acc=$("#account").val();
        if(acc==''){
            alert("账户不能为空");
            return false;
        }
        var province=$("#province").val();
        if(province==''){
            alert("开户行所在省份不能为空");
            return false;
        }
        var city=$("#city").val();
        if(city==''){
            alert("开户行所在市不能为空");
            return false;
        }
        var bank_name=$("#bank_name").val();
        if(bank_name==''){
            alert("开户行不能为空");
            return false;
        }
        $.ajax({
            type: 'post',
            url: 'ajax.php?action=add_account',
            data: {type: 'bank', acc: acc,province:province,bank_type:type,city:city,bank_name:bank_name,id:id},
            dataType: 'json',
            cache:false,
            success: function (data) {
                if (data.status == 2 || data.status == 2) {
                    alert('你登录超时!');
                    window.location = '/login.php';
                    return;
                }
                if (data.status == 0) {
                    alert(data.message);
                    getContent('smbinfo.php?','Withdrawals');
                } else {
                    alert(data.message);
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('检验失败,未知错误!');
            }
        });
    }
</script> 