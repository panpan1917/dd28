
<div class='panel panel-default'>
    <div class='panel-heading'>领取记录</div>
    <div class='panel-body'>
        <table class='table table-striped table-hover table-bordered'>

            <tr>
                <td>领取时间</td>
                <td>领取乐豆</td>
                <td>领取后银行分</td>
            </tr>
				{loop $list $k $v}
                <tr>
                    <td>{$v.log_time}</td>
                    <td>{$v.amount}</td>
                    <td>{$v.bankpoints}</td>
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
        <div class="page">{$page}</div>
    </div>
    
</div>
<script type="text/javascript">

    function page_list(p) {
        $.getJSON("b.php?c=activity&a=recomRebate&ajax=1&page="+p,function (data) {
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