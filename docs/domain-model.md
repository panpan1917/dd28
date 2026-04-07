# dd28 领域模型与核心表结构草案

## 1. 文档目的

本文档用于把老系统的表、存储过程和业务流，收敛为新系统的领域模型、聚合边界和核心表结构草案。

本文档是 [plans.md](./plans.md) 和 [execplan.md](./execplan.md) 的落地补充，重点回答三个问题：

- 老系统的业务对象在新系统里怎么拆
- 新系统的核心表应该如何设计
- 哪些对象需要先迁，哪些对象可以延后

---

## 2. 基于当前仓库确认的遗留事实

已确认的老系统关键对象包括：

- 用户主表：`users`
- 登录成功/失败日志：`login_success`、`login_fail`
- 用户行为日志：`userslog`
- 乐豆明细：`score_log`
- 充值/提现订单：`pay_online`
- 提现账户：`withdrawals`
- 中央银行及快照：`centerbank`、`centerbank_snap`、`centerbank_changlog`
- 游戏配置：`game_config`
- 游戏期号与开奖表族：如 `game28`
- 投注记录表族：如 `game28_users_tz`
- 变更审计辅助表：`update_point`、`update_tz`

老系统的领域问题也已基本明确：

- 用户、钱包、订单、下注、开奖、活动的边界没有被清晰建模
- 大量规则沉在存储过程里
- 游戏数据按玩法复制表族，缺乏统一抽象
- 钱包流水、订单流水、活动流水、后台操作流水彼此割裂

---

## 3. 新系统的限界上下文

建议把新系统划分为以下限界上下文：

| 上下文 | 核心职责 | 典型输入 | 典型输出 |
| --- | --- | --- | --- |
| `identity` | 用户身份、账号认证、密码、设备会话、验证码 | 登录、注册、改密 | 用户身份、访问令牌 |
| `profile` | 用户资料、状态、等级、实名、来源、推荐关系入口 | 资料更新、状态查询 | 用户画像、用户状态 |
| `wallet` | 主余额、保险箱余额、冻结金额、账变流水 | 充值入账、下注扣款、提现冻结 | 账户余额、账本流水 |
| `payment` | 充值订单、提现订单、支付渠道、出款审核 | 下单、回调、审核 | 订单状态、对账结果 |
| `game-catalog` | 游戏定义、玩法、赔率、限额、停盘配置 | 后台配置、前端查询 | 游戏元数据 |
| `issue-draw` | 期号生成、开奖抓取、结果发布 | 定时触发、第三方开奖源 | 开奖结果、事件 |
| `betting` | 投注受理、赔率快照、限额、自动投注 | 用户下注请求 | 投注单、扣款指令 |
| `settlement` | 开奖结算、派奖、补偿、返利触发 | 开奖事件、投注单 | 派奖流水、奖励记录 |
| `campaign` | 首充返利、日返、周返、红包、排行榜奖励 | 活动配置、结算事件 | 奖励记录 |
| `agent` | 邀请关系、代理树、返佣、团队统计 | 注册事件、结算事件 | 代理关系、返佣记录 |
| `admin` | 后台权限、配置、审计、人工操作 | 审核、调账、停盘 | 审计日志、配置变更 |

---

## 4. 聚合设计

### 4.1 身份聚合

核心聚合：

- `UserAccount`
- `UserCredential`
- `UserSession`
- `VerificationCode`

聚合原则：

- 登录名、手机号、设备会话属于身份域
- 密码、银行密码、改密记录都必须脱离 `users` 大表，进入独立安全模型
- `profile` 只读身份域主键，不直接管理密码

### 4.2 用户资料聚合

核心聚合：

- `UserProfile`
- `UserStatus`
- `UserRelation`

聚合原则：

- 昵称、头像、VIP、来源、实名字段属于资料域
- 冻结状态、黑白名单、风险标记属于资料域和风控域协作对象
- 推荐关系属于独立关系对象，不继续塞在用户大表里累计维护

### 4.3 钱包聚合

核心聚合：

- `WalletAccount`
- `LedgerEntry`
- `WalletHold`

聚合原则：

- 钱包余额只保留一个权威来源
- 所有变更必须通过账本
- 充值、提现、下注、派奖、返利、后台调账都必须映射到统一账变类型

### 4.4 支付聚合

核心聚合：

- `DepositOrder`
- `WithdrawalAccount`
- `WithdrawalOrder`
- `ChannelTransaction`

聚合原则：

- 提现账户与提现订单分离
- 充值和提现订单状态机分离
- 渠道回调日志独立落表，避免和业务订单混杂

### 4.5 游戏与投注聚合

核心聚合：

- `GameType`
- `GameIssue`
- `BetOrder`
- `BetOrderItem`
- `OddsSnapshot`

聚合原则：

- 游戏元数据统一存储
- 投注单和投注项统一建模
- 不再按玩法复制整套 `*_users_tz` 表

### 4.6 结算与奖励聚合

核心聚合：

- `SettlementBatch`
- `SettlementRecord`
- `CampaignReward`
- `AgentCommission`

聚合原则：

- 开奖结算和活动奖励分层建模
- 任意奖励最终都应回到 `wallet` 域记账

---

## 5. 老表到新模型的映射

| 老对象 | 现状问题 | 新对象 |
| --- | --- | --- |
| `users` | 字段过多，身份、资料、余额、安全、代理、状态混在一起 | `user_accounts`、`user_profiles`、`user_credentials`、`wallet_accounts`、`user_relations` |
| `login_success` / `login_fail` | 仅做日志，没有统一会话模型 | `user_sessions`、`auth_audit_logs` |
| `userslog` | 类型粗糙，行为和安全日志混在一起 | `user_audit_logs`、`security_audit_logs` |
| `score_log` | 流水粒度不统一，缺少业务幂等键 | `wallet_ledger_entries` |
| `pay_online` | 充值和提现共表，状态复用，语义混乱 | `deposit_orders`、`withdrawal_orders` |
| `withdrawals` | 账户和订单语义分离不清 | `withdrawal_accounts` |
| `centerbank` | 资金池总账和配置项混在一起 | `fund_accounts`、`fund_account_snapshots` |
| `game_config` | 配置体积大，赔率和玩法编码塞在字符串字段 | `game_types`、`game_play_rules`、`game_limit_rules`、`odds_templates` |
| `game28` 等期号表 | 每个游戏一张主表 | `game_issues`、`draw_results` |
| `game28_users_tz` 等投注表 | 每个游戏一张投注表 | `bet_orders`、`bet_order_items` |
| `update_tz` | 作为变更修补痕迹，不是正式模型 | `bet_order_amendments` 或审计日志 |
| `update_point` | 辅助记录，不是强账本 | `wallet_ledger_entries` |

---

## 6. 推荐的核心表结构

以下是新系统第一阶段到第五阶段最核心的一组表，不是全部表，但足够支撑迁移主线。

### 6.1 用户身份与资料

#### `user_accounts`

```sql
create table user_accounts (
  id bigint primary key,
  username varchar(64) not null,
  mobile varchar(32),
  email varchar(128),
  source varchar(128),
  user_type varchar(32) not null,
  status varchar(32) not null,
  freeze_reason varchar(255),
  created_at datetime not null,
  updated_at datetime not null,
  last_login_at datetime,
  last_login_ip varchar(64),
  unique key uk_user_accounts_username (username),
  unique key uk_user_accounts_mobile (mobile),
  unique key uk_user_accounts_email (email)
);
```

字段说明：

- `user_type` 取代老表中的 `usertype`、`isagent` 组合语义
- `status` 取代 `dj` 这种单字段冻结模式
- 不再把头像、昵称、密码、余额塞进同一张表

#### `user_profiles`

```sql
create table user_profiles (
  user_id bigint primary key,
  nickname varchar(64) not null,
  avatar_url varchar(255),
  real_name varchar(64),
  id_card_no varchar(64),
  gender varchar(16),
  birthday date,
  qq varchar(32),
  phone varchar(32),
  address varchar(255),
  education varchar(64),
  job varchar(64),
  bio varchar(255),
  vip_level int not null default 0,
  vip_expire_at datetime,
  created_at datetime not null,
  updated_at datetime not null
);
```

#### `user_credentials`

```sql
create table user_credentials (
  user_id bigint primary key,
  login_password_hash varchar(255) not null,
  fund_password_hash varchar(255),
  password_algo varchar(32) not null,
  password_updated_at datetime not null,
  fund_password_updated_at datetime,
  created_at datetime not null,
  updated_at datetime not null
);
```

设计原则：

- 老系统 MD5 密码只作为迁移兼容输入，不作为长期存储标准
- 新系统使用 `Argon2id` 或 `bcrypt`
- 迁移期允许“首次登录自动升级哈希”

#### `user_sessions`

```sql
create table user_sessions (
  id bigint primary key auto_increment,
  user_id bigint not null,
  session_type varchar(32) not null,
  access_jti varchar(64) not null,
  refresh_jti varchar(64) not null,
  device_id varchar(128),
  device_name varchar(128),
  client_type varchar(32) not null,
  login_ip varchar(64),
  login_at datetime not null,
  expires_at datetime not null,
  revoked_at datetime,
  revoke_reason varchar(128),
  unique key uk_user_sessions_refresh_jti (refresh_jti),
  key idx_user_sessions_user_id (user_id, revoked_at, expires_at)
);
```

### 6.2 验证码与安全审计

#### `verification_codes`

```sql
create table verification_codes (
  id bigint primary key auto_increment,
  user_id bigint,
  scene varchar(32) not null,
  receiver varchar(128) not null,
  code_hash varchar(255) not null,
  provider varchar(64),
  send_status varchar(32) not null,
  sent_at datetime not null,
  expire_at datetime not null,
  verify_times int not null default 0,
  verified_at datetime,
  fail_reason varchar(255)
);
```

#### `auth_audit_logs`

```sql
create table auth_audit_logs (
  id bigint primary key auto_increment,
  user_id bigint,
  event_type varchar(64) not null,
  event_result varchar(32) not null,
  ip varchar(64),
  client_type varchar(32),
  detail_json json,
  occurred_at datetime not null,
  key idx_auth_audit_logs_user_time (user_id, occurred_at)
);
```

该表用于吸收老系统的：

- `login_success`
- `login_fail`
- `userslog` 中的登录和改密类事件

### 6.3 钱包与账本

#### `wallet_accounts`

```sql
create table wallet_accounts (
  user_id bigint primary key,
  main_balance bigint not null default 0,
  vault_balance bigint not null default 0,
  frozen_balance bigint not null default 0,
  total_deposit bigint not null default 0,
  total_withdraw bigint not null default 0,
  total_bet bigint not null default 0,
  total_payout bigint not null default 0,
  version bigint not null default 0,
  created_at datetime not null,
  updated_at datetime not null
);
```

金额规则：

- 内部统一使用“千分单位整数”
- 兼容老系统 `points / back / lock_points`

#### `wallet_ledger_entries`

```sql
create table wallet_ledger_entries (
  id bigint primary key auto_increment,
  user_id bigint not null,
  entry_no varchar(64) not null,
  biz_type varchar(64) not null,
  biz_id varchar(64) not null,
  wallet_type varchar(32) not null,
  direction varchar(16) not null,
  amount bigint not null,
  balance_before bigint not null,
  balance_after bigint not null,
  frozen_before bigint not null default 0,
  frozen_after bigint not null default 0,
  remark varchar(255),
  operator_type varchar(32) not null,
  operator_id varchar(64),
  trace_id varchar(64),
  created_at datetime not null,
  unique key uk_wallet_ledger_entries_entry_no (entry_no),
  unique key uk_wallet_ledger_entries_biz (biz_type, biz_id, wallet_type, direction),
  key idx_wallet_ledger_entries_user_time (user_id, created_at)
);
```

该表用于取代：

- `score_log`
- `update_point`
- 部分 `userslog`

#### `wallet_holds`

```sql
create table wallet_holds (
  id bigint primary key auto_increment,
  user_id bigint not null,
  hold_no varchar(64) not null,
  biz_type varchar(64) not null,
  biz_id varchar(64) not null,
  amount bigint not null,
  status varchar(32) not null,
  created_at datetime not null,
  released_at datetime,
  unique key uk_wallet_holds_hold_no (hold_no),
  unique key uk_wallet_holds_biz (biz_type, biz_id)
);
```

作用：

- 承接提现冻结、下注预冻结、补偿冻结等场景

### 6.4 充值、提现与资金池

#### `deposit_orders`

```sql
create table deposit_orders (
  id bigint primary key auto_increment,
  order_no varchar(64) not null,
  user_id bigint not null,
  channel_code varchar(64) not null,
  amount_yuan decimal(18,2) not null,
  amount_milli bigint not null,
  give_amount_milli bigint not null default 0,
  status varchar(32) not null,
  channel_order_no varchar(128),
  qr_code_url varchar(4000),
  request_ip varchar(64),
  submitted_at datetime not null,
  paid_at datetime,
  failed_at datetime,
  fail_reason varchar(255),
  source varchar(64),
  unique key uk_deposit_orders_order_no (order_no),
  unique key uk_deposit_orders_channel_order_no (channel_order_no)
);
```

#### `withdrawal_accounts`

```sql
create table withdrawal_accounts (
  id bigint primary key auto_increment,
  user_id bigint not null,
  account_type varchar(32) not null,
  account_name varchar(128),
  account_no varchar(128) not null,
  bank_name varchar(128),
  bank_branch varchar(255),
  province varchar(64),
  city varchar(64),
  is_default tinyint not null default 0,
  status varchar(32) not null,
  created_at datetime not null,
  updated_at datetime not null,
  key idx_withdrawal_accounts_user_id (user_id, status)
);
```

#### `withdrawal_orders`

```sql
create table withdrawal_orders (
  id bigint primary key auto_increment,
  order_no varchar(64) not null,
  user_id bigint not null,
  withdrawal_account_id bigint not null,
  amount_yuan decimal(18,2) not null,
  amount_milli bigint not null,
  fee_yuan decimal(18,2) not null default 0,
  fee_milli bigint not null default 0,
  actual_yuan decimal(18,2) not null,
  actual_milli bigint not null,
  status varchar(32) not null,
  review_status varchar(32) not null,
  reject_reason varchar(255),
  request_ip varchar(64),
  submitted_at datetime not null,
  reviewed_at datetime,
  paid_at datetime,
  canceled_at datetime,
  ledger_hold_no varchar(64) not null,
  unique key uk_withdrawal_orders_order_no (order_no),
  unique key uk_withdrawal_orders_hold_no (ledger_hold_no)
);
```

#### `fund_accounts`

```sql
create table fund_accounts (
  code varchar(64) primary key,
  name varchar(128) not null,
  balance_milli bigint not null,
  enabled tinyint not null default 1,
  updated_at datetime not null
);
```

该表用于承接老系统 `centerbank` 的语义，但建议从数字 `bankIdx` 改成业务码，例如：

- `SYSTEM_SCORE`
- `ROBOT_SCORE`
- `ADMIN_SCORE`
- `ONLINE_PAY_SCORE`
- `ACTIVITY_SCORE`
- `GAME_TAX_SCORE`

#### `fund_account_ledger_entries`

```sql
create table fund_account_ledger_entries (
  id bigint primary key auto_increment,
  fund_code varchar(64) not null,
  biz_type varchar(64) not null,
  biz_id varchar(64) not null,
  amount_milli bigint not null,
  balance_before bigint not null,
  balance_after bigint not null,
  operator_id varchar(64),
  remark varchar(255),
  created_at datetime not null,
  key idx_fund_account_ledger_entries_fund_time (fund_code, created_at)
);
```

#### `fund_account_snapshots`

```sql
create table fund_account_snapshots (
  id bigint primary key auto_increment,
  snapshot_at datetime not null,
  fund_code varchar(64) not null,
  balance_milli bigint not null,
  remark varchar(255),
  key idx_fund_account_snapshots_time (snapshot_at, fund_code)
);
```

### 6.5 游戏、期号、开奖

#### `game_types`

```sql
create table game_types (
  id int primary key,
  code varchar(64) not null,
  name varchar(64) not null,
  category varchar(64) not null,
  issue_interval_sec int not null,
  close_before_draw_sec int not null,
  draw_delay_sec int not null,
  min_bet_milli bigint not null,
  max_bet_milli bigint not null,
  status varchar(32) not null,
  stop_reason varchar(255),
  sort_no int not null default 0,
  unique key uk_game_types_code (code)
);
```

#### `game_play_rules`

```sql
create table game_play_rules (
  id bigint primary key auto_increment,
  game_type_id int not null,
  play_code varchar(64) not null,
  play_name varchar(128) not null,
  play_group varchar(64) not null,
  odds_mode varchar(32) not null,
  base_odds decimal(18,6) not null,
  min_bet_milli bigint not null,
  max_bet_milli bigint not null,
  state varchar(32) not null,
  config_json json not null,
  unique key uk_game_play_rules_code (game_type_id, play_code)
);
```

#### `game_issues`

```sql
create table game_issues (
  id bigint primary key auto_increment,
  game_type_id int not null,
  issue_no varchar(64) not null,
  official_issue_no varchar(64),
  open_time datetime not null,
  close_time datetime not null,
  draw_time datetime not null,
  status varchar(32) not null,
  draw_source varchar(64),
  draw_result_raw varchar(255),
  draw_result_value varchar(255),
  created_at datetime not null,
  updated_at datetime not null,
  unique key uk_game_issues_type_issue (game_type_id, issue_no)
);
```

#### `draw_results`

```sql
create table draw_results (
  id bigint primary key auto_increment,
  issue_id bigint not null,
  result_raw varchar(255) not null,
  result_json json not null,
  published_at datetime not null,
  source_latency_ms bigint,
  source_payload json,
  unique key uk_draw_results_issue_id (issue_id)
);
```

### 6.6 投注与结算

#### `bet_orders`

```sql
create table bet_orders (
  id bigint primary key auto_increment,
  order_no varchar(64) not null,
  user_id bigint not null,
  game_type_id int not null,
  issue_id bigint not null,
  total_bet_milli bigint not null,
  total_payout_milli bigint not null default 0,
  total_win_loss_milli bigint not null default 0,
  status varchar(32) not null,
  source varchar(32) not null,
  odds_version varchar(64),
  created_at datetime not null,
  settled_at datetime,
  unique key uk_bet_orders_order_no (order_no),
  key idx_bet_orders_user_issue (user_id, issue_id, status)
);
```

#### `bet_order_items`

```sql
create table bet_order_items (
  id bigint primary key auto_increment,
  bet_order_id bigint not null,
  play_code varchar(64) not null,
  play_name varchar(128) not null,
  bet_content varchar(255) not null,
  bet_amount_milli bigint not null,
  odds decimal(18,6) not null,
  payout_milli bigint not null default 0,
  result_status varchar(32),
  snapshot_json json not null,
  key idx_bet_order_items_order_id (bet_order_id)
);
```

#### `settlement_records`

```sql
create table settlement_records (
  id bigint primary key auto_increment,
  issue_id bigint not null,
  bet_order_id bigint not null,
  user_id bigint not null,
  payout_milli bigint not null,
  tax_milli bigint not null default 0,
  win_loss_milli bigint not null,
  settlement_status varchar(32) not null,
  ledger_entry_no varchar(64),
  settled_at datetime not null,
  unique key uk_settlement_records_bet_order_id (bet_order_id)
);
```

---

## 7. 核心关系图

```text
user_accounts 1---1 user_profiles
user_accounts 1---1 user_credentials
user_accounts 1---n user_sessions
user_accounts 1---1 wallet_accounts
user_accounts 1---n wallet_ledger_entries
user_accounts 1---n withdrawal_accounts
user_accounts 1---n deposit_orders
user_accounts 1---n withdrawal_orders
user_accounts 1---n bet_orders

game_types 1---n game_play_rules
game_types 1---n game_issues
game_issues 1---1 draw_results
game_issues 1---n bet_orders
bet_orders 1---n bet_order_items
bet_orders 1---1 settlement_records

fund_accounts 1---n fund_account_ledger_entries
fund_accounts 1---n fund_account_snapshots
```

---

## 8. 首批必须落地的对象

第一批必须先建：

- `user_accounts`
- `user_profiles`
- `user_credentials`
- `user_sessions`
- `verification_codes`
- `wallet_accounts`
- `wallet_ledger_entries`
- `withdrawal_accounts`
- `deposit_orders`
- `withdrawal_orders`
- `fund_accounts`
- `fund_account_ledger_entries`

原因：

- 这是 `auth + user + wallet + payment` 的底座
- 它们直接替代老系统里最高风险、最混乱的一层

---

## 9. 后迁对象

以下对象可以在第二批和第三批补齐：

- `game_play_rules`
- `game_issues`
- `draw_results`
- `bet_orders`
- `bet_order_items`
- `settlement_records`
- `campaign_reward_records`
- `agent_commission_records`
- `admin_audit_logs`

---

## 10. 建模约束

### 10.1 金额约束

- 内部金额统一使用 `bigint` 毫豆单位
- 对外接口统一返回格式化金额字符串或 decimal 字段
- 所有账务写入必须有幂等键

### 10.2 状态约束

- 所有订单都采用明确状态机，不复用一个整数状态字段承载多种业务
- 状态流转要保留时间戳

### 10.3 审计约束

- 改密、登录、冻结、调账、审核、派奖都必须写审计日志
- 审计日志不能再通过多个历史表拼出来

### 10.4 扩展约束

- 玩法差异进入 `config_json` 或扩展表
- 不再允许新增一类游戏就复制五张以上新表

---

## 11. 建议的下一步

在本文档基础上，建议下一步继续推进：

1. 根据本表结构草案输出 `Flyway` 初版迁移脚本清单
2. 先把 `auth + user + wallet` 对应表收敛成第一版 ER 图
3. 对照 [procedure-migration.md](./procedure-migration.md) 把关键存储过程拆成服务职责
