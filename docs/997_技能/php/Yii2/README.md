# YII2框架

## 说明

> 框架继承[YIIFRAMEWORK](https://www.yiiframework.com/doc/guide/2.0/en/start-workflow)

## 当前框架vendor包列表 `TO DO`

```sh
# 快速格式化 markdown 
# 示例：bin/markdown README.md > README.html
cebe/markdown                  1.2.1   A super fast, highly extensible markdown parser for PHP
# 
doctrine/lexer                 v1.0.1  Base library for a lexer that can be used in Top-Down, Recursive Descent Parsers.
egulias/email-validator        2.1.7   A library for validating emails against several RFCs
ezyang/htmlpurifier            v4.10.0 Standards compliant HTML filter written in PHP
swiftmailer/swiftmailer        v6.2.0  Swiftmailer, free feature-rich PHP mailer
symfony/polyfill-iconv         v1.11.0 Symfony polyfill for the Iconv extension
symfony/polyfill-intl-idn      v1.11.0 Symfony polyfill for intl's idn_to_ascii and idn_to_utf8 functions
symfony/polyfill-mbstring      v1.11.0 Symfony polyfill for the Mbstring extension
symfony/polyfill-php72         v1.11.0 Symfony polyfill backporting some PHP 7.2+ features to lower PHP versions
yidas/yii2-composer-bower-skip 2.0.13  A Composer package that allows you to install or update Yii2 without Bower-Asset
yiisoft/yii2                   2.0.17  Yii PHP Framework Version 2
yiisoft/yii2-composer          2.0.7   The composer plugin for Yii extension installer
yiisoft/yii2-swiftmailer       2.1.0   The SwiftMailer integration for the Yii framework

```

## 使用说明

### 克隆框架

> 可以放到任意位置

```sh
git clone
```

### 克隆项目DEMO

> 可以放到任意位置

```sh
git clone
```

### 修改项目配置文件

- 修改框架配置参数`vi index.php`

```php
defined('YII_FRAME') or define('YII_FRAME', '/home/dawei/www/frame');
```

### 配置nginx

```nginx
server {
    charset utf-8;
    #client_max_body_size 128M;

    listen 31011; ## listen for ipv4
    #listen [::]:80 default_server ipv6only=on; ## listen for ipv6

    server_name frame.dev.juewei.com;
    root        /home/dawei/www/frame/web;
    index       index.php;

    access_log  /home/dawei/nginx/logs/frame.juewei.com.access.log;
    error_log   /home/dawei/nginx/logs/frame.juewei.com.error.log;

    location / {
        # Redirect everything that isn't a real file to index.php
        try_files $uri $uri/ /index.php$is_args$args;
    }

    # uncomment to avoid processing of calls to non-existing static files by Yii
    #location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
    #    try_files $uri =404;
    #}
    #error_page 404 /404.html;

    # deny accessing php files for the /assets directory
    location ~ ^/assets/.*\.php$ {
        deny all;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass 127.0.0.1:9000;
        #fastcgi_pass unix:/var/run/php5-fpm.sock;
        try_files $uri =404;
    }

    location ~* /\. {
        deny all;
    }
}
```



### 快速手册

#### 项目目录说明

- config                    配置文件
- controllers               控制器
- models                    模型层
- runtime                   
- views
- web