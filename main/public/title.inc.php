<meta charset="utf-8" />
<meta name="keywords" content="<?php echo $web_keywords;?>" />
<meta name="description" content="<?php echo $web_description ;?>" />
<link type="image/x-icon" rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="css/1/layout.css?435" />
<link rel="stylesheet" type="text/css" href="css/1/global.css?436" />
<link rel="stylesheet" type="text/css" href="css/1/lv.css?435" />
<link rel="stylesheet" type="text/css" href="css/css.css?435" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.2.1.js"></script>
<script type="text/javascript" src="js/jquery.jmpopups-0.5.1.js"></script>
<script type="text/javascript" src="js/jquery.SuperSlide.2.1.js"></script>
<!-- <script type="text/javascript" src="js/layout.js"></script> -->

<?php 
if(isset($_SESSION["usersid"])) RefreshPoints();
?>
