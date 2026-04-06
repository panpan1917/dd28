<?php
include_once("inc/conn.php");
include_once("inc/function.php");

if(!isset($_SESSION['usersid'])) {
	echo "您还没登录或者链接超时，请先去<a href='/login.php'>登录</a>!";
	exit;
}

$act = intval($_GET['act']);
if ($_REQUEST['act'] == $act){
    sgame_open_recode($act);
}

function sgame_open_recode($act)
{
    global $db;
    $id = intval($_GET['id']);

    $RetContent = '<div class="popup">';
    $RetContent .= '<div class="popup-header">';
    if ($act == 5){
        $RetContent .= '<h2>北京16 第'.$id.'期开奖结果</h2>';
    }else if ($act == 4 || $act == 33){
        $RetContent .= '<h2>北京28 第'.$id.'期开奖结果</h2>';
    }else if ($act == 12){
        $RetContent .= '<h2>北京36 第'.$id.'期开奖结果</h2>';
    }else if($act == 18 || $act == 34){
        $RetContent .= '<h2>首尔28 第'.$id.'期开奖结果</h2>';
    }else if ($act == 19){
        $RetContent .= '<h2>首尔16 第'.$id.'期开奖结果</h2>';
    }else if ($act == 20){
        $RetContent .= '<h2>首尔11 第'.$id.'期开奖结果</h2>';
    }else if ($act == 21){
        $RetContent .= '<h2>首尔36 第'.$id.'期开奖结果</h2>';
    }else if ($act == 8 || $act == 35){
        $RetContent .= '<h2>加拿大28 第'.$id.'期开奖结果</h2>';
    }else if ($act == 9){
        $RetContent .= '<h2>加拿大16 第'.$id.'期开奖结果</h2>';
    }else if ($act == 10){
        $RetContent .= '<h2>加拿大11 第'.$id.'期开奖结果</h2>';
    }else if ($act == 13){
        $RetContent .= '<h2>加拿大36 第'.$id.'期开奖结果</h2>';
    }else if ($act == 3 || $act == 32){
        $RetContent .= '<h2>蛋蛋28 第'.$id.'期开奖结果</h2>';
    }else if ($act == 11){
        $RetContent .= '<h2>蛋蛋36 第'.$id.'期开奖结果</h2>';
    }else if ($act == 25){
        $RetContent .= '<h2>蛋蛋外围 第'.$id.'期开奖结果</h2>';
    }else if ($act == 26){
        $RetContent .= '<h2>蛋蛋定位 第'.$id.'期开奖结果</h2>';
    }else if ($act == 27){
        $RetContent .= '<h2>加拿大外围 第'.$id.'期开奖结果</h2>';
    }else if ($act == 28){
        $RetContent .= '<h2>加拿大定位 第'.$id.'期开奖结果</h2>';
    }else if ($act == 30){
        $RetContent .= '<h2>首尔外围 第'.$id.'期开奖结果</h2>';
    }else if ($act == 31){
        $RetContent .= '<h2>首尔定位 第'.$id.'期开奖结果</h2>';
    }else if ($act == 38){
        $RetContent .= '<h2>北京11 第'.$id.'期开奖结果</h2>';
    }else if ($act == 39){
        $RetContent .= '<h2>蛋蛋11 第'.$id.'期开奖结果</h2>';
    }else if ($act == 40){
        $RetContent .= '<h2>蛋蛋16 第'.$id.'期开奖结果</h2>';
    }else if ($act == 41){
        $RetContent .= '<h2>北京外围 第'.$id.'期开奖结果</h2>';
    }else if ($act == 42){
        $RetContent .= '<h2>北京定位 第'.$id.'期开奖结果</h2>';
    }
    
    
    $RetContent .= '<a class="close-link" title="关闭" onclick="closerecord('.$id.')" href="javascript:;">[关闭]</a>';
    $RetContent .= '<br clear="all">';
    $RetContent .= '</div>';
    $RetContent .= '<div class="Pattern">';
    $RetContent .= '<div class="titles nav-game_tab-link">';
    $RetContent .= '<div class="Content">';
    $RetContent .= '<table class="table_list table_list_show table table-hover table-bordered table-striped">';
    $RetContent .= '<tbody>';
    if($act == "5") //北京16
    {
        $sql = "SELECT kgtime,kgNo,kgjg FROM gamebj16 WHERE id = '{$id}'";
        $rs=$db->fetch_first($sql);
        $_html['kgtime'] = $rs['kgtime'];
        $_html['kgNo'] = explode('|',$rs['kgNo']);
        $kgjg=explode('|',$rs['kgjg']);
        sort($_html['kgNo']);
        $str = '';
        $str .= "<span>";
        foreach($_html['kgNo'] as $k => $v){
            if (in_array($k+1,array(1,4,7,10,13,16))) $str.= "<span class='red'>$v</span> ";
            if (in_array($k+1,array(2,5,8,11,14,17))) $str.= "<span class='blue'>$v</span> ";
            if (in_array($k+1,array(3,6,9,12,15,18))) $str.= "<span class='brown'>$v</span> ";
            if (in_array($k+1,array(19,20))) $str.= "<span class='grey'>$v</span> ";
        }
        $str .= "</span>";

        foreach($_html['kgNo'] as $k=> $v){
            if (in_array($k+1,array(1,4,7,10,13,16))){
                $_html['kgNo1'] .= "$v,";
            }
            $_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
            $_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
            $_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
            $_html['kgNo1_main'] = ($_html['kgNo1_sum']%6)+1;
            if (in_array($k+1,array(2,5,8,11,14,17))){
                $_html['kgNo2'] .= "$v,";
            }
            $_html['kgNo2_v'] =substr($_html['kgNo2'],0,-1);
            $_html['kgNo2_array'] = explode(',',$_html['kgNo2']);
            $_html['kgNo2_sum'] = array_sum($_html['kgNo2_array']);
            $_html['kgNo2_main'] = ($_html['kgNo2_sum']%6)+1;
            if (in_array($k+1,array(3,6,9,12,15,18))){
                $_html['kgNo3'] .= "$v,";
            }
            $_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
            $_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
            $_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
            $_html['kgNo3_main'] = ($_html['kgNo3_sum']%6)+1;
            $_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
        }
        $RetContent .= "\t\t<tr><td colspan='4'>北京16 第 $id 期开奖结果</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[0]."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[1]."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[2]."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$kgjg[0]."'></li><i class=\"hja\"></i><li class='kj kj_".$kgjg[1]."'></li><i class=\"hja\"></i><li class='kj kj_".$kgjg[2]."'></li><i class=\"hdeng\"></i><li class='mh m".$kgjg[3]."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "4" || $act == "33" || $act == "41" || $act == "42") //北京28 北京28固定 北京外围 北京定位
    {
        $sql = "SELECT kgtime,kgNo,kgjg FROM gameself28 WHERE id = '{$id}'";
        $rs=$db->fetch_first($sql);
        $_html['kgtime'] = $rs['kgtime'];
        $_html['kgNo'] = explode('|',$rs['kgNo']);
        $kgjg=explode('|',$rs['kgjg']);
        sort($_html['kgNo']);
        $str = '';
        $str .= "<span>";
        foreach($_html['kgNo'] as $k => $v){
            if (in_array($k+1,array(1,20))) $str.= "<span class='grey'>$v</span> ";
            if (in_array($k+1,array(2,5,8,11,14,17))) $str.= "<span class='red'>$v</span> ";
            if (in_array($k+1,array(3,6,9,12,15,18))) $str.= "<span class='blue'>$v</span> ";
            if (in_array($k+1,array(4,7,10,13,16,19))) $str.= "<span class='brown'>$v</span> ";
        }
        $str .= "</span>";

        foreach($_html['kgNo'] as $k=> $v){
            if (in_array($k+1,array(2,5,8,11,14,17))){
                $_html['kgNo1'] .= "$v,";
            }
            $_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
            $_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
            $_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
            $_html['kgNo1_main'] = substr($_html['kgNo1_sum'],-1);
            if (in_array($k+1,array(3,6,9,12,15,18))){
                $_html['kgNo2'] .= "$v,";
            }
            $_html['kgNo2_v'] =substr($_html['kgNo2'],0,-1);
            $_html['kgNo2_array'] = explode(',',$_html['kgNo2']);
            $_html['kgNo2_sum'] = array_sum($_html['kgNo2_array']);
            $_html['kgNo2_main'] = substr($_html['kgNo2_sum'],-1);
            if (in_array($k+1,array(4,7,10,13,16,19))){
                $_html['kgNo3'] .= "$v,";
            }
            $_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
            $_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
            $_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
            $_html['kgNo3_main'] = substr($_html['kgNo3_sum'],-1);
            $_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
        }
        $RetContent .= "\t\t<tr><td colspan='4'>北京28 第 $id 期开奖结果</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[0]."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[1]."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[2]."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$kgjg[0]."'></li><i class=\"hja\"></i><li class='kj kj_".$kgjg[1]."'></li><i class=\"hja\"></i><li class='kj kj_".$kgjg[2]."'></li><i class=\"hdeng\"></i><li class='mh m".$kgjg[3]."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "12") //北京36
    {
        $sql = "SELECT kgtime,kgjg,kgNo FROM gamebj36 WHERE id = '{$id}'";
        $rs=$db->fetch_first($sql);
        $_html['kgtime'] = $rs['kgtime'];
        $_html['kgNo'] = explode('|',$rs['kgNo']);
        $kgjg=explode('|',$rs['kgjg']);
        sort($_html['kgNo']);
        $str = '';
        $str .= "<span>";
        foreach($_html['kgNo'] as $k => $v){
            if (in_array($k+1,array(1,20))) $str.= "<span class='grey'>$v</span> ";
            if (in_array($k+1,array(2,5,8,11,14,17))) $str.= "<span class='red'>$v</span> ";
            if (in_array($k+1,array(3,6,9,12,15,18))) $str.= "<span class='blue'>$v</span> ";
            if (in_array($k+1,array(4,7,10,13,16,19))) $str.= "<span class='brown'>$v</span> ";
        }
        $str .= "</span>";

        foreach($_html['kgNo'] as $k=> $v){
            if (in_array($k+1,array(2,5,8,11,14,17))){
                $_html['kgNo1'] .= "$v,";
            }
            $_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
            $_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
            $_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
            $_html['kgNo1_main'] = substr($_html['kgNo1_sum'],-1);
            if (in_array($k+1,array(3,6,9,12,15,18))){
                $_html['kgNo2'] .= "$v,";
            }
            $_html['kgNo2_v'] =substr($_html['kgNo2'],0,-1);
            $_html['kgNo2_array'] = explode(',',$_html['kgNo2']);
            $_html['kgNo2_sum'] = array_sum($_html['kgNo2_array']);
            $_html['kgNo2_main'] = substr($_html['kgNo2_sum'],-1);
            if (in_array($k+1,array(4,7,10,13,16,19))){
                $_html['kgNo3'] .= "$v,";
            }
            $_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
            $_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
            $_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
            $_html['kgNo3_main'] = substr($_html['kgNo3_sum'],-1);
            $_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
        }
        $_html['kgjg'] = explode('|',$rs['kgjg']);
        $RetContent .= "\t\t<tr><td colspan='4'>北京36 第 $id 期开奖结果</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[0]."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[1]."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[2]."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$kgjg[0]."'></li><i class=\"hja\"></i><li class='kj kj_".$kgjg[1]."'></li><i class=\"hja\"></i><li class='kj kj_".$kgjg[2]."'></li><i class=\"hdeng\"></i><li class='zh z".$kgjg[3]."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "19")   // 首尔16
    {
        $sql = "SELECT kgtime,kgNo FROM gamehg16 WHERE id = '{$id}'";
        $rs=$db->fetch_first($sql);
        $_html['kgtime'] = $rs['kgtime'];
        $_html['kgNo'] = explode('|',$rs['kgNo']);
        sort($_html['kgNo']);
        $str = '';
        $str .= "<span>";
        foreach($_html['kgNo'] as $k => $v){
            if (in_array($k+1,array(19,20))) $str.= "<span class='grey'>$v</span> ";
            if (in_array($k+1,array(1,4,7,10,13,16))) $str.= "<span class='red'>$v</span> ";
            if (in_array($k+1,array(2,5,8,11,14,17))) $str.= "<span class='blue'>$v</span> ";
            if (in_array($k+1,array(3,6,9,12,15,18))) $str.= "<span class='brown'>$v</span> ";
        }
        $str .= "</span>";

        foreach($_html['kgNo'] as $k=> $v){
            if (in_array($k+1,array(1,4,7,10,13,16))){
                $_html['kgNo1'] .= "$v,";
            }
            $_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
            $_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
            $_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
            $_html['kgNo1_main'] = ($_html['kgNo1_sum']%6)+1;
            if (in_array($k+1,array(2,5,8,11,14,17))){
                $_html['kgNo2'] .= "$v,";
            }
            $_html['kgNo2_v'] =substr($_html['kgNo2'],0,-1);
            $_html['kgNo2_array'] = explode(',',$_html['kgNo2']);
            $_html['kgNo2_sum'] = array_sum($_html['kgNo2_array']);
            $_html['kgNo2_main'] = ($_html['kgNo2_sum']%6)+1;
            if (in_array($k+1,array(3,6,9,12,15,18))){
                $_html['kgNo3'] .= "$v,";
            }
            $_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
            $_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
            $_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
            $_html['kgNo3_main'] = ($_html['kgNo3_sum']%6)+1;
            $_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
        }
        $RetContent .= "\t\t<tr><td colspan='4'>首尔16 第 $id 期开奖结果</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo1_main']."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo2_main']."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo3_main']."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$_html['kgNo1_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo2_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo3_main']."'></li><i class=\"hdeng\"></i><li class='mh m".$_html['total']."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "20") // 首尔11
    {
        $sql = "SELECT kgtime,kgNo FROM gamehg16 WHERE id = '{$id}'";
        $rs=$db->fetch_first($sql);
        $_html['kgtime'] = $rs['kgtime'];
        $_html['kgNo'] = explode('|',$rs['kgNo']);
        $str = '';
        $str .= "<span>";
        foreach($_html['kgNo'] as $k => $v){
            if (in_array($k+1,array(1,4,7,10,13,16))) $str.= "<span class='red'>$v</span> ";
            if (in_array($k+1,array(2,5,8,11,14,17,19,20))) $str.= "<span class='grey'>$v</span> ";
            if (in_array($k+1,array(3,6,9,12,15,18))) $str.= "<span class='blue'>$v</span> ";
        }
        $str .= "</span>";

        foreach($_html['kgNo'] as $k=> $v){
            if (in_array($k+1,array(1,4,7,10,13,16))){
                $_html['kgNo1'] .= "$v,";
            }
            $_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
            $_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
            $_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
            $_html['kgNo1_main'] = ($_html['kgNo1_sum']%6)+1;
            if (in_array($k+1,array(3,6,9,12,15,18))){
                $_html['kgNo3'] .= "$v,";
            }
            $_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
            $_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
            $_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
            $_html['kgNo3_main'] = ($_html['kgNo3_sum']%6)+1;
            $_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
        }
        $RetContent .= "\t\t<tr><td colspan='4'>首尔11 第 $id 期开奖结果</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo1_main']."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo3_main']."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$_html['kgNo1_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo3_main']."'></li><i class=\"hdeng\"></i><li class='mh m".$_html['total']."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "18" || $act == "30" || $act == "31" || $act == "34") // 首尔28，外围，定位
    {
        $sql = "SELECT kgtime,kgNo FROM gamehg28 WHERE id = '{$id}'";
        $rs=$db->fetch_first($sql);
        $_html['kgtime'] = $rs['kgtime'];
        $_html['kgNo'] = explode('|',$rs['kgNo']);
        $str = '';
        $str .= "<span>";
        foreach($_html['kgNo'] as $k => $v){
            if (in_array($k+1,array(1,20))) $str.= "<span class='grey'>$v</span> ";
            if (in_array($k+1,array(2,5,8,11,14,17))) $str.= "<span class='red'>$v</span> ";
            if (in_array($k+1,array(3,6,9,12,15,18))) $str.= "<span class='blue'>$v</span> ";
            if (in_array($k+1,array(4,7,10,13,16,19))) $str.= "<span class='brown'>$v</span> ";
        }
        $str .= "</span>";

        foreach($_html['kgNo'] as $k=> $v){
            if (in_array($k+1,array(2,5,8,11,14,17))){
                $_html['kgNo1'] .= "$v,";
            }
            $_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
            $_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
            $_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
            $_html['kgNo1_main'] = substr($_html['kgNo1_sum'],-1);
            if (in_array($k+1,array(3,6,9,12,15,18))){
                $_html['kgNo2'] .= "$v,";
            }
            $_html['kgNo2_v'] =substr($_html['kgNo2'],0,-1);
            $_html['kgNo2_array'] = explode(',',$_html['kgNo2']);
            $_html['kgNo2_sum'] = array_sum($_html['kgNo2_array']);
            $_html['kgNo2_main'] = substr($_html['kgNo2_sum'],-1);
            if (in_array($k+1,array(4,7,10,13,16,19))){
                $_html['kgNo3'] .= "$v,";
            }
            $_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
            $_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
            $_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
            $_html['kgNo3_main'] = substr($_html['kgNo3_sum'],-1);
            $_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
        }
        if($act == "18" || $act == "34") $title = "首尔28 第 {$id} 期开奖结果";
        if($act == "30") $title = "首尔外围 第 {$id} 期开奖结果";
        if($act == "31") $title = "首尔定位 第 {$id} 期开奖结果";
        $RetContent .= "\t\t<tr><td colspan='4'>{$title}</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo1_main']."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo2_main']."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo3_main']."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$_html['kgNo1_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo2_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo3_main']."'></li><i class=\"hdeng\"></i><li class='mh m".$_html['total']."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "21") //首尔36
    {
        $sql = "SELECT kgtime,kgjg,kgNo FROM gamehg36 WHERE id = '{$id}'";
        $rs=$db->fetch_first($sql);
        $_html['kgtime'] = $rs['kgtime'];
        $_html['kgNo'] = explode('|',$rs['kgNo']);
        $str = '';
        $str .= "<span>";
        foreach($_html['kgNo'] as $k => $v){
            if (in_array($k+1,array(1,20))) $str.= "<span class='grey'>$v</span> ";
            if (in_array($k+1,array(2,5,8,11,14,17))) $str.= "<span class='red'>$v</span> ";
            if (in_array($k+1,array(3,6,9,12,15,18))) $str.= "<span class='blue'>$v</span> ";
            if (in_array($k+1,array(4,7,10,13,16,19))) $str.= "<span class='brown'>$v</span> ";
        }
        $str .= "</span>";
        foreach($_html['kgNo'] as $k=> $v){
            if (in_array($k+1,array(2,5,8,11,14,17))){
                $_html['kgNo1'] .= "$v,";
            }
            $_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
            $_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
            $_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
            $_html['kgNo1_main'] = substr($_html['kgNo1_sum'],-1);
            if (in_array($k+1,array(3,6,9,12,15,18))){
                $_html['kgNo2'] .= "$v,";
            }
            $_html['kgNo2_v'] =substr($_html['kgNo2'],0,-1);
            $_html['kgNo2_array'] = explode(',',$_html['kgNo2']);
            $_html['kgNo2_sum'] = array_sum($_html['kgNo2_array']);
            $_html['kgNo2_main'] = substr($_html['kgNo2_sum'],-1);
            if (in_array($k+1,array(4,7,10,13,16,19))){
                $_html['kgNo3'] .= "$v,";
            }
            $_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
            $_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
            $_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
            $_html['kgNo3_main'] = substr($_html['kgNo3_sum'],-1);
            $_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
        }
        $_html['kgjg'] = explode('|',$rs['kgjg']);
        $RetContent .= "\t\t<tr><td colspan='4'>首尔36 第 $id 期开奖结果</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo1_main']."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo2_main']."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo3_main']."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$_html['kgNo1_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo2_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo3_main']."'></li><i class=\"hdeng\"></i><li class='zh z".$_html['kgjg'][3]."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "10") // 加拿大11
    {
        $sql = "SELECT kgtime,kgNo FROM gamecan11 WHERE id = '{$id}'";
        $rs=$db->fetch_first($sql);
        $_html['kgtime'] = $rs['kgtime'];
        $_html['kgNo'] = explode('|',$rs['kgNo']);
        $str = '';
        $str .= "<span>";
        foreach($_html['kgNo'] as $k => $v){
            if (in_array($k+1,array(1,4,7,10,13,16))) $str.= "<span class='red'>$v</span> ";
            if (in_array($k+1,array(2,5,8,11,14,17,19,20))) $str.= "<span class='grey'>$v</span> ";
            if (in_array($k+1,array(3,6,9,12,15,18))) $str.= "<span class='blue'>$v</span> ";
        }
        $str .= "</span>";
        foreach($_html['kgNo'] as $k=> $v){
            if (in_array($k+1,array(1,4,7,10,13,16))){
                $_html['kgNo1'] .= "$v,";
            }
            $_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
            $_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
            $_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
            $_html['kgNo1_main'] = ($_html['kgNo1_sum']%6)+1;
            if (in_array($k+1,array(3,6,9,12,15,18))){
                $_html['kgNo3'] .= "$v,";
            }
            $_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
            $_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
            $_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
            $_html['kgNo3_main'] = ($_html['kgNo3_sum']%6)+1;
            $_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
        }
        $RetContent .= "\t\t<tr><td colspan='4'>加拿大11 第 $id 期开奖结果</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo1_main']."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo3_main']."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$_html['kgNo1_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo3_main']."'></li><i class=\"hdeng\"></i><li class='mh m".$_html['total']."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "9")
    {
        $sql = "SELECT kgtime,kgNo FROM gamecan16 WHERE id = '{$id}'";
        $rs=$db->fetch_first($sql);
        $_html['kgtime'] = $rs['kgtime'];
        $_html['kgNo'] = explode('|',$rs['kgNo']);
        $str = '';
        $str .= "<span>";
        foreach($_html['kgNo'] as $k => $v){
            if (in_array($k+1,array(19,20))) $str.= "<span class='grey'>$v</span> ";
            if (in_array($k+1,array(1,4,7,10,13,16))) $str.= "<span class='red'>$v</span> ";
            if (in_array($k+1,array(2,5,8,11,14,17))) $str.= "<span class='blue'>$v</span> ";
            if (in_array($k+1,array(3,6,9,12,15,18))) $str.= "<span class='brown'>$v</span> ";
        }
        $str .= "</span>";
        foreach($_html['kgNo'] as $k=> $v){
            if (in_array($k+1,array(1,4,7,10,13,16))){
                $_html['kgNo1'] .= "$v,";
            }
            $_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
            $_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
            $_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
            $_html['kgNo1_main'] = ($_html['kgNo1_sum']%6)+1;
            if (in_array($k+1,array(2,5,8,11,14,17))){
                $_html['kgNo2'] .= "$v,";
            }
            $_html['kgNo2_v'] =substr($_html['kgNo2'],0,-1);
            $_html['kgNo2_array'] = explode(',',$_html['kgNo2']);
            $_html['kgNo2_sum'] = array_sum($_html['kgNo2_array']);
            $_html['kgNo2_main'] = ($_html['kgNo2_sum']%6)+1;
            if (in_array($k+1,array(3,6,9,12,15,18))){
                $_html['kgNo3'] .= "$v,";
            }
            $_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
            $_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
            $_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
            $_html['kgNo3_main'] = ($_html['kgNo3_sum']%6)+1;
            $_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
        }
        $RetContent .= "\t\t<tr><td colspan='4'>加拿大16 第 $id 期开奖结果</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo1_main']."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo2_main']."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo3_main']."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$_html['kgNo1_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo2_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo3_main']."'></li><i class=\"hdeng\"></i><li class='mh m".$_html['total']."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "8" || $act == "27" || $act == "28" || $act == "35")//加拿大28，外围，定位
    {
        $sql = "SELECT kgtime,kgNo FROM gamecan28 WHERE id = '{$id}'";
        $rs=$db->fetch_first($sql);
        $_html['kgtime'] = $rs['kgtime'];
        $_html['kgNo'] = explode('|',$rs['kgNo']);
        $str = '';
        $str .= "<span>";
        foreach($_html['kgNo'] as $k => $v){
            if (in_array($k+1,array(1,20))) $str.= "<span class='grey'>$v</span> ";
            if (in_array($k+1,array(2,5,8,11,14,17))) $str.= "<span class='red'>$v</span> ";
            if (in_array($k+1,array(3,6,9,12,15,18))) $str.= "<span class='blue'>$v</span> ";
            if (in_array($k+1,array(4,7,10,13,16,19))) $str.= "<span class='brown'>$v</span> ";
        }
        $str .= "</span>";
        foreach($_html['kgNo'] as $k=> $v){
            if (in_array($k+1,array(2,5,8,11,14,17))){
                $_html['kgNo1'] .= "$v,";
            }
            $_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
            $_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
            $_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
            $_html['kgNo1_main'] = substr($_html['kgNo1_sum'],-1);
            if (in_array($k+1,array(3,6,9,12,15,18))){
                $_html['kgNo2'] .= "$v,";
            }
            $_html['kgNo2_v'] =substr($_html['kgNo2'],0,-1);
            $_html['kgNo2_array'] = explode(',',$_html['kgNo2']);
            $_html['kgNo2_sum'] = array_sum($_html['kgNo2_array']);
            $_html['kgNo2_main'] = substr($_html['kgNo2_sum'],-1);
            if (in_array($k+1,array(4,7,10,13,16,19))){
                $_html['kgNo3'] .= "$v,";
            }
            $_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
            $_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
            $_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
            $_html['kgNo3_main'] = substr($_html['kgNo3_sum'],-1);
            $_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
        }
        if($act == "8") $title = "加拿大28 第 {$id} 期开奖结果";
        if($act == "27") $title = "加拿大外围 第 {$id} 期开奖结果";
        if($act == "28") $title = "加拿大定位 第 {$id} 期开奖结果";
        $RetContent .= "\t\t<tr><td colspan='4'>{$title}</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo1_main']."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo2_main']."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo3_main']."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$_html['kgNo1_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo2_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo3_main']."'></li><i class=\"hdeng\"></i><li class='mh m".$_html['total']."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "13")
    {
        $sql = "SELECT kgtime,kgjg,kgNo FROM gamecan36 WHERE id = '{$id}'";
        $rs=$db->fetch_first($sql);
        $_html['kgtime'] = $rs['kgtime'];
        $_html['kgNo'] = explode('|',$rs['kgNo']);
        $str = '';
        $str .= "<span>";
        foreach($_html['kgNo'] as $k => $v){
            if (in_array($k+1,array(1,20))) $str.= "<span class='grey'>$v</span> ";
            if (in_array($k+1,array(2,5,8,11,14,17))) $str.= "<span class='red'>$v</span> ";
            if (in_array($k+1,array(3,6,9,12,15,18))) $str.= "<span class='blue'>$v</span> ";
            if (in_array($k+1,array(4,7,10,13,16,19))) $str.= "<span class='brown'>$v</span> ";
        }
        $str .= "</span>";
        foreach($_html['kgNo'] as $k=> $v){
            if (in_array($k+1,array(2,5,8,11,14,17))){
                $_html['kgNo1'] .= "$v,";
            }
            $_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
            $_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
            $_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
            $_html['kgNo1_main'] = substr($_html['kgNo1_sum'],-1);
            if (in_array($k+1,array(3,6,9,12,15,18))){
                $_html['kgNo2'] .= "$v,";
            }
            $_html['kgNo2_v'] =substr($_html['kgNo2'],0,-1);
            $_html['kgNo2_array'] = explode(',',$_html['kgNo2']);
            $_html['kgNo2_sum'] = array_sum($_html['kgNo2_array']);
            $_html['kgNo2_main'] = substr($_html['kgNo2_sum'],-1);
            if (in_array($k+1,array(4,7,10,13,16,19))){
                $_html['kgNo3'] .= "$v,";
            }
            $_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
            $_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
            $_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
            $_html['kgNo3_main'] = substr($_html['kgNo3_sum'],-1);
            $_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
        }
        $_html['kgjg'] = explode('|',$rs['kgjg']);
        $RetContent .= "\t\t<tr><td colspan='4'>加拿大36 第 $id 期开奖结果</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo1_main']."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo2_main']."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo3_main']."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$_html['kgNo1_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo2_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo3_main']."'></li><i class=\"hdeng\"></i><li class='zh z".$_html['kgjg'][3]."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "11") //蛋蛋36
    {
        $sql = "SELECT kgtime,kgjg,kgNo FROM game36 WHERE id = '{$id}'";
        $rs=$db->fetch_first($sql);
        $_html['kgtime'] = $rs['kgtime'];
        $_html['kgNo'] = explode('|',$rs['kgNo']);
        sort($_html['kgNo']);
        $kgjg=explode('|',$rs['kgjg']);
        $str = '';
        $str .= "<span>";
        foreach($_html['kgNo'] as $k => $v){
            if (in_array($k+1,array(19,20))) $str.= "<span class='grey'>$v</span> ";
            if (in_array($k+1,array(1,2,3,4,5,6))) $str.= "<span class='red'>$v</span> ";
            if (in_array($k+1,array(7,8,9,10,11,12))) $str.= "<span class='blue'>$v</span> ";
            if (in_array($k+1,array(13,14,15,16,17,18))) $str.= "<span class='brown'>$v</span> ";
        }
        $str .= "</span>";
        foreach($_html['kgNo'] as $k=> $v){
            if (in_array($k+1,array(1,2,3,4,5,6))){
                $_html['kgNo1'] .= "$v,";
            }
            $_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
            $_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
            $_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
            $_html['kgNo1_main'] = substr($_html['kgNo1_sum'],-1);
            if (in_array($k+1,array(7,8,9,10,11,12))){
                $_html['kgNo2'] .= "$v,";
            }
            $_html['kgNo2_v'] =substr($_html['kgNo2'],0,-1);
            $_html['kgNo2_array'] = explode(',',$_html['kgNo2']);
            $_html['kgNo2_sum'] = array_sum($_html['kgNo2_array']);
            $_html['kgNo2_main'] = substr($_html['kgNo2_sum'],-1);
            if (in_array($k+1,array(13,14,15,16,17,18))){
                $_html['kgNo3'] .= "$v,";
            }
            $_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
            $_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
            $_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
            $_html['kgNo3_main'] = substr($_html['kgNo3_sum'],-1);
            $_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
        }
        $_html['kgjg'] = explode('|',$rs['kgjg']);
        $RetContent .= "\t\t<tr><td colspan='4'>蛋蛋36 第 $id 期开奖结果</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第1/2/3/4/5/6位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第7/8/9/10/11/12位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第13/14/15/16/17/18位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[0]."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[1]."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[2]."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$kgjg[0]."'></li><i class=\"hja\"></i><li class='kj kj_".$kgjg[1]."'></li><i class=\"hja\"></i><li class='kj kj_".$kgjg[2]."'></li><i class=\"hdeng\"></i><li class='zh z".$kgjg[3]."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "3" || $act == "25" || $act == "26" || $act == "32") //蛋蛋28，外围，定位
    {
        $sql = "SELECT kgtime,kgNo,kgjg FROM game28 WHERE id = '{$id}'";
        $rs=$db->fetch_first($sql);
        $_html['kgtime'] = $rs['kgtime'];
        $_html['kgNo'] = explode('|',$rs['kgNo']);
        $kgjg=explode('|',$rs['kgjg']);
        sort($_html['kgNo']);
        $str = '';
        $str .= "<span>";
        foreach($_html['kgNo'] as $k => $v){
            if (in_array($k+1,array(19,20))) $str.= "<span class='grey'>$v</span> ";
            if (in_array($k+1,array(1,2,3,4,5,6))) $str.= "<span class='red'>$v</span> ";
            if (in_array($k+1,array(7,8,9,10,11,12))) $str.= "<span class='blue'>$v</span> ";
            if (in_array($k+1,array(13,14,15,16,17,18))) $str.= "<span class='brown'>$v</span> ";
        }
        $str .= "</span>";
        foreach($_html['kgNo'] as $k=> $v){
            if (in_array($k+1,array(1,2,3,4,5,6))){
                $_html['kgNo1'] .= "$v,";
            }
            $_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
            $_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
            $_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
            $_html['kgNo1_main'] = substr($_html['kgNo1_sum'],-1);
            if (in_array($k+1,array(7,8,9,10,11,12))){
                $_html['kgNo2'] .= "$v,";
            }
            $_html['kgNo2_v'] =substr($_html['kgNo2'],0,-1);
            $_html['kgNo2_array'] = explode(',',$_html['kgNo2']);
            $_html['kgNo2_sum'] = array_sum($_html['kgNo2_array']);
            $_html['kgNo2_main'] = substr($_html['kgNo2_sum'],-1);
            if (in_array($k+1,array(13,14,15,16,17,18))){
                $_html['kgNo3'] .= "$v,";
            }
            $_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
            $_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
            $_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
            $_html['kgNo3_main'] = substr($_html['kgNo3_sum'],-1);
            $_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
        }
        if($act == "3") $title = "蛋蛋28 第 {$id} 期开奖结果";
        if($act == "25") $title = "蛋蛋外围 第 {$id} 期开奖结果";
        if($act == "26") $title = "蛋蛋定位 第 {$id} 期开奖结果";
        $RetContent .= "\t\t<tr><td colspan='4'>{$title}</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第1/2/3/4/5/6位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第7/8/9/10/11/12位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第13/14/15/16/17/18位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_v']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."</td>\r\n";
        $RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[0]."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[1]."'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_".$kgjg[2]."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$kgjg[0]."'></li><i class=\"hja\"></i><li class='kj kj_".$kgjg[1]."'></li><i class=\"hja\"></i><li class='kj kj_".$kgjg[2]."'></li><i class=\"hdeng\"></i><li class='mh m".$kgjg[3]."'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "38") // 北京11
    {
    	$sql = "SELECT kgtime,kgNo FROM gamebj11 WHERE id = '{$id}'";
    	$rs=$db->fetch_first($sql);
    	$_html['kgtime'] = $rs['kgtime'];
    	$_html['kgNo'] = explode('|',$rs['kgNo']);
    	$str = '';
    	$str .= "<span>";
    	foreach($_html['kgNo'] as $k => $v){
    		if (in_array($k+1,array(1,4,7,10,13,16))) $str.= "<span class='red'>$v</span> ";
    		if (in_array($k+1,array(2,5,8,11,14,17,19,20))) $str.= "<span class='grey'>$v</span> ";
    		if (in_array($k+1,array(3,6,9,12,15,18))) $str.= "<span class='blue'>$v</span> ";
    	}
    	$str .= "</span>";
    	foreach($_html['kgNo'] as $k=> $v){
    		if (in_array($k+1,array(1,4,7,10,13,16))){
    			$_html['kgNo1'] .= "$v,";
    		}
    		$_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
    		$_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
    		$_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
    		$_html['kgNo1_main'] = ($_html['kgNo1_sum']%6)+1;
    		if (in_array($k+1,array(3,6,9,12,15,18))){
    			$_html['kgNo3'] .= "$v,";
    		}
    		$_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
    		$_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
    		$_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
    		$_html['kgNo3_main'] = ($_html['kgNo3_sum']%6)+1;
    		$_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
    	}
    	$RetContent .= "\t\t<tr><td colspan='4'>北京11 第 $id 期开奖结果</td></tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
    	$RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
    	$RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>区位</td>\r\n";
    	$RetContent .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
    	$RetContent .= "\t\t\t<td>第三区[第3/6/9/12/15/18位数字]</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>数字</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>求和</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>计算</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."除以6的余数 + 1</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."除以6的余数 + 1</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>计算</td>\r\n";
    	$RetContent .= "\t\t\t<td>取尾数</td>\r\n";
    	$RetContent .= "\t\t\t<td>取尾数</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>结果</td>\r\n";
    	$RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo1_main']."'></li></td>\r\n";
    	$RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo3_main']."'></li></td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>开奖</td>\r\n";
    	$RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$_html['kgNo1_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo3_main']."'></li><i class=\"hdeng\"></i><li class='mh m".$_html['total']."'></li></td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "39") // 蛋蛋11
    {
    	$sql = "SELECT kgtime,kgNo FROM game11 WHERE id = '{$id}'";
    	$rs=$db->fetch_first($sql);
    	$_html['kgtime'] = $rs['kgtime'];
    	$_html['kgNo'] = explode('|',$rs['kgNo']);
    	$str = '';
    	$str .= "<span>";
    	foreach($_html['kgNo'] as $k => $v){
    		if (in_array($k+1,array(1,2,3,4,5,6))) $str.= "<span class='red'>$v</span> ";
    		if (in_array($k+1,array(7,8,9,10,11,12))) $str.= "<span class='blue'>$v</span> ";
    		if (in_array($k+1,array(13,14,15,16,17,18))) $str.= "<span class='orange'>$v</span> ";
    		if (in_array($k+1,array(19,20))) $str.= "<span class='grey'>$v</span> ";
    	}
    	$str .= "</span>";
    	foreach($_html['kgNo'] as $k=> $v){
    		if (in_array($k+1,array(1,2,3,4,5,6))){
    			$_html['kgNo1'] .= "$v,";
    		}
    		$_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
    		$_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
    		$_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
    		$_html['kgNo1_main'] = ($_html['kgNo1_sum']%6)+1;
    		if (in_array($k+1,array(13,14,15,16,17,18))){
    			$_html['kgNo3'] .= "$v,";
    		}
    		$_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
    		$_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
    		$_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
    		$_html['kgNo3_main'] = ($_html['kgNo3_sum']%6)+1;
    		$_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
    	}
    	$RetContent .= "\t\t<tr><td colspan='4'>蛋蛋11 第 $id 期开奖结果</td></tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
    	$RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
    	$RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>区位</td>\r\n";
    	$RetContent .= "\t\t\t<td>第一区[第1/2/3/4/5/6位数字]</td>\r\n";
    	$RetContent .= "\t\t\t<td>第三区[第13/14/15/16/17/18位数字]</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>数字</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>求和</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>计算</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."除以6的余数 + 1</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."除以6的余数 + 1</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>计算</td>\r\n";
    	$RetContent .= "\t\t\t<td>取尾数</td>\r\n";
    	$RetContent .= "\t\t\t<td>取尾数</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>结果</td>\r\n";
    	$RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo1_main']."'></li></td>\r\n";
    	$RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo3_main']."'></li></td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>开奖</td>\r\n";
    	$RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$_html['kgNo1_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo3_main']."'></li><i class=\"hdeng\"></i><li class='mh m".$_html['total']."'></li></td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "40")
    {
    	$sql = "SELECT kgtime,kgNo FROM game16 WHERE id = '{$id}'";
    	$rs=$db->fetch_first($sql);
    	$_html['kgtime'] = $rs['kgtime'];
    	$_html['kgNo'] = explode('|',$rs['kgNo']);
    	$str = '';
    	$str .= "<span>";
    	foreach($_html['kgNo'] as $k => $v){
    		if (in_array($k+1,array(1,2,3,4,5,6))) $str.= "<span class='red'>$v</span> ";
    		if (in_array($k+1,array(7,8,9,10,11,12))) $str.= "<span class='blue'>$v</span> ";
    		if (in_array($k+1,array(13,14,15,16,17,18))) $str.= "<span class='brown'>$v</span> ";
    		if (in_array($k+1,array(19,20))) $str.="<span class='grey'>$v</span> ";
    	}
    	$str .= "</span>";
    	foreach($_html['kgNo'] as $k=> $v){
    		if (in_array($k+1,array(1,2,3,4,5,6))){
    			$_html['kgNo1'] .= "$v,";
    		}
    		$_html['kgNo1_v'] =substr($_html['kgNo1'],0,-1);
    		$_html['kgNo1_array'] = explode(',',$_html['kgNo1']);
    		$_html['kgNo1_sum'] = array_sum($_html['kgNo1_array']);
    		$_html['kgNo1_main'] = ($_html['kgNo1_sum']%6)+1;
    		if (in_array($k+1,array(7,8,9,10,11,12))){
    			$_html['kgNo2'] .= "$v,";
    		}
    		$_html['kgNo2_v'] =substr($_html['kgNo2'],0,-1);
    		$_html['kgNo2_array'] = explode(',',$_html['kgNo2']);
    		$_html['kgNo2_sum'] = array_sum($_html['kgNo2_array']);
    		$_html['kgNo2_main'] = ($_html['kgNo2_sum']%6)+1;
    		if (in_array($k+1,array(13,14,15,16,17,18))){
    			$_html['kgNo3'] .= "$v,";
    		}
    		$_html['kgNo3_v'] =substr($_html['kgNo3'],0,-1);
    		$_html['kgNo3_array'] = explode(',',$_html['kgNo3']);
    		$_html['kgNo3_sum'] = array_sum($_html['kgNo3_array']);
    		$_html['kgNo3_main'] = ($_html['kgNo3_sum']%6)+1;
    		$_html['total'] = ($_html['kgNo1_main']+$_html['kgNo2_main']+$_html['kgNo3_main']);
    	}
    	$RetContent .= "\t\t<tr><td colspan='4'>蛋蛋16 第 $id 期开奖结果</td></tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
    	$RetContent .= "\t\t\t<td colspan='2'>".$_html['kgtime']."</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
    	$RetContent .= "\t\t\t<td colspan='2'>$str</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>区位</td>\r\n";
    	$RetContent .= "\t\t\t<td>第一区[第1/2/3/4/5/6位数字]</td>\r\n";
    	$RetContent .= "\t\t\t<td>第二区[第7/8/9/10/11/12位数字]</td>\r\n";
    	$RetContent .= "\t\t\t<td>第三区[第13/14/15/16/17/18位数字]</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>数字</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo1_v']."</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo2_v']."</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo3_v']."</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>求和</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>计算</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo1_sum']."除以6的余数 + 1</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo2_sum']."除以6的余数 + 1</td>\r\n";
    	$RetContent .= "\t\t\t<td>".$_html['kgNo3_sum']."除以6的余数 + 1</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>计算</td>\r\n";
    	$RetContent .= "\t\t\t<td>取尾数</td>\r\n";
    	$RetContent .= "\t\t\t<td>取尾数</td>\r\n";
    	$RetContent .= "\t\t\t<td>取尾数</td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>结果</td>\r\n";
    	$RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo1_main']."'></li></td>\r\n";
    	$RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo2_main']."'></li></td>\r\n";
    	$RetContent .= "\t\t\t<td><li class='kj kj_".$_html['kgNo3_main']."'></li></td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    	$RetContent .= "\t\t<tr>\r\n";
    	$RetContent .= "\t\t\t<td>开奖</td>\r\n";
    	$RetContent .= "\t\t\t<td colspan=3><li class='kj kj_".$_html['kgNo1_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo2_main']."'></li><i class=\"hja\"></i><li class='kj kj_".$_html['kgNo3_main']."'></li><i class=\"hdeng\"></i><li class='mh m".$_html['total']."'></li></td>\r\n";
    	$RetContent .= "\t\n</tr>\r\n";
    }
    
    
    
    /*
    else if($act == "13") //加拿大36 
    {
        $RetContent .= "\t\t<tr><td colspan='4'>采用加拿大快乐8数据，每4分钟一期，每天336期，每天19:00-20:30暂停开奖</td></tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td width='120'>如第1773065期</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>06,15,32,40,53,64</td>\r\n";
        $RetContent .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
        $RetContent .= "\t\t\t<td>13,31,35,44,62,68</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>210</td>\r\n";
        $RetContent .= "\t\t\t<td>233</td>\r\n";
        $RetContent .= "\t\t\t<td>253</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_0'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_0'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hdeng\"></i><li class='zh z2'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='4' style='color:#F90; font-weight:bold'>游戏结果说明</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;结果优先顺序：豹 > 顺 > 对 > 半 > 杂</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z1'></li>  3个结果号码相同，如222,333,999</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z3'></li>  3个结果号码从小到大排序后，号码都相连，如231,765,645.特例:排序后019算顺子</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z2'></li>  3个结果号码只有两个相同，如535,337,899</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z4'></li>  3个结果号码只有任意两个是相连的,不包含顺、对，如635,367,874.特例:包含0和9也算顺子</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z5'></li>  3个结果号码没有任何关联，如638,942,185</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "8") //加拿大28
    {
        $RetContent .= "\t\t<tr><td colspan='4'>首尔16 第768404 期开奖结果</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>2016-07-05 11:50:00</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>05,09,10,12,18,21　24,28,29,34,47,49　55,58,60,72,73,74</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>05,12,24,34,55,72</td>\r\n";
        $RetContent .= "\t\t\t<td>09,18,28,47,58,73</td>\r\n";
        $RetContent .= "\t\t\t<td>10,21,29,49,60,74</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>202</td>\r\n";
        $RetContent .= "\t\t\t<td>233</td>\r\n";
        $RetContent .= "\t\t\t<td>243</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_0'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_9'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_0'></li><i class=\"hja\"></i><li class='kj kj_9'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hdeng\"></i><li class='mh m12'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "9") //加拿大16
    {
        $RetContent .= "\t\t<tr><td colspan='4'>首尔16 第768404 期开奖结果</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>2016-07-05 11:50:00</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>05,09,10,12,18,21　24,28,29,34,47,49　55,58,60,72,73,74</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>05,12,24,34,55,72</td>\r\n";
        $RetContent .= "\t\t\t<td>09,18,28,47,58,73</td>\r\n";
        $RetContent .= "\t\t\t<td>10,21,29,49,60,74</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>202</td>\r\n";
        $RetContent .= "\t\t\t<td>233</td>\r\n";
        $RetContent .= "\t\t\t<td>243</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_0'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_9'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_0'></li><i class=\"hja\"></i><li class='kj kj_9'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hdeng\"></i><li class='mh m12'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "10") // 加拿大11
    {
        $RetContent .= "\t\t<tr><td colspan='4'>首尔16 第768404 期开奖结果</td></tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖时间</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>2016-07-05 11:50:00</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>开奖号码</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>05,09,10,12,18,21　24,28,29,34,47,49　55,58,60,72,73,74</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>05,12,24,34,55,72</td>\r\n";
        $RetContent .= "\t\t\t<td>10,21,29,49,60,74</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>202</td>\r\n";
        $RetContent .= "\t\t\t<td>243</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>218除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\t\t<td>218除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_0'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_0'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hdeng\"></i><li class='mh m12'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "18") //首尔28
    {
        $RetContent .= "\t\t<tr><td colspan='4'>采用首尔快乐8数据，每1分半钟一期，每天5:00-70:00暂停开奖</td></tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td width='120'>如第1234567期</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>06,15,32,40,53,64</td>\r\n";
        $RetContent .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
        $RetContent .= "\t\t\t<td>13,31,35,44,62,68</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>210</td>\r\n";
        $RetContent .= "\t\t\t<td>233</td>\r\n";
        $RetContent .= "\t\t\t<td>253</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_0'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_0'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hdeng\"></i><li class='mh m6'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "19") //首尔16
    {
        $RetContent .= "\t\t<tr><td colspan='4'>采用首尔快乐8数据，每1分半钟一期，每天5:00-70:00暂停开奖</td></tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td width='120'>如第1234567期</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>01,13,31,35,44,62</td>\r\n";
        $RetContent .= "\t\t\t<td>06,15,32,40,53,64</td>\r\n";
        $RetContent .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>186</td>\r\n";
        $RetContent .= "\t\t\t<td>210</td>\r\n";
        $RetContent .= "\t\t\t<td>233</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>186除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\t\t<td>210除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\t\t<td>233除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_6'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_6'></li><i class=\"hdeng\"></i><li class='mh m8'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "20") //首尔11
    {
        $RetContent .= "\t\t<tr><td colspan='3'>采用首尔快乐8数据，每1分半钟一期，每天5:00-70:00暂停开奖</td></tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td width='120'>如第1234567期</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='2'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第1/4/7/10/13/16位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>01,13,31,35,44,62</td>\r\n";
        $RetContent .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>186</td>\r\n";
        $RetContent .= "\t\t\t<td>233</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>186除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\t\t<td>233除以6的余数 + 1</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_1'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_6'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_1'></li><i class=\"hja\"></i><li class='kj kj_6'></li><i class=\"hdeng\"></i><li class='mh m7'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    else if($act == "21") //首尔36 
    {
        $RetContent .= "\t\t<tr><td colspan='4'>采用首尔快乐8数据，每1分半钟一期，每天5:00-70:00暂停开奖</td></tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td width='120'>如第1234567期</td>\r\n";
        $RetContent .= "\t\t\t<td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>区位</td>\r\n";
        $RetContent .= "\t\t\t<td>第一区[第2/5/8/11/14/17位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第二区[第3/6/9/12/15/18位数字]</td>\r\n";
        $RetContent .= "\t\t\t<td>第三区[第4/7/10/13/16/19位数字]</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>数字</td>\r\n";
        $RetContent .= "\t\t\t<td>06,15,32,40,53,64</td>\r\n";
        $RetContent .= "\t\t\t<td>07,28,33,43,57,65</td>\r\n";
        $RetContent .= "\t\t\t<td>13,31,35,44,62,68</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>求和</td>\r\n";
        $RetContent .= "\t\t\t<td>210</td>\r\n";
        $RetContent .= "\t\t\t<td>233</td>\r\n";
        $RetContent .= "\t\t\t<td>253</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>计算</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\t\t<td>取尾数</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>结果</td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_0'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
        $RetContent .= "\t\t\t<td><li class='kj kj_3'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td>开奖</td>\r\n";
        $RetContent .= "\t\t\t<td colspan=3><li class='kj kj_0'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hja\"></i><li class='kj kj_3'></li><i class=\"hdeng\"></i><li class='zh z2'></li></td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='4' style='color:#F90; font-weight:bold'>游戏结果说明</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;结果优先顺序：豹 > 顺 > 对 > 半 > 杂</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z1'></li>  3个结果号码相同，如222,333,999</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z3'></li>  3个结果号码从小到大排序后，号码都相连，如231,765,645.特例:排序后019算顺子</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z2'></li>  3个结果号码只有两个相同，如535,337,899</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z4'></li>  3个结果号码只有任意两个是相连的,不包含顺、对，如635,367,874.特例:包含0和9也算顺子</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";

        $RetContent .= "\t\t<tr>\r\n";
        $RetContent .= "\t\t\t<td colspan='4' style='text-align:left'>&nbsp;<li class='zh z5'></li>  3个结果号码没有任何关联，如638,942,185</td>\r\n";
        $RetContent .= "\t\n</tr>\r\n";
    }
    */
    $RetContent .= '</tbody>';
    $RetContent .= '</table>';
    $RetContent .= '</div>';
    $RetContent .= '</div>';
    $RetContent .= '</div>';
    $RetContent .= '</div>';
    echo $RetContent;
    exit;
}





?>