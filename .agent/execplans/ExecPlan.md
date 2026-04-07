# 启动 dd28 现代化迁移，并交付第一阶段可运行底座

本文件是当前仓库的主动执行规格书，按 `.agent/PLANS.md` 维护。  
它吸收了 `docs/plans.md`、`docs/execplan.md`、`docs/domain-model.md`、`docs/procedure-migration.md`、`docs/auth-user-wallet-design.md` 的核心内容，但从现在起，这些文档只作为支撑材料；真正用于驱动 Codex CLI 执行的单一真相源是本文件。

本计划不试图一次性重写整套老系统。当前可执行范围明确收敛为：在保留旧 PHP 系统可运行的前提下，于当前仓库内建立 `modern/` 目录，初始化新的后端、用户端和后台端工程骨架，并完成第一条真正可运行的业务主链路：`auth + user + wallet`。完成后，仓库中应同时存在可继续运行的遗留系统，以及一套可以本地启动、可以登录、可以查询用户摘要和钱包余额、并且具备后续支付与下注迁移基础的新系统底座。

## Big Picture

当前仓库是一个多入口、重数据库过程、重运营后台、重定时任务的老 PHP 业务系统。入口层位于 `main/index.php`、`main/b.php`、`main/mobile.php` 与 `admin/index.php`，启动核心在 `core/ini.php`，移动端登录态和余额刷新主要集中在 `lib/mobile/action/BaseAction.php`，游戏下注与轮询核心在 `lib/mobile/action/GameAction.php`，完整数据库快照在 `kdydata.sql`。数据库不是辅助角色，而是业务主承载层：用户、钱包、提现、下注、开奖、返利、排行榜和代理逻辑都高度依赖表族、触发器和存储过程。

这次执行的目标不是“把 PHP 页面逐个翻译成 Kotlin 或 Flutter”，而是建立一条新的业务骨架。新的骨架必须先把老系统里最危险、最难持续维护的核心部分拆开：身份认证、用户资料、钱包总账。只要这一条链路稳住，后面的支付、提现、开奖、下注、结算和活动迁移才不会每走一步都反复返工。

因此，这个 `ExecPlan` 的完成标志不是“整个迁移结束”，而是下面这些结果同时成立：仓库中出现 `modern/backend`、`modern/user_app`、`modern/admin_web`、`modern/ops` 这些新目录；后端可以在本地构建并启动，至少暴露健康检查、登录、注册、用户摘要、钱包余额和钱包流水接口；后端数据库通过正式的迁移机制创建 `auth + user + wallet + fund` 所需表；一个迁移过来的老用户可以通过兼容老密码哈希的方式登录一次，并在成功登录后升级为新哈希；Flutter 用户端至少能完成登录并展示钱包余额；后台端至少要有最小的壳和环境接线，为后续支付、风控和人工调账页面做准备。

## Progress

- [x] 2026-04-07 01:58 JST 已核对仓库既有 `CHANGELOG.md` 记录，确认此前已经完成一次老系统整体摸底与一次技术选型决策。
- [x] 2026-04-07 02:06 JST 已产出仓库级迁移总规划与执行计划，形成 `docs/plans.md` 和 `docs/execplan.md`。
- [x] 2026-04-07 08:51 JST 已从 `kdydata.sql` 抽取关键表与关键存储过程，完成 `docs/domain-model.md`、`docs/procedure-migration.md` 与 `docs/auth-user-wallet-design.md`。
- [x] 2026-04-07 08:51 JST 已把现有规划收敛为 `.agent/PLANS.md` 和本 `ExecPlan`，明确本文件为当前执行真相源。
- [ ] 创建 `modern/backend`、`modern/user_app`、`modern/admin_web`、`modern/ops` 的初始目录与工程骨架。
- [ ] 在 `modern/backend` 中建立可启动的 Kotlin + Spring Boot 项目，接入配置、日志、统一错误处理、健康检查、Flyway 和测试基座。
- [ ] 在 `modern/backend` 中落地 `auth + user + wallet + fund` 的第一版数据库迁移脚本和领域模型。
- [ ] 在 `modern/backend` 中实现兼容遗留密码的登录、注册、改密、用户摘要、钱包余额和钱包流水接口。
- [ ] 在 `modern/backend` 中实现读取遗留快照并导入用户、资料、凭据和钱包余额的首版迁移器。
- [ ] 在 `modern/user_app` 中实现最小 Flutter 应用，能够登录并展示用户摘要与钱包余额。
- [ ] 在 `modern/admin_web` 中实现最小 Next.js 壳工程，包含环境配置、受保护布局和待接入的后台首页占位。
- [ ] 完成本地构建、单元测试、接口冒烟和一次遗留用户迁移验证。

## Surprises & Discoveries

- `users` 不是普通用户表，而是一个把身份、资料、密码、资金密码、主余额、保险箱余额、冻结金额、推荐关系、冻结状态、代理标记、来源字段全部揉在一起的大表。这个事实决定了新系统不能做“先照搬表再慢慢拆”的迁移，而必须从第一阶段就把 `user_accounts`、`user_profiles`、`user_credentials` 和 `wallet_accounts` 拆开。证据在 `kdydata.sql` 的 `users` 表定义，以及 `lib/mobile/action/BaseAction.php` 中直接从会话和 `users` 表读取 `points`、`back`、`dj` 的行为。

- `pay_online` 同时承载充值和提现，提现状态使用 `30`、`31`、`32` 复用同一张表。这意味着老系统里“订单”本身的语义已经混乱，不能继续沿用单表承载两个完全不同的状态机。证据在 `kdydata.sql` 的 `pay_online` 表定义，以及 `withdrawals`、`withdrawals2`、`withdrawals3` 过程往 `pay_online` 写提现单的行为。

- `withdrawals` 表本身并不是提现订单，而只是用户收款账户存档。真正的提现申请记录是过程写入 `pay_online`。这个发现直接影响新系统建模：必须分成 `withdrawal_accounts` 和 `withdrawal_orders` 两层。

- `score_log` 虽然看起来像账变流水，但它的 `opr_type` 语义过于粗糙，既承载充值、提现、活动奖励，也承载红包、排行奖励、系统会员充值等不同来源，而且没有天然稳定的业务幂等键。这个事实决定新系统要建立统一的 `wallet_ledger_entries`，并强制要求 `biz_type + biz_id` 或等价唯一键。

- `game_config` 使用大段字符串字段保存赔率、标准投注额、玩法模式和开关，并且不同玩法通过 `game_table_prefix` 映射到独立表族。这个发现意味着后续游戏迁移不能直接复制 `game_config`，而必须拆成 `game_types`、`game_play_rules`、`game_limit_rules` 和 `odds_templates`。

- `game28` 与 `game28_users_tz` 这类表只是一个缩影；整个数据库中存在大规模“每个游戏复制一组主表、自动投注表、用户投注表、开奖投注表”的模式。这个发现进一步强化了本计划的范围收敛：当前阶段只打基础，不碰下注与结算重构。

后续任何新发现，只要会改变目录结构、数据模型、实施顺序或回滚方案，都必须追加到这里，并同步更新下面的决策和执行章节。

## Decision Log

- 2026-04-07 采用 `Kotlin + Spring Boot` 作为后端、`Flutter` 作为用户端、`Next.js` 作为后台端的目标技术路线。原因是当前业务属于强事务、强账务、强状态、强实时的混合系统，后端稳定性和事务治理优先级高于语言统一。

- 2026-04-07 决定把当前主动可执行范围收敛为“迁移启动 + 第一阶段底座 + auth/user/wallet”，而不是直接写成“整站迁移到底”。原因是 `ExecPlan` 必须指向可观察、可验证、可分步交付的结果；如果直接覆盖支付、开奖、下注、返利和后台全量替换，计划会失去可执行性。

- 2026-04-07 决定在当前仓库内新增 `modern/` 目录，而不是立刻移动或清洗既有 PHP 目录。原因是遗留系统仍需保留作为运行对照和数据语义参考；在没有完成迁移验证前，不应动老入口和老逻辑。

- 2026-04-07 决定新系统内部金额继续使用 `bigint` 保存“千分单位”的金额。原因是老系统 `users.points`、`users.back`、`score_log.amount`、`withdrawals*` 过程以及前端 `/1000` 展示规则都依赖这个精度模型；如果第一阶段擅自改为新的金额尺度，迁移与对账成本会显著上升。

- 2026-04-07 决定把老系统的 `pay_online` 拆成 `deposit_orders` 与 `withdrawal_orders` 两张业务表，把 `withdrawals` 拆成 `withdrawal_accounts`。原因是老系统把账户、订单和渠道状态混在一起，新系统必须从一开始建立清晰的订单状态机。

- 2026-04-07 决定允许“兼容老哈希一次登录，成功后立即升级为新哈希”的策略。原因是老系统密码散布在 `users.password` 与 `users.bankpwd` 中，且登录逻辑通过旧哈希比较；强制全量用户立即改密会显著增加切换成本和业务风险。

## Outcomes & Retrospective

在本计划写成时，真实完成的产物还只有分析和设计文档，没有任何新代码已经落地，所以这里的阶段性结果只有一个：我们已经把“为什么迁、迁什么、先后顺序、关键表和关键过程、第一阶段的接口和数据模型”这些容易在会话间丢失的信息固化进仓库。这个结果本身不是业务交付，但它已经把后续编码从“重新理解老系统”转成了“沿着一个清晰目标去做工程”。

后续每完成一个大阶段，都要在这里追加一段简短总结，明确写出哪些结果已经落地、哪些风险被消除了、哪些预期没有实现以及下一步的收缩或扩张范围。这个章节不能写成空泛复盘，必须服务于后面的执行。

## Context and Orientation

当前仓库根目录下只有极少量文档文件，真正的业务主体都在旧 PHP 代码和 SQL 快照里。`README.md` 几乎没有有效说明，`kdydata.sql` 才是事实上的系统说明书之一。任何开始执行本计划的人，都要先记住下面这些定位关系。

`main/index.php` 是总入口，按设备跳到 `pcindex.php` 或 `mobile.php`。`main/b.php` 是 PC MVC 入口，`main/mobile.php` 是移动 MVC 入口，`admin/index.php` 是后台入口。`core/ini.php` 负责启动、自定义自动加载、全局参数过滤和配置装载。`lib/mobile/action/BaseAction.php` 负责移动端登录校验、异地登录判断、余额刷新和会话信息灌入模板。`lib/mobile/action/GameAction.php` 负责核心游戏视图、轮询和下注入口。`kdydata.sql` 中不但有表定义，还有登录、注册、改密、提现、开奖、下注等关键存储过程。

当前仓库还没有 `modern/` 目录，也没有新的后端、Flutter 或 Next.js 工程。本计划的第一步就是把这些目录创建出来。它们应被放在当前仓库下，而不是另起一仓。目录布局固定如下：

`modern/backend` 承载 Kotlin + Spring Boot 服务端。  
`modern/user_app` 承载 Flutter Web/iOS/Android 用户端。  
`modern/admin_web` 承载 Next.js 后台端。  
`modern/ops` 承载本地环境、Compose、迁移说明和开发脚本。  

遗留代码继续留在原位置，不做目录搬迁。任何实现新系统的代码，都不应混入 `main/`、`core/`、`lib/` 或 `admin/` 目录。

术语也必须统一。这里的“主余额”指遗留系统的 `users.points`。这里的“保险箱余额”指遗留系统的 `users.back`。这里的“冻结金额”指遗留系统的 `users.lock_points`。这里的“老哈希”指遗留系统在登录前对密码所做的兼容哈希处理，最终用于和 `users.password` 或 `users.bankpwd` 比较。这里的“资金池”指遗留系统 `centerbank` 所承载的各类系统分、活动分、在线充值分和游戏税累积等账户。

支持设计稿已经存在于 `docs/` 目录下，但任何执行者都不应该把这些文档当成必须先读完才可开工的前提。本 `ExecPlan` 已经把当前阶段所需的上下文再次写进来了。后续编码中如果发现 `docs/` 与本文件不一致，以本文件为准，并在修改后同步回写支撑文档。

## Plan of Work

第一阶段的工作必须按“先搭骨架、再立账本、再接身份、最后做最小前端”的顺序推进，不能颠倒。原因很直接：如果没有独立的后端工程和数据库迁移机制，后面的接口与模型都会失去落点；如果没有稳定的钱包模型和账本约束，认证和用户信息虽然能跑，但一接到支付或下注就会重新返工；如果没有前端最小壳做烟雾验证，后端接口很容易停留在只通过单测、没有真实调用路径的状态。

第一个阶段是工程骨架。要在 `modern/` 下建立三个工程和一个运维目录，并让后端能够独立启动、日志可读、配置可切换、Flyway 可执行、单测可运行。这里不要试图一开始就接入所有业务；健康检查、配置加载、统一错误响应、测试基座和最小目录结构先落地，后面才有稳定的承载位置。

第二个阶段是数据库和领域模型的第一落点。后端里必须先把 `auth + user + wallet + fund` 的表通过 Flyway 建出来，并配套最小的仓储和领域对象。这个阶段不要求一次性覆盖所有字段，但必须把账号、资料、凭据、会话、验证码、钱包余额、账本流水、冻结记录和资金池账户这些核心对象先站稳。

第三个阶段是认证与用户能力。这里要把登录、注册、改登录密码、改资金密码、刷新令牌、查询用户摘要这一批接口做出来。登录必须支持老用户平滑进入，因此要明确实现“读旧哈希、校验成功后升级新哈希”的路径。这个阶段的重点不是做出最完整的账号中心，而是确保老用户迁过来之后确实能登录，且安全模型比老系统更清晰。

第四个阶段是钱包读写与导入。钱包余额读取、钱包流水查询、内部测试记账接口和用户余额快照导入器都属于这一阶段。导入器不能直接破坏老库，也不能依赖人工 SQL 拼接；它应以读遗留快照、写新库的方式实现，支持重复执行和断点续跑。只有当用户资料和钱包快照能够稳定导入，新系统才具有真实可验证的数据基础。

第五个阶段是最小前端联调。Flutter 用户端至少要能完成登录并展示用户摘要和钱包余额。Next.js 后台端这阶段只需要把工程壳、环境和受保护布局建好，不强求真正的后台业务页面。前端的目的不是在当前阶段完成完整业务，而是为下一轮支付和后台迁移准备真实调用链。

## Concrete Steps

先在仓库根目录创建 `modern/backend`、`modern/user_app`、`modern/admin_web` 和 `modern/ops`。如果这些目录已经存在，先审查现有内容，再决定是复用还是补齐，不要无脑覆盖。后端工程建议使用 `com.dd28.platform` 作为根包名，主启动类固定放在 `modern/backend/src/main/kotlin/com/dd28/platform/PlatformApplication.kt`。配置放在 `modern/backend/src/main/resources/application.yml` 和必要的环境样板文件中。数据库迁移脚本放在 `modern/backend/src/main/resources/db/migration/`。所有阶段性的接口测试和服务测试都应放在 `modern/backend/src/test/`。

在后端工程建立之后，先实现一个最小可启动的服务，至少包含健康检查、全局异常处理、基础日志、配置读取和测试基座。服务起起来之后，马上引入 Flyway，并新增第一批迁移脚本，例如 `V1__init_auth_user_wallet.sql`，内容覆盖 `user_accounts`、`user_profiles`、`user_credentials`、`user_sessions`、`verification_codes`、`auth_audit_logs`、`wallet_accounts`、`wallet_ledger_entries`、`wallet_holds`、`fund_accounts`、`fund_account_ledger_entries` 和 `fund_account_snapshots`。这些表的字段和约束以 `docs/domain-model.md` 为背景，但最终实现以本计划要求为准。

然后在后端中落地服务层和接口层。接口建议至少覆盖 `POST /api/v1/auth/register`、`POST /api/v1/auth/login/password`、`POST /api/v1/auth/token/refresh`、`POST /api/v1/auth/logout`、`POST /api/v1/auth/password/change`、`POST /api/v1/auth/fund-password/change`、`GET /api/v1/users/me`、`GET /api/v1/wallets/me` 和 `GET /api/v1/wallets/me/ledger`。这些接口的行为约束可直接参考 `docs/auth-user-wallet-design.md`，但本计划要求再强调一次：登录必须支持老哈希兼容；成功登录后要升级哈希；所有登录、失败和改密动作都要写审计日志；钱包读写必须通过统一账本，不允许直接裸改余额字段。

兼容登录需要一个明确的旧密码校验器。后端中应新增一个类似 `LegacyPasswordVerifier` 的组件，它能够读取遗留配置中与密码前缀相关的值，并按老系统规则生成比较值。只有当老哈希验证成功时，系统才允许登录并把密码升级成新哈希。这个逻辑必须被单元测试覆盖，不能只靠人工调试。

数据迁移器建议先放在后端工程内，而不是另起一个孤立脚本仓。新增 `modern/backend/src/main/kotlin/com/dd28/platform/migration/LegacyUserSnapshotImporter.kt` 或等价的导入组件，支持从遗留数据源读取用户主档、资料、凭据占位和钱包余额快照，并写入新系统。导入器必须支持重复执行，按 `user_id` 或稳定外部键做 upsert，而不是每次插入新记录。导入器还应提供最小的恢复能力，例如批量大小参数、从某个起始 ID 继续、只导入指定范围等。

在 `modern/user_app` 中初始化 Flutter 工程后，先不要追求复杂页面。先做环境配置、路由、登录页和钱包页。登录成功后把令牌安全地保存在开发态可接受的本地存储中，再调用 `/api/v1/users/me` 与 `/api/v1/wallets/me` 渲染页面。页面样式可以简单，但交互链路必须真实。

在 `modern/admin_web` 中初始化 Next.js 工程后，只需建立环境配置、登录占位、受保护布局和后台首页占位。当前阶段不做真实后台业务页，但必须能编译、能启动，并预留后续接入认证和 RBAC 的位置。

开发与验证时按下面的顺序执行命令。首先在后端目录运行构建和测试，然后本地启动服务。之后再启动 Flutter 端和后台壳工程。推荐使用这些命令作为阶段性检查点：在 `modern/backend` 下运行 `./gradlew test`，确认单元测试通过；运行 `./gradlew bootRun`，确认服务启动；对健康检查执行 `curl -s http://localhost:8080/actuator/health` 或等价路径，观察到服务健康响应；在 `modern/user_app` 下运行 `flutter test`，确认 Flutter 单元测试通过；在 `modern/admin_web` 下运行 `npm run build` 或等价构建命令，确认后台壳工程可构建。

## Validation and Acceptance

这一阶段的验收必须以“一个新用户链路、一个老用户链路、一条钱包链路”都成立为最低门槛。新用户链路指：通过新注册接口可以创建账号，创建后能立即获得有效访问令牌，并能读取自己的用户摘要和空钱包。老用户链路指：从遗留快照导入一个老用户后，可以使用遗留密码完成首次登录，登录成功后系统把凭据升级成新哈希；随后再次登录时不再依赖老哈希路径。钱包链路指：导入后的 `main_balance`、`vault_balance` 和 `frozen_balance` 与遗留快照中的 `points`、`back` 和 `lock_points` 一致；通过内部测试记账接口写入一条账变后，查询余额和账本时结果同步变化。

后端验收不止看接口是否返回 200。必须确认 Flyway 可以从空库完成建表，后端测试至少覆盖：登录成功、登录失败、冻结用户拦截、老哈希升级、新用户注册、用户摘要查询、钱包余额查询、钱包账本幂等写入。Flutter 验收以“可以登录并看到用户与钱包信息”为准。后台壳工程验收以“能够启动或构建成功，并能显示受保护占位页”为准。

如果本地有条件访问真实或脱敏的遗留快照，必须至少选一名普通用户做端到端验证。这个验证结果要记录在 `Outcomes & Retrospective` 中，明确写出旧数据与新数据是否一致、密码升级是否成功、是否出现字段缺失或精度偏差。

## Idempotence and Recovery

本计划中的所有结构性操作都必须设计成可重复执行。目录创建如果重复运行，不应该破坏已有文件；Flyway 迁移必须是追加式和版本化的，不能通过覆盖老脚本来“修复”已执行迁移；导入器必须以稳定主键做 upsert，重复导入同一用户不会生成重复账号或重复钱包账户；账本写入必须以业务唯一键或等价幂等键防止重复记账。

遗留系统在整个第一阶段都必须保持只读参考或原样运行状态，不允许对 `main/`、`core/`、`lib/`、`admin/` 和遗留数据库结构做破坏性调整。任何需要读取遗留数据的实现，都应该优先使用单独的数据源或导出快照，而不是在新服务里直接向遗留库写入。

如果某一步失败，恢复方式必须明确。后端构建失败就修构建，不迁就跳过测试。Flyway 迁移失败时，优先修正新的迁移版本，不回写已执行版本。导入器失败时，应能按批次或起始主键继续重跑。兼容登录路径如果出现问题，不应为了“先通”而删除哈希升级逻辑；应该先修兼容验证，再继续执行。

## Artifacts and Notes

后端健康检查成功时，预期至少返回一个明确表明服务存活的响应，例如包含 `UP` 或等价状态字段。登录成功响应中必须返回访问令牌、刷新令牌和用户摘要。`GET /api/v1/users/me` 必须返回 `userId`、`username`、`nickname`、`status` 等关键信息。`GET /api/v1/wallets/me` 必须返回主余额、保险箱余额和冻结金额，并明确金额口径。`GET /api/v1/wallets/me/ledger` 至少要能按时间逆序返回账变记录。

环境样板文件也属于阶段产物。后端需要 `.env.example` 或等价配置样板，用于说明 MySQL、Redis、JWT 密钥和遗留数据源的配置方式。Flutter 端需要清楚说明 API Base URL 的本地配置方法。Next.js 后台端需要 `.env.local.example` 或等价样板。

`modern/ops` 中需要留下足够的本地运行说明，例如本地需要哪些版本的 JDK、Node、Flutter、MySQL、Redis，是否提供 Compose 文件，以及如果没有这些运行时应该如何跳过某些步骤而仍然能完成静态构建和单测。

## Interfaces and Dependencies

后端最小服务边界如下。`AuthCommandService` 负责注册、登录、刷新令牌、登出和改密。`UserQueryService` 负责用户摘要与安全概况查询。`WalletCommandService` 负责余额增加、扣减、冻结和解冻。`WalletQueryService` 负责余额与账本查询。`LegacyPasswordVerifier` 负责老哈希兼容。`LegacyUserSnapshotImporter` 负责把遗留用户和钱包快照导入到新库。每个服务都必须有明确输入输出对象，而不是在控制器里直接拼 SQL 或直接改表。

运行依赖也必须提前固定。后端需要 JDK 21 级别的运行时、Gradle、MySQL 8、Redis 和一个现代密码哈希库。Flutter 端需要稳定可用的 Flutter SDK。Next.js 端需要 Node 20 或更高版本。遗留数据读取必须通过一个显式的“只读遗留数据源”或导出快照来完成，不能把新库和老库混成一个默认数据源。

这个阶段不引入 RabbitMQ、WebSocket、支付渠道或游戏开奖依赖。它们属于后续阶段。当前阶段唯一必须落地的外部依赖是：能够跑起后端、能够建表、能够导入用户快照、能够让一个前端真实调用后端。

## 收尾条件

当本计划所有未完成的 `Progress` 项都被勾掉，并且 `Validation and Acceptance` 里的三条链路都通过时，本阶段就算完成。完成后，不要继续在本文件里无边界扩写支付、提现、开奖和下注的实现细节；届时应该基于已经落地的新底座，另开下一轮 `ExecPlan`，或把当前计划明确扩展到下一个清晰范围。
