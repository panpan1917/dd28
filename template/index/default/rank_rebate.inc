<div class='panel panel-default'>
    <div class='panel-heading'>领取昨日排行榜奖励</div>
    <div class='panel-body'>
        <table class='table table-striped table-hover table-bordered'>
            <tr>
                <td>可领取乐豆</td>
                <td>领取状态</td>
            </tr>
            <tr>
                <td>{$point.point}</td>
                <td>
                <!-- {if $point.status}{$point.message}{else}<a href="javascript:get();" class='btn btn-danger'>领取</a>{/if}-->
                <?php if($point->point>0){?>
                            <?php if($id > 0){?>已经领取过了<?php }else{?>
                                <input type='button' id='btnGet' class='btn btn-danger' value='领取'/>
                                <?php }}?>
                </td>
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
<script type="text/javascript">
$(function () {
    $("#btnGet").bind('click',function () {
    	$("#btnGet").attr("disabled",true);
        $.getJSON('b.php?c=activity&a=rankrebate&g=1',function (data) {
            if(data.status==0){
                alert(data.message);
                $("#btnGet").attr("disabled",false);
                getContents('activity','rankrebate');
            }else{
                alert(data.message);
                $("#btnGet").attr("disabled",false);
            }
        });
    })
})
        

    
    function page_list(p) {
        $.getJSON("b.php?c=activity&a=rankrebate&ajax=1&page="+p,function (data) {
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