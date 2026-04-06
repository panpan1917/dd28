
<div class='panel panel-default'>
    <div class='panel-heading'>领取推荐奖励</div>
    <div class='panel-body'>
        <table class='table table-striped table-hover table-bordered'>
            <tr>
                <td>可领取乐豆</td>
                <td>领取状态</td>
            </tr>
            <tr>
                <td>{$point}</td>
                <td><?php if($point>0){?>
                        <?php if($id){ echo '已经领取过了';}else{?>
                            <input type='button' id='btnGet' class='btn btn-danger' value='领取'/>
                            <?php }}?></td>
            </tr>

        </table>
    </div>
</div>

<div class='panel panel-default'>
    <div class='panel-heading'>领取记录</div>
    <div class='panel-body'>
        <table class='table table-striped table-hover table-bordered'>

            <tr>
                <td>领取时间</td>
                <td>领取乐豆</td>
                <td>领取状态</td>
            </tr>
				{loop $list $k $v}
                <tr>
                    <td>{$v.log_time}</td>
                    <td>{$v.amount}</td>
                    <td>已领取</td>
                </tr>
            {/loop}
        </table>
    </div>
</div>
<div class='panel panel-default'>
    <div class='panel-heading'>下线列表</div>
    <div class='panel-body'>
        <table class='table table-striped table-hover table-bordered'>

            <tr>
                <td>用户昵称</td>
                <td>登录时间</td>
                <td>昨日投注</td>
            </tr>
            <tbody id="bod">
{loop $user_list $k $v}
                <tr>
                    <td>{$v.nickname}</td>
                    <td>{$v.logintime}</td>
                    <td>{if $v.points}{$v.points}{else}0{/if}</td>
                </tr>
            {/loop}
            </tbody>
        </table>
    </div>
    <div class="page">{$page}</div>
</div>
<script type="text/javascript">
$(function () {
    $("#btnGet").bind('click',function () {
    	$("#btnGet").attr("disabled",true);
        $.getJSON('b.php?c=user&a=extension&g=1',function (data) {
            if(data.status==0){
                alert(data.message);
                $("#btnGet").attr("disabled",false);
                getContents('user','extension');
            }else{
                alert(data.message);
                $("#btnGet").attr("disabled",false);
            }
        });
    })
})

    
    function page_list(p) {
        $.getJSON("b.php?c=user&a=extension&ajax=1&page="+p,function (data) {
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