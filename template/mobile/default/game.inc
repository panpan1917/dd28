<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    {tpl style}
    <link rel="stylesheet" type="text/css" href="{$skin}css/js.css?15" />
    <script type="text/javascript" src="{$skin}js/vue.min.js"></script>
    <title>{$webname}</title>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header">
            <a href="{:url('game/index')}" class="navbar-brand" ><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#list_nav">
                <span class="glyphicon glyphicon-option-horizontal"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="list_nav" style="color: #ecc296;">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{:url("game/rule/id/$id")}">游戏规则</a></li>
                <li><a href="{:url("game/record/id/$id")}">投注记录</a></li>
                <li><a href="{:url("game/total/id/$id")}">盈利统计</a></li>
                <li><a href="{:url("game/auto/id/$id")}">自动投注</a></li>
            </ul>
        </div>
        <p onclick="window.location.reload();" style="position:absolute; top:15px; left:50%; margin-left:-18px; font-size:16px;  text-shadow:2px 2px 3px;color: #ecc296;">{$game_name}</p>
    </div>

</nav>
<div id="main" style="margin-top:60px;margin-bottom:0px;">
    <div class="container-fluid">
        <div class="row">
            <div class="list-group white_bg" id="list">
                <template v-for="v in list">
                    <a href="javascript:;" id="{{v.id}}" class="list-group-item status{{v.status}}">
                        <div class="container-fluid">
                            <div class="row list_pad">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-left pad0"><span >{{v.sid}}</span></div>
                                <template v-if="v.kj==0">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-left pad0"><span>{{v.kgtime}}</span></div>
                                </template>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-left pad0 w{{v.kj}}" >
                                    <span v-if="v.kj==1">
                                        <template v-for="(index, sv) in v.kjjg">
                                            <span v-if="sv>-1">
                                        		<img src="{$skin}img/jg/number_{{type}}{{sv}}.gif">
                                            </span>
                                        </template>
                                    </span>
                                    <template v-if="v.kj==0">
                                        <span v-if="v.stoptime>0">截止<span class="red">{{v.stoptime}}</span>秒</span>
                                        <span v-else>
                                            <span v-if="v.kjtime>0">还有<span class="red">{{v.kjtime}}</span>秒,开奖 </span>
                                                <span v-else>开奖中...</span>
                                    </template>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0 press">
                                    <span v-if="v.status==1">{{v.points}}/{{v.hdpoints}}</span>
                                    <span v-if="v.status==2" id="press"><button class="btn btn-info kj"><span v-if="v.points>0">{{v.points}}</span><span v-else>投　注</span></button></span>
                                    <span v-if="v.status==3"><button class="btn btn-warning kj">开奖中</button></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </template>

            </div>
            
        </div>
    </div>
</div>


<script type="text/javascript">
    var vm = new Vue({
        el: '#list',
        data: {
            list:[],
            type:'',
            name: 'Vue.js'
        }
    });
    var timerid;
    function getContent(){
        var url="{:url("game/ajax/id/$id")}&t=" + Math.random();
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            timeout:3000,
            cache:false,
            success: function(data){
                if(!data.points)data.points=0;
                if(!data.bank)data.bank=0;
                $("#money").text(data.points);
                $("#bank").text(data.bank);
                vm.$data.list=data.list;
                vm.$data.type=data.type;
                timerid=setInterval(djs,1000);
            },
            error:function(){
                if(textStatus=="timeout"){
                    alert("加载超时，请重试");
                }else{
                    alert(textStatus);
                }
                getContent();
            }
        })
    }
    function djs() {
        for(var i in vm.$data.list){
            --vm.$data.list[i].stoptime;
            --vm.$data.list[i].kjtime;
            if(vm.$data.list[i].kj==0 && vm.$data.list[i].kjtime<1){
                console.info('<<'+vm.$data.list[i].kjtime);
                clearInterval(timerid);
                getContent();
                break;
            }
        }
    }
    $(document).ready(function(){
        clearInterval(timerid);
        getContent();
    });
    $(function () {
        $("#list").on('click','.press',function () {
            if($(this).find(".btn").hasClass("btn-default")){
            	alert("投注已结束");//已过期
                return false;
            }
            $("#list .btn-danger").removeClass("btn-danger");
            $(this).find('.btn').addClass('btn-danger');
            no=$(this).parents("a").attr("id");
            //window.location="{:url("game/press/id/$id")}&no="+no;
            
            //TODO
            showPressPannel();

            $("#no").text(no);
            //TODO
            //$(".status1").hide();
            return false;
        });
        $("#list").on("click",".text-left",function () {
            no=$(this).parents("a").attr("id");
            window.location="{:url("game/recorddetail/id/$id")}&no="+no;
            //window.location="{:url("game/record/id/$id")}";
        })
        var flag = 1;
        $('#clickIn').click(function(){
            //TODO
            //$(".status1").show()
        })
    })
    
    function showPressPannel() {
	    var bh = $("body").height();
	    var bw = $("body").width();
	    $("#fullbg").css({
	        height:bh,
	        width:bw,
	        display:"block"
	    });
	    $("#presspannel").show();
	    $("#pressbottom").show();
	}
	
	function closePressPannel() {
	    $("#fullbg,#presspannel,#pressbottom").hide();
	}
</script>


<div id="presspannel">
	<div style="position:absolute;right:0px;top:0px;"><img src="{$skin}img/close.png" alt="关闭" id="closeimg" onclick="closePressPannel();"></div>
    <div class="container-fluid">
        <div class="row">
        
            <!-- 选号区 -->
            <div class="small-show">
                <div id="press">
                	<div class="row small-btn small-36">
	                    {loop $press $k $v}
	                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
	                        <a id="tbNum<?php echo $k;?>" class="col-lg-2 btn btn-default small-bg"><?php echo $k+$step;?><div class="betXSmall">x{$odds[$k]}</div></a>
	                    </div>
	                    {/loop}
	                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
	                        <a class="col-lg-2 btn btn-default small-bg2 " id="reselect" href="javascript:reselect();" rel="4">反选</a>
	                    </div>
	                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
	                        <a class="col-lg-2 btn btn-default small-bg2 " href="javascript:remove();" rel="4">清除</a>
	                    </div>
                    </div>
                </div>
                <div class="row small-btn small-36" id="dx">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a class="col-lg-2 btn btn-default small-bg2 " href="javascript:;" rel="4">大</a></div>

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a class="col-lg-2 btn btn-default small-bg2" href="javascript:;" rel="3">小</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a class="col-lg-2 btn btn-default small-bg2" href="javascript:;" rel="2">单</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a class="col-lg-2 btn btn-default small-bg2" href="javascript:;" rel="1">双</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a class="col-lg-2 btn btn-default small-bg2" href="javascript:;" rel="8">小单</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a class="col-lg-2 btn btn-default small-bg2" href="javascript:;" rel="7">大单</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a class="col-lg-2 btn btn-default small-bg2" href="javascript:;" rel="10">小双</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a class="col-lg-2 btn btn-default small-bg2" href="javascript:;" rel="9">大双</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a class="col-lg-2 btn btn-default small-bg2" href="javascript:;" rel="5">中</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a class="col-lg-2 btn btn-default small-bg2" href="javascript:;" rel="6">边</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a class="col-lg-2 btn btn-default small-bg2" href="javascript:;" rel="11">大边</a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pad0">
                        <a class="col-lg-2 btn btn-default small-bg2" href="javascript:;" rel="12">小边</a>
                    </div>
                    
                </div>
            </div>
            <!-- 选号区 -->
            
            
            
        </div>
        
        
        
        
    </div>
</div>
        

<nav class="navbar-default navbar-fixed-bottom" id="pressbottom" style="display:none;">
    <div class="container-fluid betting_bottom">
        <div class="row">
                <div class="bottom_left">
                    <p style="font-size:14px;color:#fff;"><span>投注:</span><input type="number" name="total" id="total" class="form-control total_press" style="display:inline-block;height:35px;width:40%;" value="0"/>　余额:<span>{$info.points}</span></p>
                    <div class="chips">
                        <a href="javascript:chouma(1);"><img src="{$skin}img/chips_1000.png" class="img-responsive" alt="1"></a>
                        <a href="javascript:chouma(10);"><img src="{$skin}img/chips_10000.png" class="img-responsive" alt="10"></a>
                        <a href="javascript:chouma(100);"><img src="{$skin}img/chips_100000.png" class="img-responsive" alt="100"></a>
                        <a href="javascript:chouma(500);"><img src="{$skin}img/chips_500000.png" class="img-responsive" alt="500"></a>
                        <a href="javascript:chouma(1000);"><img src="{$skin}img/chips_1000000.png" class="img-responsive" alt="1000"></a>
                        <a href="javascript:chouma(5000);"><img src="{$skin}img/chips_5000000.png" class="img-responsive" alt="5000"></a>
                    </div>
                </div>
        </div>
        <div class="row">
                <div class="bottom_right">
                    <a href="javascript:last_press();" class="btn-tz btn btn-success">上次</a>
                    <a href="javascript:cancel();" class="btn-tz btn btn-warning">取消</a>
                    <a href="javascript:;" class="btn-tz btn btn-danger" onclick="tz()">投注</a>
                </div>
        </div>
    </div>
</nav>

<script type="text/javascript">
    var MAX_SCORE = {$pl.press_max};
    var MIN_SCORE = {$pl.press_min};
    var MY_SCORE = {};
    var GTYPE = {$pl.gameType};
    var PRESSNUM = '{$pl.pressNum}';
    var money=0,jf=0;
    var no='';
    var num=0;

    $(function () {
        $(".btn-qc").on('click',function () {
            $("#press ").removeClass('small-red');
        })
        $("#dx").on("click",'a',function () {
            if($(this).hasClass("small-red")) {
                useModel($(this).attr('rel'),2);
                $(this).removeClass("small-red")
            }else {
                useModel($(this).attr('rel'));
                $(this).addClass("small-red");
            }
            $("#reselect").removeClass("small-red")
        })
        $("#press").on("click","a",function () {
            if($(this).hasClass("small-red")){
                $(this).removeClass("small-red");
            }else {
                $(this).addClass("small-red");
            }
            money=0;
            total();
        })
        //TODO
        $("#total").on('change',function () {
        	money=parseInt($(this).val());
        	total();
        })
    });
    function last_press() {
        $.ajax({
            type:"post",
            url:"{:url('game/getLastPress')}",
            data:{gtype:{$id}},
            timeout:3000,
            cache:false,
            dataType:"json",
            success:function (data) {
                jf=0;
                var pre=data.message.split('|');
                for(var i in pre){
                    var v=pre[i].split(',');
                    $("#tbNum" + v[0]).addClass("small-red");
                    jf+=parseInt(v[1]);
                }
                console.info(jf);

                money=Math.ceil(jf/1000);
                console.info(jf);
                total();
            }
        });
    }
    function cancel() {
    	//TODO
    	closePressPannel();
        money=0;
        total();
    }
    function remove(){
        $("#press a").removeClass('small-red');
        $("#dx a").removeClass('small-red');
        money=0;
        total();
    }
    function reselect(){
        useModel(37,2);
        $("#dx a").removeClass("small-red");
    }
    function total() {
    	//TODO
        //$("#total").text(money);
        $("#total").val(money);
        jf=money*1000;
    }
    function chouma(v) {
        money+=parseInt(v);
        total();
    }
    function tz() {
        var press="";
        $("#press a").each(function (i) {
            if($(this).hasClass("small-red"))num++;
        });
        if(num>money){
            //money=num;
            //total();
        }
        if(num==0){
            alert("您还没有投注");return;
        }
        press=usefenpei(jf);
        if(jf > MAX_SCORE)
        {
            alert("您的投注额已大于最大限制" + MAX_SCORE);
            return;
        }
        if(jf < MIN_SCORE)
        {
            console.info(jf)
            alert("您的投注额小于最小限制" + MIN_SCORE);
            return;
        }
        if(!confirm("您确定投注 ¥" + money + " 吗?"))
            return;
        if(no==0){
            alert("请选择投资的期数");
            return;
        }
        $.ajax({
            type: "POST",
            url: "{:url('game/PostPress')}",
            data:{gtype:{$id},no:no,press:press},
            dataType: "json",
            cache:false,
            timeout:3000,
            success: function(data){
                console.info(data);
              
                if(data.status==0){
                    alert("投注成功!");
                  	//TODO
                    closePressPannel();
                    window.location='{:url("game/game/id/$id")}';
                }else{
                    alert(data.message);
                  	//TODO
                    closePressPannel();
                }

            },
            error:function(){
            }
        });
        total();
    }
    function useModel(o,t) {
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
        var data = PRESSNUM.split(",");
        console.info(data);
        var cc = parseInt(data.length);
        console.info(cc);
        //$("#press a").removeClass("small-red");
        //$("#dx a").removeClass("small-red");
        //全
        if (o == 0) {
            $("[name = 'tbChk']:checkbox").attr("checked", true);
            for (var i = 0; i < cc; i++) {
                $("#tbNum" + i).addClass("small-red");
            }
        }

        //双
        if (o == 1) {
            for (var i = 0; i < cc; i++) {
                if((i + istart) % 2 == 0)
                {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    //$("#tbChk" + i).attr("checked", "checked");
                }
            }
        }
        //单
        if (o == 2) {
            for (var i = 0; i < cc; i++) {
                if((i + istart) % 2 == 1)
                {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
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
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");

                    }
                }
                else {
                    if (i < num) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
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
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");

                    }
                }
                else {
                    if (i >= num) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
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
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }

                }
                else if (GTYPE == 10 || GTYPE == 7) {
                    if (i >= num - 1 & i <= 2 * num) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }

                }
                else if (GTYPE == 22) {
                    if (i > num - 1 && i < 2 * num) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else if (GTYPE == 11) {
                    if (i > num - 1 && i < 2 * num) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if (i >= num & i < 2 * num - 1) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
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
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else if (GTYPE == 5) {
                    if (i < num - 1 || i > 3 * num ) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else if (GTYPE == 16 || GTYPE == 17) {
                    if (i <= num || i >= 3 * num - 1) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else if (GTYPE == 11) {
                    if (i <= num || i > 2 * num + 2) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else if (GTYPE == 22) {
                    if (i <= num + 1 || i >= 3 * num - 2) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if (i < num + 3 || i > 3 * num - 4) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
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
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if ((i + istart) > num && (i + istart) % 2 == 1) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
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
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if ((i + istart) < num && (i + istart) % 2 == 1) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
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
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if ((i + istart) >= num && (i + istart) % 2 == 0) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
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
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if ((i + istart) < num && (i + istart) % 2 == 0) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
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
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else if (GTYPE == 11) {
                    if ((i + istart) > 2 * num + 1) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if ((i + istart) > 2 * num - 1) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
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
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }
                else {
                    if ((i + istart) <= num) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }
                }

            }
        }
        //0尾
        if (o == 13) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 0) {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //1尾
        if (o == 14) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 1) {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //2尾
        if (o == 15) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 2) {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //3尾
        if (o == 16) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 3) {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //4尾
        if (o == 17) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 4) {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //小尾
        if (o == 18) {
            if (GTYPE == 10) {
                for (var i = 0; i < cc; i++) {
                    if ((i + istart) % 10 < 5 && (i + istart) % 10 >= 0) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }

                }
            } else {
                for (var i = 0; i < cc; i++) {
                    if ((i + istart) % 10 < 5) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }

                }
            }
        }
        //5尾
        if (o == 19) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 5) {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //6尾
        if (o == 20) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 6) {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //7尾
        if (o == 21) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 7) {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //8尾
        if (o == 22) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 8) {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //9尾
        if (o == 23) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 10 == 9) {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //大尾
        if (o == 24) {
            if (GTYPE == 10) {
                for (var i = 0; i < cc; i++) {
                    if ((i + istart) % 10 >= 5) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }

                }
            }
            else {
                for (var i = 0; i < cc; i++) {
                    if ((i + istart) % 10 >= 5) {
                        if(t==2){
                            $("#tbNum" + i).removeClass("small-red");
                        }else {
                            $("#tbNum" + i).addClass("small-red");
                        }
                        $("#tbChk" + i).attr("checked", "checked");
                    }

                }
            }
        }
        //3余0
        if (o == 25) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 3 == 0) {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //3余1
        if (o == 26) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 3 == 1) {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //3余2
        if (o == 27) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 3 == 2) {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //4余0
        if (o == 28) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 4 == 0) {
                    if(t==2){
                        $("#tbNum" + i).removeClass("small-red");
                    }else {
                        $("#tbNum" + i).addClass("small-red");
                    }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //4余1
        if (o == 29) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 4 == 1) {
                    if(t==2){                             $("#tbNum" + i).removeClass("small-red");                         }else {                             $("#tbNum" + i).addClass("small-red");                         }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //4余2
        if (o == 30) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 4 == 2) {
                    if(t==2){                             $("#tbNum" + i).removeClass("small-red");                         }else {                             $("#tbNum" + i).addClass("small-red");                         }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //4余3
        if (o == 31) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 4 == 3) {
                    if(t==2){                             $("#tbNum" + i).removeClass("small-red");                         }else {                             $("#tbNum" + i).addClass("small-red");                         }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //5余0
        if (o == 32) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 5 == 0) {
                    if(t==2){                             $("#tbNum" + i).removeClass("small-red");                         }else {                             $("#tbNum" + i).addClass("small-red");                         }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //5余1
        if (o == 33) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 5 == 1) {
                    if(t==2){                             $("#tbNum" + i).removeClass("small-red");                         }else {                             $("#tbNum" + i).addClass("small-red");                         }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //5余2
        if (o == 34) {

            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 5 == 2) {
                    if(t==2){                             $("#tbNum" + i).removeClass("small-red");                         }else {                             $("#tbNum" + i).addClass("small-red");                         }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //5余3
        if (o == 35) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 5 == 3) {
                    if(t==2){                             $("#tbNum" + i).removeClass("small-red");                         }else {                             $("#tbNum" + i).addClass("small-red");                         }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        //5余4
        if (o == 36) {
            for (var i = 0; i < cc; i++) {
                if ((i + istart) % 5 == 4) {
                    if(t==2){                             $("#tbNum" + i).removeClass("small-red");                         }else {                             $("#tbNum" + i).addClass("small-red");                         }
                    $("#tbChk" + i).attr("checked", "checked");
                }

            }
        }
        else if(o==37){
            for(var i=0;i<cc;i++){
                if($("#tbNum"+i).hasClass("small-red")){
                    $("#tbNum" + i).removeClass("small-red");
                }else{
                    $("#tbNum" + i).addClass("small-red");
                }
            }
        }
        $('#myModal1').modal('hide');
        money=0;
        total();
    }


    function usefenpei(Input_Score){
        var totalScore = 0;
        var perScore = 0;
        var totalPressScore = 0;
        var data = PRESSNUM.split(",");
        var result="";


        for(var i = 0; i < data.length; i++)
        {
            if($("#tbNum" + i).hasClass("small-red"))
            {
                totalScore += parseInt(data[i]);
            }
        }
        for(var i= 0; i < data.length; i++)
        {
            perScore=0;

            if($("#tbNum" + i).hasClass("small-red"))
            {
                if(Input_Score <= MAX_SCORE)
                {
                    perScore = Input_Score / totalScore * parseInt(data[i]);
                }
                else
                {
                    perScore = MAX_SCORE / totalScore * parseInt(data[i]);
                }
                totalPressScore += parseInt(perScore);
            }
            result+=parseInt(perScore)+',';
        }
        return result;
    }


</script>
        
<div id="fullbg"></div>
        
</body>
</html>

