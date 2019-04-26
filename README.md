# Septnn Doc

## 个人相关的文档，基于[DAUX](http://daux.io)

## 安装

1. **克隆**

```sh 
git clone https://github.com/sepntt/SeptnnDoc.git
```

2. **Composer**

```sh
composer update
```


## 动态WEB配置  
1. **WEB服务**  
```sh
cd septnndoc
php -S 127.0.0.1:81
```
2. 访问`http://127.0.0.1:81`  

## 静态WEB配置  
1. 执行命令生成静态文件
```sh
cd septnndoc
./bin/daux generate
```
2. WEB配置  
```sh
cd static
php -S 127.0.0.1:82
```
3. 访问 `http://127.0.0.1:82`  
