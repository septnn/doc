参考 https://blog.csdn.net/ydyang1126/article/details/69681212
75
skip-external-locking 跳过进程锁表
external-locking = FALSE 避免外部锁定
key_buffer_size = 16M 
table_open_cache = 64  表高速缓存的大小Open_tables / table_open_cache <= 0.95
net_buffer_length = 8K 默认1M 通信时缓存数据的大小
read_buffer_size = 256K 读入缓冲区大小  1/16内存大小合适
read_rnd_buffer_size = 512K 
myisam_sort_buffer_size = 8M
binlog-ignore-db=mysql 指定库不写入binlog
open_files_limit    = 10240 mysqld打开的文件数
max_connections = 5000 最大连接数 max_used_connections / max_connections * 100% （理想值≈ 85%）
max_connect_errors = 6000 没有成功建立链接的一台机器，达到次数，阻断以后的请求
max_allowed_packet = 256M 服务器一次能处理最大的查询包的值，也是服务器程序能够处理的最大查询,大sql插入或更新的大小限制
thread_cache_size = 300 线程池中缓存的连接线程最大数量
sort_buffer_size = 1M 默认256k 排序 会话 的缓存大小 连接独享内存
join_buffer_size = 1M 联合查询操作所能使用的缓冲区大小 连接独享内存
query_cache_size = 12M 查询缓冲区的大小
query_cache_limit = 2M 小于此设置值的结果 被缓存
query_cache_min_res_unit = 2k
thread_stack = 192K 每个线程的堆栈大小 默认为192KB
tmp_table_size = 12M 内存临时表最大值
max_heap_table_size = 12M 独立的内存表所允许的最大容量
skip-name-resolve 禁止MySql对外部连接进行DNS解析
#slave server需要注释下面的log设置
log-bin=/data/mysql-5.5.43/logs/binlog/mysql-bin
max_binlog_cache_size = 256M
max_binlog_size = 1G
#slave结束
default-storage-engine=InnoDB
innodb_additional_mem_pool_size = 16M 用来设置InnoDB存储的数据目录信息和其他内部数据结构的内存池大小
#根据不同机器设置buffer
innodb_buffer_pool_size = 8G 缓冲池来保存索引和原始数据
innodb_file_io_threads = 8 文件I/O线程 默认4
innodb_thread_concurrency = 8 有几个CPU就设置为几 默认8
innodb_flush_log_at_trx_commit = 0 innodb_log_buffer_size队列满后在统一存储 默认1
innodb_log_buffer_size = 32M 默认为1MB，通常设置为8~16MB
innodb_log_file_size = 128M 日志文件的大小
innodb_log_files_in_group = 3 循环方式将日志文件写到多个文件 推荐3
innodb_file_per_table = 1 独立表空间模式，每个数据库的每个表都会生成一个数据空间 0关闭1开启
innodb_io_capacity=2000 缓冲区刷新到磁盘时，刷新脏页数量 取决于磁盘的IOPS
#结束
[mysqldump]
quick
max_allowed_packet = 16M
[mysql]
no-auto-rehash
max_allowed_packet = 32M
[myisamchk] #MyISAM引擎
key_buffer_size = 20M
sort_buffer_size = 20M
read_buffer = 2M
write_buffer = 2M
[mysqlhotcopy] #MyISAM引擎
interactive-timeout

