<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );
//login_check( "users" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>游戏管理--机器人下注模式</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta name="GENERATOR" content="MSHTML 6.00.3790.4275">
	<link rel="stylesheet" type="text/css" href="images/css_body.css">
	<link rel="stylesheet" type="text/css" href="images/window.css">
	<link rel="Stylesheet" type="text/css" href="images/jquery_ui.css" />
	<script type="text/javascript" src="images/jquery.js"></script> 
	<script type="text/javascript" src="images/jquery_ui.js"></script>
</head>
<body>
	<div class="bodytitle">
		<div class="bodytitleleft"></div>
		<div class="bodytitletxt">机器人下注模式</div>
	</div>
	<!-- 机器人下注模式 -->
    <div class="categorylist" id="div_GameConfig">
        <table class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;" >				
			<tr bgcolor="#FFFFFF">
				<td colspan="8">
					选择游戏
					<select id = "sltGameList">
                    </select>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td width="150">投注模式</td>
				<td>
					<select id = "sltModeType">
                    </select>
                    <input type="button" value="刷新" id="btnRefresh" class="btn-1" />
                    <input type="button" value="删除模式" id="btnRemoveModel" class="btn-1" />
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>模式ID</td>
				<td><label id="lblModelID"></label></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>模式名称</td>
				<td><input type="text" id="txtModelName" style="width:150px" />
					取指定帐号ID在该游戏的模式:<input type="text" id="txtSpecialID" style="width:60px" />
					<input type="button" value="读取" id="btnGetSpecialModel" class="btn-1" /> 
					<select id = "sltSpecialModeType">
                    </select>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>投注明细</td>
				<td><input id="txtPressDetail" type="text" style="width:850px" /><br>
				    格式：号码,金额|号码,金额|号码,金额
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>投注总额</td>
				<td>
					<label id="lblPressPoint"></label>
				    <input type="button" value="计算" id="btnCalc" class="btn-1" />
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td></td>
				<td>
					<input type="button" value="保存设置" id="btnSaveMode" class="btn-1" />
					<input type="button" value="建新模式" id="btnAddMode" class="btn-1" />
				</td>
			</tr>                
		</table>
		<table id='tbResult' class="tbtitle" width="99%" cellspacing="1" cellpadding="0" border="0" align="center" style="BACKGROUND: #cad9ea;">
			<tr bgcolor="#f5fafe">
				<td align="center">模式ID</td>
				<td align="center">游戏名称</td>
				<td align="center">模式名称</td>
				<td align="center">投注额</td> 
				<td align="center">投注明细</td>
			</tr>
		</table>
	</div>
</form>    
</body>
<script type= "text/javascript" language ="javascript">
    $(window.parent.document).attr("title","机器人下注模式");
    $(document).ready(function() {
        GetGameListOption();
        GetModelOption();
        GetModelList();
        //***************************************************************
		//保存设置
		$("#btnSaveMode").click(function(){
			SaveModelDetail("save");
		});
		//建新模式
		$("#btnAddMode").click(function(){
			SaveModelDetail("new");
		});  
		//删除模式
		$("#btnRemoveModel").click(function(){
			RemoveModel();
		});
		//刷新
		$("#btnRefresh").click(function(){
			GetModelOption();
			GetModelConfig(); 
		});
		//游戏列表变更
		$("#sltGameList").change(function(){
			$("#sltSpecialModeType").empty();
            GetModelOption();
			GetModelList();
        });
        //模式列表变更     
        $("#sltModeType").change(function(){
            GetModelConfig();
			GetModelList();
        });
        //自动计算
		$("#btnCalc").click(function(){
			var pressdetail = $("#txtPressDetail").val();
			if(pressdetail == "")
			{
				$("#lblPressPoint").html("0");
				return;
			}
			var arr = new Array();
			arr = pressdetail.split("|");
			var sumPoint = 0;
			for(var i= 0; i < arr.length;i++)
			{
				var arrTmp = new Array();
				arrTmp = arr[i].split(",");
				sumPoint += parseInt(arrTmp[1]);
			}
			$("#lblPressPoint").html(sumPoint);
		});
		//取指定帐号模式
		$("#btnGetSpecialModel").click(function(){
			var userid = $("#txtSpecialID").val();
			if(userid == "") return;
			var data = "action=get_specialid_model&gametype=" + $("#sltGameList").val() + "&userid=" + userid;
			SendAjax(data);
		});
		//指定模式下拉列表变化
		$("#sltSpecialModeType").change(function(){
            var data = "action=game_specialmodel_config&id=" + $("#sltSpecialModeType").val() + "&gametype=" + $("#sltGameList").val();
			SendAjax(data);
        });
         
	});
    //***************************************************************************************************
    //取游戏列表
    function GetGameListOption()
    {
		var data = "action=get_gamelist_option";
		SendAjax(data);
    }
    //取模式列表
    function GetModelOption()
    {
		var data = "action=get_model_option&gametype=" + $("#sltGameList").val();
		SendAjax(data);
    }
    //取模式配置
    function GetModelConfig()
    {
    	$("#lblModelID").html($("#sltModeType").val());
    	$("#txtModelName").val($("#sltModeType").find("option:selected").text());
		var data = "action=game_model_config&id=" + $("#lblModelID").html();
		SendAjax(data);
    }
    //取记录
    function GetModelList()
    { 
        var data = GetData();
        SendAjax(data);
    }
    //取参数
    function GetData()
    {   
    	$("#tbResult tr:gt(0)").remove(); 
        var data = "action=get_model_list&gametype=" + $("#sltGameList").val();
        return data;
    }
    //保存设置
    function SaveModelDetail(t)
    {
        var data = "action=save_modeldetail&oprtype=" + t;
        var GameType = $("#sltGameList").val();
        var ModelID = $.trim($("#lblModelID").html());
        var ModelName = $.trim($("#txtModelName").val());
        var PressDetail = $.trim($("#txtPressDetail").val());
        
        if(GameType == "") 
        {
			alert("游戏类型错误!");
			return false;
        }
        if(ModelID != "" && isNaN(ModelID))
        {
			alert("请选择一个模式!");
			return false;
        }
        if(ModelName == "")
        {
			alert("模式名称不能为空!");
			return false;
        }
        if(PressDetail == "")
        {
			alert("投注明细不能为空!");
			return false;
        }
        
        data += "&gametype=" + GameType + "&modelid=" + ModelID + "&modelname=" + ModelName + "&pressdetail=" + PressDetail;
        SendAjax(data);
    }
    //删除模式
    function RemoveModel()
    {
		var ModelID = $("#sltModeType").val();
		if(ModelID == "" || isNaN(ModelID)) return;
		var data = "action=remove_model&id=" + ModelID + "&gametype=" + $("#sltGameList").val();;
		SendAjax(data);
    }
    //***************************************************************************************************
	//ajax处理
	function SendAjax(SendData)
	{
		var PostURL = "sgamemanage.php";
		$.ajax({
		       type: "POST",
		       async:false,
		       dataType: "json",
		       url: PostURL,
		       data: SendData,
		       success: function(data) {DataSuccess(data);}
		});
	}
	//数据成功后
	function DataSuccess(json)
	{ 
		var tbody = "";
		var pageinfo = "";
        var InfoType = "";
		$.each(json,function(i,item){
			if(i == 0)
			{
                InfoType = item.cmd;
				switch(item.cmd)
				{
					case "game_specialmodel_config":
						break;
					case "get_specialid_model":
						$("#sltSpecialModeType").empty();
                        $(item.msg).appendTo("#sltSpecialModeType");
                        break;
					case "remove_model":
						alert(item.msg);
						GetModelOption(); 
						break;
					case "get_model_list":
                        break;
                    case "game_model_config":
                    	break;
                    case "get_model_option":
						$("#sltModeType").empty();
                        $(item.msg).appendTo("#sltModeType");
						break;
					case "get_gamelist_option":
						$("#sltGameList").empty();
                        $(item.msg).appendTo("#sltGameList");
						return;
					case "save_modeldetail":
						alert(item.msg);
						GetModelOption();
						GetModelList();
						break;
					default:
						alert(item.msg);
						return;
						break;
				}
			}
			else
			{
				if(InfoType == "get_model_list")
                {
                    tbody += "<tr bgcolor='#FFFFFF'>" +
                            "<td align='center'>" + item.ModelID +"</td>" +
                            "<td align='center'>" + item.GameName + "</td>" + 
                            "<td align='center'>" + item.ModelName + "</td>" + 
                            "<td align='center'>" + item.PressPoint + "</td>" +
                            "<td align='center'>" + item.PressDetail + "</td>" +
                            "</tr>";
                 }
                 else if(InfoType == "game_model_config")
                {
                    $("#txtModelName").val(item.ModelName);
                    $("#txtPressDetail").val(item.PressDetail);
                    $("#lblPressPoint").html(item.PressPoint);
                }
                else if(InfoType == "game_specialmodel_config")
                {
                	$("#txtModelName").val(item.ModelName);
					$("#txtPressDetail").val(item.PressDetail);
                    $("#lblPressPoint").html(item.PressPoint);
                }
			}

		});
		if(tbody != "")
		{
			if(InfoType == "get_model_list")
            {
                $("#tbResult tr:gt(0)").remove();
                $("#tbResult").append(tbody);
            }
		}
	}
    
</script>

</html>
