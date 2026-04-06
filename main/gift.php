<?php
require_once("inc/conn.php");
require_once("inc/function.php"); 
session_check();
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $web_name;?> - 兑换中心</title>
<?php require_once("public/title.inc.php");?>
<link rel="stylesheet" type="text/css" href="css/1/gift.css" />
<link rel="stylesheet" type="text/css" href="css/1/merchants.css" />
</head>
<?php $_SESSION['curpage'] = "gift";?>
<?php require_once("top.php");?>
<?php require_once("public/notice.inc.php");?>


<div id="active">
    <div class="active_list width_980">
            <div class="panel-body">
                <div class="tab-content tab_active">
                    <div class="tab-pane active" id="ing">
                            <div class="panel panel-default">
                                <div class="panel-heading">兑换中心</div>
                                <div class="panel-body">
								<div class="exch-border exch-itemrow"  style="border: none;">
						            <ul>
						                    <li>
						                        <a href="product.php?a=2">
						                            <img src="images/gift/product2.png" alt="" />
						                            <cite>滴滴体验卡100元</cite>
						                            <cite><span>100000</span></cite>
						                            <i class="exch-border"></i>
						                        </a>
						                       <a class="ui-btn" href="product.php?a=2">兑换</a>
						                    </li>
						                   
						                    <li>
						                        <a href="product.php?a=4">
						                            <img src="images/gift/product4.png" alt="" />
						                            <cite>滴滴体验卡500元</cite>
						                            <cite><span>500000</span></cite>
						                            <i class="exch-border"></i>
						                        </a>
						                       <a class="ui-btn" href="product.php?a=4">兑换</a>
						                    </li>
						                  
						                    <li>
						                        <a href="product.php?a=6">
						                            <img src="images/gift/product6.png" alt="" />
						                            <cite>滴滴体验卡1000元</cite>
						                            <cite><span>1000000</span></cite>
						                            <i class="exch-border"></i>
						                        </a>
						                       <a class="ui-btn" href="product.php?a=6">兑换</a>
						                    </li>
						                    <li>
						                        <a href="product.php?a=7">
						                            <img src="images/gift/product7.png" alt="" />
						                            <cite>滴滴体验卡5000元</cite>
						                            <cite><span>5000000</span></cite>
						                            <i class="exch-border"></i>
						                        </a>
						                       <a class="ui-btn" href="product.php?a=7">兑换</a>
						                    </li>
						                    
						                    <li>
						                        <a href="product.php?a=8">
						                            <img src="images/gift/product8.png" alt="" />
						                            <cite>滴滴体验卡10000元</cite>
						                            <cite><span>10000000</span></cite>
						                            <i class="exch-border"></i>
						                        </a>
						                       <a class="ui-btn" href="product.php?a=8">兑换</a>
						                    </li>
						                    
						                    <li>
						                        <a href="product.php?a=9">
						                            <img src="images/gift/product9.png" alt="" />
						                            <cite>滴滴体验卡50000元</cite>
						                            <cite><span>50000000</span></cite>
						                            <i class="exch-border"></i>
						                        </a>
						                       <a class="ui-btn" href="product.php?a=9">兑换</a>
						                    </li>
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
