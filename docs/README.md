# Septnn Doc Install

- **Clone**

```sh 
git clone https://github.com/sepntt/SeptnnDoc.git
```

- **Nginx**

```nginx
server {
    listen       xxx;
    server_name  xxx.com;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ \.php($|/) {
        root /xxx/SeptnnDoc/;
        if ($fastcgi_script_name ~* "^/upload.*/.+"){
            return 404;
        }
        set     $script         $uri;
        set     $path_info      "";
        if ($uri ~ "^(.+\.php)(/.+)") {
            set     $script         $1;
            set     $path_info      $2;
        }
        fastcgi_pass    127.0.0.1:9000;
        fastcgi_param   PATH_INFO       $path_info;
        fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param   SCRIPT_NAME     $script;
        include fastcgi_params;
    }
}
```

- **访问**
```sh
动态访问 ： `http://xxx.com`  
静态访问 ： 生成静态文件需要执行`./bin/daux generate`，然后访问`http://xxx.com/static`
```