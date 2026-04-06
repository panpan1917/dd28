
<div class='panel panel-default'>
    <div class='panel-heading'>首充返利记录</div>
    <div class='panel-body'>
        <table class='table table-striped table-hover table-bordered'>

            <tr>
                <td>领取时间</td>
                <td>领取乐豆</td>
                <td>领取后银行分</td>
            </tr>
            {loop $list $k $v}
            <tr>
                <td>{$v.log_time}</td>
                <td>{$v.amount}</td>
                <td>{$v.bankpoints}</td>
            </tr>
            {/loop}
        </table>
    </div>
</div>
