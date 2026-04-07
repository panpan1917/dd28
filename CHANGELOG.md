──────────────────────────────────────────────
时间：2026-04-06 22:09:43 JST

**整体判断**
这个仓库本质上是一个完整的 PHP 竞猜/游戏运营系统，而不是单一网站。它同时包含前台 PC 站、移动站、后台管理、开奖采集脚本、返利结算脚本，以及一份大型 MySQL 快照。真正的业务主干是“用户账户 -> 充值提现 -> 下注 -> 开奖 -> 返利/代理 -> 后台运营”。

它的代码风格明显是 2016-2018 年的传统 PHP：自研 MVC、脚本页、jQuery、早期 Vue、自定义模板标签、手写 SQL、存储过程和定时脚本并存。你如果只看页面会觉得杂，但把入口、框架、业务、数据库四层连起来以后，结构其实是清楚的。

**先看入口**
1. 根入口是 [main/index.php](/Users/uchi/dark/web_project/dddd188.com/main/index.php#L1)。它只负责判断是否移动端，然后把请求跳到 `pcindex.php` 或 `mobile.php`。
2. PC 老首页 [main/pcindex.php](/Users/uchi/dark/web_project/dddd188.com/main/pcindex.php#L1) 是典型的“直出脚本页”：自己引数据库连接、自己写 SQL、自己拼 HTML。
3. 真正的 PC MVC 入口是 [main/b.php](/Users/uchi/dark/web_project/dddd188.com/main/b.php#L1)，移动端 MVC 入口是 [main/mobile.php](/Users/uchi/dark/web_project/dddd188.com/main/mobile.php#L1)。
4. 这两个入口都会载入 [core/ini.php](/Users/uchi/dark/web_project/dddd188.com/core/ini.php#L1)，然后交给 [core/controller/Controller.php](/Users/uchi/dark/web_project/dddd188.com/core/controller/Controller.php#L1) 用 `c` 和 `a` 参数去实例化对应的 `Action` 类和方法。
5. 所以这个仓库不是单一架构，而是“旧式 `main/*.php` 页面”和“`b.php`/`mobile.php` + Action 控制器”并存的过渡态。
6. 后台又是第三套体系：[admin/index.php](/Users/uchi/dark/web_project/dddd188.com/admin/index.php#L1) 直接用 `frameset` 组织顶部、左侧菜单和右侧工作区，和前台框架几乎独立。

**框架层怎么跑**
- [core/ini.php](/Users/uchi/dark/web_project/dddd188.com/core/ini.php#L1) 是启动核心。它定义常量、注册 `__autoload`、加载数据库配置、挂异常处理、再做一轮基于正则的全局参数拦截。
- 它的自动加载顺序很关键：先找 `core/controller`，再找 `core/base`，再找 `lib/<app>/action`，最后找 `lib/util`。这说明项目的“框架层”和“业务层”是硬耦合在目录约定上的。
- [core/controller/Action.php](/Users/uchi/dark/web_project/dddd188.com/core/controller/Action.php) 是控制器基类，负责 `assign`、`display`、`fetch`，并根据 `APP_NAME` 选择模板目录。
- [core/controller/View.php](/Users/uchi/dark/web_project/dddd188.com/core/controller/View.php#L1) 是自定义模板引擎。它把 `.inc` 模板解析成 PHP，写到 `data/compiled/` 下，再 `include` 编译后的文件。
- 这个模板引擎支持 `{if}`、`{loop}`、`{tpl}`、`{$var}` 这样的自定义标签，所以模板不是 Twig/Blade，而是“类 Discuz/早期 CMS”的私有语法。
- [core/base/Req.php](/Users/uchi/dark/web_project/dddd188.com/core/base/Req.php#L1) 只是对 `$_GET`/`$_POST`/`$_REQUEST` 的薄封装，本质仍然是老式超全局访问。
- 数据库层由 [core/base/Db.php](/Users/uchi/dark/web_project/dddd188.com/core/base/Db.php#L1) 和 [core/base/Dbmysqli.php](/Users/uchi/dark/web_project/dddd188.com/core/base/Dbmysqli.php#L1) 提供。虽然名字叫 `dbmysqli`，实际已经改成 PDO，但大部分 SQL 依旧是字符串拼接，不是现代 ORM 或参数化查询思路。
- 配置主要放在 [data/config.php](/Users/uchi/dark/web_project/dddd188.com/data/config.php#L1)。这里既有数据库连接，也有站点名、支付展示名、密码盐、体验卡档位等。文件里有硬编码敏感信息，我这里不复述具体值。

**目录可以这样理解**
- `main/`：站点对外入口、旧式页面、支付回调、公共脚本、静态资源。
- `core/`：自研微框架，包含路由、控制器、视图、数据库、支付工具、基础类。
- `lib/index/action/`：PC 端走 MVC 的用户、活动、充值、提现等逻辑。
- `lib/mobile/action/`：移动端核心业务，尤其是登录注册、游戏、钱包、活动，几乎是全站最重要的目录。
- `template/index/default/` 和 `template/mobile/default/`：PC/移动模板。
- `admin/`：独立后台系统，脚本式页面很多，功能覆盖用户、充值、排行、活动、系统参数、日志等。
- `caiji/`：开奖采集、自动返利、短信批处理、排名生成等定时任务脚本。
- `kdydata.sql`：数据库结构和大量业务 SQL，是理解系统的另一半源码。

**真正最核心的业务模块**
- 用户体系主要在 [lib/mobile/action/UsersAction.php](/Users/uchi/dark/web_project/dddd188.com/lib/mobile/action/UsersAction.php#L1) 和 [lib/index/action/UserAction.php](/Users/uchi/dark/web_project/dddd188.com/lib/index/action/UserAction.php#L1)。登录、注册、重置密码不是 PHP 自己拼逻辑，而是大量依赖存储过程，比如 `web_user_login`、`web_user_mobile_reg`、`web_user_resetpwd`。
- 移动端公共登录态逻辑在 [lib/mobile/action/BaseAction.php](/Users/uchi/dark/web_project/dddd188.com/lib/mobile/action/BaseAction.php#L1)。这里会做登录校验、封号校验、异地登录校验、微信环境识别、积分刷新，还把 `points` 和 `bankpoints` 格式化后喂给模板。
- 这个系统的资金模型是“双余额”：`points` 更像可下注余额，`back`/`bankpoints` 更像银行余额。很多值在数据库里按 1000 倍存储，展示时再除以 1000，这也是为什么代码里频繁看到 `/1000`、`*1000`。
- 提现/收款账户逻辑主要在 [lib/mobile/action/BankingAction.php](/Users/uchi/dark/web_project/dddd188.com/lib/mobile/action/BankingAction.php#L1)。它管理支付宝/微信/银行卡账户，并通过 `withdrawals`、`withdrawals2`、`withdrawals3` 这类存储过程处理免费提现和手续费规则。
- 活动中心在 [lib/mobile/action/ActivityAction.php](/Users/uchi/dark/web_project/dddd188.com/lib/mobile/action/ActivityAction.php#L1)。这里能看到日返利、周返利、首充返利、排行榜奖励、推荐奖励、红包领取等活动。红包领取这一段还显式用了事务，说明它非常在意并发抢红包的一致性。
- 游戏系统是整个仓库最重的部分，核心在 [lib/mobile/action/GamebaseAction.php](/Users/uchi/dark/web_project/dddd188.com/lib/mobile/action/GamebaseAction.php#L1) 和 [lib/mobile/action/GameAction.php](/Users/uchi/dark/web_project/dddd188.com/lib/mobile/action/GameAction.php#L1)。
- `GamebaseAction` 最重要的职责是“把游戏类型映射到具体数据表名”。也就是说，游戏不是一张通用表，而是 `game28`、`game16`、`gamepk10`、`gamecqssc` 这类横向拆开的独立表族。
- `GameAction` 负责游戏大厅、开奖轮询、投注、自动投注、投注记录、规则页。下注前它会校验参数、下注频率、总站开关、单游戏开关、账号状态、代理限制、自动投注冲突、封盘时间，然后再调用 `web_tz_xxx` 存储过程真正落库。
- 前端展示层也能看出它的混合风格：[template/mobile/default/game.inc](/Users/uchi/dark/web_project/dddd188.com/template/mobile/default/game.inc#L1) 一边用服务器模板语法，一边内嵌 Vue 和 jQuery，靠 AJAX 每秒拉取开奖/封盘状态。

**数据库才是这个系统的另一半**
- 我直接扫了 SQL 快照，`kdydata.sql` 里有 332 张表。这已经不是“小网站数据库”，而是一个完整业务平台数据库。
- 核心业务表至少包括：`users`、`pay_online`、`score_log`、`presslog`、`rank_log`、`rank_list`、`withdrawals`、`centerbank`、`game_config`。这些表在 SQL 里的建表位置分别能看到，例如 `users` 在 7064 行，`pay_online` 在 6606 行，`score_log` 在 6806 行，`withdrawals` 在 7327 行。
- 最有辨识度的是游戏表族：几乎每个游戏都有 `主表 + users_tz + kg_users_tz + auto + auto_tz` 这一组，比如 `game28`、`game28_users_tz`、`game28_kg_users_tz`、`game28_auto`、`game28_auto_tz`。这解释了为什么 PHP 侧必须先做表名映射。
- 更关键的是，数据库里真的带了大量存储过程定义。`web_tz_game28`、`web_tz_gamepk10`、`web_user_login`、`web_user_mobile_reg`、`web_user_resetpwd` 等都在 SQL 后半段，说明“真正的业务规则”有很大一部分沉在数据库层，而不是 PHP 层。
- `centerbank` 是一个很关键的业务概念，可以理解成活动奖励、注册赠送、红包、返利等资金池的总账。很多发奖操作都会同步扣减 `centerbank`，所以它不是普通配置表，而像一层总账本。

**后台和定时任务也很重要**
- 后台不是装饰品，而是运营主控台。你从 `admin/` 文件名就能看出来：用户管理、代理管理、支付配置、开奖管理、排行配置、活动配置、系统消息、日志查询都在里面。
- 后台权限逻辑写在 [admin/inc/function.php](/Users/uchi/dark/web_project/dddd188.com/admin/inc/function.php#L125) 一带，除了登录态校验，还用了 `admin_ips` 表做后台 IP 白名单，这很符合老式站点“只允许固定办公 IP 进后台”的部署习惯。
- [caiji/crawler.php](/Users/uchi/dark/web_project/dddd188.com/caiji/crawler.php#L1) 是开奖采集总入口，会根据 `source` 选择不同爬虫类，然后循环抓数据、保存数据、触发开盘。
- [caiji/autoReturnScore.php](/Users/uchi/dark/web_project/dddd188.com/caiji/autoReturnScore.php#L1) 负责推荐奖励、亏损返利、排行奖励等自动结算。这意味着很多“每天发奖”的业务并不是用户请求时算的，而是靠计划任务跑批。

**如果从工程角度评价这个库**
- 优点是业务闭环非常完整，目录虽然老，但职责并不完全混乱；一旦你接受“PHP + 存储过程 + 定时脚本”这个世界观，很多模块反而是成体系的。
- 难点是技术债很重：有三套入口体系、很多重复代码、正则防注入到处复制、手写 SQL 很多、模板和 JS 风格混搭、配置里存在硬编码敏感信息、几乎看不到自动化测试和现代依赖管理。
- 它不像现代应用那样可以只靠读 PHP 控制器就吃透。这个仓库必须“PHP 代码 + SQL 存储过程 + 定时脚本”一起看，否则你会觉得很多逻辑凭空消失。

**如果你准备接手，我建议的阅读顺序**
1. 先看 [main/index.php](/Users/uchi/dark/web_project/dddd188.com/main/index.php#L1)、[main/b.php](/Users/uchi/dark/web_project/dddd188.com/main/b.php#L1)、[main/mobile.php](/Users/uchi/dark/web_project/dddd188.com/main/mobile.php#L1)，把入口分流先记住。
2. 再看 [core/ini.php](/Users/uchi/dark/web_project/dddd188.com/core/ini.php#L1)、[core/controller/View.php](/Users/uchi/dark/web_project/dddd188.com/core/controller/View.php#L1)、[core/base/Db.php](/Users/uchi/dark/web_project/dddd188.com/core/base/Db.php#L1)，理解它的运行骨架。
3. 然后重点啃 [lib/mobile/action/UsersAction.php](/Users/uchi/dark/web_project/dddd188.com/lib/mobile/action/UsersAction.php#L1)、[lib/mobile/action/BankingAction.php](/Users/uchi/dark/web_project/dddd188.com/lib/mobile/action/BankingAction.php#L1)、[lib/mobile/action/ActivityAction.php](/Users/uchi/dark/web_project/dddd188.com/lib/mobile/action/ActivityAction.php#L1)、[lib/mobile/action/GameAction.php](/Users/uchi/dark/web_project/dddd188.com/lib/mobile/action/GameAction.php#L1)。
4. 最后配合 `kdydata.sql` 去追 `web_user_login`、`web_tz_*`、`withdrawals*` 这些存储过程，你才会真正知道业务到底在哪里结算。

按工作区规则，我还新建了 [CHANGELOG.md](/Users/uchi/dark/web_project/dddd188.com/CHANGELOG.md) 并追加了本次说明。你如果愿意，我下一轮可以继续把这个仓库按“逐目录”或者直接按“游戏模块”给你拆到函数级别。
──────────────────────────────────────────────
时间：2026-04-06 22:09:43 JST

**结论**
结合这个仓库的特征，我最推荐的主方案是：
- 后端：Kotlin + Spring Boot
- 用户端三端：Flutter
- 架构：API-first 的模块化单体（先不要微服务）+ MySQL + Redis + MQ + WebSocket/SSE

如果你把“后台管理 web”也算进来，而且非常重视 SEO、复杂表格和运营后台效率，我会改成：
- 后端：Kotlin + Spring Boot
- Web/后台：Next.js + TypeScript
- iOS/Android：Flutter
- 共享：OpenAPI/SDK + 统一 Design Token + 统一领域服务

**为什么我不建议直接选单一全栈语言**
这个项目不是普通 CMS，而是“账户、钱包、充值提现、下注、开奖、返利、代理、后台、定时任务”一体化系统。你现在的逻辑还有大量 SQL、存储过程、跑批脚本和实时状态更新。对这种系统，后端稳定性和事务一致性比“前后端一门语言”更重要。

Spring Boot 官方文档目前仍强调它适合做 production-grade 应用，并内建安全、健康检查、指标、配置等基础能力；Spring 对 Kotlin 也有一等支持。Flutter 官方仍把 web/iOS/Android 视为同一代码库的目标平台，适合做登录后型、交互密集型应用。  
如果只从“一个前端项目同时发 web+iOS+Android”看，Flutter是比 React Native 更自然的一套。

**这套组合为什么适合你这个项目**
- 业务很重：Kotlin/Spring Boot 比 Node/NestJS 更适合承接账户、账变、结算、对账、定时返利、风控这类强事务逻辑。
- 三端要求明确：Flutter 官方支持从同一代码库构建 iOS、Android、Web，能减少三端 UI 重写成本。
- 当前系统实时性不低：游戏列表、封盘时间、开奖、余额刷新，适合后端用 WebSocket/SSE，客户端统一消费。
- 当前库技术债很深：先做“模块化单体”最稳，不建议一上来微服务；否则迁移难度会被成倍放大。
- MySQL 可继续保留：但不建议把现在“存储过程承载核心业务”的模式原样搬过去，新系统应把核心规则移回应用层，MySQL 主要负责事务持久化。
- 运维成本可控：Spring Boot + MySQL + Redis + RabbitMQ/Kafka 这一套是非常成熟的生产组合。

**推荐的目标架构**
- 接入层：Nginx / API Gateway
- 客户端：Flutter App（web、iOS、android）
- 后端：Spring Boot 模块化单体
- 数据层：MySQL
- 缓存与会话：Redis
- 异步任务：RabbitMQ 或 Kafka
- 实时推送：WebSocket 或 SSE
- 对象存储：OSS / S3 兼容存储
- 认证：JWT + Refresh Token + 设备会话管理
- 运维：Docker + CI/CD + 灰度发布

**后端模块建议**
- `auth`：登录、注册、设备会话、验证码
- `user`：用户资料、等级、黑白名单
- `wallet`：余额、银行余额、账变流水
- `payment`：充值订单、支付回调、提现申请
- `game-config`：游戏配置、赔率、开关、封盘时间
- `betting`：下注、撤单、限额、频率控制
- `draw`：采集结果、开奖、结算触发
- `settlement`：派奖、返利、排行榜、红包
- `agent`：代理、推广、返佣
- `admin`：后台运营、报表、配置
- `risk-control`：风控规则、IP/设备/频率限制
- `scheduler`：跑批、日结、周结、补偿任务

**数据库层我建议这样迁**
- 保留 MySQL，但重做表设计，不要直接照搬现在 332 张表。
- 游戏类表不要继续“大量按游戏类型复制整套表”；优先改成“通用投注/开奖/赔率模型 + 少量特化表”。
- 账务表、订单表、下注表、开奖表、活动表要重新定义主键、索引、幂等键和审计字段。
- 现有存储过程先通过“兼容层”调用，迁移完成后逐步下线。
- 所有资金相关操作都必须有明确的事务边界、幂等策略和对账机制。

**如果你非常想统一成一门语言**
那就选第二方案：
- 后端：NestJS
- Web：Next.js
- iOS/Android：Expo / React Native
- 共享语言：TypeScript

这套方案的优点是团队协作和招聘会更轻，前后端包、DTO、校验、SDK 也更容易共享。Expo 官方文档现在仍强调一套 JS/TS 项目可跑 Android、iOS、Web；NestJS 官方也仍强调它是可扩展的 TypeScript 服务端框架。

但这套方案对你这个项目有两个弱点：
- 后端在强事务、复杂批处理、结算链路上，长期稳定性和治理体验通常不如 Spring Boot 体系。
- Web、移动虽然能共享不少逻辑，但“真正一套 UI 同时覆盖后台 web 和原生移动”的体验，通常不如 Flutter 直接。

**我的最终建议**
如果你的第一优先级是“长期稳定运营 + 资金/结算安全 + 可维护”，选：
- `Kotlin + Spring Boot` 后端
- `Flutter` 用户三端
- `Next.js` 作为可选的后台/官网 web

如果你的第一优先级是“团队统一语言 + 开发速度 + 招人容易”，选：
- `TypeScript + NestJS + Next.js + Expo`

就你这个仓库的现状，我更推荐第一套，而且架构上一定要走：
- “模块化单体”
- “API first”
- “先兼容、再替换”
- “先迁核心账务和登录，再迁游戏和活动，再迁后台”

我这次是按 2026-04-06 查了官方文档后给的判断。参考链接：
- [Flutter 官方首页](https://flutter.dev/)
- [Flutter Web 官方文档](https://docs.flutter.dev/platform-integration/web)
- [Spring Boot 官方文档总览](https://docs.spring.io/spring-boot/)
- [Spring Boot Kotlin 支持](https://docs.spring.io/spring-boot/reference/features/kotlin.html)
- [Expo 官方文档](https://docs.expo.dev/)
- [Expo Core Concepts](https://docs.expo.dev/core-concepts/)
- [Next.js 官方文档](https://nextjs.org/docs)
- [NestJS 官方文档](https://docs.nestjs.com/guides/enterprise)

如果你想，我下一步可以继续直接给你出一份“迁移落地蓝图”，把这个 PHP 项目拆成新系统的模块、表结构、接口边界和分阶段迁移计划。
──────────────────────────────────────────────
时间：2026-04-07 02:06:47 JST

这次我会先快速核对仓库结构和现有文档，再产出两份可直接落地的迁移文档：一份总体 `plans`，一份按阶段执行的 `execplan`。文档会按你前面确认的主方案来写，并且尽量贴合这个老 PHP 系统的现状。

我已经确认这个仓库确实是那套老 PHP 业务系统，当前根目录只有 `README.md` 和 `CHANGELOG.md` 两份文档。接下来我会先补齐迁移文档结构，再把业务拆分、目标架构、阶段计划、里程碑、风险和验收标准都写完整。

我已经把仓库结构和几个关键业务点核对过了，`main / lib / admin / caiji / kdydata.sql` 这些都在，说明之前的判断和这份仓库是对得上的。现在我开始落文档：一份偏“目标蓝图 + 迁移总规划”，一份偏“按阶段执行 + 交付验收”的执行手册。

我又补看了入口、启动文件和移动端核心逻辑，确认这个系统确实是“多入口 + 自研框架 + 大量存储过程 + 游戏强实时”的形态。为了让计划更有可执行性，我再补几项量化信息，比如数据库规模和业务脚本分布，然后就开始写文档。

我准备落两份文档到新的 `docs/` 目录里：`plans.md` 负责总体蓝图，`execplan.md` 负责执行路径、阶段任务和验收。内容会直接绑定现在这套代码的结构，不会写成泛泛而谈的模板文档。

两份文档已经落下来了。我现在做一轮快速校对，重点看结构是否完整、阶段顺序是否前后一致，以及有没有明显缺口需要补。

我在做最后一轮整理时发现 `execplan` 的阶段标题层级还可以更清晰一点。我先把标题层级顺一遍，再检查后半段内容有没有断层。

我把文档主体检查完了，内容没缺段，主要就是把执行计划里的阶段标题层级顺一下，方便后面继续扩展。改完之后我就补日志并给你总结文档入口。

文档已经校对完，结构和阶段顺序都通了。接下来我按项目规则把这轮输出追加进 `CHANGELOG.md`，然后给你一个简洁的交付说明和下一步建议。

我重新定位到文件尾了，这次直接按末尾内容追加，避免上下文匹配偏掉。追加完成后我会再核对一次时间戳和最新段落是否在尾部。

我已经按主方案补了两份文档：

- 总规划：[plans.md](/Users/uchi/dark/web_project/dd28/docs/plans.md)，涵盖现状、目标架构、模块拆分、数据策略、迁移路线、里程碑、风险和组织建议。
- 执行计划：[execplan.md](/Users/uchi/dark/web_project/dd28/docs/execplan.md)，涵盖分波次时间表、六条工作流、每阶段目标/任务/交付物/退出标准、测试验收、数据迁移、切换和回滚。

这次只新增文档，没有改业务代码；我做了结构校对，当前变更集中在 `docs/` 目录。未运行测试，因为本次仅新增 Markdown 文档。

下一步最适合继续补三份落地稿：`领域模型与表结构草案`、`存储过程迁移清单`、`auth + user + wallet` 的 API/数据库设计。你要的话，我下一轮可以直接接着写。
──────────────────────────────────────────────
时间：2026-04-07 08:51:13 JST

我继续往下补设计稿，这一轮会把“领域模型 / 存储过程迁移 / auth-user-wallet 设计”三份文档直接写出来。先快速抽取 `kdydata.sql` 里的关键表和关键过程，保证文档不是空泛方案。

我已经把关键对象的大致位置定位出来了，接下来补读几段核心结构：`users / pay_online / score_log / withdrawals / centerbank / game_config`，以及登录注册重置密码三个过程。这样文档里的表草案和迁移映射会更靠谱。

关键表结构已经拿到了，接下来我再把登录/注册/重置密码过程和提现相关过程读出来。这样“存储过程迁移清单”和 `auth-user-wallet` 设计就能把旧逻辑拆成明确的新服务职责。

我已经拿到登录、注册、重置密码过程，也确认了游戏下注记录表和几张变更审计表。现在再把 `withdrawals / withdrawals2 / withdrawals3` 读出来，文档里就能把“旧提现流程如何切分成新订单状态机”写清楚。

我现在补两小段支撑信息：改密码过程和中央银行调配过程。这样三份文档里能把“认证改密”和“资金池总账”都交代完整，不会只覆盖登录与提现。

我准备开始落这三份设计文档了。会把旧表、旧过程和新模块的映射一起写进去，尤其把 `users / score_log / pay_online / withdrawals / centerbank` 这些关键对象拆成新系统里的账户、认证、总账和订单模型。

三份文档已经落下来了。我先做一轮快速校对，重点看交叉引用、命名一致性，以及 `auth / user / wallet` 这份设计有没有和前面的总规划打架。

我在校对时看到两个小问题，顺手修一下会更整洁：一是部分小节标题层级偏高，二是 `centerbank` 映射里提到了快照对象，文档里最好也补一张快照表草案，避免前后不一致。

我现在把这两个一致性问题补一下：统一小节标题层级，并在领域模型里补上 `fund_account_snapshots` 草案。这样这三份文档就能直接作为后续设计输入用了。

这轮小修已经打上了。我再做最后一次检查：确认标题层级都顺了、三份文档都能互相引用，然后就把本轮内容追加进 `CHANGELOG.md`。

三份文档我已经校对完了，交叉引用和结构都通。现在按项目规则把这轮会话内容追加到 `CHANGELOG.md`，然后我给你一个简洁的交付摘要。

我已经定位到日志尾部了，这次会把“继续写下去”这一轮补的三份设计稿和校对结果完整追加进去。追加完我就直接给你交付入口和下一步可继续的方向。

这轮新增了三份落地文档：

- [domain-model.md](/Users/uchi/dark/web_project/dd28/docs/domain-model.md)：把老系统的用户、钱包、支付、游戏、投注、资金池对象拆成新的限界上下文、聚合和核心表结构草案。
- [procedure-migration.md](/Users/uchi/dark/web_project/dd28/docs/procedure-migration.md)：把 `web_user_login`、`web_user_mobile_reg`、`withdrawals*`、`web_AdminTrans`、`web_tz_*`、`web_kj_*` 等过程按优先级、归属模块和退场顺序做了迁移清单。
- [auth-user-wallet-design.md](/Users/uchi/dark/web_project/dd28/docs/auth-user-wallet-design.md)：细化了 `auth + user + wallet` 的模块边界、首批 API、建表建议、关键流程、兼容迁移和测试清单。

这次仍然只改了 Markdown 文档，没有改业务代码；我做了结构校对和一致性修正。未运行测试，因为本次没有代码变更。
──────────────────────────────────────────────
时间：2026-04-07 09:00:30 JST

我先去核对一下 OpenAI Cookbook 里 `ExecPlans` 的结构要求，再把我们现有几份文档重组为你要的两个文件：`.agent/PLANS.md` 和 `ExecPlan`。核对完我会直接按那个规范落文件，不会只给口头建议。

我已经把 OpenAI Cookbook 的 `ExecPlans` 文章结构核对完了，接下来会按那个框架把我们现有内容真正收敛成两个文件：`.agent/PLANS.md` 负责规范，根目录 `ExecPlan` 负责当前可执行规格。为了让它能直接给 Codex CLI 用，我会把 `ExecPlan` 的范围收敛到“迁移启动 + 第一阶段底座与 auth/user/wallet”这一条可落地主线。

我现在开始写这两个最终文件。`PLANS.md` 我会做成仓库级规范，`ExecPlan` 我会做成当前这次迁移工作的单一执行真相源；旧的 `docs/` 文件先保留，作为支撑材料，不删。

两个目标文件已经写出来了。我先快速检查一下结构、文件名和内容是否跟我们已有文档对齐，然后再把这轮内容记进 `CHANGELOG.md`。

结构已经对上了：`.agent/PLANS.md` 是规范文件，根目录 `ExecPlan` 是当前执行规格书，而且范围已经收敛成能真正落地的第一阶段。接下来我按项目规则把这轮输出继续追加到 `CHANGELOG.md`。

我已经新增并整理成你要的两个文件：

- [.agent/PLANS.md](/Users/uchi/dark/web_project/dd28/.agent/PLANS.md)：给 Codex 的执行计划规范，定义了何时必须使用 ExecPlan、必备章节、写作风格、更新方式，以及本仓库如何把根目录 `ExecPlan` 作为当前执行真相源。
- [ExecPlan](/Users/uchi/dark/web_project/dd28/ExecPlan)：当前这次 `dd28` 现代化迁移的可执行规格书，按 ExecPlans 结构重写，范围明确收敛为“迁移启动 + 第一阶段底座 + auth/user/wallet”。

我保留了 `docs/` 里之前写的规划和设计稿，把它们降级为支撑材料，没有删除。新的执行入口现在是根目录 `ExecPlan`。

这次仍然只改了文档，没有改业务代码，也没有运行测试。结构参考了 OpenAI Cookbook 的 ExecPlans 文章，并按当前仓库的中文和迁移场景做了本地化整理。
──────────────────────────────────────────────
时间：2026-04-07 13:34:45 JST

你说得对，我把结构按你要的方式修正：把执行文件改成带 `.md` 后缀，并放到 `.agent/execplans/` 目录下；同时补上 `.agent/AGENTS.md`。我也会顺手把 `.agent/PLANS.md` 里的位置说明一起改一致，避免后面再打架。

我先把文档内容修正好，再用文件移动把计划文件放到正确目录。这样既能保持内容一致，也能把路径结构改到你要的样子。

目录结构已经改过去了。现在我检查一下新路径是否存在、老的根目录文件是否已经消失，以及 `.agent/AGENTS.md` 内容是否就是你要的那段。

目录已经改对了：现在执行文件在 `.agent/execplans/ExecPlan.md`，根目录旧的 `ExecPlan` 已经移走；`.agent/AGENTS.md` 也已经按你给的内容创建好了。为了避免文档里还留着旧说法，我再扫一遍是否还有“根目录 ExecPlan”这类残留，再补日志。

仓库里的活文档已经一致了，历史 `CHANGELOG.md` 里保留旧表述我先不回改，避免破坏历史记录。现在我按规则把这轮结构修正追加进去，然后给你最终确认。

这轮修正包括：

- 把执行计划文件从根目录 `ExecPlan` 移动到 [.agent/execplans/ExecPlan.md](/Users/uchi/dark/web_project/dd28/.agent/execplans/ExecPlan.md)
- 更新了 [.agent/PLANS.md](/Users/uchi/dark/web_project/dd28/.agent/PLANS.md) 中关于默认执行计划位置的说明
- 新增了 [.agent/AGENTS.md](/Users/uchi/dark/web_project/dd28/.agent/AGENTS.md)，内容为你指定的 `ExecPlans` 提示

这次仍然只改了文档和文件结构，没有改业务代码，也没有运行测试。
