function ToSave(gtype,no)
{
	var totalScore = parseInt($("#tbTotal").html());
	var data = PRESSNUM.split(",");
	
	if(totalScore > MAX_SCORE)
	{
	 alert("您的投注额已大于最大限制" + MAX_SCORE);
	 return;
	}
	if(totalScore < MIN_SCORE)
	{
	 alert("您的投注额小于最小限制" + MIN_SCORE);
	 return;
	}   
	if(!confirm("您确定投注 " + totalScore + " 吗?"))
	return;
	var press = "";
	for(var i = 0; i < data.length; i++)
	{
	 //if($("#tbChk" + i).attr("checked"))
		press += parseInt($("#tbNum" + i).val()==""?"0":$("#tbNum" + i).val()) + ",";
	 //else
	//	press += ",";
	}
	$.post("sgameservice.php?t=" + Math.random(),{act:"savepress",gtype:gtype,no:no,press:press,total:totalScore},function(ret){
		switch(ret.cmd)
		{
			case "ok":
				alert("投注成功!");
				var arrPoint = (ret.msg).split('|');
				$("#leftpoints").html(arrPoint[0]);
				$("#leftbankpoints").html(arrPoint[1]);
				TopRefreshPoints();
				getContent("sgame.php?act=" + gtype + "&t=" + Math.random());
				break;
			default:
				alert(ret.msg);
				break;
		}	
	},"json");
}
function RefreshOdds(gtype,no)
{
	$.post("sgameservice.php?t="+Math.random(),{act:"getodds",gtype:gtype,no:no},function(ret){
		if(ret.cmd == "ok")
		{
			if(ret.msg == "")
			{
				alert("已刷新!");
				return;
			}
			var arrodds = (ret.msg).split('|');
			for(var i = 0; i < arrodds.length; i++)
			{
				$("#odds_" + i).html(arrodds[i]);
			} 
			alert("已刷新!");
		}
		else
		{
			alert(ret.msg);
		}		
	},"json");	
}
function LastPress(gtype,no)
{
	$.post("sgameservice.php?t="+Math.random(),{act:"getlastpress",gtype:gtype,no:no},function(ret){
		init();
		if(ret.cmd == "ok")
		{
			var arrLast = (ret.msg).split('|');
			var totalScore = 0;
			for(var i = 0; i < arrLast.length; i++)
			{
				var arrT = arrLast[i].split(',');
				$("#tbChk"+arrT[0]).attr("checked",true);
				$("#tbNum" + arrT[0]).val(arrT[1]);
				totalScore += parseInt(arrT[1]);
			}
			$("#tbTotal").html(totalScore); 
		}
		else
		{
			alert(ret.msg);
		}
	},"json");
}
function LastPress_red(gtype,no)
{
	$.post("sgameservice.php?t="+Math.random(),{act:"getlastpress",gtype:gtype,no:no},function(ret){
		init();
		if(ret.cmd == "ok")
		{
			var arrLast = (ret.msg).split('|');
			var totalScore = 0;
			for(var i = 0; i < arrLast.length; i++)
			{
				var arrT = arrLast[i].split(',');
				$("#tbChk"+arrT[0]).attr("checked",true);
				$("#tbNum" + arrT[0]).val(arrT[1]);
                $("#tbNum"+arrT[0]).parents("tr").addClass("hover");
				totalScore += parseInt(arrT[1]);
			}
			$("#tbTotal").html(totalScore);
		}
		else
		{
			alert(ret.msg);
		}
	},"json");
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
}
function chgTimes(numID,times)
{
    var numIDx = "#" + numID;
    if(parseInt($(numIDx).val() * times) > 0)
    {
        $("#tbTotal").html(parseInt($("#tbTotal").html()) + parseInt($(numIDx).val() * times) - parseInt($(numIDx).val()));
        $(numIDx).val(parseInt($(numIDx).val() * times));
    }
}
function clearNum(numID,chkID){
    var numIDx = "#" + numID;
    var chkIDx = "#" + chkID;
    $("#tbTotal").html(parseInt($("#tbTotal").html()) - parseInt($(numIDx).val()));
    $(numIDx).val('');
    $(chkIDx).attr("checked",false);
}
function insert(obj,numID){
    var numIDx = "#" + numID;
    var theNum = numID.substr(5);
    var data = PRESSNUM.split(",");
    if($(obj).attr("checked") == 'checked' || $(obj).attr("checked") ==true)
    {
        $(numIDx).val(data[theNum]);
        if($("#tbTotal").html() == "")
        {
            $("#tbTotal").html(0);
        }
        $("#tbTotal").html(parseInt($("#tbTotal").html()) + parseInt(data[theNum]));
    }
    else
    {
        if($("#tbTotal").html() == "")
        {
            $("#tbTotal").html(0);   
        }
        $("#tbTotal").html(parseInt($("#tbTotal").html()) - parseInt($(numIDx).val()));
        $(numIDx).val('');
    }
}
function input(obj,chkID)
{
    var chkIDx = "#" + chkID;
    if($(obj).val() <= 0)
    {
        $(obj).val('');
    }
    var tmpMoney = 0;
    if($(obj).val() != "")
    {
        if(chkInt($(obj).val()) == false)
        {
            $(obj).val('');
        }
        if($(obj).val() != 0)
        {
            $(chkIDx).attr("checked",true);
        }
        else
        {
            $(chkIDx).attr("checked",false);
        }
    }
    else
    {
        $(chkIDx).attr("checked",false);
    }
    $("input[name='tbNum[]']").each(function(){
        if($(this).val() != "")
            tmpMoney += parseInt($(this).val());
    });
    $("#tbTotal").html(tmpMoney);
}
function useSuoha_red(){
    var totalScore = 0;
    var perScore = 0;
    var totalPressScore = 0;
    var data = PRESSNUM.split(",");
    $(".tztable tr.hover").each(function (b) {
        i=$(this).attr('attr');
        var v=$("#tbNum" + i).val();
        if (typeof (v)=="undefined"){
            v=0;
        }
            totalScore += parseInt(v);
    });
    $(".tztable tr.hover").each(function (b) {

        i=$(this).attr('attr');
            var v=$("#tbNum" + i).val();
            if (typeof (v)=="undefined"){
                v=0;
            }
            if (MY_SCORE <= MAX_SCORE) {
                perScore = MY_SCORE / totalScore * parseInt(v);
            }
            else {
                perScore = MAX_SCORE / totalScore * parseInt(v);
            }
            $("#tbNum" + i).val(parseInt(perScore));
            totalPressScore += parseInt(perScore);
    });
    $("#tbTotal").html(totalPressScore);
}
function useSuoha(){
    var totalScore = 0;
    var perScore = 0;
    var totalPressScore = 0;
    var data = PRESSNUM.split(",");
    for(var i = 0; i < data.length; i++)
    {
        if($("#tbChk" + i).attr("checked"))
        {
            totalScore += parseInt($("#tbNum" + i).val());
        }
    }
    for(var i= 0; i < data.length; i++)
    {
        if($("#tbChk" + i).attr("checked"))
        {
            if(MY_SCORE <= MAX_SCORE)
            {
                perScore = MY_SCORE / totalScore * parseInt($("#tbNum"+i).val());
            }
            else
            {
                perScore = MAX_SCORE / totalScore * parseInt($("#tbNum"+i).val());
            }
            $("#tbNum" + i).val(parseInt(perScore));
            totalPressScore += parseInt(perScore);
        }
    }
    $("#tbTotal").html(totalPressScore);
}
function useSuoha2(){
	var totalScore = 0;
	var perScore = 0;
	var totalPressScore = 0;
	var data = PRESSNUM.split(",");
	for(var i = 0; i < data.length; i++)
	{
		if($("#tbChk" + i).attr("checked"))
		{
			totalScore += parseInt($("#tbNum" + i).val());
		}
	}
	for(var i= 0; i < data.length; i++)
	{
		if($("#tbChk" + i).attr("checked"))
		{
			if(MY_SCORE <= MAX_SCORE)
			{
				perScore = MY_SCORE / totalScore * parseInt($("#tbNum"+i).val());
			}
			else
			{
				perScore = MAX_SCORE / totalScore * parseInt($("#tbNum"+i).val());
			}
			$("#tbNum" + i).val(parseInt(perScore));
			totalPressScore += parseInt(perScore); 
		}
	}
	$("#tbTotal").html(totalPressScore);	
}
function subSelect(){
    var data = PRESSNUM.split(",");
    for(i = 0;i < data.length;i++)
    {
        if(data[i] > 0)
        {
            if(typeof($("#tbChk" + i).attr("checked")) =="undefined" || $("#tbChk" + i).attr("checked") == false)
            {
                $("#tbNum" + i).val(Math.floor(data[i] * $("#hidTimes").val()));
                $("#tbChk" + i).attr("checked",true);
                $("#tbTotal").html(Math.floor(parseInt($("#tbTotal").html()) + parseInt(data[i]) * $("#hidTimes").val() ));
            }
            else
            {
                $("#tbChk" + i).attr("checked",false); 
                $("#tbTotal").html(Math.floor(parseInt($("#tbTotal").html()) - parseInt($("#tbNum" + i).val()) ));
                $("#tbNum" + i).val('');
            }  
        }
    }
}
function subSelect_red(){
    var data = PRESSNUM.split(",");
    var total=0;
            $(".tztable tr").each(function (b) {
                i=$(this).attr('attr');
                if(typeof (i)!="undefined") {

                    if (!$(this).hasClass("hover")) {
                        $(this).addClass("hover")
                        $("#tbNum" + i).val(Math.floor(data[i] * $("#hidTimes").val()));
                        var tmp = parseInt($("#tbNum" + i).val())
                        if (isNaN(tmp)) {
                            tmp = 0
                        }
                        total += tmp
                    }
                    else {
                        $(this).removeClass("hover")
                        $("#tbNum" + i).val('');
                    }
                }
        });
    $("#tbTotal").html(total);
}
function chgAllTimes2(v){
    var total=0
    $(".tztable tr.hover").each(function (i) {
             i=$(this).attr('attr');
            total+=parseInt(parseInt($("#tbNum" +i).val())*v);
    });
    if (total>MAX_SCORE){
        alert('已超过最大上限' + MAX_SCORE);
        return;
    }
    total=0
     $(".tztable tr.hover").each(function (i) {
             i=$(this).attr('attr');
            $("#tbNum" + i).val(parseInt(parseInt($("#tbNum" +i).val())*v));
         total+=parseInt($("#tbNum" +i).val());
    });
    $("#tbTotal").html(total);
}
function chgAllTimes(times)
{
	var data = PRESSNUM.split(",");
	if(parseInt($("#tbTotal").html()) * times > MAX_SCORE)
	{
		alert('已超过最大上限' + MAX_SCORE);
		return;
	}
    $("#hidTimes").val($("#hidTimes").val() * times);
    for(i = 0; i < data.length; i++)
    {
        if(parseInt($("#tbNum" + i).val()) * times > 1)
        { 
            if($("#tbNum" + i).val() > 0)
            {
                $("#tbTotal").html( parseInt($("#tbTotal").html()) + Math.floor(parseInt($("#tbNum" + i).val()) * times) - parseInt($("#tbNum" + i).val()) );
                $("#tbNum" + i).val( Math.floor(parseInt($("#tbNum" + i).val()) * times ));
                $("#tbChk" + i).attr("checked",true);
            }
        }
        else
        {
            if($("#tbNum" + i).val() > 0)
            {
                $("#tbTotal").html( parseInt($("#tbTotal").html())  - parseInt($("#tbNum" + i).val()) );
                $("#tbNum" + i).val('');
                $("#tbChk" + i).attr("checked",false);
            }
        }
    }
}

function chkInt(ints,minLength,maxLength,pattern){
    pattern = typeof(pattern) == 'undefined' ? '^-?[1-9]+[0-9]*$' : pattern;
    pattern = new RegExp(pattern);
    if(pattern.test(ints)==false){
        return false;
    }
    return chkStrLen(ints,minLength,maxLength);	
}

function chkStrLen(str,minLength,maxLength){
    if(str.length < minLength) {
        return false;
    }
    if(maxLength != null && str.length > maxLength) {
        return false;
    }
    return true;
}
function useModel2(o){
    init();
   var data = PRESSNUM.split(",");
   var cc = parseInt(data.length);
       for (var i = 0; i < cc; i++) {
           $("#tbNum" + i).val(parseInt(data[i]));
           $("#tbNum"+i).parents("tr").addClass("hover");
       }
   var tmpscore=0;
   $(".tztable tr.hover").each(function (i) {
           tmpscore += parseInt($("#tbNum" + $(this).attr('attr')).val());
   });
   $("#tbTotal").html(tmpscore);
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
    var cc = parseInt(data.length);
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
                $("#tbChk" + i).attr("checked", "checked");
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
    $("input[name='tbNum[]']").each(function(){
        if($(this).val() != "")
            tmpscore += parseInt($(this).val());
    });
    $("#tbTotal").html(tmpscore);
}


function is_new_game(){
    //var arr = [22,23,24,25,26,27,43,45,46,47,48,49,50,51,52,28,53,54,55,56,59];
    var arr = [25,26,27,43,45,46,47,48,49,50,51,52,28,53,54,55,56,59];
    var is_self=false;
    for(var i in arr){
        if(game_id==arr[i]){
            is_self=true;
        }
    }
    return is_self;
}
var c=0;
function chips(num,i){
    $("#yushe").attr("checked",false);
    var best=parseInt($("#betsLeft").val())?parseInt($("#betsLeft").val()):0;
    if(c==i && best>0 && best%parseInt(num)==0){
        best=parseInt(num)+best;
    }else{
        best=num;
    }
    c=i;
    if(is_new_game()){
        batch(num,false);
    }else{
        $("#betsLeft").val(best);
        usefenpei();
    }
}
function chips2(num,i){
    $("#yushe").attr("checked",false);
    var best=parseInt($("#betsLeft").val())?parseInt($("#betsLeft").val()):0;
    if(c==i && best>0 && best%parseInt(num)==0){
        best=parseInt(num)+best;
    }else{
        best=num;
    }
    c=i;
    $("#betsLeft").val(best);
    usefenpei2();
}
function chips3(num,i){
    var best=parseInt($("#yushe").val())?parseInt($("#yushe").val()):0;
    if(c==i && best>0 && best%parseInt(num)==0){
        best=parseInt(num)+best;
    }else{
        best=num;
    }
    if (num>MAX_SCORE)num=MAX_SCORE;
    c=i;
    $("#yushe").val(best);
    //yushe(num,false);
    usefenpei_red()
}
var money_arr=[10000,100000,500000,1000000,5000000];

//批量下
function batch(Input_Score,isset){
    var checked=true;
    if(isset==true){
        var Input_Score =$("#betsLeft").val();
    }else{
        $("#betsLeft").val(Input_Score);
    }
    var Score_isOK=true;
    if(isNaN(Input_Score) ){
        $("#betsLeft").val('');
        Score_isOK=false;
    }else{
        if(  Input_Score<1){
            $("#betsLeft").val('');
            Score_isOK=false;
        }
        if(checkRate(Input_Score)==false){
            $("#betsLeft").val('');
            Score_isOK=false;
        }
    }
    Input_Score=parseInt(Input_Score);
    var checked_num=0;
    var use_Score=0;
    var data = PRESSNUM.split(",");
    for(var i = 0; i < data.length; i++)
    {
        if($("#tbChk" + i).attr("checked"))
        {
            if($("#tbselected" + i).attr("checked")==false){
                use_Score=use_Score+parseInt($("#tbNum" + i).val());
            }
        }
    }

    if(checked==false){
        var iScore=Input_Score;
        if((Input_Score+use_Score)>MAX_SCORE){
            if(MAX_SCORE>MY_SCORE){
                iScore=(MY_SCORE-use_Score);
            }else{
                iScore=(MAX_SCORE-use_Score);
            }
            $("#betsLeft").val(iScore);
            return ;
        }
        if((Input_Score+use_Score)>MY_SCORE){
            iScore=(MY_SCORE-use_Score);
        }
        $("#betsLeft").val(iScore);
    }else{
        if(Score_isOK==true){
            var checked= $("input[name='tbselected']:checked").length;
            var checked_Score=Input_Score;
            if(((Input_Score*checked)+use_Score)>MAX_SCORE){
                var checked_Score=(MAX_SCORE-use_Score)/checked;
            }

            if(((Input_Score*checked)+use_Score)>MY_SCORE){
                if(MY_SCORE>MAX_SCORE){
                    var checked_Score=(MAX_SCORE-use_Score)/checked;
                }else{
                    var checked_Score=(MY_SCORE-use_Score)/checked;
                }
            }

            if(checked_Score>0){
                if(checked_Score.toString().indexOf(".")>-1){
                    checked_Score=parseInt(checked_Score);
                }
                $("#betsLeft").val(checked_Score);
                $("input[name='tbselected']:checked").each(function(){
                    var id=$(this).attr("id");
                    id=id.replace("tbselected","");
                    $("#tbChk" + id).attr("checked",true);
                    $("#tbNum" + id).val(checked_Score);
                });
            }
            Count_Total();
        }
    }


}
function batch_back(Input_Score,isset){
    var checked=true;
    if(isset==true){
        var Input_Score =$("#betsLeft").val();
    }else{
        $("#betsLeft").val(Input_Score);
    }
    var Score_isOK=true;
    if(isNaN(Input_Score) ){
        $("#betsLeft").val('');
        Score_isOK=false;
    }else{
        if(  Input_Score<1){
            $("#betsLeft").val('');
            Score_isOK=false;
        }
        if(checkRate(Input_Score)==false){
            $("#betsLeft").val('');
            Score_isOK=false;
        }
    }
    Input_Score=parseInt(Input_Score);
    var checked_num=0;
    var use_Score=0;
    var data = PRESSNUM.split(",");
    for(var i = 0; i < data.length; i++)
    {
        if($("#tbChk" + i).attr("checked"))
        {
            if($("#tbselected" + i).attr("checked")==false){
                use_Score=use_Score+parseInt($("#tbNum" + i).val());
            }
        }
    }

    if(checked==false){
        var iScore=Input_Score;
        if((Input_Score+use_Score)>MAX_SCORE){
            if(MAX_SCORE>MY_SCORE){
                iScore=(MY_SCORE-use_Score);
            }else{
                iScore=(MAX_SCORE-use_Score);
            }
            $("#betsLeft").val(iScore);
            return ;
        }
        if((Input_Score+use_Score)>MY_SCORE){
            iScore=(MY_SCORE-use_Score);
        }
        $("#betsLeft").val(iScore);
    }else{
        if(Score_isOK==true){
            var checked= $("input[name='tbselected']:checked").length;
            var checked_Score=Input_Score;
            if(((Input_Score*checked)+use_Score)>MAX_SCORE){
                var checked_Score=(MAX_SCORE-use_Score)/checked;
            }

            if(((Input_Score*checked)+use_Score)>MY_SCORE){
                if(MY_SCORE>MAX_SCORE){
                    var checked_Score=(MAX_SCORE-use_Score)/checked;
                }else{
                    var checked_Score=(MY_SCORE-use_Score)/checked;
                }
            }

            if(checked_Score>0){
                if(checked_Score.toString().indexOf(".")>-1){
                    checked_Score=parseInt(checked_Score);
                }
                $("#betsLeft").val(checked_Score);
                $("input[name='tbselected']:checked").each(function(){
                    var id=$(this).attr("id");
                    id=id.replace("tbselected","");
                    $("#tbChk" + id).attr("checked",true);
                    $("#tbNum" + id).val(checked_Score);
                });
            }
            Count_Total();
        }
    }


}

//判断正整数
function checkRate(val)
{
    var re = /^[0-9]*[1-9][0-9]*$/;
    if (!re.test(val))
    {
        return false;
    }else{
        return true;
    }
}
function usefenpei(){
    var totalScore = 0;
    var perScore = 0;
    var totalPressScore = 0;
    var data = PRESSNUM.split(",");
    var Input_Score = $('#betsLeft').val();
    if(isNaN(Input_Score) ){
        $('#betsLeft').val('');
        alert('分配分必须为数字!');
        return;
    }else{
        if(  Input_Score<1){
            $('#betsLeft').val('');
            alert('分配分必须大于0!');
            return;
        }
        if(checkRate(Input_Score)==false){
            $('#betsLeft').val('');
            alert('分配分必须正整数!');
            return;
        }
        var checked_num=0;
        for(var i = 0; i < data.length; i++)
        {
            if($("#tbChk" + i).attr("checked"))
            {
                checked_num=checked_num+1;
            }
        }
        if(Input_Score<checked_num){
            alert('分配分不够!');
            return;
        }
        if(Input_Score>MAX_SCORE){
            $('#betsLeft').val(MAX_SCORE);
            return;
        }
    }

    for(var i = 0; i < data.length; i++)
    {
        if($("#tbChk" + i).attr("checked"))
        {
            totalScore += parseInt($("#tbNum" + i).val());
        }
    }
    for(var i= 0; i < data.length; i++)
    {
        if($("#tbChk" + i).attr("checked"))
        {
            if(Input_Score <= MAX_SCORE)
            {
                perScore = Input_Score / totalScore * parseInt($("#tbNum"+i).val());
            }
            else
            {
                perScore = MAX_SCORE / totalScore * parseInt($("#tbNum"+i).val());
            }
            $("#tbNum" + i).val(parseInt(perScore));
            totalPressScore += parseInt(perScore);
        }
    }
    $("#tbTotal").html(totalPressScore);
}


function usefenpei2(){
    var totalScore = 0;
    var perScore = 0;
    var totalPressScore = 0;
    var data = PRESSNUM.split(",");
    var Input_Score = $('#betsLeft').val();
    if(isNaN(Input_Score) ){
        $('#betsLeft').val('');
        alert('分配分必须为数字!');
        return;
    }else{
        if(  Input_Score<1){
            $('#betsLeft').val('');
            alert('分配分必须大于0!');
            return;
        }
        if(checkRate(Input_Score)==false){
            $('#betsLeft').val('');
            alert('分配分必须正整数!');
            return;
        }
        var checked_num=$(".tztable tr.hover").length;
        if(Input_Score<checked_num){
            alert('分配分不够!');
            return;
        }
        if(Input_Score>MAX_SCORE){
            $('#betsLeft').val(MAX_SCORE);
            return;
        }
    }

    $(".tztable tr.hover").each(function (i) {
            totalScore += parseInt($("#tbNum" + $(this).attr('attr')).val())?parseInt($("#tbNum" + $(this).attr('attr')).val()):1;
    });
    $(".tztable tr.hover").each(function (b) {
        var i=$(this).attr('attr');
        var v=parseInt($("#tbNum"+i).val())?parseInt($("#tbNum"+i).val()):1;        
        var f=totalScore * v;
        if(Input_Score <= MAX_SCORE)
        {
            perScore = Input_Score /totalScore * v;
        }
        else
        {
            perScore = MAX_SCORE /totalScore * v;
        }
        $("#tbNum" + i).val(parseInt(perScore));
        totalPressScore += parseInt(perScore);
    });
    $("#tbTotal").html(totalPressScore);
}
function usefenpei_red(){
    var totalScore = 0;
    var perScore = 0;
    var totalPressScore = 0;
    var data = PRESSNUM.split(",");
    var Input_Score = $('#yushe').val();
    if(isNaN(Input_Score) ){
        $('#yushe').val('');
        alert('分配分必须为数字!');
        return;
    }else{
        if(  Input_Score<1){
            $('#yushe').val('');
            alert('分配分必须大于0!');
            return;
        }
        if(checkRate(Input_Score)==false){
            $('#yushe').val('');
            alert('分配分必须正整数!');
            return;
        }
        var checked_num=$(".tztable tr.hover").length;
        if(Input_Score<checked_num){
            alert('分配分不够!');
            return;
        }
        if(Input_Score>MAX_SCORE){
            $('#yushe').val(MAX_SCORE);
            return;
        }
    }

    $(".tztable tr.hover").each(function (b) {
        i=$(this).attr('attr');
        if(Input_Score <= MAX_SCORE)
        {
            perScore = parseInt(Input_Score) ;
        }
        else
        {
            perScore = MAX_SCORE ;
        }
        $("#tbNum" + i).val(parseInt(perScore));
        totalPressScore += parseInt(perScore);
    });
    $("#tbTotal").html(totalPressScore);
}
