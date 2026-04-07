# dd28 `auth + user + wallet` 设计稿

## 1. 文档范围

本文档只覆盖新系统第一阶段最核心的三个模块：

- `auth`
- `user`
- `wallet`

本文档要解决的问题：

- 老系统登录、注册、改密、余额模型如何迁到新系统
- 第一批 API 应该怎么定
- 第一批表结构如何落地
- 如何在不打断业务的前提下渐进切换

---

## 2. 当前遗留行为摘要

根据当前仓库和 `kdydata.sql`，与本设计直接相关的遗留事实如下：

- 登录依赖 `web_user_login`
- 注册依赖 `web_user_mobile_reg`
- 重置密码依赖 `web_user_resetpwd`
- 改密码依赖 `web_user_changepwd`
- 用户主档、密码、余额、冻结状态、推荐关系都在 `users`
- 钱包流水主要在 `score_log`
- 登录安全日志散在 `login_success`、`login_fail`、`userslog`
- 主余额在 `users.points`
- 保险箱余额在 `users.back`
- 冻结金额在 `users.lock_points`

结论：

- 老系统把身份、资料、安全、余额塞进同一张表
- 新系统第一阶段必须先拆出这三层

---

## 3. 模块边界

### `auth`

负责：

- 登录
- 注册
- Refresh Token
- 退出登录
- 改登录密码
- 改资金密码
- 密码重置
- 验证码
- 会话吊销

不负责：

- 昵称、头像、实名资料
- 钱包余额
- 提现订单

### `user`

负责：

- 用户主档
- 昵称、头像、VIP
- 状态、冻结原因
- 来源渠道
- 邀请人关系入口

不负责：

- 密码哈希
- 账务流水

### `wallet`

负责：

- 主余额
- 保险箱余额
- 冻结金额
- 统一账本
- 账变明细查询

不负责：

- 支付渠道接入
- 提现审核流程

---

## 4. 目标能力清单

第一阶段必须交付的能力：

1. 新用户注册
2. 老用户登录
3. 登录后获取用户摘要
4. 改登录密码
5. 改资金密码
6. 发验证码与校验验证码
7. 查询钱包余额
8. 查询账变明细
9. 管理员或系统可写测试账变

第一阶段暂不强制交付：

- 实名认证全流程
- 提现申请
- 充值下单
- 下注扣款

这些功能会依赖 `wallet`，但不在本稿内展开。

---

## 5. API 设计

### 5.1 认证 API

### `POST /api/v1/auth/register`

用途：

- 新用户注册

请求体：

```json
{
  "username": "test001",
  "nickname": "测试用户",
  "password": "PlainPassword",
  "mobile": "13800000000",
  "inviteCode": "928312",
  "source": "h5-campaign-a"
}
```

响应体：

```json
{
  "userId": "12000001",
  "username": "test001",
  "nickname": "测试用户",
  "accessToken": "xxx",
  "refreshToken": "xxx",
  "expiresIn": 7200
}
```

服务动作：

- 校验用户名和昵称
- 创建 `user_accounts`
- 创建 `user_profiles`
- 创建 `user_credentials`
- 初始化 `wallet_accounts`
- 如有注册奖励则写入 `wallet_ledger_entries`
- 建立会话

### `POST /api/v1/auth/login/password`

用途：

- 账号密码登录

请求体：

```json
{
  "username": "test001",
  "password": "PlainPassword",
  "clientType": "mobile",
  "deviceId": "ios-xxxx",
  "deviceName": "iPhone 15"
}
```

响应体：

```json
{
  "user": {
    "userId": "12000001",
    "username": "test001",
    "nickname": "测试用户",
    "status": "ACTIVE"
  },
  "accessToken": "xxx",
  "refreshToken": "xxx",
  "expiresIn": 7200
}
```

登录规则：

- 兼容老 MD5 密码校验
- 若校验走的是老哈希，登录成功后升级为新哈希
- 被冻结用户返回明确错误码
- 记录登录成功或失败审计

### `POST /api/v1/auth/token/refresh`

用途：

- 刷新访问令牌

请求体：

```json
{
  "refreshToken": "xxx"
}
```

### `POST /api/v1/auth/logout`

用途：

- 注销当前设备会话

### `POST /api/v1/auth/password/change`

用途：

- 修改登录密码

请求体：

```json
{
  "oldPassword": "old",
  "newPassword": "new"
}
```

### `POST /api/v1/auth/fund-password/change`

用途：

- 修改资金密码

请求体：

```json
{
  "oldFundPassword": "old",
  "newFundPassword": "new"
}
```

### `POST /api/v1/auth/password/reset/request`

用途：

- 请求重置密码验证码

### `POST /api/v1/auth/password/reset/confirm`

用途：

- 校验验证码并重置密码

---

### 5.2 用户 API

### `GET /api/v1/users/me`

响应体建议：

```json
{
  "userId": "12000001",
  "username": "test001",
  "nickname": "测试用户",
  "avatarUrl": "https://...",
  "status": "ACTIVE",
  "vipLevel": 1,
  "source": "h5-campaign-a",
  "inviterUserId": "928312"
}
```

### `PATCH /api/v1/users/me/profile`

用途：

- 修改昵称、头像等非敏感资料

### `GET /api/v1/users/me/security`

用途：

- 返回安全概况

响应字段建议：

- 是否设置资金密码
- 最近改密时间
- 最近登录时间
- 最近登录 IP

---

### 5.3 钱包 API

### `GET /api/v1/wallets/me`

响应体：

```json
{
  "mainBalance": "1280.500",
  "vaultBalance": "320.000",
  "frozenBalance": "100.000",
  "currency": "MILLI_BEAN"
}
```

### `GET /api/v1/wallets/me/ledger`

查询参数：

- `bizType`
- `from`
- `to`
- `page`
- `pageSize`

响应体字段建议：

- `entryNo`
- `bizType`
- `direction`
- `amount`
- `balanceBefore`
- `balanceAfter`
- `remark`
- `createdAt`

### `POST /internal/v1/wallets/{userId}/adjustments`

用途：

- 内部接口，仅供管理员调账、注册奖励、测试初始化等调用

请求体：

```json
{
  "walletType": "VAULT",
  "direction": "CREDIT",
  "amountMilli": 100000,
  "bizType": "REGISTER_BONUS",
  "bizId": "reg-12000001",
  "operatorType": "SYSTEM",
  "remark": "注册奖励"
}
```

要求：

- 幂等
- 记账前后余额完整
- 失败可重试

---

## 6. 数据库设计

### 6.1 必需表

第一阶段必需建表：

- `user_accounts`
- `user_profiles`
- `user_credentials`
- `user_sessions`
- `verification_codes`
- `auth_audit_logs`
- `wallet_accounts`
- `wallet_ledger_entries`
- `wallet_holds`

这些表的草案见 [domain-model.md](./domain-model.md)。

### 6.2 补充索引建议

### `user_accounts`

- `uk_user_accounts_username`
- `uk_user_accounts_mobile`
- `idx_user_accounts_status`

### `auth_audit_logs`

- `idx_auth_audit_logs_user_time`
- `idx_auth_audit_logs_event_time`

### `wallet_ledger_entries`

- `uk_wallet_ledger_entries_entry_no`
- `uk_wallet_ledger_entries_biz`
- `idx_wallet_ledger_entries_user_time`

### `user_sessions`

- `uk_user_sessions_refresh_jti`
- `idx_user_sessions_user_id`

---

## 7. 服务设计

### 7.1 `AuthCommandService`

职责：

- 注册
- 登录
- 登出
- 刷新令牌
- 改密
- 密码重置

核心方法建议：

- `register(RegisterCommand)`
- `loginByPassword(LoginCommand)`
- `refresh(RefreshTokenCommand)`
- `logout(LogoutCommand)`
- `changeLoginPassword(ChangePasswordCommand)`
- `changeFundPassword(ChangeFundPasswordCommand)`
- `requestResetPassword(RequestResetPasswordCommand)`
- `confirmResetPassword(ConfirmResetPasswordCommand)`

### 7.2 `UserQueryService`

职责：

- 获取用户摘要
- 获取用户资料
- 获取安全概况

### 7.3 `WalletCommandService`

职责：

- 记账
- 冻结
- 解冻
- 扣减
- 增加

核心方法建议：

- `credit()`
- `debit()`
- `hold()`
- `releaseHold()`
- `transferBetweenWallets()`

### 7.4 `WalletQueryService`

职责：

- 查询余额
- 查询账变

---

## 8. 关键流程设计

### 8.1 注册流程

```text
客户端提交注册
-> auth 校验用户名/昵称/密码强度
-> user 创建 account/profile
-> auth 创建 credentials
-> wallet 初始化账户
-> 如有注册奖励则写账本
-> auth 创建 session
-> 返回 token + 用户摘要
```

迁移注意点：

- 老系统可能允许用户名为空场景生成 `ru__` 编号，这类规则要单独梳理
- 邀请关系绑定不要直接在注册事务里更新三层统计

### 8.2 登录流程

```text
客户端提交账号密码
-> auth 读取 account + credentials
-> 校验状态
-> 兼容验证 legacy hash
-> 成功后建立 session
-> 写 auth_audit_logs
-> 返回 token + 用户摘要
```

迁移注意点：

- 先兼容老哈希，再逐步升级
- 老系统异地登录用 `logintime` 对比，新系统改为设备会话模型

### 8.3 改登录密码流程

```text
校验当前密码
-> 更新 credentials.login_password_hash
-> 吊销其他设备或标记二次验证
-> 写安全审计
```

### 8.4 改资金密码流程

```text
校验旧资金密码
-> 更新 credentials.fund_password_hash
-> 写安全审计
```

### 8.5 钱包查询流程

```text
读取 wallet_accounts
-> 按格式化规则输出 main/vault/frozen
```

### 8.6 账变写入流程

```text
接收业务命令
-> 校验幂等键
-> 锁定 wallet_accounts
-> 更新余额
-> 插入 wallet_ledger_entries
-> 提交事务
```

---

## 9. 兼容迁移设计

### 9.1 登录兼容

迁移期建议：

- 首次登录时先按老哈希尝试
- 成功后立即转成新哈希
- 用户无需统一强制重置密码

### 9.2 用户主数据迁移

从老表迁出：

- `users.username`
- `users.mobile`
- `users.email`
- `users.nickname`
- `users.head`
- `users.vip`
- `users.vipdate`
- `users.tjid`
- `users.source`
- `users.dj`
- `users.djly`

拆分目标：

- `user_accounts`
- `user_profiles`
- `user_relations`

### 9.3 钱包迁移

从老表迁出：

- `users.points -> wallet_accounts.main_balance`
- `users.back -> wallet_accounts.vault_balance`
- `users.lock_points -> wallet_accounts.frozen_balance`

注意：

- 这一步只迁余额快照，不迁全量流水
- 老 `score_log` 作为历史查询源保留一段时间

### 9.4 流水迁移

建议分两步：

1. 新系统上线后只写新账本
2. 历史流水按时间窗口分批导入，映射成统一 `biz_type`

---

## 10. 错误码建议

认证与用户钱包至少统一以下错误码：

| 错误码 | 含义 |
| --- | --- |
| `AUTH_INVALID_CREDENTIALS` | 用户名或密码错误 |
| `AUTH_ACCOUNT_FROZEN` | 账号被冻结 |
| `AUTH_SESSION_EXPIRED` | 会话失效 |
| `AUTH_REFRESH_TOKEN_INVALID` | Refresh Token 无效 |
| `AUTH_PASSWORD_OLD_MISMATCH` | 原密码错误 |
| `AUTH_FUND_PASSWORD_OLD_MISMATCH` | 原资金密码错误 |
| `USER_NICKNAME_DUPLICATED` | 昵称重复 |
| `USER_USERNAME_DUPLICATED` | 用户名重复 |
| `WALLET_INSUFFICIENT_BALANCE` | 余额不足 |
| `WALLET_IDEMPOTENT_CONFLICT` | 幂等冲突 |

---

## 11. 安全要求

- 所有密码必须使用强哈希
- Refresh Token 必须可吊销
- 改密后要写审计
- 验证码只存哈希，不存明文
- 资金密码相关操作必须具备单独风控规则

---

## 12. 测试清单

### 认证

- 用户名不存在
- 密码错误
- 冻结用户
- 老哈希兼容登录
- 登录成功后新哈希升级
- Refresh Token 吊销

### 注册

- 用户名重复
- 昵称重复
- 推荐码为空
- 推荐码存在
- 注册奖励记账成功

### 改密

- 原密码错误
- 原资金密码错误
- 后台重置密码
- 短信验证码重置密码

### 钱包

- 增加余额
- 扣减余额
- 冻结和解冻
- 并发记账
- 幂等重复提交

---

## 13. 建议的实施顺序

1. 先建表
2. 再写 `auth` 登录注册改密
3. 再写 `wallet` 记账内核
4. 再补 `user` 查询和编辑
5. 再接支付和提现

这样做的原因很简单：

- 没有统一身份和账本，后面的支付、下注、结算都会反复返工

---

## 14. 下一步建议

建议下一轮直接继续补：

1. `Flyway` 初版建表脚本目录规划
2. `auth + user + wallet` 的 OpenAPI 草案
3. 登录、注册、记账三个核心时序图
