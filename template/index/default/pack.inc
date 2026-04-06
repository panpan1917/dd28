<div id='bank'>
    <div class='panel panel-default'>
        <div class='panel-heading'>红包</div>
        <!-- <div class='panel-body'></div> -->
    </div>
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
<div class='panel panel-default'>
    <div class='panel-heading'>领取记录</div>
    <div class='panel-body'>
        <table class='table table-striped table-hover table-bordered'>

            <tr>
                <td>领取时间</td>
                <td>领取乐豆</td>
                <td>领取状态</td>
            </tr>

            {loop $list $v}
                <tr>

                    <td>{function=date('Y-m-d H:i:s',$v.time)}</td>
                    <td>{$v.ledou}</td>
                    <td>已领取</td>
                </tr>
            {/loop}
        </table>
    </div>
</div>
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
            $.getJSON('b.php?c=user&a=get_pack&num='+l,function (data) {
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