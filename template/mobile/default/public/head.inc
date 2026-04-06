<nav class="navbar navbar-default navbar-fixed-top nav_bg navbar_width">
    <div class="container">
        <div class="navbar-header">
            <a href="{:url('index/index')}" class="navbar-brand logo"><img src="{$skin}img/logo.png" alt="{$webname}"></a>
            <a href="/pcindex.php?pc=1" class="navbar-brand computer"><img src="{$skin}img/computer.png" alt="{$webname}"></a>

            {if $info.usersid > 0}
                <ul class="navbar_info">
                    <li><span>{$info.nickname}</span></li>
                    <li>￥<span>{$info.points}</span></li>
                </ul>
            {else}
                <ul class="navbar_ul">
                    <li><a href="{:url('users/reg')}" class="btn-white btn-danger">注 册</a></li>
                    <li><a href="{:url('users/login')}" class="btn-white btn-success">登 录</a></li>
                </ul>
            {/if}


<!--           -->
<!--            <button type="button" class="navbar-toggle white" data-toggle="collapse" data-target="#nav_list">-->
<!--                <span class="icon-bar"></span>-->
<!--                <span class="icon-bar"></span>-->
<!--                <span class="icon-bar"></span>-->
<!--            </button>-->

        </div>
<!--        <div class="collapse navbar-collapse" id="nav_list">-->
<!--            <ul class="nav navbar-nav navbar-right">-->
<!--                <li><a href="{:url('index/index')}"><span class="glyphicon glyphicon-home"></span> 首页</a></li>-->
<!--                <li><a href="{:url('game/index')}"><span class="glyphicon glyphicon-th-large"></span> 游戏乐园</a></li>-->
<!--                {if $info.usersid > 0}-->
<!--                <li><a href="{:url('users/index')}"><span class="glyphicon glyphicon-user"></span> 会员</a></li>-->
<!--                <li><a href="{:url('users/out')}"><span class="glyphicon glyphicon-off"></span> 退出</a></li>-->
<!--                {/if}-->
<!--            </ul>-->
<!--        </div>-->


    </div>
</nav>