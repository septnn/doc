fastcgi_param SERVER_SOFTWARE nginx; 隐藏nginx版本号
fastcgi_param HTTPS $https if_not_empty; 有https协议，启动nginxhttps，否则忽略
worker_processes 32 启动32个工作进程，cpu核数的2倍
events use epoll 使用epoll的io模型
worker_connections 10240 工作进程的最大连接数
keepalive_timeout 120 默认75 客户端keeplive 保持活动连接时间 
fastcgi_connect_timeout 300 默认60 连接FastCGI服务器建立连接的超时时间
fastcgi_send_timeout 300 默认60 将请求传输到FastCGI服务器的超时
fastcgi_read_timeout 300 默认60 从FastCGI服务器读取响应的超时
fastcgi_buffer_size 128k 默认4k 读取从FastCGI服务器接收的响应的第一部分的缓冲区的大小
fastcgi_buffers 8 128k 默认8 4k 从FastCGI服务器读取响应的缓冲区的数量和大小，用于单个连接 
fastcgi_busy_buffers_size 128k 整个数据请求需要多大的缓存区，建议设置为fastcgi_buffers值的两倍
fastcgi_temp_file_write_size 128k 表示在写入缓存文件时使用多大的数据块，默认值是fastcgi_buffers的两倍
client_body_timeout 60 读取客户端请求正文的超时
client_max_body_size 300M 客户端请求正文的最大允许大小
gzip 关闭 开启gzip会影响php无刷新输出缓存，或者单独配置关闭gzip
tcp_nodelay on 
accept_mutex off 默认on 一个新连接，所有的Worker都会被唤醒，然后用一个，剩下休眠，吞吐量大off这个配置
log
log_format  main1  '$remote_addr - $remote_user [$time_local] "$request" '
                      '$status $body_bytes_sent "$http_referer" '
                      '"$http_user_agent" "$http_x_forwarded_for"'
                       '$upstream_addr $upstream_response_time $request_time ';