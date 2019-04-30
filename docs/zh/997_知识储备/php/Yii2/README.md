# 绝味框架

> 框架继承[YIIFRAMEWORK](https://www.yiiframework.com/doc/guide/2.0/en/start-workflow)

## 开始

1. Clone  
```sh
git clone http://xxxx@git.xxx.com/scm/mpos/yii2_base.git
```
2. 新建项目  
```sh
mkdir /home/xxx/newapp
cp -a yii2_base/demo/* /home/xxx/newapp/
cd /home/xxx/newapp/
```
3. 修改项目配置文件   
修改vendor地址 ,修改项目id，`vi /home/xxx/newapp/config/app.php`
```php
defined('YII_FRAME') or define('YII_FRAME', '/home/dawei/www/frame/');
defined('APP_ID') or define('APP_ID', 'TOS_API'); // 项目id，不同项目配置一次即可
```
4. 验证框架地址是否正确  
```sh
#执行yii命令
chmod 755 yii
./yii
```
5. 配置nginx  
```nginx
# 关键nginx配置，其它忽略
server {
    ......
    root        /home/dawei/www/frame/demo/web;
    ......
    location / {
        # Redirect everything that isn't a real file to index.php
        try_files $uri $uri/ /index.php$is_args$args;
    }
    ......
}
```
6. 访问 http://127.0.0.1/well-come/index
7. 示例  
一些示例代码在 `demo\controllers\WellComeController.php` 里面



## 快速手册

## 项目目录说明

- config                    配置文件
  - dev                     开发机配置文件夹
    - db.php                数据库配置
    - redis.php             redis配置
    - log.php               日志配置
  - app.php                 项目公共配置
  - web.php                 web配置
  - console.php             脚本配置
  - params.php              其它配置
- controllers               控制器
- models                    模型层
- runtime                   执行时文件
- views                     模板层
- web                       web入口
- yii                       脚本入口 


# Q & A

> None of the master DB servers is available
- 当主从数据库配置错误时，yii会把错误的配置信息，缓存10分钟，10分钟内不会读取错误配置。可以通过清除`runtime/cache/*`目录下文件。