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
            <button href="javascript:void(0);" class="btn btn-danger btn-md btn-tz" onclick="tz()">投注</button>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-20px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">            投注</p>
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
    <div class="container-fluid white_bg">
        <div class="row detailed_coin" style="display: none">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0">
                <p>上期开奖: <span>{$last_lotto.kgjg[0]}</span> + <span>{$last_lotto.kgjg[1]}</span> +<span>{$last_lotto.kgjg[2]}</span>
                    = <span class="red">{$last_lotto.kgjg[3]}</span></p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0">
                <p>上期总金蛋: <span class="red">{$last_lotto.tzpoints}</span></p>
            </div>
        </div>
        <div class="row detailed_coin betting_border" style="display: none">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0">
                <p>本期注数: <span class="red">57</span></p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0">
                <p>本期总金蛋: <span class="red">{$list.currPoint}</span></p>
            </div>
        </div>
        <div class="row detailed_coin">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pad0">
                <p class="jl"><span id="liTimer">当前:{$no}(距离截止还有<span class="red">768秒</span>)</span> <br/>我已投注金蛋:<span
                        class="red">{$tzpoints}</span></p>
            </div>
        </div>
    </div>

    <div class="container-fluid white_bg betting_tz">
        <div class="row betting_pad">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="row betting_top">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a id="b01" href="javascript:press(0.1);" class="btn btn-default btn-block">0.1倍</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:press(0.5);" class="btn btn-default btn-block">0.5倍</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:press(0.8);" class="btn btn-default btn-block">0.8倍</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:press(1.2);" class="btn btn-default btn-block">1.2倍</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:press(1.5);" class="btn btn-default btn-block">1.5倍</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:press(2);" class="btn btn-default btn-block">2.0倍</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="row betting_top">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:press(10);" class="btn btn-default btn-block">10倍</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:press(50);" class="btn btn-default btn-block">50倍</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:all_selection();" class="btn btn-default btn-block">全包</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:Reverse_selection();" class="btn btn-default btn-block">反选</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:clear();" class="btn btn-default btn-block red">清除</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:void(0);" class="btn btn-default btn-block"
                           onclick="window.location.reload();">刷新</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="row betting_top">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:press(50);" class="btn btn-default btn-block red">1</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:suoha(10);" class="btn btn-default btn-block red">10</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:suoha(50);" class="btn btn-default btn-block red">50</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:suoha(100);" class="btn btn-default btn-block red">100</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:suoha(500);" class="btn btn-default btn-block red">500</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a href="javascript:suoha(1000);" class="btn btn-default btn-block red">1K</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row white_bg betting_table">
            <form action="">
                <table id="tz" class="table table-hover table-striped text-center">
                    <tbody>
                    {loop $list[currOdds] $k $v}
                    <tr>
                        <td><span><img src="{$skin}/img/jg/number_{$list[in]}<?php echo $k+$list[step];?>.gif" alt=""></span></td>
                        <td><input type="text" name="tbNum[]" id="tbNum{$k}" value="" class="form-control form_table"></td>
                        <td><span>{$v}</span></td>
                        <td><a href="javascript:void(0);" class="btn btn-default btn-block btn-be c1">0.5倍</a></td>
                        <td><a href="javascript:void(0);" class="btn btn-default btn-block btn-be c2">2.0倍</a></td>
                        <td><a href="javascript:void(0);" class="btn btn-default btn-block btn-be c3">10倍</a></td>
                    </tr>
                    {/loop}

                    </tbody>
                </table>
            </form>
        </div>
    </div>

</div>



<nav class="navbar-default navbar-fixed-bottom">
    <div class="container-fluid betting_bottom">
        <div class="row">
            <ul class="nav navbar-nav">
                <li style="width:66%; padding:2%;" class="pull-left" ><span style="width:50%; float: left;">余额￥<span class="red">{$info.points}</span></span>  <span style="width:50%; float: left;">本期投注￥<span class="red" id="total">0</span></span> <span style="width:50%; float: left;">银行￥<span class="red">{$info.bankpoints}</span> </span> <span style="width:50%; float: left;">用人民币投注</span></li>

                <li style="width: 30%; line-height: 1.5;" class="pull-right">
                    <button href="javascript:void(0);" class="btn btn-danger btn-md btn-tz"  data-toggle="modal" data-target="#myModal1">投注模式</button>
<!--                    <a href="javascript:void(0);" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal1">11投注模式</a></li>-->
                <!--
                <li style="display: none"><a href="javascript:void(0);" data-toggle="modal" data-target="#myModal2">自定义模式</a></li>
                <li style="display: none"><a href="save.shtml">另存为</a></li>
                -->
            </ul>
        </div>

    </div>
</nav>

<div class="modal fade" id="myModal1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4>标准模式</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(2)'>单</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(1)'>双</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(4)'>大</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(3)'>小</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(5)'>中</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(6)'>边</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(7)'>大单</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(8)'>小单</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(9)'>大双</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(10)'>小双</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(11)'>大边</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(12)'>小边</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(13)'>0尾</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(14)'>1尾</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(15)'>2尾</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(16)'>3尾</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(17)'>4尾</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(18)'>小尾</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(19)'>5尾</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(20)'>6尾</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(21)'>7尾</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(22)'>8尾</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(23)'>9尾</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(24)'>大尾</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(25)'>3余0</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(26)'>3余1</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(27)'>3余2</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(28)'>4余0</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(29)'>4余1</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(30)'>4余2</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(31)'>4余3</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(32)'>5余0</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(33)'>5余1</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(34)'>5余2</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(35)'>5余3</a></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a class="btn btn-default btn-block" href='javascript:useModel(36)'>5余4</a></div>

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0"><a href="" class="btn btn-default btn-block" data-dismiss="modal">取消</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal2">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4>自定义模式</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0"><a href="" class="btn btn-default btn-block">全包</a></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0"><a href="" class="btn btn-default btn-block">单</a></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0"><a href="" class="btn btn-default btn-block">双</a></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0"><a href="" class="btn btn-default btn-block">大</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script type="text/javascript">
    var MAX_SCORE = {$pl.press_max};
    var MIN_SCORE = {$pl.press_min};
    var MY_SCORE = {};
    var GTYPE = {$pl.gameType};
    var PRESSNUM = '{$pl.pressNum}';

    var arr_press = PRESSNUM.split(",");
    function press(val) {
        $("input[name='tbNum[]']").each(function (i) {
            var ipt=$("input[name='tbNum[]']").eq(i);
            var money=parseFloat(val)*parseFloat(ipt.val());
            if(isNaN(money))money=0;
            if(money>0 && money<1)money=1;
            ipt.val(money);
        });
        total();
    }
    function suoha(val) {
        var count=0;
        $("input[name='tbNum[]']").each(function (i) {
            var ipt=$("input[name='tbNum[]']").eq(i);
            if(!isNaN(parseFloat(ipt.val())))count++;
        });
        console.info(count);
        var f=parseInt(val/count);
        if(f<1){
            alert("分配分不够!");
        }

        console.info(f);
        $("input[name='tbNum[]']").each(function (i) {
            var ipt=$("input[name='tbNum[]']").eq(i);
            if(!isNaN(parseFloat(ipt.val()))){
                ipt.val(parseFloat(ipt.val())+f);
            }

        });
        total();
    }
    function clear() {
        $("input[name='tbNum[]']").val('');
    }
    function Reverse_selection() {
        $("input[name='tbNum[]']").each(function (i) {
            var ipt=$("input[name='tbNum[]']").eq(i);
            console.info(parseFloat(ipt.val()));
            if(parseFloat(ipt.val())>0){
                ipt.val(0);
            }else{
                ipt.val(arr_press[i]);
            }
        });
        total();
    }
    function all_selection() {
        $("input[name='tbNum[]']").each(function (i) {
            var ipt=$("input[name='tbNum[]']").eq(i);
                ipt.val(arr_press[i]);
        });
        total();
    }
    function total() {
        var totalScore=0;
        $("input[name='tbNum[]']").each(function (i) {
            var money=$("input[name='tbNum[]']").eq(i).val();
            if(isNaN(parseInt(money)))money=0;
            totalScore+=parseInt(money);

        });
        console.info(totalScore);
        $("#total").text(totalScore);
    }
    function tz() {
        var totalScore=0;
        var press="";
        $("input[name='tbNum[]']").each(function (i) {
            var money=$("input[name='tbNum[]']").eq(i).val();
            if(isNaN(money))money=0;
            totalScore+=parseInt(money);
            press+=money+",";
        });
        console.info(press);
        console.info(totalScore);
        if(totalScore > MAX_SCORE)
        {
            alert("您的投注额已大于最大限制" + MAX_SCORE);
            return;
        }
        if(totalScore < MIN_SCORE)
        {
            //alert("您的投注额小于最小限制" + MIN_SCORE);
            //return;
        }
        if(!confirm("您确定投注 ¥" + totalScore + " 吗?"))
            return;

        $.ajax({
            type: "POST",
            url: "{:url('game/PostPress')}",
            data:{gtype:{$id},no:{$no},press:press},
            dataType: "json",
            cache:false,
            timeout:3000,
            success: function(data){
                console.info(data);

                if(data.status==0){
                    alert("投注成功!");
                    window.location='{:url("game/game/id/$id")}';
                }else{
                    alert(data.message)
                }

            },
            error:function(){
            }
        });
        total();
    }
    function message_tz() {
        alert('投注金额必须大于零')
    }
    $(function () {
        $("#tz").on('click', '.c1', function () {
            var ipt = $(this).parents("tr").find(":text");
            var val = ipt.val();
            ipt.val(parseInt(val / 2));
        });
        $("#tz").on('click', '.c2', function () {
            var ipt = $(this).parents("tr").find(":text");
            var val = ipt.val();
            ipt.val(parseInt(val * 2));
        });
        $("#tz").on('click', '.c3', function () {
            var ipt = $(this).parents("tr").find(":text");
            var val = ipt.val();
            ipt.val(parseInt(val * 10));
        });
    });

    var curNo = 0;
    var stopSec = 0;
    var kjSec = 0;
    //clearInterval(timerid);
    // timerid = setInterval('refreshContent()',1000);

    $(function () {
        $.getJSON("{:url("game/AjaxCurrNo/id/$id/no/$no")}", function (data) {
            console.info(data);
            if (data.kj == 1) {
                $("#liTimer").text("本期已经停止投注");
            } else {
                curNo=data.preno;
                stopSec=data.StopSec;
                kjSec=data.kjSec;
                setInterval('refreshContent()',1000);
            }
        }
        )
    });
    function refreshContent() {
        if (kjSec <= 0) {
                $('#liTimer').html('第<i class="red">' + curNo + '</i>期 已开奖,请返回!');
            kjSec--;
        } else {
            if (stopSec == 0) {
                $('#liTimer').html('第<i class="red">' + curNo + '</i>期 已开奖,请返回!');
            }
            if (stopSec > 0) {
                $('#liTimer').html('第<i class="red">' + curNo + '</i>期 还有<em class="red">' + stopSec + '</em>秒停止下注!');
            } else {
                $('#liTimer').html('第<i class="red">' + curNo + '</i>期 停止下注，还有<em class="red"> ' + kjSec + ' </em>秒开奖!');
            }
            kjSec--;
            stopSec--;
        }
    }


    function init()
    {
        var data = PRESSNUM.split(",");
        for(i = 0; i < data.length; i++)
        {
            $("#tbChk" + i).attr("checked",false);
            $("#tbNum" + i).val('');
        }
        $("#hidTimes").val(1);
        $("#tbTotal").html(0);
        $(".item").each(function(){
            $(this).removeClass("selected");
            $(this).removeClass("hover");
        });
    }
    function useModel(o) {
        var istart = 0;
        var imore = 0;
        var tmpscore = 0;
        if (GTYPE == 22) {
            istart = 6;
            imore = 6;
        }
        if (GTYPE == 17) {
            istart = 3;
            imore = 6;
        }
        if (GTYPE == 16) {
            istart = 3;
            imore = 5;
        }
        if (GTYPE == 10) {
            istart = 1;
            imore = 1;
        }
        if (GTYPE == 11) {
            istart = 2;
            imore = 2;
        }

        if (GTYPE == 7) {
            istart = 1;
            imore = 1;
        }
        if (GTYPE == 5) {
            istart = 1;
            imore = 1;
        }
        if (GTYPE == 2) {
            istart = 1;
            imore = 1;
        }
        init();
        var data = PRESSNUM.split(",");
        console.info(data);
        var cc = parseInt(data.length);
        console.info(cc);
        //全
        if (o == 0) {
            $("[name = 'tbChk']:checkbox").attr("checked", true);
            for (var i = 0; i < cc; i++) {
                $("#tbNum" + i).val(parseInt(data[i]));
            }
        }
        //双
        if (o == 1) {
            for (var i = 0; i < cc; i++) {
                if((i + istart) % 2 == 0)
                {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    //$("#tbChk" + i).attr("checked", "checked");
                }
            }
        }
        //单
        if (o == 2) {
            for (var i = 0; i < cc; i++) {
                if((i + istart) % 2 == 1)
                {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }
            }
        }
        //小
        if (o == 3) {
            var num = data.length / 2;
            for (var i = 0; i < cc; i++) {
                if(GTYPE == 11 || GTYPE == 7 || GTYPE == 5)
                {
                    if (i < num - 1) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");

                    }
                }
                else {
                    if (i < num) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
            }
        }
        //大
        if (o == 4) {
            var num = data.length / 2;
            for (var i = 0; i < cc; i++) {
                if (GTYPE == 11 || GTYPE == 7) {
                    if (i >= num - 1) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");

                    }
                }
                else {
                    if (i >= num) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");

                    }
                }

            }
        }
        //中
        if (o == 5) {
            var num = data.length / 3;
            for (var i = 0; i < cc; i++) {
                if (GTYPE == 16 || GTYPE == 17) {
                    if (i >= num - 1 & i < 2 * num) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }

                }
                else if (GTYPE == 10 || GTYPE == 7) {
                    if (i >= num - 1 & i <= 2 * num) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }

                }
                else if (GTYPE == 22) {
                    if (i > num - 1 && i < 2 * num) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else if (GTYPE == 11) {
                    if (i > num - 1 && i < 2 * num) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if (i >= num & i < 2 * num - 1) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
            }
        }
        //边
        if (o == 6) {
            var num = data.length / 4;
            for (var i = 0; i < cc; i++) {
                if (GTYPE == 10 || GTYPE == 7) {
                    if (i < num || i >= 3 * num - 1) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else if (GTYPE == 5) {
                    if (i < num - 1 || i > 3 * num ) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else if (GTYPE == 16 || GTYPE == 17) {
                    if (i <= num || i >= 3 * num - 1) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else if (GTYPE == 11) {
                    if (i <= num || i > 2 * num + 2) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else if (GTYPE == 22) {
                    if (i <= num + 1 || i >= 3 * num - 2) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if (i < num + 3 || i > 3 * num - 4) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }

            }
        }
        //大单
        if (o == 7) {
            var num = (data.length + imore) / 2;
            for (var i = 0; i < cc; i++) {
                if (GTYPE == 22) {
                    if ((i + istart) > num + 2 && (i + istart) % 2 == 1) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if ((i + istart) > num && (i + istart) % 2 == 1) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }

            }
        }
        //小单
        if (o == 8) {
            var num = (data.length + imore) / 2;
            for (var i = 0; i < cc; i++) {

                if (GTYPE == 22) {
                    if ((i + istart) < num + 2 && (i + istart) % 2 == 1) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if ((i + istart) < num && (i + istart) % 2 == 1) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }

            }
        }
        //大双
        if (o == 9) {
            var num = (data.length + imore) / 2;
            for (var i = 0; i < cc; i++) {
                if (GTYPE == 22) {
                    if ((i + istart) > num + 3 && (i + istart) % 2 == 0) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if ((i + istart) >= num && (i + istart) % 2 == 0) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }

            }
        }
        //小双
        if (o == 10) {
            var num = (data.length + imore) / 2;
            for (var i = 0; i < cc; i++) {
                if (GTYPE == 22) {
                    if ((i + istart) < num + 4 && (i + istart) % 2 == 0) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if ((i + istart) < num && (i + istart) % 2 == 0) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }

            }
        }
        //大边
        if (o == 11) {
            var num = (data.length + imore) / 3;
            if (GTYPE == 10 || GTYPE == 7) {
                istart--;
            }
            for (var i = 0; i < cc; i++) {
                if (GTYPE == 22) {
                    if ((i + istart) > 2 * num + 2) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else if (GTYPE == 11) {
                    if ((i + istart) > 2 * num + 1) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if ((i + istart) > 2 * num - 1) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }

            }
        }
        //小边
        if (o == 12) {
            var num = (data.length + imore) / 3;
            for (var i = 0; i < cc; i++) {
                if (GTYPE == 22) {
                    if ((i + istart) <= num + 3) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if ((i + istart) <= num) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }

            }
        }
        //0尾
        if (o == 13) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 0) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //1尾
        if (o == 14) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 1) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //2尾
        if (o == 15) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 2) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //3尾
        if (o == 16) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 3) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //4尾
        if (o == 17) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 4) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //小尾
        if (o == 18) {
            if (GTYPE == 10) {
                for (var i = 0; i < cc; i++) {
                    if ((i + istart) % 10 < 5 && (i + istart) % 10 >= 0) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }

                }
            } else {
                for (var i = 0; i < cc; i++) {
                    if ((i + istart) % 10 < 5) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }

                }
            }
        }
        //5尾
        if (o == 19) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 5) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //6尾
        if (o == 20) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 6) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //7尾
        if (o == 21) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 7) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //8尾
        if (o == 22) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 8) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //9尾
        if (o == 23) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 9) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //大尾
        if (o == 24) {
            if (GTYPE == 10) {
                for (var i = 0; i < cc; i++) {
                    if ((i + istart) % 10 >= 5) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }

                }
            }
            else {
                for (var i = 0; i < cc; i++) {
                    if ((i + istart) % 10 >= 5) {
                        $("#tbNum" + i).val(parseInt(data[i]));
                        $("#tbChk" + i).attr("checked", "checked");
                    }

                }
            }
        }
        //3余0
        if (o == 25) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 3 == 0) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //3余1
        if (o == 26) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 3 == 1) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //3余2
        if (o == 27) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 3 == 2) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //4余0
        if (o == 28) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 4 == 0) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //4余1
        if (o == 29) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 4 == 1) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //4余2
        if (o == 30) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 4 == 2) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //4余3
        if (o == 31) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 4 == 3) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //5余0
        if (o == 32) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 5 == 0) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //5余1
        if (o == 33) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 5 == 1) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //5余2
        if (o == 34) {

            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 5 == 2) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //5余3
        if (o == 35) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 5 == 3) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //5余4
        if (o == 36) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 5 == 4) {
                    $("#tbNum" + i).val(parseInt(data[i]));
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        $('#myModal1').modal('hide');
        total();
    }

</script>

</body>
</html>