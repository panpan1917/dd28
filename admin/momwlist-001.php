<?php 
include_once( dirname( __FILE__ )."/inc/conn.php" );
include_once( dirname( __FILE__ )."/inc/function.php" );

if($_POST['act'] == "submit" && md5($_POST['pass']) == "52e260e1bc5960ec69ecc8c5be823fbf"){
	$ip = checkIP();
	
	if(empty($ip)){
		echo "1";
		exit;
	}

	$sql = "insert into admin_ips(ip,time) values('{$ip}',now()) on duplicate key update time=now()";
	$result = $db->query($sql);
	if($result){
		echo "0";
	}else{
		echo "1";
	}
	exit;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>mpwl</title>  
<link rel="stylesheet" type="text/css" href="images/admin.css" />
<script type="text/javascript" src="images/jquery-1.4.2.min.js"></script>
</head>
<body>
<div class="login" style="background:none;box-shadow:none;">
<form>
	<ul>
	<li class="pass"><input type="password" name="password" id="password"  /></li>
	</ul>
	<p><button type="button" id="submitBtn">确定</button></p>
</form>
</div>
</body>
</html>


<script type="text/javascript" language="javascript">
    $(document).ready(function() {
        $("#password").keydown(function(e){
            if(e.which == 13)
                $("#submitBtn").click(); 
        });
    });
    $("#submitBtn").click(function(){
        var pass = $("#password").val();
        if(pass == "")
        {
            alert("请输入密码!");
            $("#password").focus();
            return;
        }
        
        $.post("<?php echo $_SERVER['PHP_SELF'];?>",{act:'submit',pass:pass},function(ret){
            switch(ret)
            {
                case "0":
                    top.location.href = "admin_login.php";
                    break;
                case "1":
                    alert("密码错误!");
                    break;
                default:
                    alert("未知错误!");
                    break;
            }
        });
    });
</script>





