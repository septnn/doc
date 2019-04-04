#mysql

##合理建立表结构

###字段合理

 - 越小越好，占用更少的磁盘，内存，cpu缓存，cpu处理周期短
     - 简单的数据类型，cpu处理周期短，优先级int、字符，使用内置数据类型存储特殊数据（时间、intip，inet_aton/inet_ntoa）
 - notnull，NULL使索引、索引统计和值更加复杂，多一个字节的存储是否null
 - 字段属性
     - int 32位8字节，值2的32-1次方-1，unsigned正负，ZEROFILL前补0，int(10)显示10位
     - char固定字节，空右填充空格
     - varchar可变n+1|2字节，1|2存长度，1字节存长度为255字节，超过存2字节
     - 二进制存储字符串，大小写敏感，直接比较字节，比字符串查询快
     - blob text 排序按照max_sort_length长度的前缀排序
     - enum 存的整型，排序按照整型，可以用field函数显示排序
     - datetime 1001-9999年
     - timestamp 1970-2038年，时区变化，DEFAULT CURRENT_TIMESTAMP当前时间
 - 减少列，存储引擎工作时，需要在服务器层和存储引擎层之间通过行缓冲格式copy数据，然后在服务层将缓冲内容解码成各个列。
 - 减少关联，最好在12个表内关联
 - 减少过度枚举，1-100都枚举
 - 合理利用范式
 - 减少大字段，合理合并大字段，压缩大字段(compress函数，不建议用)，大字段占用大空间，可能走不上索引

###优化

 - 查询过程：
     - 客户端->通讯协议->服务器->mysql服务器->查询缓存->解析器->解析数->与处理器->解析数->查询优化器->查询执行计划->查询执行引擎->API->存储引擎->数据->返回
 - 核心：在一定负载下降低响应时间
 - 计数器，单表多条，随机更新一条，避免互斥锁，取sum
 - 业务上，精确数据查询，减少查询数据量，精确列查询。减少io内存cpu的消耗
 - 衡量查询开销的三个基本值
     -  响应时间
         - 服务时间
         - 排队时间
 - 扫描行数
 - 返回行数
 - 索引优化：性能优化/索引优化
 - 查看sql查询成本，性能优化/sql查询成本 ，没啥大用
 - mysql连接和断开连接都很轻量
 - 减少联合查询
     - 业务可以增加缓存
     - 减少锁竞争
     - 高性能可扩展
     - 关联字段
         - 长度一致、编码一致
 - on using子句中的列有索引，在关联顺序中的第二个表的相应列创建索引
 - groupby orderby 在一个表，mysql可能会用到索引
 - limit慢
     - 可以使用内联innerjoin 例:select id,name from t inner join (select id from t order by id desc limit 100,10) as l using id
     - 利用between代替 例:select id,name from t where between 100 and 110 order by id desc 
     - 利用id> 例:select id,name from t where id > 100 order by id desc limit 10
 - 利用show profile分析sql语句，并优化
 - 分表，高并发，大数据量，可以用merge存储引擎分表，也可以手动分表
 - 分区，大数据量，sql create创建分区，分区不是越多越好，根据机器性能评估，最好200以内
 - 全文索引
     - 5.7.6 支持中文，ngram插件
         - 自然语言模式  in natural language mode
             - select * from t where match(name) against ('你好' in natural language mode)
         - 布尔模式 in boolean mode
             - select * from t where match(name) against ('你好' in boolean mode)
 - 减少使用mysql自带函数，now date相关函数，可能会导致不走查询缓存
 - 配置优化
     - key_buffer_size 索引缓存大小 myisam 内存的 30-40%
     - innodb_buffer_pool_size 70-80% 的可用内存，越大吞吐量(单位： tps)就越高
     - table_open_cache 表高速缓存的大小 
     - thread_cache_size 线程缓存数
     - query_cache_size 查询缓存
     - read_buffer_size 读入缓冲区大小
     - read_rnd_buffer_size 随机读缓冲区大小
     - sort_buffer_size 排序缓存大小 不过多小的排序都分全部设置内存

###问题排查

 - sql问题还是服务器问题，借助监控工具：工具/，简答原理
     - show global status 
     - Threads_connected 连接数
     - Threads_running 并发执行stmt/command的数量 http://blog.itpub.net/15480802/viewspace-1452265/
     - Questions 已经发送给服务器的查询的个数
     - Queries 服务器执行的请求个数，包含存储过程中的请求
 - 检查表损坏 check table，修复表 repair table，rsync可能会导致innodb表损坏
     - innodb恢复数据工具：工具/innodb表损坏恢复数据
 - 查询语句过长保存，show variables max_allowed_packet
 - 检查mysql服务状态
     - mysqladmin extended-status -ri10

###技巧使用

 - ON DUPLICATE KEY UPDATE，并且插入行后会导致在一个UNIQUE索引或PRIMARY KEY中出现重复值，则执行旧行UPDATE
 - 隔离级别：mysql/四个隔离级别
     - sql查询状态，性能优化/show processlist详解
     - sleep 线程正在等待客户端发送新请求
     - query 线程正在执行查询或者正在将结果发送给客户端
     - locked 线程等待表锁，
     - analyzing and statistics 线程正在收集存储引擎的统计信息，并生成查询的执行计划
     - copying to tmp table [on disk] 线程正在执行查询，并讲结果复制到临时表，应该正在做groupby、文件排序（没有利用索   - 引的排序）、union。on disk 代码线程正在把内存临时表放到磁盘
     - sorting result 线程正在对结果集进行排序
     - sending data 线程在多个状态之间传送数据、线程生成结果集、线程向客户端发送数据
 - show index from 查看索引基数
 - 当客户端从服务器获取数据的时候，实际上是服务器不断给客户端推送数据
 - mysql_query php把结果集全部缓存到内存中，适合慢多，pdo默认。mysql_unbuffered_query php不会缓存，节省内存，但    - close慢，适合快少，pdo参数mysql_use_result=1。
 - 主从，读写分离
     - sync_binlog=500 每次提交事物前，讲二进制日志同步到磁盘上。配置在主库。
     - innodb_flush_logs_at_trx_commit=2 每秒执行一次 flush(刷到磁盘)操作
     - innodb_support_xa=1 分布式事务
     - 从库 read_only只读权限
     - sync_master_info=1关闭mysql时保存信息
     - sync_relay_log=1每1次relay_log事件，刷新到磁盘上
     - sync_relay_log_info=1每1次事物刷新relay_log
     - master.info文件里面存了服务器信息，注意权限
     - log_slave_updates可以让从库变主库
 - 注意内存使用情况
     - innodb缓冲池 innodb_buffer_pool_size innodb/缓冲池
         - 缓存索引、行数据、自适应哈希索引、插入缓冲、锁、内部数据结构
     - innodb日志文件/重做日志缓存 innodb/日志文件
     - myisam数据的操作系统缓存
     - myisam键缓存 key_buffer_size
     - 查询缓存 query_cache_size
     - 二进制日志缓存 %binlog%cache%
 - 注意磁盘空间

###锁

  - 共享锁，读锁
  - 排它锁，写锁
     - 堵塞读和写操作
  - 位置
     - 表锁
         - 开销最小，并发差
         - 写比读优先级高，插入队列读的前面
  - 行锁
     - 开销大，并发高
  - myisam
     - 表锁，
  - innodb
     - 有索引，行级锁
     - 没有索引，表锁

###事物
 - 事物ACID
     - 原子性
     - 一致性
     - 隔离性，隔离级别不同有分别
     - 持久性
 - 隔离级别，通过sql语句设置隔离级别：set session transaction isolation level $level
     - 未提交读：read uncommitted
     - 允许脏读，可能读取到其它事物还没有提交事物中修改的数据
         - a事物开启事物，a未提交，b事物开启事物，然后b事物更新数据，b未提交，a事物可以读取到b事物已经修改的数据，所以当b回滚前一刻，a就是脏读，读取的是错误的数据。
     - 提交读：read committed
         - 不可重复读，只能读取事物已经提交的数据
         - a事物开启事物，a未提交，b事物开启事物，然后b事物更新数据，b未提交，a事物不可以读取到b事物已经修改的数据；所以a未提交，a读取在b提交前，a读取b未提交的数据，a读取在b提交后，a读取b提交后的数据。
         - select无锁，insert、update、delete加锁
     - ？可重复读，Innodb默认级别：repeatable read。
         - a事物开启事物，a未提交，b事物开启事物，然后b事物更新数据，b未提交，a事物不可以读取到b事物已经修改的数据
     - 串行读
         - 事物串行执行，先来的先锁，后来等待



