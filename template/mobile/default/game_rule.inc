<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    {tpl style}
    <link rel="stylesheet" type="text/css" href="{$skin}css/js.css" />
    <script type="text/javascript" src="{$skin}js/vue.min.js"></script>
    <title>{$webname}</title>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="position:relative;">
        <div class="navbar-header">
            <a href="{:url("game/game/id/$id")}" class="navbar-brand" onclick="javascript:void(0);"><span class="glyphicon glyphicon-chevron-left icon"></span></a>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#list_nav">
                <span class="glyphicon glyphicon-option-horizontal"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="list_nav">
            <ul class="nav navbar-nav navbar-right" style="display: none">
                <li class="active"><a href="auto.shtml">自定义投注模式</a></li>
                <li><a href="../member_mybank.shtml">小金库</a></li>
                <li><a href="trend.shtml">{$game_name}走势图</a></li>
            </ul>
        </div>
        <p style="position:absolute; top:15px; left:50%; margin-left:-18px; font-size:16px;  text-shadow:2px 2px 3px #ccc;color: #ecc296;">{$game_name}</p>
    </div>

</nav>

<div id="main" style="overflow: scroll">
    <div class="container-fluid">
        <div class="row">
            <!--
            <table class="table table-hover table-striped table-bordered text-center">
                <tr>
                    <th class="text-center">期号</th>
                    <th class="text-center">时间</th>
                    <th class="text-center">投注</th>
                    <th class="text-center">获取</th>
                    <th class="text-center">赢取</th>
                </tr>
            </table>
            -->
            <table class="table table-hover table-striped text-center" style="font-size:12px;">
                {if $act == "0"}
                <tr><td colspan='4'>计算机系统随机产生3个数字,每个数字范围0-9,每1分钟一期，24小时开奖</td></tr>

                <tr>
                    <td width='120'>如第449159期</td>
                    <td>0-9随机数</td>
                    <td>0-9随机数</td>
                    <td>0-9随机数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>0</li></td>
                    <td><li class='kj '>9</li></td>
                    <td><li class='kj '>3</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>0</li><i class="hja"></i><li class='kj '>9</li><i class="hja"></i><li class='kj '>3</li><i class="hdeng"></i><li class=''>12</li></td>
                    </tr>
                {elseif $act == "1"}
                <tr><td colspan='4'>计算机系统随机产生3个数字,每个数字范围1-6,每1分钟一期，24小时开奖</td></tr>

                <tr>
                    <td width='120'>如第449159期</td>
                    <td>1-6随机数</td>
                    <td>1-6随机数</td>
                    <td>1-6随机数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>1</li></td>
                    <td><li class='kj '>4</li></td>
                    <td><li class='kj '>3</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>1</li><i class="hja"></i><li class='kj '>4</li><i class="hja"></i><li class='kj '>3</li><i class="hdeng"></i><li class=''>8</li></td>
                    </tr>
                {elseif $act == "2"}
                <tr><td colspan='3'>计算机系统随机产生2个数字,每个数字范围1-6,每1分钟一期，24小时开奖</td></tr>

                <tr>
                    <td width='120'>如第449159期</td>
                    <td>1-6随机数</td>
                    <td>1-6随机数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>1</li></td>
                    <td><li class='kj '>4</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=2><li class='kj '>1</li><i class="hja"></i><li class='kj '>4</li><i class="hdeng"></i><li class=''>5</li></td>
                    </tr>
                {elseif $act == "3" || $act == "32"}
                <tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第654574期</td>
                    <td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75</td>
                    </tr>

                <tr>
                    <td width='120'>区位</td>
                    <td>第一区[第1/2/3/4/5/6位数字]</td>
                    <td>第二区[第7/8/9/10/11/12位数字]</td>
                    <td>第三区[第13/14/15/16/17/18位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>02,04,07,08,18,22</td>
                    <td>25,30,35,36,43,49</td>
                    <td>50,53,59,66,69,71</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>61</td>
                    <td>218</td>
                    <td>368</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>1</li></td>
                    <td><li class='kj '>8</li></td>
                    <td><li class='kj '>8</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>1</li><i class="hja"></i><li class='kj '>8</li><i class="hja"></i><li class='kj '>8</li> <i class="hdeng"></i> <li class=''>17</li></td>
                    </tr>
                {elseif $act == "25"}
                <tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第654574期</td>
                    <td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75</td>
                    </tr>

                <tr>
                    <td width='120'>区位</td>
                    <td>第一区[第1/2/3/4/5/6位数字]</td>
                    <td>第二区[第7/8/9/10/11/12位数字]</td>
                    <td>第三区[第13/14/15/16/17/18位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>02,04,07,08,18,22</td>
                    <td>25,30,35,36,43,49</td>
                    <td>50,53,59,66,69,71</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>61</td>
                    <td>218</td>
                    <td>368</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>1</li></td>
                    <td><li class='kj '>8</li></td>
                    <td><li class='kj '>8</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>1</li><i class="hja"></i><li class='kj '>8</li><i class="hja"></i><li class='kj '>8</li> <i class="hdeng"></i> <li class=''>17</li></td>
                    </tr>
                <tr><td colspan='4'>游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td></tr>
                <tr>
                    <td>大小单双</td>
                    <td>固定赔率2.1倍, 开13,14回本。</td>
                    <td>小单</td>
                    <td>固定4.6倍（包括1,3,5,7,9,11,13）,开13回本。</td>
                </tr>  
                <tr>
                    <td>大单</td>
                    <td>固定4.2倍（15,17,19,21,23,25,27）</td>
                    <td>小双</td>
                    <td>固定4.2倍（0.2.4.6.8.10.12)</td>
                </tr>  
                <tr>
                    <td>大双</td>
                    <td>固定4.6倍（14.16.18.20.22.24.26）,开14回本。</td>
                    <td>极小</td>
                    <td>固定17倍（0,1,2,3,4,5）</td>
                </tr>
                <tr>
                    <td>极大</td>
                    <td>固定17倍（22,23,24,25,26,27）</td>
                    <td>龙</td>
                    <td>固定2.9倍（0,3,6,9,12,15,18,21,24,27）</td>
                </tr> 
                <tr>
                    <td>虎</td>
                    <td>固定2.9倍（1,4,7,10,13,16,19,22,25）</td>
                    <td>豹</td>
                    <td>固定2.9倍（2,5,8,11,14,17,20,23,26）</td>
                </tr> 
                {elseif $act == "26"}
                <tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第654574期</td>
                    <td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75</td>
                    </tr>

                <tr>
                    <td width='120'>区位</td>
                    <td>第一区[第1/2/3/4/5/6位数字]</td>
                    <td>第二区[第7/8/9/10/11/12位数字]</td>
                    <td>第三区[第13/14/15/16/17/18位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>02,04,07,08,18,22</td>
                    <td>25,30,35,36,43,49</td>
                    <td>50,53,59,66,69,71</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>61</td>
                    <td>218</td>
                    <td>368</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>1</li></td>
                    <td><li class='kj '>8</li></td>
                    <td><li class='kj '>8</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>1</li><i class="hja"></i><li class='kj '>8</li><i class="hja"></i><li class='kj '>8</li> <i class="hdeng"></i> <li class=''>17</li></td>
                    </tr>
                <tr><td colspan='4'>游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td></tr>
                <tr>
                    <td>大小</td>
                    <td>固定赔率1.98倍</td>
                    <td>单双</td>
                    <td>固定赔率1.98倍</td>
                </tr>  
                <tr>
                    <td>小单</td>
                    <td>固定3.68倍（1.3.5.7.9.11.13）</td>
                    <td>大单</td>
                    <td>固定4.2倍（15.17.19.21.23.25.27）</td>
                </tr>  
                <tr>
                    <td>小双</td>
                    <td>固定4.2倍（0.2.4.6.8.10.12)</td>
                    <td>大双</td>
                    <td>固定3.68倍（14.16.18.20.22.24.26）</td>
                </tr>
                <tr>
                    <td>极小</td>
                    <td>固定16倍（0.1.2.3.4.5）</td>
                    <td>极大</td>
                    <td>固定16倍（22.23.24.25.26.27）</td>
                </tr> 
                <tr>
                    <td>龙虎和</td>
                    <td colspan="3">
                    	龙：开出号码一大于号码三，如 号码一开出 3 号码三开出 1 ；中奖为龙。 <br>
                    	虎：开出号码一小于号码三。如 号码一开出 0 号码三开出 3 ；中奖为虎。 <br>
                    	和：开出号码一等于号码三,中奖为和。
                    </td>
                </tr>   
                    
                    
                {elseif $act == "4" || $act == "33"}
                <tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第654574期</td>
                    <td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td width='120'>区位</td>
                    <td>第一区[第2/5/8/11/14/17位数字]</td>
                    <td>第二区[第3/6/9/12/15/18位数字]</td>
                    <td>第三区[第4/7/10/13/16/19位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>04,18,30,43,53,69</td>
                    <td>07,22,35,49,59,71</td>
                    <td>08,25,36,50,66,74</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>217</td>
                    <td>243</td>
                    <td>259</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>7</li></td>
                    <td><li class='kj '>3</li></td>
                    <td><li class='kj '>9</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>7</li><i class="hja"></i><li class='kj '>3</li><i class="hja"></i><li class='kj '>9</li><i class="hdeng"></i><li class=''>19</li></td>
                    </tr>
                {elseif $act == "41"}
                <tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第654574期</td>
                    <td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td width='120'>区位</td>
                    <td>第一区[第2/5/8/11/14/17位数字]</td>
                    <td>第二区[第3/6/9/12/15/18位数字]</td>
                    <td>第三区[第4/7/10/13/16/19位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>04,18,30,43,53,69</td>
                    <td>07,22,35,49,59,71</td>
                    <td>08,25,36,50,66,74</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>217</td>
                    <td>243</td>
                    <td>259</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>7</li></td>
                    <td><li class='kj '>3</li></td>
                    <td><li class='kj '>9</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>7</li><i class="hja"></i><li class='kj '>3</li><i class="hja"></i><li class='kj '>9</li><i class="hdeng"></i><li class=''>19</li></td>
                    </tr>
                <tr><td colspan='4'>游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td></tr>
                <tr>
                    <td>大小单双</td>
                    <td>固定赔率2.1倍, 开13,14回本。</td>
                    <td>小单</td>
                    <td>固定4.6倍（包括1,3,5,7,9,11,13）,开13回本。</td>
                </tr>  
                <tr>
                    <td>大单</td>
                    <td>固定4.2倍（15,17,19,21,23,25,27）</td>
                    <td>小双</td>
                    <td>固定4.2倍（0.2.4.6.8.10.12)</td>
                </tr>  
                <tr>
                    <td>大双</td>
                    <td>固定4.6倍（14.16.18.20.22.24.26）,开14回本。</td>
                    <td>极小</td>
                    <td>固定17倍（0,1,2,3,4,5）</td>
                </tr>
                <tr>
                    <td>极大</td>
                    <td>固定17倍（22,23,24,25,26,27）</td>
                    <td>龙</td>
                    <td>固定2.9倍（0,3,6,9,12,15,18,21,24,27）</td>
                </tr> 
                <tr>
                    <td>虎</td>
                    <td>固定2.9倍（1,4,7,10,13,16,19,22,25）</td>
                    <td>豹</td>
                    <td>固定2.9倍（2,5,8,11,14,17,20,23,26）</td>
                </tr> 
                {elseif $act == "42"}
                <tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第654574期</td>
                    <td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td width='120'>区位</td>
                    <td>第一区[第2/5/8/11/14/17位数字]</td>
                    <td>第二区[第3/6/9/12/15/18位数字]</td>
                    <td>第三区[第4/7/10/13/16/19位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>04,18,30,43,53,69</td>
                    <td>07,22,35,49,59,71</td>
                    <td>08,25,36,50,66,74</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>217</td>
                    <td>243</td>
                    <td>259</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>7</li></td>
                    <td><li class='kj '>3</li></td>
                    <td><li class='kj '>9</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>7</li><i class="hja"></i><li class='kj '>3</li><i class="hja"></i><li class='kj '>9</li><i class="hdeng"></i><li class=''>19</li></td>
                    </tr>
                <tr><td colspan='4'>游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td></tr>
                <tr>
                    <td>大小</td>
                    <td>固定赔率1.98倍</td>
                    <td>单双</td>
                    <td>固定赔率1.98倍</td>
                </tr>  
                <tr>
                    <td>小单</td>
                    <td>固定3.68倍（1.3.5.7.9.11.13）</td>
                    <td>大单</td>
                    <td>固定4.2倍（15.17.19.21.23.25.27）</td>
                </tr>  
                <tr>
                    <td>小双</td>
                    <td>固定4.2倍（0.2.4.6.8.10.12)</td>
                    <td>大双</td>
                    <td>固定3.68倍（14.16.18.20.22.24.26）</td>
                </tr>
                <tr>
                    <td>极小</td>
                    <td>固定16倍（0.1.2.3.4.5）</td>
                    <td>极大</td>
                    <td>固定16倍（22.23.24.25.26.27）</td>
                </tr> 
                <tr>
                    <td>龙虎和</td>
                    <td colspan="3">
                    	龙：开出号码一大于号码三，如 号码一开出 3 号码三开出 1 ；中奖为龙。 <br>
                    	虎：开出号码一小于号码三。如 号码一开出 0 号码三开出 3 ；中奖为虎。 <br>
                    	和：开出号码一等于号码三,中奖为和。
                    </td>
                </tr>  
                    
                    
                    
                {elseif $act == "5"}
                <tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第654574期</td>
                    <td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td width='120'>区位</td>
                    <td>第一区[第1/4/7/10/13/16位数字]</td>
                    <td>第二区[第2/5/8/11/14/17位数字]</td>
                    <td>第三区[第3/6/9/12/15/18位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>02,08,25,36,50,66</td>
                    <td>04,18,30,43,53,69</td>
                    <td>07,22,35,49,59,71</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>187</td>
                    <td>217</td>
                    <td>243</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>187除以6的余数 + 1</td>
                    <td>217除以6的余数 + 1</td>
                    <td>243除以6的余数 + 1</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>2</li></td>
                    <td><li class='kj '>2</li></td>
                    <td><li class='kj '>4</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>2</li><i class="hja"></i><li class='kj '>2</li><i class="hja"></i><li class='kj '>4</li><i class="hdeng"></i><li class=''>8</li></td>
                    </tr>
                {elseif $act == "6"}
                <tr><td colspan='2'>采用北京福彩中心PK10数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第449159期</td>
                    <td><em class=''>06</em>
                        <em class=''>05</em>
                        <em class=''>01</em>
                        <em class=''>03</em>
                        <em class=''>04</em>
                        <em class=''>10</em>
                        <em class=''>07</em>
                        <em class=''>08</em>
                        <em class=''>09</em>
                        <em class=''>02</em></td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取期号尾数，尾数对应第几位数字为开奖号码，如果尾数为0取第10位</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td>第449153期的期号尾数是3,则取第3位，是1</td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class=''>1</li></td>
                    </tr>
                {elseif $act == "7"}
                <tr><td colspan='2'>采用北京福彩中心PK10数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第449159期</td>
                    <td><em class=''>06</em>
                        <em class=''>05</em>
                        <em class=''>01</em>
                        <em class=''>03</em>
                        <em class=''>04</em>
                        <em class=''>10</em>
                        <em class=''>07</em>
                        <em class=''>08</em>
                        <em class=''>09</em>
                        <em class=''>02</em></td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>开奖取首位</td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class="">6</li></td>
                    </tr>
                {elseif $act == "8" || $act == "35"}
                <tr><td colspan='4'>采用加拿大快乐8数据，每4分钟一期，每天336期，每天19:00-20:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1773065期</td>
                    <td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第2/5/8/11/14/17位数字]</td>
                    <td>第二区[第3/6/9/12/15/18位数字]</td>
                    <td>第三区[第4/7/10/13/16/19位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>06,15,32,40,53,64</td>
                    <td>07,28,33,43,57,65</td>
                    <td>13,31,35,44,62,68</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>210</td>
                    <td>233</td>
                    <td>253</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>0</li></td>
                    <td><li class='kj '>3</li></td>
                    <td><li class='kj '>3</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>0</li><i class="hja"></i><li class='kj '>3</li><i class="hja"></i><li class='kj '>3</li><i class="hdeng"></i><li class=''>6</li></td>
                    </tr>
                    
                {elseif $act == "27"}
                <tr><td colspan='4'>采用加拿大快乐8数据，每4分钟一期，每天336期，每天19:00-20:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1773065期</td>
                    <td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第2/5/8/11/14/17位数字]</td>
                    <td>第二区[第3/6/9/12/15/18位数字]</td>
                    <td>第三区[第4/7/10/13/16/19位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>06,15,32,40,53,64</td>
                    <td>07,28,33,43,57,65</td>
                    <td>13,31,35,44,62,68</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>210</td>
                    <td>233</td>
                    <td>253</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>0</li></td>
                    <td><li class='kj '>3</li></td>
                    <td><li class='kj '>3</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>0</li><i class="hja"></i><li class='kj '>3</li><i class="hja"></i><li class='kj '>3</li><i class="hdeng"></i><li class=''>6</li></td>
                    </tr>
                <tr><td colspan='4'>游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td></tr>
                <tr>
                    <td>大小单双</td>
                    <td>固定赔率2.1倍, 开13,14回本。</td>
                    <td>小单</td>
                    <td>固定4.6倍（包括1,3,5,7,9,11,13）,开13回本。</td>
                </tr>  
                <tr>
                    <td>大单</td>
                    <td>固定4.2倍（15,17,19,21,23,25,27）</td>
                    <td>小双</td>
                    <td>固定4.2倍（0.2.4.6.8.10.12)</td>
                </tr>  
                <tr>
                    <td>大双</td>
                    <td>固定4.6倍（14.16.18.20.22.24.26）,开14回本。</td>
                    <td>极小</td>
                    <td>固定17倍（0,1,2,3,4,5）</td>
                </tr>
                <tr>
                    <td>极大</td>
                    <td>固定17倍（22,23,24,25,26,27）</td>
                    <td>龙</td>
                    <td>固定2.9倍（0,3,6,9,12,15,18,21,24,27）</td>
                </tr> 
                <tr>
                    <td>虎</td>
                    <td>固定2.9倍（1,4,7,10,13,16,19,22,25）</td>
                    <td>豹</td>
                    <td>固定2.9倍（2,5,8,11,14,17,20,23,26）</td>
                </tr> 
                {elseif $act == "28"}
                <tr><td colspan='4'>采用加拿大快乐8数据，每4分钟一期，每天336期，每天19:00-20:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1773065期</td>
                    <td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第2/5/8/11/14/17位数字]</td>
                    <td>第二区[第3/6/9/12/15/18位数字]</td>
                    <td>第三区[第4/7/10/13/16/19位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>06,15,32,40,53,64</td>
                    <td>07,28,33,43,57,65</td>
                    <td>13,31,35,44,62,68</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>210</td>
                    <td>233</td>
                    <td>253</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>0</li></td>
                    <td><li class='kj '>3</li></td>
                    <td><li class='kj '>3</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>0</li><i class="hja"></i><li class='kj '>3</li><i class="hja"></i><li class='kj '>3</li><i class="hdeng"></i><li class=''>6</li></td>
                    </tr>
                <tr><td colspan='4'>游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td></tr>
                <tr>
                    <td>大小</td>
                    <td>固定赔率1.98倍</td>
                    <td>单双</td>
                    <td>固定赔率1.98倍</td>
                </tr>  
                <tr>
                    <td>小单</td>
                    <td>固定3.68倍（1.3.5.7.9.11.13）</td>
                    <td>大单</td>
                    <td>固定4.2倍（15.17.19.21.23.25.27）</td>
                </tr>  
                <tr>
                    <td>小双</td>
                    <td>固定4.2倍（0.2.4.6.8.10.12)</td>
                    <td>大双</td>
                    <td>固定3.68倍（14.16.18.20.22.24.26）</td>
                </tr>
                <tr>
                    <td>极小</td>
                    <td>固定16倍（0.1.2.3.4.5）</td>
                    <td>极大</td>
                    <td>固定16倍（22.23.24.25.26.27）</td>
                </tr> 
                <tr>
                    <td>龙虎和</td>
                    <td colspan="3">
                    	龙：开出号码一大于号码三，如 号码一开出 3 号码三开出 1 ；中奖为龙。 <br>
                    	虎：开出号码一小于号码三。如 号码一开出 0 号码三开出 3 ；中奖为虎。 <br>
                    	和：开出号码一等于号码三,中奖为和。
                    </td>
                </tr>  
                
                    
                    
                {elseif $act == "9"}
                <tr><td colspan='4'>采用加拿大快乐8数据，每4分钟一期，每天336期，每天19:00-20:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1773065期</td>
                    <td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第1/4/7/10/13/16位数字]</td>
                    <td>第二区[第2/5/8/11/14/17位数字]</td>
                    <td>第三区[第3/6/9/12/15/18位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>01,13,31,35,44,62</td>
                    <td>06,15,32,40,53,64</td>
                    <td>07,28,33,43,57,65</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>186</td>
                    <td>210</td>
                    <td>233</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>186除以6的余数 + 1</td>
                    <td>210除以6的余数 + 1</td>
                    <td>233除以6的余数 + 1</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>1</li></td>
                    <td><li class='kj '>1</li></td>
                    <td><li class='kj '>6</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>1</li><i class="hja"></i><li class='kj '>1</li><i class="hja"></i><li class='kj '>6</li><i class="hdeng"></i><li class=''>8</li></td>
                    </tr>
                {elseif $act == "10"}
                <tr><td colspan='3'>采用加拿大快乐8数据，每4分钟一期，每天336期，每天19:00-20:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1773065期</td>
                    <td colspan='2'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第1/4/7/10/13/16位数字]</td>
                    <td>第三区[第3/6/9/12/15/18位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>01,13,31,35,44,62</td>
                    <td>07,28,33,43,57,65</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>186</td>
                    <td>233</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>186除以6的余数 + 1</td>
                    <td>233除以6的余数 + 1</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>1</li></td>
                    <td><li class='kj '>6</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>1</li><i class="hja"></i><li class='kj '>6</li><i class="hdeng"></i><li class=''>7</li></td>
                    </tr>
                {elseif $act == "11"}
                <tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第654574期</td>
                    <td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td width='120'>区位</td>
                    <td>第一区[第1/2/3/4/5/6位数字]</td>
                    <td>第二区[第7/8/9/10/11/12位数字]</td>
                    <td>第三区[第13/14/15/16/17/18位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>02,04,07,08,18,22</td>
                    <td>25,30,35,36,43,49</td>
                    <td>50,53,59,66,69,71</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>61</td>
                    <td>218</td>
                    <td>368</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>1</li></td>
                    <td><li class='kj '>8</li></td>
                    <td><li class='kj '>8</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>1</li><i class="hja"></i><li class='kj '>8</li><i class="hja"></i><li class='kj '>8</li><i class="hdeng"></i><li class='zh z2'></li></td>
                    </tr>

                <tr>
                    <td colspan='4' style='color:#F90; font-weight:bold'>游戏结果说明</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;结果优先顺序：豹 > 顺 > 对 > 半 > 杂</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z1'></li>  3个结果号码相同，如222,333,999</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z3'></li>  3个结果号码从小到大排序后，号码都相连，如231,765,645.特例:排序后019算顺子</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z2'></li>  3个结果号码只有两个相同，如535,337,899</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z4'></li>  3个结果号码只有任意两个是相连的,不包含顺、对，如635,367,874.特例:包含0和9也算顺子</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z5'></li>  3个结果号码没有任何关联，如638,942,185</td>
                    </tr>
                {elseif $act == "12"}
                <tr><td colspan='4'>采用北京福彩中心快乐8数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第654574期</td>
                    <td colspan='3'>02,04,07,08,18,22,25,30,35,36,43,49,50,53,59,66,69,71,74,75(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td width='120'>区位</td>
                    <td>第一区[第2/5/8/11/14/17位数字]</td>
                    <td>第二区[第3/6/9/12/15/18位数字]</td>
                    <td>第三区[第4/7/10/13/16/19位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>04,18,30,43,53,69</td>
                    <td>07,22,35,49,59,71</td>
                    <td>08,25,36,50,66,74</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>217</td>
                    <td>243</td>
                    <td>259</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>7</li></td>
                    <td><li class='kj '>3</li></td>
                    <td><li class='kj '>9</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>7</li><i class="hja"></i><li class='kj '>3</li><i class="hja"></i><li class='kj '>9</li><i class="hdeng"></i><li class='zh z5'></li></td>
                    </tr>

                <tr>
                    <td colspan='4' style='color:#F90; font-weight:bold'>游戏结果说明</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;结果优先顺序：豹 > 顺 > 对 > 半 > 杂</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z1'></li>  3个结果号码相同，如222,333,999</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z3'></li>  3个结果号码从小到大排序后，号码都相连，如231,765,645.特例:排序后019算顺子</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z2'></li>  3个结果号码只有两个相同，如535,337,899</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z4'></li>  3个结果号码只有任意两个是相连的,不包含顺、对，如635,367,874.特例:包含0和9也算顺子</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z5'></li>  3个结果号码没有任何关联，如638,942,185</td>
                    </tr>
                {elseif $act == "13"}
                <tr><td colspan='4'>采用加拿大快乐8数据，每4分钟一期，每天336期，每天19:00-20:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1773065期</td>
                    <td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第2/5/8/11/14/17位数字]</td>
                    <td>第二区[第3/6/9/12/15/18位数字]</td>
                    <td>第三区[第4/7/10/13/16/19位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>06,15,32,40,53,64</td>
                    <td>07,28,33,43,57,65</td>
                    <td>13,31,35,44,62,68</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>210</td>
                    <td>233</td>
                    <td>253</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>0</li></td>
                    <td><li class='kj '>3</li></td>
                    <td><li class='kj '>3</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>0</li><i class="hja"></i><li class='kj '>3</li><i class="hja"></i><li class='kj '>3</li><i class="hdeng"></i><li class='zh z2'></li></td>
                    </tr>

                <tr>
                    <td colspan='4' style='color:#F90; font-weight:bold'>游戏结果说明</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;结果优先顺序：豹 > 顺 > 对 > 半 > 杂</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z1'></li>  3个结果号码相同，如222,333,999</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z3'></li>  3个结果号码从小到大排序后，号码都相连，如231,765,645.特例:排序后019算顺子</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z2'></li>  3个结果号码只有两个相同，如535,337,899</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z4'></li>  3个结果号码只有任意两个是相连的,不包含顺、对，如635,367,874.特例:包含0和9也算顺子</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z5'></li>  3个结果号码没有任何关联，如638,942,185</td>
                    </tr>
                {elseif $act == "14"}
                <tr><td colspan='2'>采用北京福彩中心PK10数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第449159期</td>
                    <td><em class=''>06</em>
                        <em class=''>05</em>
                        <em class=''>01</em>
                        <em class=''>03</em>
                        <em class=''>04</em>
                        <em class=''>10</em>
                        <em class=''>07</em>
                        <em class=''>08</em>
                        <em class=''>09</em>
                        <em class=''>02</em></td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取开奖号码前3位之和</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td colspan=3><em class=''>06</em> + <em class=''>05</em> + <em class=''>01</em> = <li class=''>12</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class=''>12</li></td>
                    </tr>
                {elseif $act == "15"}
                <tr><td colspan='2'>计算机系统随机产生10个数字,数字范围1-10各不相同,每1分钟一期，24小时开奖</td></tr>

                <tr>
                    <td width='120'>如第449159期</td>
                    <td><em class=''>06</em>
                        <em class=''>05</em>
                        <em class=''>01</em>
                        <em class=''>03</em>
                        <em class=''>04</em>
                        <em class=''>10</em>
                        <em class=''>07</em>
                        <em class=''>08</em>
                        <em class=''>09</em>
                        <em class=''>02</em></td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>开奖取首位</td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class=''>6</li></td>
                    </tr>
                {elseif $act == "22"}
                <tr><td colspan='2'>计算机系统随机产生10个数字,数字范围1-10各不相同,每1分钟一期，24小时开奖</td></tr>

                <tr>
                    <td width='120'>如第449159期</td>
                    <td><em class=''>06</em>
                        <em class=''>05</em>
                        <em class=''>01</em>
                        <em class=''>03</em>
                        <em class=''>04</em>
                        <em class=''>10</em>
                        <em class=''>07</em>
                        <em class=''>08</em>
                        <em class=''>09</em>
                        <em class=''>02</em></td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>开奖取前三个数字的和</td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class=''>12</li></td>
                    </tr>
                {elseif $act == "23"}
                <tr><td colspan='4'>计算机系统随机产生3个数字,每个数字范围0-9,每1分钟一期，24小时开奖</td></tr>

                <tr>
                    <td width='120'>如第654574期</td>
                    <td>01</td>
                    <td>08</td>
                    <td>08</td>
                    </tr>
                <tr>
                    <td>结果</td>
                    <td><li class='kj '>1</li></td>
                    <td><li class='kj '>8</li></td>
                    <td><li class='kj '>8</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>1</li><i class="hja"></i><li class='kj '>8</li><i class="hja"></i><li class='kj '>8</li><i class="hdeng"></i><li class='zh z2'></li></td>
                    </tr>

                <tr>
                    <td colspan='4' style='color:#F90; font-weight:bold'>游戏结果说明</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;结果优先顺序：豹 > 顺 > 对 > 半 > 杂</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z1'></li>  3个结果号码相同，如222,333,999</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z3'></li>  3个结果号码从小到大排序后，号码都相连，如231,765,645.特例:排序后019算顺子</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z2'></li>  3个结果号码只有两个相同，如535,337,899</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z4'></li>  3个结果号码只有任意两个是相连的,不包含顺、对，如635,367,874.特例:包含0和9也算顺子</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z5'></li>  3个结果号码没有任何关联，如638,942,185</td>
                    </tr>
                {elseif $act == "24"}
                <tr><td colspan='2'>计算机系统随机产生10个数字,数字范围1-10各不相同,每1分钟一期，24小时开奖</td></tr>

                <tr>
                    <td width='120'>如第449159期</td>
                    <td><em class=''>06</em>
                        <em class=''>05</em>
                        <em class=''>01</em>
                        <em class=''>03</em>
                        <em class=''>04</em>
                        <em class=''>10</em>
                        <em class=''>07</em>
                        <em class=''>08</em>
                        <em class=''>09</em>
                        <em class=''>02</em></td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>开奖取前两个数字的和</td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class=''>11</li></td>
                    </tr>
                {elseif $act == "16"}
                <tr><td colspan='2'>采用北京福彩中心PK10数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第449159期</td>
                    <td><em class=''>06</em>
                        <em class=''>05</em>
                        <em class=''>01</em>
                        <em class=''>03</em>
                        <em class=''>04</em>
                        <em class=''>10</em>
                        <em class=''>07</em>
                        <em class=''>08</em>
                        <em class=''>09</em>
                        <em class=''>02</em></td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取第一位和最后一位比较，第一位大为龙，最后一位大为虎</td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><em class=''>06</em> 大于 <em class=''>02</em>，结果为<li class='lh n1'>龙</li></td>
                    </tr>
                {elseif $act == "17"}
                <tr><td colspan='2'>采用北京福彩中心PK10数据，每5分钟一期，每天179期，每天0-9点暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第449159期</td>
                    <td><em class=''>06</em>
                        <em class=''>05</em>
                        <em class=''>01</em>
                        <em class=''>03</em>
                        <em class=''>04</em>
                        <em class=''>10</em>
                        <em class=''>07</em>
                        <em class=''>08</em>
                        <em class=''>09</em>
                        <em class=''>02</em></td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取开奖号码前两位之和</td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><em class=''>06</em> + <em class=''>05</em> = <li class=''>11</li></td>
                    </tr>
                {elseif $act == "18" || $act == "34"}
                <tr><td colspan='4'>采用首尔快乐8数据，每1分半钟一期，每天5:00-7:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1234567期</td>
                    <td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第2/5/8/11/14/17位数字]</td>
                    <td>第二区[第3/6/9/12/15/18位数字]</td>
                    <td>第三区[第4/7/10/13/16/19位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>06,15,32,40,53,64</td>
                    <td>07,28,33,43,57,65</td>
                    <td>13,31,35,44,62,68</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>210</td>
                    <td>233</td>
                    <td>253</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>0</li></td>
                    <td><li class='kj '>3</li></td>
                    <td><li class='kj '>3</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>0</li><i class="hja"></i><li class='kj '>3</li><i class="hja"></i><li class='kj '>3</li><i class="hdeng"></i><li class=''>6</li></td>
                    </tr>
                {elseif $act == "30"}
                <tr><td colspan='4'>采用首尔快乐8数据，每1分半钟一期，每天5:00-7:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1234567期</td>
                    <td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第2/5/8/11/14/17位数字]</td>
                    <td>第二区[第3/6/9/12/15/18位数字]</td>
                    <td>第三区[第4/7/10/13/16/19位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>06,15,32,40,53,64</td>
                    <td>07,28,33,43,57,65</td>
                    <td>13,31,35,44,62,68</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>210</td>
                    <td>233</td>
                    <td>253</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>0</li></td>
                    <td><li class='kj '>3</li></td>
                    <td><li class='kj '>3</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>0</li><i class="hja"></i><li class='kj '>3</li><i class="hja"></i><li class='kj '>3</li><i class="hdeng"></i><li class=''>6</li></td>
                    </tr>
                <tr><td colspan='4'>游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td></tr>
                <tr>
                    <td>大小单双</td>
                    <td>固定赔率2.1倍, 开13,14回本。</td>
                    <td>小单</td>
                    <td>固定4.6倍（包括1,3,5,7,9,11,13）,开13回本。</td>
                </tr>  
                <tr>
                    <td>大单</td>
                    <td>固定4.2倍（15,17,19,21,23,25,27）</td>
                    <td>小双</td>
                    <td>固定4.2倍（0.2.4.6.8.10.12)</td>
                </tr>  
                <tr>
                    <td>大双</td>
                    <td>固定4.6倍（14.16.18.20.22.24.26）,开14回本。</td>
                    <td>极小</td>
                    <td>固定17倍（0,1,2,3,4,5）</td>
                </tr>
                <tr>
                    <td>极大</td>
                    <td>固定17倍（22,23,24,25,26,27）</td>
                    <td>龙</td>
                    <td>固定2.9倍（0,3,6,9,12,15,18,21,24,27）</td>
                </tr> 
                <tr>
                    <td>虎</td>
                    <td>固定2.9倍（1,4,7,10,13,16,19,22,25）</td>
                    <td>豹</td>
                    <td>固定2.9倍（2,5,8,11,14,17,20,23,26）</td>
                </tr> 
                {elseif $act == "31"}
                <tr><td colspan='4'>采用首尔快乐8数据，每1分半钟一期，每天5:00-7:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1234567期</td>
                    <td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第2/5/8/11/14/17位数字]</td>
                    <td>第二区[第3/6/9/12/15/18位数字]</td>
                    <td>第三区[第4/7/10/13/16/19位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>06,15,32,40,53,64</td>
                    <td>07,28,33,43,57,65</td>
                    <td>13,31,35,44,62,68</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>210</td>
                    <td>233</td>
                    <td>253</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>0</li></td>
                    <td><li class='kj '>3</li></td>
                    <td><li class='kj '>3</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>0</li><i class="hja"></i><li class='kj '>3</li><i class="hja"></i><li class='kj '>3</li><i class="hdeng"></i><li class=''>6</li></td>
                    </tr>
                <tr><td colspan='4'>游戏总共有0-27共28位数字，0-13为小，14-27为大，奇数为单，偶数为双,以下赔率包含本金</td></tr>
                <tr>
                    <td>大小</td>
                    <td>固定赔率1.98倍</td>
                    <td>单双</td>
                    <td>固定赔率1.98倍</td>
                </tr>  
                <tr>
                    <td>小单</td>
                    <td>固定3.68倍（1.3.5.7.9.11.13）</td>
                    <td>大单</td>
                    <td>固定4.2倍（15.17.19.21.23.25.27）</td>
                </tr>  
                <tr>
                    <td>小双</td>
                    <td>固定4.2倍（0.2.4.6.8.10.12)</td>
                    <td>大双</td>
                    <td>固定3.68倍（14.16.18.20.22.24.26）</td>
                </tr>
                <tr>
                    <td>极小</td>
                    <td>固定16倍（0.1.2.3.4.5）</td>
                    <td>极大</td>
                    <td>固定16倍（22.23.24.25.26.27）</td>
                </tr> 
                <tr>
                    <td>龙虎和</td>
                    <td colspan="3">
                    	龙：开出号码一大于号码三，如 号码一开出 3 号码三开出 1 ；中奖为龙。 <br>
                    	虎：开出号码一小于号码三。如 号码一开出 0 号码三开出 3 ；中奖为虎。 <br>
                    	和：开出号码一等于号码三,中奖为和。
                    </td>
                </tr>      
                    
                    
                {elseif $act == "19"}
                <tr><td colspan='4'>采用首尔快乐8数据，每1分半钟一期，每天5:00-7:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1234567期</td>
                    <td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第1/4/7/10/13/16位数字]</td>
                    <td>第二区[第2/5/8/11/14/17位数字]</td>
                    <td>第三区[第3/6/9/12/15/18位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>01,13,31,35,44,62</td>
                    <td>06,15,32,40,53,64</td>
                    <td>07,28,33,43,57,65</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>186</td>
                    <td>210</td>
                    <td>233</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>186除以6的余数 + 1</td>
                    <td>210除以6的余数 + 1</td>
                    <td>233除以6的余数 + 1</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>1</li></td>
                    <td><li class='kj '>1</li></td>
                    <td><li class='kj '>6</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>1</li><i class="hja"></i><li class='kj '>1</li><i class="hja"></i><li class='kj '>6</li><i class="hdeng"></i><li class=''>8</li></td>
                    </tr>
                {elseif $act == "20"}
                <tr><td colspan='3'>采用首尔快乐8数据，每1分半钟一期，每天5:00-7:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1234567期</td>
                    <td colspan='2'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第1/4/7/10/13/16位数字]</td>
                    <td>第二区[第3/6/9/12/15/18位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>01,13,31,35,44,62</td>
                    <td>07,28,33,43,57,65</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>186</td>
                    <td>233</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>186除以6的余数 + 1</td>
                    <td>233除以6的余数 + 1</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>1</li></td>
                    <td><li class='kj '>6</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>1</li><i class="hja"></i><li class='kj '>6</li><i class="hdeng"></i><li class=''>7</li></td>
                    </tr>
                {elseif $act == "21"}
                <tr><td colspan='4'>采用首尔快乐8数据，每1分半钟一期，每天5:00-7:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1234567期</td>
                    <td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第2/5/8/11/14/17位数字]</td>
                    <td>第二区[第3/6/9/12/15/18位数字]</td>
                    <td>第三区[第4/7/10/13/16/19位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>06,15,32,40,53,64</td>
                    <td>07,28,33,43,57,65</td>
                    <td>13,31,35,44,62,68</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>210</td>
                    <td>233</td>
                    <td>253</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    <td>取尾数</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>0</li></td>
                    <td><li class='kj '>3</li></td>
                    <td><li class='kj '>3</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>0</li><i class="hja"></i><li class='kj '>3</li><i class="hja"></i><li class='kj '>3</li><i class="hdeng"></i><li class='zh z2'></li></td>
                    </tr>

                <tr>
                    <td colspan='4' style='color:#F90; font-weight:bold'>游戏结果说明</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;结果优先顺序：豹 > 顺 > 对 > 半 > 杂</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z1'></li>  3个结果号码相同，如222,333,999</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z3'></li>  3个结果号码从小到大排序后，号码都相连，如231,765,645.特例:排序后019算顺子</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z2'></li>  3个结果号码只有两个相同，如535,337,899</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z4'></li>  3个结果号码只有任意两个是相连的,不包含顺、对，如635,367,874.特例:包含0和9也算顺子</td>
                    </tr>

                <tr>
                    <td colspan='4' style='text-align:left'>&nbsp;<li class='zh z5'></li>  3个结果号码没有任何关联，如638,942,185</td>
                    </tr>
                    
                    
                {elseif $act == "29"}
                <tr><td>北京赛车规则说明</td></tr>

                <tr>
                	<td>
                		该游戏的投注时间、开奖时间和开奖号码与“北京PK拾”完全同步，北京时间（GMT+8）每天白天从上午09:02开到晚上23:57，每5分钟开一次奖,每天开奖179期。 <br>
                		具体游戏规则如下: <br>
                		1～10 两面：指 单、双；大、小。  <br>
                		单、双：号码为双数叫双，如4、8；号码为单数叫单，如5、9。 <br>
                		大、小：开出之号码大于或等于6为大，小于或等于5为小。 <br>
                		第一名～第十名 车号指定：每一个车号为一投注组合，开奖结果“投注车号”对应所投名次视为中奖，其余情形视为不中奖。<br>
                		1～5龙虎<br>
                		冠  军 龙/虎：“第一名”车号大于“第十名”车号视为【龙】中奖、反之小于视为【虎】中奖，其余情形视为不中奖。  <br>
                		亚  军 龙/虎：“第二名”车号大于“第九名”车号视为【龙】中奖、反之小于视为【虎】中奖，其余情形视为不中奖。<br>
                		第三名 龙/虎：“第三名”车号大于“第八名”车号视为【龙】中奖、反之小于视为【虎】中奖，其余情形视为不中奖。<br>
                		第四名 龙/虎：“第四名”车号大于“第七名”车号视为【龙】中奖、反之小于视为【虎】中奖，其余情形视为不中奖。<br>
                		第五名 龙/虎：“第五名”车号大于“第六名”车号视为【龙】中奖、反之小于视为【虎】中奖，其余情形视为不中奖。<br>
                		冠军车号＋亚军车号＝冠亚和值（为3~19)<br>
                		冠亚和单双：“冠亚和值”为单视为投注“单”的注单视为中奖，为双视为投注“双”的注单视为中奖，其余视为不中奖。<br>
                		冠亚和大小：“冠亚和值”大于11时投注“大”的注单视为中奖，小于或等于11时投注“小”的注单视为中奖，其余视为不中奖。 <br>
                		冠亚和指定：“冠亚和值”可能出现的结果为3～19， 投中对应“冠亚和值”数字的视为中奖，其余视为不中奖。
                	</td>
                </tr>
                    
                    
                {elseif $act == "38"}
                <tr><td colspan='3'>采用北京快乐8数据，每5分钟一期，每天00:00-09:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1234567期</td>
                    <td colspan='2'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第1/4/7/10/13/16位数字]</td>
                    <td>第二区[第3/6/9/12/15/18位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>01,13,31,35,44,62</td>
                    <td>07,28,33,43,57,65</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>186</td>
                    <td>233</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>186除以6的余数 + 1</td>
                    <td>233除以6的余数 + 1</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>1</li></td>
                    <td><li class='kj '>6</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=2><li class='kj '>1</li><i class="hja"></i><li class='kj '>6</li><i class="hdeng"></i><li class=''>7</li></td>
                    </tr>  
                    
                {elseif $act == "39"}
                <tr><td colspan='3'>采用北京快乐8数据，每5分钟一期，每天00:00-09:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1234567期</td>
                    <td colspan='2'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第1/2/3/4/5/6位数字]</td>
                    <td>第二区[第13/14/15/16/17/18位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>01,06,07,13,15,28</td>
                    <td>44,53,57,62,64,65</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>70</td>
                    <td>345</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>70除以6的余数 + 1</td>
                    <td>345除以6的余数 + 1</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>5</li></td>
                    <td><li class='kj '>4</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=2><li class='kj '>5</li><i class="hja"></i><li class='kj '>4</li><i class="hdeng"></i><li class=''>9</li></td>
                    </tr>  
                    
                {elseif $act == "40"}
                <tr><td colspan='4'>采用北京快乐8数据，每5分钟一期，每天00:00-09:00暂停开奖</td></tr>

                <tr>
                    <td width='120'>如第1234567期</td>
                    <td colspan='3'>01,06,07,13,15,28,31,32,33,35,40,43,44,53,57,62,64,65,68,80(从小到大依次排列)</td>
                    </tr>

                <tr>
                    <td>区位</td>
                    <td>第一区[第1/2/3/4/5/6位数字]</td>
                    <td>第二区[第7/8/9/10/11/12位数字]</td>
                    <td>第三区[第13/14/15/16/17/18位数字]</td>
                    </tr>

                <tr>
                    <td>数字</td>
                    <td>01,06,07,13,15,28</td>
                    <td>31,32,33,35,40,43</td>
                    <td>44,53,57,62,64,65</td>
                    </tr>

                <tr>
                    <td>求和</td>
                    <td>70</td>
                    <td>214</td>
                    <td>345</td>
                    </tr>

                <tr>
                    <td>计算</td>
                    <td>70除以6的余数 + 1</td>
                    <td>214除以6的余数 + 1</td>
                    <td>345除以6的余数 + 1</td>
                    </tr>

                <tr>
                    <td>结果</td>
                    <td><li class='kj '>5</li></td>
                    <td><li class='kj '>5</li></td>
                    <td><li class='kj '>4</li></td>
                    </tr>

                <tr>
                    <td>开奖</td>
                    <td colspan=3><li class='kj '>5</li><i class="hja"></i><li class='kj '>5</li><i class="hja"></i><li class='kj '>4</li><i class="hdeng"></i><li class=''>14</li></td>
                    </tr>  
                {elseif $act == "37"}
				<tr><td colspan='2'>该游戏的投注时间、开奖时间和开奖号码与重庆时时彩完全同步，北京时间（GMT+8）每天白天从上午10：00开到晚上22：00，夜场从22:00至凌晨2点,<br/>每10分钟开一次奖，夜场每5分钟开一次奖,每天开奖120期(白天72期,夜间48期)。具体游戏规则如下:</td></tr>
				<tr>
					<td width='120' style='color:red;' colspan='2'>1.第一球~第五球：</td>
				</tr>
				<tr>
					<td style='color:red;'>第一球特~第五球特：</td>
					<td>第一球、第二球、第三球、第四球、第五球：指下注的每一球与开出之号码其开奖顺序及开奖号码从左到右</td>
				</tr>
				<tr>
					<td style='color:red;'>单双大小：</td>
					<td>根据相应单项投注第一球特 ~ 第五球特开出的球号，判断胜负。</td>
				</tr>
				<tr>
					<td style='color:red;'>大小：</td>
					<td>根据相应单项投注的第一球特 ~ 第五球特开出的球号大於或等於5为特码大，小於或等於4为特码小。</td>
				</tr>
				<tr>
					<td style='color:red;'>单双：</td>
					<td>根据相应单项投注的第一球特 ~ 第五球特开出的球号为双数叫特双，如2、6；特码为单数叫特单，如1、3。</td>
				</tr>
				<tr>
					<td style='color:red;' colspan='2'>2.总和单双大小：</td>
				</tr>
				<tr>
					<td style='color:red;'>大小：</td>
					<td>根据相应单项投注的第一球特 ~ 第五球特开出的球号大於或等於23为特码大，小於或等於22为特码小。</td>
				</tr>
				<tr>
					<td style='color:red;'>单双：</td>
					<td>根据相应单项投注的第一球特 ~ 第五球特开出的球号数字总和值是双数为总和双，数字总和值是单数为总和单。</td>
				</tr>
				
				<tr>
					<td style='color:red;' colspan='2' >3.前三特殊玩法： 豹子 > 顺子 > 对子 > 半顺 > 杂六 。</td>
				</tr>
				<tr>
					<td style='color:red;'>豹子：</td>
					<td>中奖号码的一二三球都相同。----如中奖号码为000、111、999等，中奖号码的一二三球数字相同，则投注豹子者视为中奖，其它视为不中奖。</td>
				</tr>
				<tr>
					<td style='color:red;'>顺子：</td>
					<td>中奖号码的一二三球都相连，不分顺序。（数字9、0、1相连）----如中奖号码为123、901、321、546等，中奖号码一二三球百位数字相连，则投注顺子者视为中奖，其它视为不中奖。</td>
				</tr>
				<tr>
					<td style='color:red;'>对子：</td>
					<td>中奖号码的一二三球任意两位数字相同。（不包括豹子）----如中奖号码为001，112、696，中奖号码有两位数字相同，则投注对子者视为中奖，其它视为不中奖。如果开奖号码为豹子,则对子视为不中奖。如中奖号码为001，112、696，中奖号码有两位数字相同，则投注对子者视为中奖，其它视为不中奖。</td>
				</tr>
				<tr>
					<td style='color:red;'>半顺：</td>
					<td>中奖号码的一二三球任意两位数字相连，不分顺序。（不包括顺子、对子。）----如中奖号码为125、540、390、706，中奖号码有两位数字相连，则投注半顺者视为中奖，其它视为不中奖。如果开奖号码为顺子、对子,则半顺视为不中奖。--如中奖号码为123、901、556、233，视为不中奖。</td>
				</tr>
				<tr>
					<td style='color:red;'>杂六：</td>
					<td>不包括豹子、对子、顺子、半顺的所有中奖号码。----如中奖号码为157，中奖号码位数之间无关联性，则投注杂六者视为中奖，其它视为不中奖。</td>
				</tr>
				<tr>
					<td style='color:red;' colspan='2' >4.中三特殊玩法： 豹子 > 顺子 > 对子 > 半顺 > 杂六 。</td>
				</tr>
				<tr>
					<td style='color:red;'>豹子：</td>
					<td>中奖号码的二三四球都相同。----如中奖号码为000、111、999等，中奖号码的二三四球相同，则投注豹子者视为中奖，其它视为不中奖。</td>
				</tr>
				<tr>
					<td style='color:red;'>顺子：</td>
					<td>中奖号码的二三四球都相连，不分顺序。（数字9、0、1相连）----如中奖号码为123、901、321、546等，中奖号码二三四球相连，则投注顺子者视为中奖，其它视为不中奖。</td>
				</tr>
				<tr>
					<td style='color:red;'>对子：</td>
					<td>中奖号码的二三四球相同。（不包括豹子）----如中奖号码为001，112、696，中奖号码有两位数字相同，则投注对子者视为中奖，其它视为不中奖。如果开奖号码为豹子,则对子视为不中奖。如中奖号码为001，112、696，中奖号码有两位数字相同，则投注对子者视为中奖，其它视为不中奖。</td>
				</tr>	
				<tr>
					<td style='color:red;'>半顺：</td>
					<td>中奖号码的二三四球数字相连，不分顺序。（不包括顺子、对子。）----如中奖号码为125、540、390、706，中奖号码有两位数字相连，则投注半顺者视为中奖，其它视为不中奖。如果开奖号码为顺子、对子,则半顺视为不中奖。--如中奖号码为123、901、556、233，视为不中奖。</td>
				</tr>
				<tr>
					<td style='color:red;'>杂六：</td>
					<td>不包括豹子、对子、顺子、半顺的所有中奖号码。----如中奖号码为157，中奖号码位数之间无关联性，则投注杂六者视为中奖，其它视为不中奖。</td>
				</tr>
				<tr>
					<td style='color:red;' colspan='2'>6.后三特殊玩法： 豹子 > 顺子 > 对子 > 半顺 > 杂六 。</td>
				</tr>
				<tr>
					<td style='color:red;'>豹子：</td>
					<td>中奖号码的二三四球都相同。----如中奖号码为000、111、999等，中奖号码的二三四球相同，则投注豹子者视为中奖，其它视为不中奖。</td>
				</tr>
				<tr>
					<td style='color:red;'>顺子：</td>
					<td>中奖号码的二三四球都相连，不分顺序。（数字9、0、1相连）----如中奖号码为123、901、321、546等，中奖号码二三四球相连，则投注顺子者视为中奖，其它视为不中奖。</td>
				</tr>
				<tr>
					<td style='color:red;'>对子：</td>
					<td>中奖号码的二三四球相同。（不包括豹子）----如中奖号码为001，112、696，中奖号码有两位数字相同，则投注对子者视为中奖，其它视为不中奖。如果开奖号码为豹子,则对子视为不中奖。如中奖号码为001，112、696，中奖号码有两位数字相同，则投注对子者视为中奖，其它视为不中奖。</td>
				</tr>	
				<tr>
					<td style='color:red;'>半顺：</td>
					<td>中奖号码的二三四球数字相连，不分顺序。（不包括顺子、对子。）----如中奖号码为125、540、390、706，中奖号码有两位数字相连，则投注半顺者视为中奖，其它视为不中奖。如果开奖号码为顺子、对子,则半顺视为不中奖。--如中奖号码为123、901、556、233，视为不中奖。</td>
				</tr>
				<tr>
					<td style='color:red;'>杂六：</td>
					<td>不包括豹子、对子、顺子、半顺的所有中奖号码。----如中奖号码为157，中奖号码位数之间无关联性，则投注杂六者视为中奖，其它视为不中奖。</td>
				</tr>
				<tr>
					<td style='color:red;' colspan='2'>7.龙虎和特殊玩法： 龙 > 虎 > 和 （0为最小，9为最大）。</td>
				</tr>	
				<tr>
					<td style='color:red;'>龙：</td>
					<td>开出之号码第一球（万位）的中奖号码大于第五球（个位）的中奖号码，如 第一球开出4 第五球开出2；第一球开出9 第五球开出8；第一球开出5 第五球开出1...中奖为龙。</td>
				</tr>
				<tr>
					<td style='color:red;'>虎：</td>
					<td>开出之号码第一球（万位）的中奖号码小于第五球（个位）的中奖号码，如 第一球开出7 第五球开出9；第一球开出3 第五球开出5；第一球开出5 第五球开出8...中奖为虎。</td>
				</tr>
				<tr>
					<td style='color:red;'>和：</td>
					<td>开出之号码第一球（万位）的中奖号码等于第五球（个位）的中奖号码，例如开出结果：2***2则投注和局者视为中奖，其它视为不中奖。</td>
				</tr>
                {/if}
            </table>
        </div>

    </div>
</div>
{tpl foot}
<script type="text/javascript">
    $(function () {
        $("#list").on('click','#press',function () {
            no=$(this).parents("a").attr("id");
            window.location="{:url("game/press/id/$id")}&no="+no;
            return false;
        });
        $("#main").on("click",".item",function () {
            no=$(this).attr("rel");
            window.location="{:url("game/item/id/$id")}&no="+no;
        })

    })
</script>
</body>
</html>