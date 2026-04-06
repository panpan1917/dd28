<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    {tpl style}
    <link rel="stylesheet" type="text/css" href="{$skin}css/js.css"/>
    <script type="text/javascript" src="{$skin}js/vue.min.js"></script>
    <title>{$webname}</title>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span
                    class="glyphicon glyphicon-chevron-left icon"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-20px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">            自动投注</p>
    </div>
</nav>


<div id="main">
    <div class="container-fluid">
        <div class="row white_bg detailed_coin">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0">
                <p>¥: <span class="red" id="money">{$info.points}</span></p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0">
                <p>银行: ¥<span class="red" id="bank">{$info.bankpoints}</span></p>
            </div>
        </div>
    </div>
    <div class='Edit'>

        <ul class='new'>
            <li>开始模式:
                <select id='sltcurModel' name='select'>
                    {loop $list $k $v}
                    <option value='{$v.id}' {if $curr.autoid==$v.id}selected='selected'{/if}>{$v.tzname}</option>
                    {/loop}
                </select>
            </li>
            <li>开始期号: <input id='txtbeginno' name='txt' maxlength='12' type='text' value='{$curr.startNO}' onafterpaste="this.value=this.value.replace(/\D/g,'')" onkeyup="this.value=this.value.replace(/\D/g,'')" /></li>
            <li>期数: <input id='txttzcount' name='txt' maxlength='8' type='text' value='{$curr.num}' onafterpaste="this.value=this.value.replace(/\D/g,'')" onkeyup="this.value=this.value.replace(/\D/g,'')" /></li>
            <li>乐豆上限: <input id='txtmaxG' name='txt' maxlength='9' type='text' value='{$curr.maxG}' onafterpaste="this.value=this.value.replace(/\D/g,'')" onkeyup="this.value=this.value.replace(/\D/g,'')" /></li>
            <li>下限: <input id='txtminG' type='text' maxlength='9' name='txt' value='{$curr.minG}' onafterpaste="this.value=this.value.replace(/\D/g,'')" onkeyup="this.value=this.value.replace(/\D/g,'')" /></li>
        </ul>
        <div class='table'>
            <table class='table_list table table-striped table-bordered table-hover' cellspacing='0px' style='border-collapse:collapse;'>
                <tbody>
                <tr>
                    <th width='150'>模式</th>
                    <th width='150'>乐豆</th>
                    <th width='350'>赢用模式</th>
                    <th width='350'>输用模式</th>
                </tr>
                {loop $list $k $v}
                <tr>
                    <td>{$v.tzname}</td>
                    <td>{$v.tzpoints}</td>
                    <td>

                        <select id='slt_{$v.id}' onchange='changemodel(this)' name='select' ct='win' cid='{$v.id}'>
                            {loop $list $sk $sv}
                            <option value='{$sv.id}' {if $v.winid==$sv.id}selected='selected'{/if}>{$sv.tzname}</option>
                            {/loop}
                        </select>
                    </td>
                    <td>
                        <select id='slt_{$v.id}' onchange='changemodel(this)' name='select' ct='loss' cid='{$v.id}'>
                            {loop $list $sk $sv}
                            <option value='{$sv.id}' {if $v.lossid==$sv.id}selected='selected'{/if}>{$sv.tzname}</option>
                            {/loop}
                    </td>
                </tr>
                {/loop}

                </tbody>
            </table>
            <p class='tial'>
                <span class='btn btn-danger' id='sSubmit' ca='{if $curr.start_auto_id}cancel{else}submit{/if}'>{if $curr.start_auto_id}取消自动投注{else}确定提交{/if}</span>
		<span class='ifo'>
			<a class="btn btn-warning" href="{:url("game/game/id/$act")}">返回游戏首页</a>
		</span>
            </p></div>
        <div class='tims'>
            <p>设置方法：</p>
            <p>1．“开始模式”选择第一次投注的模式.</p>
            <p>2．设置“开始期号”与“期数”.</p>
            <p>3．设置“乐豆上限”与“下限”限制,帐号内乐豆数达到限制数量后,自助投注自动停止.</p>
            <p>4．确认并开始自动投注后，系统将会在您指定的期数内帮您自动投注，不论你离线或在线都会持续运行,直到期数终止或您关闭为止.</p>
            <p>5．银行内的乐豆不能用于投注.</p>
        </div></div>
    <script type="text/javascript">
        $(document).ready(function(){
            if($('#sSubmit').attr('ca') == 'cancel')changestat(0);
            $('#sSubmit').click(function(){
                var toaction = $('#sSubmit').attr('ca');
                if(toaction == 'submit') {
                    var bno = $('#txtbeginno').val();
                    var cnt = $('#txttzcount').val();
                    var maxg = $('#txtmaxG').val();
                    var ming = $('#txtminG').val();
                    var cid = $('#sltcurModel').val();
                    if(cid != '')
                    {
                        $.post('{:url('game/auto')}',{act:'saveautomodel',gtype:{if $act}{$act}{else}0{/if},curno:bno,bno:bno,cnt:cnt,maxg:maxg,ming:ming,cid:cid},function(ret){
                            if(ret.status == 'ok')changestat(0);
                        alert(ret.message);
                        },'json');
                    }
                    else
                    {
                        alert('您还没有设置投注模式，请先设置!');
                    }
                }
                else
                {
                    $.post('{:url('game/auto')}',{act:'removeautomodel',gtype:{if $act}{$act}{else}0{/if}},function(ret){
                        if(ret.status ==0)changestat(1);
                    },'json');
                }
            });
        });
        function changemodel(o)
        {
            var v = $(o).val();
            var cid = $(o).attr('cid');
            var ct = $(o).attr('ct');
            if(v > 0)
            {
                $.post("{:url('game/auto')}",{act:'changautomodel',gtype:{$act},cid:cid,ct:ct,v:v},function(ret){
                    alert(ret.message);
                },'json');
            }
        }
        function changestat(t)
        {
            if(t == 0)
            {
                $("select[name='select']").each(function(){
                    $(this).attr('disabled','disabled');
                });
                $("input[name='txt']").each(function(){
                    $(this).attr('disabled','disabled');
                });
                $('#sSubmit').attr('ca','cancel');
                $('#sSubmit').html('取消自动投注');
            }
            else if(t == 1)
            {
                $("select[name='select']").each(function(){
                    $(this).removeAttr('disabled');
                });
                $("input[name='txt']").each(function(){
                    $(this).removeAttr('disabled');
                });
                $('#sSubmit').attr('ca','submit');
                $('#sSubmit').html('确定提交');
            }
        }
    </script>


</div>

</body>
</html>