<?php
include_once("inc/conn.php");
include_once("inc/function.php"); 
$sql = "SELECT  u.id,u.qq,a.agent_name FROM users u,agent a
        WHERE  u.id=a.uid AND a.state=1 AND a.is_recommend=1 AND u.isagent=1
        ";// ORDER BY RAND() LIMIT 25;
$query = $db->query($sql);
$data=array();
while($rs=$db->fetch_array($query)){
	$data[]=$rs;
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $web_keywords ;?> - 商务代理</title>
<?php require_once("public/title.inc.php");?>
<link rel="stylesheet" type="text/css" href="css/1/merchants.css" />
</head>

<body>
<?php $_SESSION['curpage'] = "merchants";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>


<div id="active">
    <div class="active_list width_980">
            <div class="panel-body">
                <div class="tab-content tab_active">
                    <div class="tab-pane active" id="ing">
                            <div class="panel panel-default">
                                <div class="panel-heading">合作代理</div>
                                <div class="panel-body">
									<div class="merchants" style="border: none;">
								        <ul>   
											<?php  foreach ($data as $num => $obj){ ?> 
											<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo ChangeEncodeG2U($obj["qq"]);?>&amp;site=qq&amp;menu=yes" target="_blank"> 
								            <li>
								              <span class="n"><?php echo ChangeEncodeG2U($obj["agent_name"]);?></span>
								              <span class="q">QQ:<?php echo ChangeEncodeG2U($obj["qq"]);?></span>
								            </li>
								            </a>
								        	<?php
												}
											?> 
								        </ul>
								    </div>
                                </div>
                            </div>
                    </div>
            </div>
        </div>
   </div>
</div>


<?php include_once("footer.php"); ?>
</body>
</html>
