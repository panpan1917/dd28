<?php

class GameAction extends GamebaseAction
{
    function __construct()
    {
        parent::__construct();
    }
    
    function index(){
        $id=Req::get('id','intval');
        $this->assign('id',$id);
        $this->display('game_index');
    }
    function game(){
        $id=Req::get('id','intval');
        $id = (int)$id;
        $this->assign('id',$id);
        $games=$this->GetGameConfig($id);
        $sql='select game_std_odds,game_std_press,game_model,game_go_samples from game_config where game_type='.$id;
        $press=db::get_one($sql);
        $this->assign('press',explode(',',$press->game_std_press));//标准投注额
        $step = $this->GetFromBeginNumStep($id);
        $this->assign('step',$step);//步进
        $this->assign('game_name',$games['game_name']);
        $gconfig=$this->get_config($id);
        $this->assign('pl',$gconfig);

        if(in_array($id,[16,47])){//PK龙虎 飞艇龙虎
            $in='n';
        }elseif(in_array($id,[11,12,13,21,23])){//36游戏
            $in='3';
        }
        $this->assign('in',$in);
		if($id==29){//北京赛车
            $pressoption=explode(',',$press->game_model);
            $this->assign('pressoption',$pressoption);
            $odds=explode('|',$press->game_std_odds);
            foreach($odds as $idx=>$odd){
            	$odds[$idx] = $odd * 1;
            }
            $this->assign('odds',$odds);//显示固定赔率
            return $this->display('game_sc');
        }elseif(in_array($id,[37])){//时时彩
            $pressoption=explode(',',$press->game_model);
            $this->assign('pressoption',$pressoption);
            $odds=explode('|',$press->game_std_odds);
            foreach($odds as $idx=>$odd){
            	$odds[$idx] = $odd * 1;
            }
            $this->assign('odds',$odds);//显示固定赔率
            return $this->display('game_ssc');
        }elseif(in_array($id,[25,27,30,41])){//蛋蛋外围,加拿大外围,韩国外围,北京外围
            $pressoption=explode(',',$press->game_model);
            $this->assign('pressoption',$pressoption);
            $odds=explode('|',$press->game_std_odds);
            foreach($odds as $idx=>$odd){
            	$odds[$idx] = $odd * 1;
            }
            $this->assign('odds',$odds);//显示固定赔率
            return $this->display('game_ww');
        }elseif(in_array($id,[26,28,31,42])){//蛋蛋定位,加拿大定位,韩国定位,北京定位
        	$pressoption=explode(',',$press->game_model);
            $this->assign('pressoption',$pressoption);
            $odds=explode('|',$press->game_std_odds);
            foreach($odds as $idx=>$odd){
            	$odds[$idx] = $odd * 1;
            }
            $this->assign('odds',$odds);//显示固定赔率
            return $this->display('game_dw');
        }elseif(in_array($id,[11,12,13,21,23])){//36游戏
        	$this->assign('pressoption',['豹','对','顺','半','杂']);
        	$odds=explode('|',$press->game_std_odds);
			foreach($odds as $idx=>$odd){
        		$odds[$idx] = floor($odd*(10000-$press->game_go_samples - rand(-90,-50))/10000 * 10) / 10;
        	}
        	$this->assign('odds',$odds);//显示浮动赔率
            return $this->display('game_36');
        }elseif(in_array($id,[16,47])){//PK龙虎和飞艇龙虎
        	$this->assign('pressoption',['龙','虎']);
        	$odds=explode('|',$press->game_std_odds);
			foreach($odds as $idx=>$odd){
        		$odds[$idx] = floor($odd*(10000-$press->game_go_samples - rand(-90,-50))/10000 * 10) / 10;
        	}
        	$this->assign('odds',$odds);//显示浮动赔率
            return $this->display('game_lh');
        }else {
        	$odds=explode('|',$press->game_std_odds);
			foreach($odds as $idx=>$odd){
        		if(in_array($id,[32,33,34,35]))//固定28
        			$odds[$idx] = floor($odd*(10000-$press->game_go_samples)/10000 * 10) / 10;
        		else		
        			$odds[$idx] = floor($odd*(10000-$press->game_go_samples - rand(-90,-50))/10000 * 10) / 10;
        	}
        	$this->assign('odds',$odds);//显示浮动赔率
            return $this->display('game');
        }
    }
    function press(){
        $no=Req::get('no','intval');
        $id=Req::get('id','intval');
        $this->assign('id',$id);
        $LastNo=$no-1;
        $arrCurNoInfo = array('preno'=>'','prekgtime'=>'','game_kj_delay'=>'','game_tz_close'=>'');
        $this->GetHeadContent($id,$sid=1,$arrCurNoInfo);
        //var_dump($head);
        /*$kjSec = $arrCurNoInfo['prekgtime'] + $arrCurNoInfo['game_kj_delay'];
        $StopSec = $arrCurNoInfo['prekgtime'] - $arrCurNoInfo['game_tz_close'];
        $this->assign('kjSec',$kjSec);
        $this->assign('StopSec',$StopSec);
        $this->assign('last_lotto',$head['last_lotto']);*/

        $result=$this->GetOdds($id, $no, $LastNo);
        $this->assign('pl',$this->get_config($id));
        $this->assign('no',$no);
        $this->assign('tzpoints',array_sum($result['press']));//已下注金豆
        $this->assign('list',$result);
        $games=$this->GetGameConfig($id);
        $this->assign('game_name',$games['game_name']);
        $info=$this->RefreshPoints();
        $this->assign('money',$info);
        return $this->display('game_press');
    }
    function ajax(){
        $id=Req::get('id','intval');
        $arrCurNoInfo = array('preno'=>'','prekgtime'=>'','game_kj_delay'=>'','game_tz_close'=>'');
        $arrCurNoInfo=$this->GetKjTzTime($id);
        $data=$this->GetTableContent($id,1 ,20 , $arrCurNoInfo);
        $info=$this->RefreshPoints();
        $data['points']=$this->info['points'];
        $data['bank']=$this->info['bankpoints'];

        if(in_array($id,array(11,12,13,21,23))){//36游戏
            $data['type']='3';
        }elseif(in_array($id,array(16,47))){//PK龙虎 飞艇龙虎
            $data['type']='n';
        }else{
            $data['type']='';
        }

        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }
    function AjaxCurrNo(){
        $no=Req::get('no','intval');
        $id=Req::get('id','intval');
        $currNo=$this->GetCurrNo($id,$no);
        
        echo json_encode($currNo,JSON_UNESCAPED_UNICODE);
    }
    
    
    function PostPress(){
        $No=(int)Req::post('no','intval');
        $press=Req::post('press');
        $GameType=(int)Req::post('gtype','intval');
        
        if($this->parameterCheck($press)){
        	return $this->result(1,'参数错误');
        }
        
        $Press=explode(',',$press);


        $arrRet = array('cmd'=>'ok','msg'=>'');
        $procedue = "";
        //判断投注串合法性
        if(!$this->CheckPressStrValid($GameType,$Press)) {
            return $this->result(1,'投注验证失败!');
        }
        //判断下注间隔时间
        if(isset($_SESSION["pressinterval"]) && isset($_SESSION["lastpresstime"])) {
                if( strtotime(date('Y-m-d H:i:s',time())) - $_SESSION["lastpresstime"] <= $_SESSION["pressinterval"] ) {
                    return $this->result(1,'下注太频繁了!');
                }
        }else{
            $sql = "select fldValue from sys_config where fldVar in('game_press_interval')";
            $result=db::get_one($sql,'assoc');
            $_SESSION["pressinterval"] = $result["fldValue"];
        }

        //判断游戏是否允许下注
        $sql = "select fldVar,fldValue from sys_config where fldVar in('game_open_flag','game_shutdown_reason') order by fldIdx";
        $result=db::get_one($sql,'assoc');
            if($result["fldValue"] == "1"){
                return $this->result(1,"游戏已停止下注,原因:" . $result["fldValue"]);
            }

        //判断单个游戏是否允许下注
        $sql = "select isstop,stop_msg from game_config where game_type = '{$GameType}'";
        $result=db::get_one($sql,'assoc');
            if($result["isstop"] == 1){
                return $this->result(1,'游戏已停止下注,原因:'.$result['stop_msg']);
            }
        //判断用户
        $sql = "select dj,isagent from users where id = '{$_SESSION['usersid']}'";
        $result=db::get_one($sql,'assoc');
        if($result["isagent"] == 1){
            return $this->result(1,'为了安全，代理绑定帐号禁止玩游戏');
        }
        if($result["dj"] == 1){
            return $this->result(1,'帐号已被冻结');
        }

        if($this->CheckAutoPress($GameType)){
            return $this->result(1,'您已经设置了自动投注！请先取消');
        }
        $step = $this->GetFromBeginNumStep($GameType);
        $procedue = "web_tz_" . $this->GetGameTableName($GameType,"game");

        if($procedue == "web_tz_"){
            return $this->result(1,'游戏类型错误!');
        }
        $sumScore = 0;
        $arrPress = $Press;

        $PressStr = "";
        for($i = 0 ; $i < count($arrPress); $i++)
        {
            if($arrPress[$i] != "" && intval($arrPress[$i]) > 0)
            {
                //$arrPress[$i]*=1000;
                $PressStr .= ($i+$step) . "," . $arrPress[$i] . "|";
                $sumScore += intval($arrPress[$i]);
            }
        }
        if($PressStr == ""){
            return $this->result(1,'您还没有投注!');
        }
        $PressStr = substr($PressStr,0,-1);

        if($this->CheckGameTimeout($GameType,$No)){
            return $this->result(1,'本期投注时间已过，请选其它期!');
        }
        
        
        
        
        
        
        /*
        //禁止翻倍加码投注
        $secnum = 360;
        if(in_array($GameType,[3,4,5,11,12,25,26,32,33])) $secnum = 1800;//北京，蛋蛋
        if(in_array($GameType,[6,7,14,16,17,29])) $secnum = 1800;//PK
        if(in_array($GameType,[8,9,10,13,27,28,35])) $secnum = 1260;//加拿大
        if(in_array($GameType,[18,19,20,21,30,31,34])) $secnum = 540;//韩国
        if(in_array($GameType,[36])) $secnum = 3600;//农场
        
        $table_users_tz = $this->GetGameTableName($GameType,"users_tz");
        $sql = "select * from {$table_users_tz} where uid={$_SESSION['usersid']}
        			AND UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(time) <= {$secnum}
        			order by id desc limit 5";
        $list = db::get_all($sql,'assoc');
        
        $countNum = 0;
        $lastNO = 0;
        $lasttznum = "";
        $lastpoints = 0;
        $break = false;
        
        if(count($list) > 0){
        	foreach ($list as $key => $row) {
        		$qihao[$key] = $row['NO'];
        	}
        	array_multisort($qihao, SORT_ASC, $list);
        			
        	$tmptznum = array();
        	foreach ($list as $key => $row) {
        		if(count($tmptznum) > 0){
	        		$_exist = false;
	        		foreach($tmptznum as $_tmptznum){
		        		if(stristr($row['tznum'] , $_tmptznum) !== FALSE){
		        			$_exist = true;
		        		}
	        		}
        		}
        
	        	if($countNum > 0 && abs($row['NO'] - $lastNO) == 1 && $_exist && $row['points'] <= $lastpoints){
	        		break;
	        	}
        
	        	$lastNO = $row['NO'];
				$lasttznum = $row['tznum'];
	        	$lastpoints = $row['points'];
	        
	        	$tmptznum[] = $row['tznum'];
	        					
	        	$countNum++;
        	}
        }
        
		if($countNum >= 5){
        	$break = true;
        }
        
        if($break){
        	return $this->result(1,'系统检测到非法投注，请重新投注!');
        }
        //禁止翻倍加码投注
        */
        
        
        
        
        
        
        
        
        
        //$sql = "insert into presslog(uid,no,gametype,pressStr,totalscore) values({$_SESSION['usersid']},{$No},{$GameType},'{$PressStr}',{$sumScore})";
        //db::_query($sql);
        
        //保存
        $sql = "call {$procedue}({$_SESSION['usersid']},{$No},{$sumScore},0,'{$PressStr}')";
        $arr = db::get_all($sql,'assoc');
        switch($arr[0]["result"])
        {
            case '0': //成功
                $_SESSION["points"] = $arr[0]["points"];
                $_SESSION["bankpoints"] = $arr[0]["back"];
                $_SESSION["lastpresstime"] = strtotime(date('Y-m-d H:i:s',time()));
                $arrRet['cmd'] = "0";
                $arrRet['msg'] = number_format($arr[0]["points"]) . "|" . number_format($arr[0]["points"]);
                break;
            case '1': //投注额过小
                $arrRet['cmd'] = "1";
                $arrRet['msg'] = "您的投注额小于最小限制" . $web_presspoint_game28_min;
                break;
            case '2': //余额不足
                $arrRet['cmd'] = "1";
                $arrRet['msg'] = "您的余额不足!";
                break;
            case '3': //核对投注额失败
                $arrRet['cmd'] = "1";
                $arrRet['msg'] = "您的实际投注额核对失败!";
                break;
            case '4': //已开奖过了
                $arrRet['cmd'] = "1";
                $arrRet['msg'] = "本期已开奖过了!";
                break;
            case '5': //投注额低于最小限制
                $arrRet['cmd'] = "1";
                $arrRet['msg'] = $arr[0]["msg"];
                break;
            case '6': //投注额大于限制
                $arrRet['cmd'] = "1";
                $arrRet['msg'] = $arr[0]["msg"];
                break;
            case '99': //数据库错误
                $arrRet['cmd'] = "1";
                $arrRet['msg'] = "系统错误，投注失败，请稍后再试!";
                break;
            default:
                $arrRet['cmd'] = "1";
                $arrRet['msg'] = "未知错误!";
                break;
        }

        return $this->result($arrRet['cmd'],$arrRet['msg']);
    }

    function GetTableContent($act,$page,$pagesize,$arrnoinfo)
    {
        $tablegame = $this->GetGameTableName($act,"game");
        $tablegametz = $this->GetGameTableName($act,"users_tz");
        $tablegamekg = $this->GetGameTableName($act,"kg_users_tz");
        $MinuteAdd = 15; //北京数据源
        if(in_array($act,array(0,1,2,15,22,23,24))) { //急速
            $MinuteAdd = "3";
        }elseif(in_array($act,array(8,9,10,13,35,27,28))){//加拿大源
            $MinuteAdd = "13";
        }elseif(in_array($act,array(18,19,20,21,34,30,31))) {//韩国源
            $MinuteAdd = "5";
        }elseif(in_array($act,array(37))) {//时时彩
            $MinuteAdd = "30";
        }
        
        $sql="select t.id,t.kgtime,now() as nowtime,t.kj,t.kgjg,kgNo,ifnull(tz.points,0) as points,ifnull(tz.hdpoints,0) as hdpoints from {$tablegame} as t left join {$tablegametz} tz on tz.NO=t.id and tz.uid={$_SESSION[usersid]}  where t.kgtime < DATE_ADD(NOW(),INTERVAL {$MinuteAdd} MINUTE) order by id desc limit 16";
        $list=db::get_all($sql,'assoc');
        $result=[];
        foreach ($list as $k=>$v){
            $result[$k]['sid']=substr($v['id'],-4);
            $result[$k]['id']=$v['id'];
            $result[$k]['kj']=$v['kj'];
            $t=strtotime($v['kgtime'])-strtotime($v['nowtime']);
            $result[$k]['stoptime'] = $t - $arrnoinfo['game_tz_close'];
            $result[$k]['kjtime']=$t + $arrnoinfo['game_kj_delay'];//-$result[$k]['stoptime'];
            $result[$k]['kjjg']=$arrTmpKg=explode('|', $v['kgjg']);//开奖结果
            
            if(in_array($act,array(25,27,30,41)) && count($arrTmpKg)==4){//外围游戏
            	$result[$k]['kjjg2']=$this->getGameWWResult($arrTmpKg[0],$arrTmpKg[1],$arrTmpKg[2]);
            }
            
            if(in_array($act,array(26,28,31,42)) && count($arrTmpKg)==4){//定位游戏
            	$result[$k]['kjjg2']=$this->getGameDWResult($arrTmpKg[0],$arrTmpKg[1],$arrTmpKg[2]);
            }
            
            if(in_array($act,array(29)) && count($arrTmpKg)==4){//赛车游戏
            	$kjNoArr = explode("|", $v['kgNo']);
            	$result[$k]['kjjg2']=$this->getGameSCResult($kjNoArr);
            }
            
            if(in_array($act,array(37)) && count($arrTmpKg)==6){//时时彩
            	$kjNoArr = explode("|", $v['kgNo']);
            	$result[$k]['kjjg2']=$this->getGameCqsscResult($kjNoArr);
            }
            
            if(count($result[$k]['kjjg'])==3){
                $result[$k]['kjjg'][2]=$result[$k]['kjjg'][2].'22';
            }elseif(count($result[$k]['kjjg'])==4){
                $result[$k]['kjjg'][3]=$result[$k]['kjjg'][3].'22';
            }

            if(count($arrTmpKg)==4) {
                $result[$k]['num'] = $arrTmpKg[3];
            }else{
                $result[$k]['num']='';
            }
            //$result[$k]['kjjg']=$v['kgjg'];
            $result[$k]['kgtime']=date('H:i:s',strtotime($v['kgtime']));
            if($v['kj'] == 0)
            {
                $sql='select sum(tzpoints) as point from '.$tablegamekg.' where uid=\''.$_SESSION['usersid'].'\' and NO=\''.$v['id'].'\'';
                $point=db::get_one($sql);
                $v['points']=$point->point;
                if( (($arrnoinfo['prekgtime']) <= $arrnoinfo['game_tz_close']) && $v['id'] == $arrnoinfo['preno'] ) //正在开奖
                {
                    $result[$k]['status']=3;//
                }else{

                    if($this->DateDiff($v["kgtime"],$v["nowtime"],"s") > 0)
                    {
                        $result[$k]['status']=2;//投注
                    }else{

                        $result[$k]['status']=3;
                    }
                }
                $TmpKaiNum = "";
            }else{
                $result[$k]['status']=1;//已开奖

            }
            $result[$k]['points']=ceil($v['points']/1000);//显示金额，不显示分数
            $result[$k]['hdpoints']=ceil($v['hdpoints']/1000);//显示金额，不显示分数

        }

        return array('list'=>$result);
    }
    function item(){
        $id=Req::get('id','intval');
        $No=Req::get('no','intval');
        $this->assign('no',$No);
        $tabletz = $this->GetGameTableName($id,'users_tz');
        $tablegame = $this->GetGameTableName($id,"game");
        if($tabletz == "")
            return "提交参数错误！";
        //取押注情况
        $sql = "SELECT tz.tznum,tz.tzpoints,tz.points,tz.hdpoints,tz.zjpoints,tz.zjpl,g.zjpl,g.kj,g.kgtime,g.kgjg FROM {$tabletz} tz right join {$tablegame} g on g.id=tz.NO and tz.uid = '{$_SESSION['usersid']}' WHERE g.id = '{$No}'";
        $result=db::get_one($sql,'assoc');
        if(is_array($result)){
            $arrtznum = explode("|",$result['tznum']);
            $arrtzpoints = explode("|",$result["tzpoints"]);
            $arrzjpoints = explode("|",$result['zjpoints']);
            $hdpoints = $result['hdpoints'];
            $points = $result['points'];
            $zjpl=$result['zjpl'];
            $arrRwardOdds = explode("|",$result['zjpl']);
            $kgtime = $result['kgtime'];
            $this->assign('arrtznum',$arrtznum);
            $this->assign('arrtzpl',explode("|",$result['zjpl']));
            if($result['kj']==1) {
                $kjjg = explode('|', $result['kgjg']);
                if(in_array($id,array(16,47))){//PK龙虎 飞艇龙虎
                    if($kjjg[3] == 1){ //龙
                        $jg=$kjjg[0].' > '.$kjjg[1].' = 龙';
                    }else{ //虎
                        $jg=$kjjg[0].' > '.$kjjg[1].' = 虎';
                    }

                }elseif (in_array($id, array(15,6,7,43,46))) {//急速10 PK10 PK冠军 飞艇10 飞艇冠军
                    $jg = $kjjg[0];
                }elseif (in_array($id,array(2,10,17,20,24,38,39,45))){//11游戏 急速冠亚军 飞艇冠亚军 PK冠亚军
                    $jg=$kjjg[0].' + '.$kjjg[1].' = '.$kjjg[3];
                }elseif (in_array($id,array(0,1,3,4,5,8,9,14,18,19,22,32,33,34,35,40,44))){//16,28,22游戏
                    $jg=$kjjg[0].' + '.$kjjg[1].' + '.$kjjg[2].' = '.$kjjg[3];
                }elseif (in_array($id,array(11,12,13,21,23))){
                    switch($kjjg[3])
                    {
                        case 1:
                            $kjFinal = "豹";
                            break;
                        case 2:
                            $kjFinal = "对";
                            break;
                        case 3:
                            $kjFinal = "顺";
                            break;
                        case 4:
                            $kjFinal = "半";
                            break;
                        case 5:
                            $kjFinal = "杂";
                            break;
                        default:
                            $kjFinal = "";
                            break;
                    }
                    $jg=$kjjg[0].' + '.$kjjg[1].' + '.$kjjg[2].' = '.$kjFinal;
                }
            }else{
                $jg='未开奖';
            }
            $this->assign('jg',$jg);
        }else{
            return "<p>很抱歉,无投注记录！</p>";
        }
        //取标准赔率
        $reward_num_type = $this->GetGameOddsType($id);

        $sql = "SELECT GROUP_CONCAT(num SEPARATOR '|') AS strnum,GROUP_CONCAT(odds SEPARATOR '|') AS strodds FROM gameodds WHERE game_type = '{$reward_num_type}' ORDER BY num";
        $result=db::get_one($sql,'assoc');
        if(is_array($result)) {
            $arrStdNums = explode("|",$result['strnum']);
            $arrStdOdds = explode("|",$result['strodds']);
        }else{
            return "无法取得标准赔率";
        }
        $this->assign('Nums',$arrStdNums);
        $this->assign('Odds',$arrStdOdds);
        //重新格式化押注情况
        $arrNewtz = array();
        $arrNewhdPoints = array();
        foreach($arrStdNums as $num)
        {
            $arrNewtz[$num] = 0;
            $arrNewhdPoints[$num] = 0;
        }
        for($i = 0; $i < count($arrtznum); $i++)
        {
            $arrNewtz[$arrtznum[$i]] = $arrtzpoints[$i];
            $arrNewhdPoints[$arrtznum[$i]] = $arrzjpoints[$i];
        }

        $this->assign('arrNewhdPoints',$arrNewhdPoints);
        $this->assign('arrNewtz',$arrNewtz);
        $games=$this->GetGameConfig($id);
        $this->assign('game_name',$games['game_name']);
        //$info=$this->RefreshPoints();
        //$this->assign('money',$info);
        return $this->display('game_item');
    }

    function GetOdds($act,$No,$LastNo)
    {
        $tablegame = $this->GetGameTableName($act,"game");
        $tablegamekg = $this->GetGameTableName($act,"kg_users_tz");
        //号码表格
        //取赔率
        $sql = "select zjpl from {$tablegame} where id in({$No},{$LastNo}) order by id";
        $list=db::get_all('select tzpoints,zjpl from '.$tablegame.' where id in('.$No.','.$LastNo.')','assoc');
        if(is_array($list)) {
            $curOdds = $list[1]['zjpl'];
            $lastOdds = $list[0]['zjpl'];
        }
        $currPoint=$list[1]['tzpoints'];
        $arrCurOdds = explode('|',$curOdds);
        $arrLastOdds = explode('|',$lastOdds);
        //WriteLog($curOdds);
        //取已投注
        $arrHadPress = array();
        $step = $this->GetFromBeginNumStep($act);
        for($i = 0; $i < count($arrCurOdds); $i++){
            $arrHadPress[] = 0;
        }
        $sql='select tznum,tzpoints from '.$tablegamekg.' where NO='.$No.' and uid='.$_SESSION['usersid'];
        $list=db::get_all($sql,'assoc');
        foreach ($list as $k=>$v){
            $arrHadPress[$v['tznum']-$step] = $v['tzpoints'];
        }
        if(in_array($act,[16,47])){//PK龙虎 飞艇龙虎
            $in='n';
        }elseif(in_array($act,[11,12,13,21,23])){//36游戏
            $in='3';
        }
        return array('currOdds'=>$arrCurOdds,'currPoint'=>$currPoint,'press'=>$arrHadPress,'step'=>$step,'in'=>$in);
    }

    /*
        *
        */
    function CheckPressStrValid($gametype,&$press)
    {
        $ret = false;
        $sql = "select reward_num from game_config where game_type = '{$gametype}'";
        $result=db::get_one($sql);
        $pressCount = count($press) - 1;
        if($pressCount == $result->reward_num && $pressCount > 0) {
        	for($i=0;$i<$pressCount;$i++){
			 	/* if(!is_numeric($press[$i])){
			 		return $ret;
			 	} */
        		$press[$i] = (int)$press[$i];
			}
			
            $ret = true;
        }
        return $ret;
    }
    /* 检测是否设置了自动投注
    *
    */
    function CheckAutoPress($t)
    {
        $tableName =$this->GetGameTableName($t,"auto");
        $sql = "select 1 from " . $tableName . " where uid = '{$_SESSION['usersid']}'";
        $result=db::get_one($sql);
        if($result) {
            return true;
        }else {
            return false;
        }
    }

    /* 检测本期投注时间是否已过
    *
    */
    function CheckGameTimeout($t,$no)
    {
        $tableName = $this->GetGameTableName($t,"game");
        $retState = true;
        $sql = "select game_kj_delay,game_tz_close from game_config where game_type = '{$t}'";
        $result=db::get_one($sql,'assoc');
        if(is_array($result))
        {
            $game_kj_delay = $result["game_kj_delay"];
            $game_tz_close = $result["game_tz_close"];
        }
        $no = (int)$no;
        $sql = "select kj,kgtime,now() as servertime from " . $tableName . " where id = '{$no}' and kj=0";
        $result=db::get_one($sql,'assoc');
        if(is_array($result))
        {
        	/* $timediff = $this->DateDiff($result["kgtime"],$result["servertime"],"s");
        	if($timediff <= 0) return true;
        	
            if($result["kj"] == 0 && $timediff - $game_tz_close > 0)
                $retState = false; */
            
            if(strtotime($result["servertime"]) > (strtotime($result["kgtime"])-$game_tz_close)){
            	return true;
            }else{
            	return false;
            }
        }
        return $retState;
    }

    function auto(){
        if(IS_AJAX){
            $act=Req::post('act');
            if($act=='saveautomodel'){
                return $this->SaveAutoModel();
            }elseif ($act=='changautomodel') {
                return $this->ajax_auto();
            }elseif ($act='removeautomodel'){
                return $this->RemoveAutoModel();
            }
        }
        $id=Req::get('id','intval');
        $this->assign('act',$id);
        $tableautotz = $this->GetGameTableName($id,"auto_tz");
        $tableauto = $this->GetGameTableName($id,"auto");
        //取当前自动下注配置
        $sql = "SELECT autoid,startNO,endNO,minG,maxG,start_auto_id FROM {$tableauto} WHERE uid = '{$_SESSION['usersid']}'";                 $curr=db::get_one($sql);
        if(!$curr->autoid) {
            $curr=(object)[];
            //最新开奖号码
            $new_no = $this->GetNewNo($id);
            $curr->num=3000;
            $curr->startNO=$new_no;
            $curr->minG=3000;
            $curr->maxG=999999999;
        }else{
            
            $curr->num=$curr->endNO-$curr->startNO;
        }
        $this->assign('curr',$curr);

        //模式列表
        $sql = "SELECT id,tzname,tzpoints,tzid,winid,lossid FROM {$tableautotz} WHERE uid = '{$_SESSION['usersid']}'";
        $list=db::get_all($sql);
        foreach ($list as $k=>$v){
            $list[$k]->tzpoints=number_format($v->tzpoints);
        }
        $this->assign('list',$list);
        $this->display('game_auto');
    }
    private function ajax_auto(){
        $GameType = intval($_POST["gtype"]);
        $WinOrLossID = intval($_POST['v']);
        $RecID = intval($_POST['cid']);
        $WinOrLossType = Req::post('ct');
        $tableautotz = $this->GetGameTableName($GameType,"auto_tz");
        $sql = "update {$tableautotz} set " . (($WinOrLossType == "win") ? "winid" : "lossid") . " = {$WinOrLossID} where id = {$RecID} and uid = '{$_SESSION['usersid']}'";
        $result = db::_query($sql);
        return $this->result(0,'修改成功');
    }/* 保存自动投注模式
	*
	*/
    private function SaveAutoModel()
    {
        $GameType = intval($_POST["gtype"]);
        $CurNo = intval($_POST['no']);
        $BeginNo = intval($_POST["bno"]);
        $tzCount = intval($_POST["cnt"]);
        $tzMaxG = intval($_POST["maxg"]);
        $tzMinG = intval($_POST["ming"]);
        $BeginRecID = intval($_POST["cid"]);

        $arrRet = array('cmd'=>'ok','msg'=>'');

        if($BeginNo < $CurNo){
            return $this->result(1,'开始期号至少要大于当前的期号!');
        }
        if($tzCount < 10)
        {
            return $this->result(1,'投注期数至少不少于10期!');
        }
        if($tzMinG  < 100)
        {
            return $this->result(1,'乐豆下限至少不少于100');
        }
        if($tzMinG >= $tzMaxG)
        {
            return $this->result(1,'乐豆下限必须要小于上限！');
        }

        $tableautotz = $this->GetGameTableName($GameType,"auto_tz");
        $sql = "select count(id) cnt from {$tableautotz} where id = {$BeginRecID} and uid = '{$_SESSION['usersid']}'";
        $rs = db::get_one($sql);
        if($rs->cnt < 1)
        {
            return $this->result(1,'您所要选择开始的投注模式已不存在，请刷新页面!');
        }
        if($tzMaxG < $_SESSION['points']){
            return $this->result(1,'您当前的乐豆已经大于自动投注设置的上限了，您可以加大上限或者把一部分乐豆转回银行!');
        }

        $table_kg_users_tz = $this->GetGameTableName($GameType,"kg_users_tz");
        $sql = "select max(NO) as maxno from {$table_kg_users_tz} where uid = '{$_SESSION['usersid']}'";
        $rs = db::get_one($sql,'assoc');
        $BeginNo = ($BeginNo > $rs['maxno']) ? $BeginNo : ($rs['maxno']+1);
        $endNo = $BeginNo + $tzCount;
        $tableauto = $this->GetGameTableName($GameType,"auto");
        $sql = "insert into {$tableauto}(startNO,endNO,minG,maxG,autoid,usertype,status,uid,start_auto_id)
				values({$BeginNo},{$endNo},{$tzMinG},{$tzMaxG},{$BeginRecID},0,1,{$_SESSION['usersid']},'{$BeginRecID}')";
        if(db::_query($sql,false)){
            return $this->result(0,'保存成功，系统将从第'.$BeginNo.'期开始为您自动投注(注意:您不在线也会自动投注)');
        }else{
            return $this->result(1,'系统繁忙，保存失败!');
        }
    }

    /* 取消自动投注
    *
    */
    private function RemoveAutoModel()
    {
        $GameType = intval($_POST["gtype"]);
        $tableauto = $this->GetGameTableName($GameType,"auto");
        $arrRet = array('cmd'=>'ok','msg'=>'');

        $sql = "delete from {$tableauto} where uid = '{$_SESSION['usersid']}'";
        if(db::_query($sql,false) > 0)
        {
            return $this->result(0,'ok');
        }
        else
        {
            return $this->result(1,'err');
        }
    }

    /* 修改自动投注模式
    *
    */
    private function ChangeAutoModel()
    {
        global $db;
        $GameType = intval($_POST["gtype"]);
        $WinOrLossID = intval($_POST['v']);
        $RecID = intval($_POST['cid']);
        $WinOrLossType = str_check($_POST['ct']);
        $tableautotz = GetGameTableName($GameType,"auto_tz");
        $arrRet = array('cmd'=>'ok','msg'=>'');

        $sql = "update {$tableautotz} set " . (($WinOrLossType == "win") ? "winid" : "lossid") . " = {$WinOrLossID} where id = {$RecID} and uid = '{$_SESSION['usersid']}'";
        //WriteLog($sql);
        $result = $db->query($sql);
        $arrRet['cmd'] = "ok";
        $arrRet['msg'] = "修改成功";
        echo json_encode($arrRet);
        exit;
    }

    /* 保存模式
    *
    */
    private function SaveModel()
    {
        global $db;
        $GameType = intval($_POST["gtype"]);
        $ID = intval($_POST["thev"]);
        $newName = ChangeEncodeU2G(str_check($_POST["thename"]));
        $newName = substr($newName,0,20);
        $press = str_check($_POST["press"]);
        $totalScore = intval($_POST["total"]);
        $tableautotz = GetGameTableName($GameType,"auto_tz");
        $tableauto = GetGameTableName($GameType,"auto");
        $arrRet = array('cmd'=>'ok','msg'=>'');
        
        $Press=explode(',',$press);
        
        
        $sql = "select count(*) cnt from {$tableautotz} where uid = '{$_SESSION['usersid']}'";
        $result = $db->query($sql);
        $rs = $db->fetch_array($result);
        if($rs["cnt"] >= 5)
        {
        	$arrRet['cmd'] = "err";
        	$arrRet['msg'] = "单个游戏最多只能设置5个模式!";
        	echo json_encode($arrRet);
        	exit;
        }
        
        

        //判断投注串合法性
        if(!$this->CheckPressStrValid($GameType,$Press))
        {
            $arrRet['cmd'] = "err";
            $arrRet['msg'] = "投注验证失败!";
            echo json_encode($arrRet);
            exit;
        }
        //检查是否正在自动投注
        if(CheckAutoPress($GameType))
        {
            $arrRet['cmd'] = "err";
            $arrRet['msg'] = "您当前已设置了自动投注，不允许编辑模式，请先取消自动投注!";
            echo json_encode($arrRet);
            exit;
        }
        //名称是否重名
        $sql = "select count(*) cnt from {$tableautotz} where id <> {$ID} and tzname = '{$newName}' and uid = '{$_SESSION['usersid']}'";
        $result = $db->query($sql);
        $rs = $db->fetch_array($result);
        if($rs["cnt"] > 0)
        {
            $arrRet['cmd'] = "err";
            $arrRet['msg'] = "模式名称重名了，请先修改!";
            echo json_encode($arrRet);
            exit;
        }
        $step = $this->GetFromBeginNumStep($GameType);

        $arrPress = $Press;
        $PressStr = "";
        $sumScore = 0;
        for($i = 0 ; $i < count($arrPress); $i++)
        {
            if($arrPress[$i] != "" && intval($arrPress[$i]) > 0)
            {
                $PressStr .= ($i+$step) . "," . $arrPress[$i] . "|";
                $sumScore += intval($arrPress[$i]);
            }
        }
        if($PressStr == "")
        {
            $arrRet['cmd'] = "err";
            $arrRet['msg'] = "您还没有选择投注!";
            echo json_encode($arrRet);
            exit;
        }
        $PressStr = substr($PressStr,0,-1);
        if($sumScore != $totalScore)
        {
            $arrRet['cmd'] = "err";
            $arrRet['msg'] = "您的投注信息验证错误!";
            echo json_encode($arrRet);
            exit;
        }
        $sql = "select game_press_min,game_press_max from game_config where game_type = '{$GameType}'";
        $result = $db->query($sql);
        if($rs = $db->fetch_array($result))
        {
            $press_min = $rs['game_press_min'];
            $press_max = $rs['game_press_max'];
            if($sumScore < $press_min)
            {
                $arrRet['cmd'] = "err";
                $arrRet['msg'] = "投注额至少大于下限" . $press_min;
                echo json_encode($arrRet);
                exit;
            }
            if($sumScore > $press_max)
            {
                $arrRet['cmd'] = "err";
                $arrRet['msg'] = "投注额不能超过上限" . $press_max;
                echo json_encode($arrRet);
                exit;
            }
        }
        if($ID == "0")
            $sql = "insert into {$tableautotz}(uid,tzname,tzpoints,tznum,tzid,winid,lossid)
						values({$_SESSION['usersid']},'{$newName}',{$totalScore},'{$PressStr}',0,0,0)";
        else
            $sql = "update {$tableautotz} set tzname = '{$newName}',tzpoints={$totalScore},tznum='{$PressStr}'
					where id = '{$ID}' and uid = '{$_SESSION['usersid']}'";
        $result = $db->query($sql);
        $autoID = 0;
        if($ID == "0")
        {
            $insertID = $db->insert_id();
            $autoID = $insertID;
            if($insertID > 0)
                $sql = "update {$tableautotz} set winid={$insertID},lossid = {$insertID} where id = {$insertID} and uid='{$_SESSION['usersid']}'";
            $result = $db->query($sql);
        }

        if($ID != '0') $autoID = $ID;
        //更新模式
        $sql = "select EditPressModel({$GameType},{$autoID},{$_SESSION['usersid']},'{$PressStr}')";
        $result = $db->query($sql);

        $arrRet['cmd'] = "ok";
        $arrRet['msg'] = "";
        echo json_encode($arrRet);
        exit;
    }

    /*删除模式
    *
    */
    private function RemoveUserModel()
    {
        global $db;
        $GameType = intval($_POST["gtype"]);
        $ID = intval($_POST["id"]);
        $tableautotz = GetGameTableName($GameType,"auto_tz");
        $arrRet = array('cmd'=>'ok','msg'=>'');
        //检查是否正在自动投注
        if(CheckAutoPress($GameType))
        {
            $arrRet['cmd'] = "err";
            $arrRet['msg'] = "您当前已设置了自动投注，不允许编辑模式，请先取消自动投注!";
            echo json_encode($arrRet);
            exit;
        }
        $sql = "delete from {$tableautotz} where id = {$ID} and uid = '{$_SESSION['usersid']}'";
        $result = $db->query($sql);
        //删除模式
        $sql = "delete from gameall_auto_tz where autoid = {$ID} and uid = '{$_SESSION['usersid']}' and gametype='{$GameType}'";
        $result = $db->query($sql);

        $arrRet['cmd'] = "ok";
        $arrRet['msg'] = "删除成功!";
        echo json_encode($arrRet);
        exit;
    }
    /* 检测是否可以去投注
    *
    */
    function CheckPress()
    {
        global $db;
        $GameType = intval($_POST["gtype"]);
        $No = intval($_POST["no"]);
        $tableName = GetGameTableName($GameType,"auto");
        $arrRet = array('cmd'=>'ok','msg'=>'');
        if($tableName == "")
        {
            $arrRet['cmd'] = "err";
            $arrRet['msg'] = "游戏类型错误!";
            echo json_encode($arrRet);
            exit;
        }
        //判断总游戏是否允许下注
        $sql = "select fldVar,fldValue from sys_config where fldVar in('game_open_flag','game_shutdown_reason') order by fldIdx";
        $result = $db->query($sql);
        if($rs = $db->fetch_array($result))
        {
            if($rs["fldValue"] == "1")
            {
                $rs = $db->fetch_array($result);
                $arrRet['cmd'] = "showdown";
                $arrRet['msg'] = "游戏已停止下注,原因:" . ChangeEncodeG2U($rs["fldValue"]);
                echo json_encode($arrRet);
                exit;
            }

        }
        //判断单个游戏是否允许下注
        $sql = "select isstop,stop_msg from game_config where game_type = '{$GameType}'";
        $result = $db->query($sql);
        if($rs = $db->fetch_array($result))
        {
            if($rs["isstop"] == 1)
            {
                $arrRet['cmd'] = "showdown";
                $arrRet['msg'] = "游戏已停止下注,原因:" . ChangeEncodeG2U($rs["stop_msg"]);
                echo json_encode($arrRet);
                exit;
            }

        }
        $sql = "select 1 from " . $tableName . " where uid = '{$_SESSION['usersid']}'";
        $result = $db->query($sql);
        if($rs = $db->fetch_array($result))
        {
            $arrRet['cmd'] = "auto";
            $arrRet['msg'] = "您已设置了自动投注，请先停止!";
        }
        //WriteLog(json_encode($arrRet));
        echo json_encode($arrRet);
        exit;
    }
    function record(){
        $page = isset($_GET['page'])?$_GET['page']:1;
        $page =intval($page);
        $act=Req::get('id','intval');
        $this->assign('id',$act);
        $pagesize = 20;
        $tableuserstz = $this->GetGameTableName($act,"users_tz");
        $tablegame = $this->GetGameTableName($act,"game");
        //号码表格
        //$sql = "SELECT count(id) FROM {$tableuserstz} WHERE uid = {$_SESSION['usersid']} and `time` > DATE_ADD(CURDATE(),INTERVAL -6 DAY)";
        //$TotalRecCount = $db->GetRecordCount($sql);
        $sql = "
		 	SELECT a.id,a.no,DATE_FORMAT(a.time,'%H:%i:%s') as time,a.points,a.hdpoints
			FROM {$tableuserstz} a 
			
			WHERE a.uid = {$_SESSION['usersid']} AND a.`time` > DATE_ADD(CURDATE(),INTERVAL -1 DAY) 
			ORDER BY a.id desc limit 60";
        $list=db::get_all($sql);
        foreach ($list as $k=>$v){
            $list[$k]->hd=($v->hdpoints-$v->points);
            /*$list[$k]->hd=sprintf('%.2f',($v->hdpoints-$v->points)/1000);
            $list[$k]->points=sprintf('%.2f',$v->points/1000);
            $list[$k]->hdpoints=sprintf('%.2f',$v->hdpoints/1000);*/
        }
        $this->assign('list',$list);
        $games=$this->GetGameConfig($act);
        $this->assign('game_name',$games['game_name']);
        $this->display('game_record');
    }
    function recorddetail(){
    	$act=Req::get('id','intval');
    	$this->assign('id',$act);
    	$no=Req::get('no','intval');
    	$this->assign('no',$no);
    
    	$sql = "select game_model from game_config where game_type={$act}";
    	$modelrs = db::get_one($sql,'assoc');
    	$modelrs['game_model'] = explode(",",$modelrs['game_model']);
    	
    	$tablegame = $this->GetGameTableName($act,"game");
    	$sql = "SELECT kgjg,kgNo FROM {$tablegame} WHERE id = {$no} limit 1";
    	$resultgame=db::get_one($sql,'assoc');
    	$this->assign('kgjgArr',explode("|", $resultgame['kgjg']));
    	$this->assign('kgNoArr',explode("|", $resultgame['kgNo']));
    
    	$tableuserstz = $this->GetGameTableName($act,"users_tz");
    	$sql = "SELECT tznum,tzpoints,zjpoints,zjpl FROM {$tableuserstz} WHERE uid = {$_SESSION['usersid']} AND NO = {$no} limit 1";
    	$recdata=db::get_one($sql,'assoc');
    	//foreach ($list as $k=>$v){
    	//	$list[$k]->hd=($v->hdpoints-$v->points);
    	//}
    	//$this->assign('list',$list);
    	$list = array();
    	if(!empty($recdata)){
    		$tznumArr = explode("|", $recdata['tznum']);
    		$tzpointsArr = explode("|", $recdata['tzpoints']);
    		$zjpointsArr = explode("|", $recdata['zjpoints']);
    		$zjplArr = explode("|", $recdata['zjpl']);
    		
    		$tzpointsTotal = 0;
    		$zjpointsTotal = 0;
    
    		foreach($tznumArr as $idx=>&$item){
    			if(in_array($act, [25,26,27,28,29,30,31,36,37,41,42])){//外围，定位，赛车，农场，时时彩
    				$prefix = "";
    				if(in_array($act,[26,28,31,42]) && $item>=13 && $item<=26) $prefix = "(1球)";
    				if(in_array($act,[26,28,31,42]) && $item>=27 && $item<=40) $prefix = "(2球)";
    				if(in_array($act,[26,28,31,42]) && $item>=41 && $item<=54) $prefix = "(3球)";
    	    
    				if(in_array($act,[29]) && $item>=21 && $item<=34) $prefix = "(1球)";
    				if(in_array($act,[29]) && $item>=35 && $item<=48) $prefix = "(2球)";
    				if(in_array($act,[29]) && $item>=49 && $item<=62) $prefix = "(3球)";
    				if(in_array($act,[29]) && $item>=63 && $item<=76) $prefix = "(4球)";
    				if(in_array($act,[29]) && $item>=77 && $item<=90) $prefix = "(5球)";
    				if(in_array($act,[29]) && $item>=91 && $item<=104) $prefix = "(6球)";
    				if(in_array($act,[29]) && $item>=105 && $item<=118) $prefix = "(7球)";
    				if(in_array($act,[29]) && $item>=119 && $item<=132) $prefix = "(8球)";
    				if(in_array($act,[29]) && $item>=133 && $item<=146) $prefix = "(9球)";
    				if(in_array($act,[29]) && $item>=147 && $item<=160) $prefix = "(10球)";
    				if(in_array($act,[29]) && $item>=161 && $item<=162) $prefix = "(1v10)";
    				if(in_array($act,[29]) && $item>=163 && $item<=164) $prefix = "(2v9)";
    				if(in_array($act,[29]) && $item>=165 && $item<=166) $prefix = "(3v8)";
    				if(in_array($act,[29]) && $item>=167 && $item<=168) $prefix = "(4v7)";
    				if(in_array($act,[29]) && $item>=169 && $item<=170) $prefix = "(5v6)";
    	    
    				if(in_array($act,[36]) && $item>=6 && $item<=37) $prefix = "(1球)";
    				if(in_array($act,[36]) && $item>=38 && $item<=69) $prefix = "(2球)";
    				if(in_array($act,[36]) && $item>=70 && $item<=101) $prefix = "(3球)";
    				if(in_array($act,[36]) && $item>=102 && $item<=133) $prefix = "(4球)";
    				if(in_array($act,[36]) && $item>=134 && $item<=165) $prefix = "(5球)";
    				if(in_array($act,[36]) && $item>=166 && $item<=197) $prefix = "(6球)";
    				if(in_array($act,[36]) && $item>=198 && $item<=229) $prefix = "(7球)";
    				if(in_array($act,[36]) && $item>=230 && $item<=261) $prefix = "(8球)";
    				if(in_array($act,[36]) && $item>=262 && $item<=263) $prefix = "(1v8)";
    				if(in_array($act,[36]) && $item>=264 && $item<=265) $prefix = "(2v7)";
    				if(in_array($act,[36]) && $item>=266 && $item<=267) $prefix = "(3v6)";
    				if(in_array($act,[36]) && $item>=268 && $item<=269) $prefix = "(4v5)";
    	    
    				if(in_array($act,[37]) && $item>=7 && $item<=20) $prefix = "(1球)";
    				if(in_array($act,[37]) && $item>=21 && $item<=34) $prefix = "(2球)";
    				if(in_array($act,[37]) && $item>=35 && $item<=48) $prefix = "(3球)";
    				if(in_array($act,[37]) && $item>=49 && $item<=62) $prefix = "(4球)";
    				if(in_array($act,[37]) && $item>=63 && $item<=76) $prefix = "(5球)";
    				if(in_array($act,[37]) && $item>=77 && $item<=81) $prefix = "(前3)";
    				if(in_array($act,[37]) && $item>=82 && $item<=86) $prefix = "(中3)";
    				if(in_array($act,[37]) && $item>=87 && $item<=91) $prefix = "(后3)";
    	    
    				$item = $prefix . $modelrs['game_model'][$item];
    				 
    				$list[$idx]['zjpl'] = $zjplArr[$idx];
    			}else{
	    			if(in_array($act, [11,12,13,21,23])){//36游戏
	    				if($item == 1) $item = "豹";
	    				if($item == 2) $item = "对";
	    				if($item == 3) $item = "顺";
	    				if($item == 4) $item = "半";
	    				if($item == 5) $item = "杂";
	    			}
	    			 
	    			if(in_array($act, [16,47])){//PK龙虎 飞艇龙虎
	    				if($item == 1) $item = "龙";
	    				if($item == 2) $item = "虎";
	    			}
	    			
	    			if($zjpointsArr[$idx] > 0)$list[$idx]['zjpl'] = $recdata['zjpl'];
	    			else $list[$idx]['zjpl'] = 0.00;
    			}
    			 
    			$list[$idx]['no'] = $no;
    			$list[$idx]['tznum'] = $tznumArr[$idx];
    			$list[$idx]['tzpoints'] = $tzpointsArr[$idx];
    			$list[$idx]['zjpoints'] = $zjpointsArr[$idx];
    			
    			$tzpointsTotal = $tzpointsTotal + $tzpointsArr[$idx];
    			$zjpointsTotal = $zjpointsTotal + $zjpointsArr[$idx];
    		}
    		
    		$list[$idx+1]['no'] = "总计";
    		$list[$idx+1]['tznum'] = "";
    		$list[$idx+1]['tzpoints'] = $tzpointsTotal;
    		$list[$idx+1]['zjpoints'] = $zjpointsTotal;
    		$list[$idx+1]['zjpl'] = "";
    	}
    	 
    	 
    	$this->assign('list',$list);
    	 
    	$games=$this->GetGameConfig($act);
    	$this->assign('game_name',$games['game_name']);
    	$this->display('game_recorddetail');
    }
    function total(){
        $page = isset($_GET['page'])?$_GET['page']:1;
        $page =intval($page);
        $act=Req::get('id','intval');
        $this->assign('id',$act);
        $pagesize = 20;
        $tableuserstz = $this->GetGameTableName($act,"users_tz");
        $tablegame = $this->GetGameTableName($act,"game");
        //号码表格
        //$sql = "SELECT count(id) FROM {$tableuserstz} WHERE uid = {$_SESSION['usersid']} and `time` > DATE_ADD(CURDATE(),INTERVAL -6 DAY)";
        //$TotalRecCount = $db->GetRecordCount($sql);
        $sql = "
		 	SELECT a.id,a.no,a.points,a.hdpoints
			FROM {$tableuserstz} a 
			WHERE a.uid = {$_SESSION['usersid']} AND a.`time` > DATE_ADD(CURDATE(),INTERVAL -1 DAY)  and a.hdpoints>0
			ORDER BY a.id desc limit 60";
        $list=db::get_all($sql);

        $this->assign('list',$list);
        $games=$this->GetGameConfig($act);
        $this->assign('game_name',$games['game_name']);
        $this->display('game_total');
    }
    function rule(){
        $id=Req::get('id','intval');
        $this->assign('act',$id);
        $this->assign('id',$id);
        $this->display('game_rule');
    }
    //取离第一个开奖数字步长
    function GetFromBeginNumStep($GameType)
    {
        $step = 0;
        if($GameType == "14" || $GameType == "22" || $GameType == "44")//飞艇22 急速22 PK22
            $step = 6;
        else if($GameType == "1" || $GameType == "5" || $GameType == "9" || $GameType == "17" || $GameType == "19" || $GameType == "24" || $GameType == "40" || $GameType == "45")//16 冠亚军
            $step = 3;
        else if($GameType == "2" || $GameType == "10" || $GameType == "20" || $GameType == "38" || $GameType == "39")//11游戏
            $step = 2;
        else if($GameType == "6" || $GameType == "7" || $GameType == "11" || $GameType == "12" || $GameType == "13" || $GameType == "15" || $GameType == "16" || $GameType == "21" || $GameType == "23" || $GameType == "43" || $GameType == "46" || $GameType == "47")//10 冠军 龙虎
            $step = 1;

        return $step;
    }
    /* 取上盘押注情况
	*
	*/
    function getLastPress(){
        $GameType = Req::post('gtype','intval');
        $No = Req::post('no','intval');
        $tabletz = $this->GetGameTableName($GameType,"users_tz");
        $tablekg = $this->GetGameTableName($GameType,"kg_users_tz");
        $arrRet = array('cmd'=>'','msg'=>'');

        if($tabletz == ""){
            return $this->result(1,"游戏类型错误!");
        }
        $pos = $this->GetFromBeginNumStep($GameType);
        $pos = -$pos;
        $retMsg = "";
        $sql = "select tznum,tzpoints from {$tabletz} where  uid = '{$_SESSION['usersid']}' order by id desc";
        $rs = db::get_one($sql,'assoc');
        if($rs){
            $arrNum = explode("|",$rs['tznum']);
            $arrPoints = explode("|",$rs['tzpoints']);

            for($i = 0; $i < count($arrNum); $i++)
            {
                $retMsg .= ($arrNum[$i] + $pos) . "," . $arrPoints[$i] . "|";
            }
            if($retMsg != "")
            {
                $retMsg = substr($retMsg,0,-1);
            }
        }else{
            $sql = "select tznum,tzpoints from {$tablekg} where uid = '{$_SESSION['usersid']}' order by id desc";
            $result = db::_query($sql);
            while($rs = db::_assoc($result))
            {
                $retMsg .= ($rs['tznum'] + $pos) . "," . $rs['tzpoints'] . "|";
            }
            if($retMsg != "")
            {
                $retMsg = substr($retMsg,0,-1);
            }
        }
        if($retMsg == "")
        {
            return $this->result(1,'您上期没有投注!');
        }else{
            return $this->result(0,$retMsg);
        }
    }
    
    
    
    private function getGameWWResult($a,$b,$c){//外围开奖结果
    	$total = $a + $b + $c;
    	$result = [];
    
    	$is_max = 0;
    	if($total >= 14){//大
    		$is_max = 1;
    		$result[] = 1;
    		if($total >= 22){//极大
    			$result[] = 9;
    		}
    	}else{//小
    		$result[] = 6;
    		if($total <= 5){
    			$result[] = 4;//极小
    		}
    	}
    
    	if($total % 2 == 0){//双
    		$result[] = 5;
    		if($is_max){
    			$result[] = 8;//大双
    		}else{
    			$result[] = 7;//小双
    		}
    	}else{//单
    		$result[] = 0;
    		if($is_max){
    			$result[] = 3;//大单
    		}else{
    			$result[] = 2;//小单
    		}
    	}
    
    	if(in_array($total , [0,3,6,9,12,15,18,21,24,27])) $result[] = 10;
    	if(in_array($total , [1,4,7,10,13,16,19,22,25])) $result[] = 11;
    	if(in_array($total , [2,5,8,11,14,17,20,23,26])) $result[] = 12;
    
    	sort($result);
    	return $result;
    }
    
    
    private function getGameDWResult($a,$b,$c){//定位开奖结果
    	$total = $a + $b + $c;
    	$result = [];
    
    	$is_max = 0;
    	if($total >= 14){//大
    		$is_max = 1;
    		$result[] = 1;
    		if($total >= 22){//极大
    			$result[] = 9;
    		}
    	}else{//小
    		$result[] = 6;
    		if($total <= 5){
    			$result[] = 4;//极小
    		}
    	}
    
    	if($total % 2 == 0){//双
    		$result[] = 5;
    		if($is_max){
    			$result[] = 8;//大双
    		}else{
    			$result[] = 7;//小双
    		}
    	}else{//单
    		$result[] = 0;
    		if($is_max){
    			$result[] = 3;//大单
    		}else{
    			$result[] = 2;//小单
    		}
    	}
    
    	if($a > $c) $result[] = 10;
    	if($a < $c) $result[] = 11;
    	if($a == $c) $result[] = 12;
    
    	if($a >= 5){//大
    		$result[] = 13;
    	}else{//小
    		$result[] = 14;
    	}
    
    	if($a % 2 == 0){//双
    		$result[] = 16;
    	}else{//单
    		$result[] = 15;
    	}
    
    	$result[] = $a + 17;
    
    
    	if($b >= 5){//大
    		$result[] = 27;
    	}else{//小
    		$result[] = 28;
    	}
    
    	if($b % 2 == 0){//双
    		$result[] = 30;
    	}else{//单
    		$result[] = 29;
    	}
    
    	$result[] = $b + 31;
    
    
    	if($c >= 5){//大
    		$result[] = 41;
    	}else{//小
    		$result[] = 42;
    	}
    
    	if($c % 2 == 0){//双
    		$result[] = 44;
    	}else{//单
    		$result[] = 43;
    	}
    
    	$result[] = $c + 45;
    
    	sort($result);
    	return $result;
    }
    
    
    private function getGameSCResult($kjNoArr){//赛车开奖结果
    	$a = $kjNoArr[0];
    	$b = $kjNoArr[1];
    	$c = -1;
    	$total = $a + $b;
    
    	$result[] = $total - 3;
    
    	if($total >= 12){//大
    		$result[] = 17;
    	}else{//小
    		$result[] = 18;
    	}
    
    	if($total % 2 == 0){//双
    		$result[] = 20;
    	}else{//单
    		$result[] = 19;
    	}
    
    	//10个车道
    	for($n=0;$n<10;$n++){
    		if($kjNoArr[$n] >= 6){//大
    			$result[] = 20 + $n * 14 + 1;
    		}else{//小
    			$result[] = 20 + $n * 14 + 2;
    		}
    
    		if($kjNoArr[$n] % 2 == 0){//双
    			$result[] = 20 + $n * 14 + 4;
    		}else{//单
    			$result[] = 20 + $n * 14 + 3;
    		}
    
    		for($i=1;$i<=10;$i++){
    			if($kjNoArr[$n] == $i){
    				$result[] = 20 + $n * 14 + 4 + $i;
    				break;
    			}
    		}
    
    	}
    
    	//龙虎
    	for($n=0;$n<5;$n++){
    		if($kjNoArr[$n] > $kjNoArr[9-$n]){
    			$result[] = 160 + $n * 2 + 1;
    		}else{
    			$result[] = 160 + $n * 2 + 2;
    		}
    	}
    
    	sort($result);
    	return $result;
    }
    
    private function getGameCqsscResult($kjNoArr){//时时彩开奖结果
    	$total = (int)$kjNoArr[0] + (int)$kjNoArr[1] + (int)$kjNoArr[2] + (int)$kjNoArr[3] + (int)$kjNoArr[4];
    
    	if($total >= 23 && $total <= 45) $result[] = 0;//大
    	if($total >= 0 && $total <= 22) $result[] = 1;//小
    
    	if($total % 2 != 0) $result[] = 2;//单
    	if($total % 2 == 0) $result[] = 3;//双
    
    	if($kjNoArr[0] > $kjNoArr[4]) $result[] = 4;//龙
    	if($kjNoArr[0] < $kjNoArr[4]) $result[] = 5;//虎
    	if($kjNoArr[0] == $kjNoArr[4]) $result[] = 6;//和
    
    	//5个车道
    	for($n=0;$n<5;$n++){
    		$kjNoArr[$n] = (int)$kjNoArr[$n];
    			
    		if($kjNoArr[$n] >= 5 && $kjNoArr[$n] <= 9) $result[] = 7 + $n * 14;//大
    		if($kjNoArr[$n] >= 0 && $kjNoArr[$n] <= 4) $result[] = 8 + $n * 14;//小
    			
    		if($kjNoArr[$n] % 2 != 0) $result[] = 9 + $n * 14;//单
    		if($kjNoArr[$n] % 2 == 0) $result[] = 10 + $n * 14;//双
    			
    		for($i=0;$i<=9;$i++){
    			if($kjNoArr[$n] == $i){
    				$result[] = 11 + $n * 14 + $i;
    				break;
    			}
    		}
    	}
    
    	$a = $this->getGame36Result($kjNoArr[0],$kjNoArr[1],$kjNoArr[2]);
    	$result[] = 76 + $a;
    	$b = $this->getGame36Result($kjNoArr[1],$kjNoArr[2],$kjNoArr[3]);
    	$result[] = 81 + $b;
    	$c = $this->getGame36Result($kjNoArr[2],$kjNoArr[3],$kjNoArr[4]);
    	$result[] = 86 + $c;
    
    	sort($result);
    	return $result;
    }
    
    
    protected function getGame36Result($a,$b,$c){//36开奖结果
    	$arrNum = array($a,$b,$c);
    	sort($arrNum);
    	if($arrNum[0] == $arrNum[2]) //豹子
    		return 1;
    	if($arrNum[0] == $arrNum[1] || $arrNum[1] == $arrNum[2]) //对子
    		return 2;
    	if($arrNum[0] == 0 && ($arrNum[1] == 1 || $arrNum[1]==8) && $arrNum[2] == 9)//顺子特例
    		return 3;
    	if($arrNum[1] - $arrNum[0] == 1 && $arrNum[2] - $arrNum[1] == 1) //顺子
    		return 3;
    	if($arrNum[0] == 0  && $arrNum[2] == 9)//半顺特例
    		return 4;
    	if($arrNum[1] - $arrNum[0] == 1 || $arrNum[2] - $arrNum[1] == 1) //半顺
    		return 4;
    
    	return 5; //杂
    }
}