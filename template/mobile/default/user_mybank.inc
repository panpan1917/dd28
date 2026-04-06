
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <link rel="stylesheet" type="text/css" href="{$skin}css/member_basic.css" />
    <link rel="stylesheet" type="text/css" href="{$skin}style/member_total.css" />
    {tpl style}
    <title>会员中心-{$webname}</title>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header nav_header">
            <a href="javascript:void(0);" class="navbar-brand" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <a href="javascript:void(0);" class="btn refresh" onclick="window.location.reload();"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-30px; font-size:16px; text-shadow:2px 2px 3px #ccc;color: #ecc296;">我的银行</p>
    </div>
</nav>
<div id="main">
    {tpl info}
    <div class="container-fluid">
        <div class="row white_bg member_mycard">
            <form action="">
                <div class="form-group member_coin">
                    <label for="desposit">存豆金额:</label>
                    <input type="text" class="form-control" name="deposit" id="deposit" placeholder="请输入金额整数">
                </div>
                
                <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="savemoney(50)">50</button>
                <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="savemoney(100)">100</button>
                <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="savemoney(500)">500</button>
                <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="savemoney(1000)">1K</button>
                <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="savemoney(5000)">5K</button>
                <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="saveall()">全部</button>
                <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="removes1()">清除</button>
                
                <button type="button" class="btn btn-danger btn-block set" style="margin-top:10px;">存入金额</button>
                
            </form>
            <form action="">
                <div class="form-group member_coin">
                    <label for="desposit">取豆金额:</label>
                    <input type="text" class="form-control" name="take" id="take" placeholder="请输入金额整数">
                </div>
                <div class="form-group member_coin">
                    <label for="take">银行密码:</label>
                    <input type="password" class="form-control" name="pass" placeholder="请输入银行密码">
                </div>
                
                
                <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="getmoney(50)">50</button>
                <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="getmoney(100)">100</button>
                <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="getmoney(500)">500</button>
                <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="getmoney(1000)">1K</button>
                <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="getmoney(5000)">5K</button>
                <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="getall()">全部</button>
                <button type="button"  class="btn btn-danger" style="font-size:12px;" onclick="removes2()">清除</button>
                
                <button type="button" class="btn btn-danger btn-block get" style="margin-top:10px;">取出金额</button>
            </form>
            <p class="green message_text">温馨提示:银行只能存取金额</p>
        </div>
    </div>
</div>
{tpl foot}
<script type="text/javascript">
    $(function () {
        $(".set").bind('click',function () {
            var point = $("input[name=deposit]").val();
            console.info(typeof  point);
            if (parseInt(point) > 0) {

            } else {
                alert('请输入金额数');
            }
            $.ajax({
                    type: 'post',
                    url: '{:url('users/mybank')}',
                data:{t:'save', point:point},
            dataType:'json',
            cache:false,
                success:function (data) {
                alert(data.message);
                if(data.status==0){
                    location.reload();
                }
            },
            error:function () {
                alert("超时");
            }

        })

        });
            $(".get").bind('click',function () {
                var point = $("input[name=take]").val();
                var pass = $("input[name=pass]").val();
                if (parseInt(point) > 0) {

                } else {
                    alert('请输入金额数');
                }
                if (pass.length < 6) {

                    alert('密码必须大于6位');
                }
                $.ajax({
                        type: 'post',
                        url: '{:url('users/mybank/')}',
                    data:{point:point,pwd:pass},
                dataType:'json',
                cache:false,
                    success:function (data) {
                    alert(data.message);
                    if(data.status==0){
                        location.reload();
                    }
                }

                ,
                error:function () {
                    alert("超时");
                }

            });
            });


    });

    function savemoney(i) {
        var deposit=parseInt($("#deposit").val());
        if(isNaN(deposit))deposit=0;
        $("#deposit").val(deposit+parseInt(i));
    }
    function removes1() {
        $("#deposit").val("");
    }
    function saveall(){
        $.ajax({
            type: 'post',
            url: '{:url('users/getmymoney/')}',
	        data:{},
		    dataType:'json',
		    cache:false,
		        success:function (data) {
			        if(data.status==0){
			        	$("#deposit").val(Math.floor(data.points/1000));
			        }
		    },
		    error:function () {
		        alert("超时");
		    }
		
		});
    }

    function getmoney(i) {
        var take=parseInt($("#take").val());
        if(isNaN(take))take=0;
        $("#take").val(take+parseInt(i));
    }
    function removes2() {
        $("#take").val("");
    }
    function getall(){
        $.ajax({
            type: 'post',
            url: '{:url('users/getmymoney/')}',
	        data:{},
		    dataType:'json',
		    cache:false,
		        success:function (data) {
			        if(data.status==0){
			        	$("#take").val(Math.floor(data.back/1000));
			        }
		    },
		    error:function () {
		        alert("超时");
		    }
		
		});
    }
    
</script>
</body>
</html>