<div class="member_header">
    <div class="container-fluid">
        <div class="row member_bg">
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 pad0">
                <div class="media">
                    <div class="media-left">
                        <img src="{$skin}img/user/head.png" alt="用户头像" class="media-object">
                    </div>
                    <div class="media-body">
                        <h4 class=""><span class="red">{$info.nickname}</span> (ID : <span class="red">{$info.usersid}</span>)</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 info_login pad0">
                <div class="loginout pull-right">
                    <a href="{:url('users/out')}" class="btn btn-default">退出登录</a>
                    <a style="display: none" href="member_mysignin.shtml" class="btn btn-default">签到</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid member_infos">
        <div class="row member_bank white_bg">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0">
                <p>　<img src="{$skin}img/user/coin_1.png" alt="乐币"> 余额 : ¥ <span class="red">{$info.points}</span></p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0">
                <p><img src="{$skin}img/user/coin_2.png" alt="金币"> 银行 : ¥  <span class="red">{$info.bankpoints}</span></p>
            </div>
        </div>
        <!-- <div class="row member_bank white_bg">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0">
                <p>　<img src="{$skin}img/user/payls.png" width="25" height="25" alt="金币"> 流水 : ¥ <span class="red">{$tzpoint}</span></p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad0">
                <p><img src="{$skin}img/user/pay.png" width="25" height="25" alt="乐币"> 充值 : ¥ <span class="red">{$rmb}</span></p>
            </div>
        </div> -->
    </div>



</div>