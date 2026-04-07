# dd28 存储过程迁移清单

## 1. 文档目的

老系统大量核心逻辑不在 PHP 控制器，而在 `kdydata.sql` 的存储过程中。  
本文档用于回答：

- 哪些过程必须优先迁移
- 每个过程在新系统里应拆给哪个模块
- 迁移时应该如何校验、影子跑和退场

---

## 2. 总体策略

迁移原则不是“把 SQL 原封不动翻译成 Kotlin”，而是：

1. 识别过程承担的业务职责
2. 找到输入、输出、事务边界和副作用
3. 在应用层拆成领域服务、仓储调用和事件
4. 保留一段时间对照校验
5. 稳定后彻底停止过程调用

迁移分为四种处理方式：

| 方式 | 说明 | 适用对象 |
| --- | --- | --- |
| `Retain` | 短期保留，仅旁路读取或对照 | 非核心、低频、暂未切换对象 |
| `Wrap` | 外部由新服务接入，内部短期仍调用过程 | 中风险过渡对象 |
| `Rewrite` | 完全在应用层重写 | 认证、钱包、支付、投注、结算主链路 |
| `Retire` | 完成切换后下线 | 已被新模型完全替代的对象 |

---

## 3. 优先级分层

### P0：必须最先迁移

- `web_user_login`
- `web_user_mobile_reg`
- `web_user_resetpwd`
- `web_user_changepwd`
- `withdrawals`
- `withdrawals2`
- `withdrawals3`
- `update_user_point`
- `web_AdminTrans`

### P1：第二波迁移

- `web_ChangeChenterBankItem`
- `web_kj_*`
- `web_tz_*`
- `web_tz_*_auto_new`
- `sys_game_generateno`

### P2：第三波迁移

- 代理、活动、补偿、机器人、报表类过程

---

## 4. 关键过程迁移清单

### 4.1 认证与账号

### `web_user_login`

老过程职责：

- 读取 `users`
- 校验用户名和密码
- 校验冻结状态
- 更新 `loginip` 和 `logintime`
- 写入 `userslog`
- 写入 `login_success` / `login_fail`
- 返回登录结果及余额、经验、头像、代理标识

新系统归属模块：

- `auth`
- `user`

新系统拆分方式：

- `AuthCommandService.login()`
- `UserSessionService.createSession()`
- `AuthAuditService.recordLoginSuccess()`
- `AuthAuditService.recordLoginFailure()`

迁移建议：

- 先兼容老 MD5 哈希
- 新登录成功后立即升级为新哈希
- 登录结果不要再直接从 `users` 回整包字段，改为聚合用户摘要 DTO

退场条件：

- 所有登录入口都走新 API
- 老 PHP 不再直接 `CALL web_user_login`

### `web_user_mobile_reg`

老过程职责：

- 校验用户名、昵称、敏感词
- 生成用户 ID 或使用传入 ID
- 从 `web_config` 取注册送分和登录经验
- 插入 `users`
- 更新 `centerbank`
- 更新 `webtj`
- 更新推荐层级人数
- 写登录成功记录

新系统归属模块：

- `auth`
- `user`
- `wallet`
- `agent`

新系统拆分方式：

- `RegistrationService.register()`
- `UserProfileService.initializeProfile()`
- `WalletBootstrapService.grantRegisterBonus()`
- `ReferralService.bindInviter()`
- `ReferralStatsProjector.onUserRegistered()`

迁移建议：

- 停止由数据库随机生成用户号，改为应用层统一发号
- 推荐层级统计改为事件驱动，不在注册事务内直接层层更新
- 注册奖励改为显式账变和资金池流水

退场条件：

- 新用户注册只写新库
- 老注册入口关闭或仅做跳转

### `web_user_resetpwd`

老过程职责：

- 按用户名直接更新登录密码

问题：

- 没有验证令牌、验证码、风控上下文
- 只是简单覆盖密码

新系统归属模块：

- `auth`

新系统拆分方式：

- `PasswordResetService.requestReset()`
- `PasswordResetService.confirmReset()`

迁移建议：

- 引入验证码或重置令牌
- 全程写安全审计日志
- 不允许无凭证直接改密

### `web_user_changepwd`

老过程职责：

- 修改登录密码或银行密码
- 校验旧密码
- 写 `userslog`
- 写 `changedetaillog`

新系统归属模块：

- `auth`
- `wallet`（仅资金密码的业务用途）

新系统拆分方式：

- `CredentialService.changeLoginPassword()`
- `CredentialService.changeFundPassword()`
- `SecurityAuditService.recordPasswordChange()`

迁移建议：

- 登录密码和资金密码独立策略
- 后台重置和短信重置单独场景化，不用 `p_OprUser='back'/'sms'` 这种字符串分支

---

### 4.2 钱包、提现与调账

### `withdrawals`

老过程职责：

- 校验冻结状态
- 校验银行密码
- 读取 `withdrawals` 收款账户
- 校验银行余额 `back`
- 从 `users.back` 扣款
- 增加 `lock_points`
- 写 `pay_online` 状态 `30`

新系统归属模块：

- `payment`
- `wallet`

新系统拆分方式：

- `WithdrawalAccountService.validateAccount()`
- `WithdrawalOrderService.submit()`
- `WalletHoldService.createHold()`
- `PaymentAuditService.recordWithdrawalSubmitted()`

迁移建议：

- 不再复用 `pay_online` 承载提现
- 提现单独建 `withdrawal_orders`
- 锁定金额必须形成正式 `wallet_holds`

### `withdrawals2`

老过程职责：

- 与 `withdrawals` 基本相同
- 额外计算费率 `_fee_rate`
- 把手续费写入 `pay_online.fee`

新系统归属模块：

- `payment`
- `wallet`

新系统拆分方式：

- `WithdrawalFeePolicy.calculateByRate()`
- `WithdrawalOrderService.submitWithRateFee()`

迁移建议：

- 手续费规则策略化，不再靠多个过程区分
- 手续费以毫豆和元双口径同时保留

### `withdrawals3`

老过程职责：

- 与 `withdrawals` 基本相同
- 手续费由外部直接传入固定值 `_cashfee`

新系统归属模块：

- `payment`

新系统拆分方式：

- `WithdrawalFeePolicy.applyFixedFee()`
- `WithdrawalOrderService.submitWithFixedFee()`

迁移建议：

- 将三套提现过程统一收敛为一个服务接口，手续费策略参数化

### `update_user_point`

老过程职责：

- 调整 `centerbank`
- 增加用户 `back`
- 更新 `game_static`

问题：

- 只改余额，不落正式统一账本
- 通过 `P_center_val` / `P_day_static_val` 传魔法数字

新系统归属模块：

- `wallet`
- `fund`

新系统拆分方式：

- `FundAccountService.transferToUserVault()`
- `WalletLedgerService.bookFundTransfer()`

迁移建议：

- 改为显式 `fund_account -> wallet_account` 转账
- `game_static` 改为报表投影，不再在核心事务中硬更新

### `web_AdminTrans`

老过程职责：

- 管理员对用户主余额、保险箱、经验、冻结额做人工操作
- 更新 `centerbank`
- 写 `score_log`
- 写 `admin_translog`

问题：

- 使用硬编码口令
- 调账权限模型弱
- 账务和审计模型不完整

新系统归属模块：

- `admin`
- `wallet`
- `fund`

新系统拆分方式：

- `AdminWalletAdjustmentService.adjustMainBalance()`
- `AdminWalletAdjustmentService.adjustVaultBalance()`
- `AdminWalletAdjustmentService.adjustFrozenBalance()`
- `AdminAuditService.recordAdjustment()`

迁移建议：

- 后台操作必须使用 RBAC + 二次确认
- 不再依赖过程内硬编码密码
- 每次调账必须生成正式账变和资金池账变

### `web_ChangeChenterBankItem`

老过程职责：

- 调整中央银行某账户
- 记录 `centerbank_changlog`

新系统归属模块：

- `fund`
- `admin`

新系统拆分方式：

- `FundAccountService.adjustBalance()`
- `FundLedgerService.recordAdjustment()`
- `AdminAuditService.recordFundChange()`

迁移建议：

- `centerbank` 数字账户改成业务码账户
- 快照和流水分离

---

### 4.3 开奖、下注与自动投注

### `web_kj_*`

老过程职责：

- 接收开奖结果
- 更新对应 `game*` 主表
- 写用户中奖结果
- 可能联动税金、统计和自动投注

新系统归属模块：

- `issue-draw`
- `settlement`

新系统拆分方式：

- `DrawIngestionService.persistDrawResult()`
- `IssueStateService.closeIssue()`
- `SettlementOrchestrator.startSettlement()`

迁移建议：

- 不同玩法的开奖结果解析进入策略类
- 入库和结算分离成两个事务阶段

### `web_tz_*`

老过程职责：

- 受理下注
- 校验期号、用户、余额、限额、赔率
- 扣减余额
- 写对应 `*_users_tz`
- 更新对应游戏统计

新系统归属模块：

- `betting`
- `wallet`

新系统拆分方式：

- `BetCommandService.placeBet()`
- `OddsSnapshotService.capture()`
- `WalletCommandService.debitForBet()`
- `BetProjectionService.updateIssueStats()`

迁移建议：

- 所有玩法共用统一投注单模型
- 不再按游戏复制投注表
- 统计改为异步投影，核心事务只保留订单和账本

### `web_tz_*_auto_new`

老过程职责：

- 自动投注续投
- 复制上一期或模板数据

新系统归属模块：

- `betting`
- `scheduler`

新系统拆分方式：

- `AutoBetRuleService`
- `AutoBetExecutionJob`

迁移建议：

- 自动投注规则单独建模
- 执行结果仍生成正式投注单和账变

### `sys_game_generateno`

老过程职责：

- 生成新期号

新系统归属模块：

- `issue-draw`

新系统拆分方式：

- `IssueGenerationJob`
- `IssueRepository`

迁移建议：

- 期号生成从数据库过程转为应用定时任务
- 使用分布式锁避免重复生成

---

### 4.4 推荐、活动与代理

这类过程数量多、耦合深，建议放在第三波迁移。处理原则如下：

- 返利类过程迁入 `campaign`
- 推荐关系和层级统计迁入 `agent`
- 统计和排行榜迁入报表投影层
- 所有奖励最后都回到 `wallet_ledger_entries`

---

## 5. 迁移执行模板

每个过程都按以下模板迁移：

1. 记录输入参数
2. 记录读写表
3. 记录业务副作用
4. 抽取领域动作
5. 编写应用层实现
6. 建立对照测试
7. 在灰度环境影子跑
8. 切流量
9. 下线过程调用

---

## 6. 推荐的退场顺序

建议按以下顺序停止过程调用：

1. `web_user_login`
2. `web_user_mobile_reg`
3. `web_user_resetpwd`
4. `web_user_changepwd`
5. `withdrawals / withdrawals2 / withdrawals3`
6. `update_user_point`
7. `web_AdminTrans`
8. `sys_game_generateno`
9. `web_kj_*`
10. `web_tz_*`
11. `web_tz_*_auto_new`

原因：

- 先切认证和钱包底座
- 再切支付和调账
- 最后切下注与开奖这一批最重、最复杂的过程

---

## 7. 对照校验清单

### 认证类

- 登录成功率
- 登录失败原因分布
- 会话创建结果
- 冻结用户拦截结果

### 钱包与提现类

- 用户余额变化
- 冻结金额变化
- 提现单状态
- 手续费结果

### 下注与结算类

- 投注单数量
- 投注明细
- 扣款金额
- 派奖金额
- 税金金额

---

## 8. 结论

这份迁移清单的核心判断是：

- 认证、钱包、提现、管理员调账必须优先重写
- 开奖和下注不应该直接平移过程，而应该按“期号服务 + 投注服务 + 结算服务”重新建模
- 所有历史过程最终都应退场，不建议长期保留“新系统外壳 + 老过程内核”的模式

下一步建议直接结合 [auth-user-wallet-design.md](./auth-user-wallet-design.md) 开始画第一版接口和数据库迁移脚本。
