# 一台机器装多个php版本，很是费劲，研究了一下docker php，贴上来，给大家参考。

1. 本地nginx+docker php-apache
官网的apache+nginx反向，我没有验证。
```sh
docker run -it --rm --name php-apache -p 8080:80 -v /home/www/:/home/www/:rw  php:7.2.8-apache
```
nginx conf
```sh
location / {
    proxy_pass   127.0.0.1:8080;
}
```

2. 本地nginx+docker php-fpm  
阅读官网教程后，所得如下：

volume最好前后写的一样，不容易出错。
```sh
docker run -itd --name php7.2 -p 9000:9000 -v /home/dw:/home/dw:rw php:7.2.8-fpm
```  
nginx conf 注意fastcgi_param配置，index.php的执行路径要写对。  
```sh
        location ~ \.php$ {
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME  /home/www/api/public/$fastcgi_script_name;
            include fastcgi_params;
        }
```  
可能会出现404报错
一般就是没有可执行权限，在宿主机上把对应的项目目录给上755权限。

临时脚本执行
不过还是没有直接php执行用的爽。
```sh
docker run -it --rm --name php-cli -v /home/www:/home/www:rw php php /home/www/1.php
```
3. 其它版本
多版本php依葫芦画瓢。

文章仅供参考，有什么问题欢迎提出。

4. php扩展安装
mcrypt为例：

已经安装好7.0 fpm。

注意，本次用的是7.0，7.2 docker-php-ext-configure提示already compiled，暂时没有找到原因。
`docker exec -it php7.0 /bin/bash`  
进入容器后： 
```sh
# 更新apt-get
apt-get update

# 安装mcrypt
apt-get install libmcrypt-dev

docker-php-ext-configure mcrypt

docker-php-ext-install mcrypt 
# 安装gd
apt-get install libfreetype6-dev libjpeg62-turbo-dev libpng-dev

docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include

docker-php-ext-install gd

# 安装zip
apt-get install libzip-dev

docker-php-ext-configure zip --with-libzip

docker-php-ext-install zip

安装pdo

安装bcmath

退出重启

docker restart php7.0
```