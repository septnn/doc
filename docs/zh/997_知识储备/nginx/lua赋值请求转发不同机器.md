编写一个 copy request 的 lua 脚本copy_req.lua

local res1, res2, action
action = ngx.var.request_method
if action == "POST" then
        arry = {method = ngx.HTTP_POST, body = ngx.req.read_body()}
else
        arry = {method = ngx.HTTP_GET}
end

if ngx.var.svr == "on" then
        res1, res2 = ngx.location.capture_multi {
                { "/product" .. ngx.var.request_uri , arry},
                { "/test" .. ngx.var.request_uri , arry},
        }
else
        res1, res2 = ngx.location.capture_multi {
                { "/product" .. ngx.var.request_uri , arry},
        }
end

if res1.status == ngx.HTTP_OK then
        local header_list = {"Content-Length", "Content-Type", "Content-Encoding", "Accept-Ranges"}
        for _, i in ipairs(header_list) do
                if res1.header[i] then
                        ngx.header[i] = res1.header[i]
                end
        end
        ngx.say(res1.body)　　#此处代表只返回生产环境的返回结果
else
        ngx.status = ngx.HTTP_NOT_FOUND
end

此处文件地址引用是可以写觉得地址，相对地址是相对于nginx目录的。
b、配置对应的Nginx配置文件，此处本文地址是conf/vhost/fenliu.conf,在nginx.conf下端加入include vhost/*.conf;

fenliu.conf文件配置如下：

upstream product {
        server  127.0.0.1:80;
}
upstream test {
        server  192.168.1.1:88;
}
server {
        listen      8000;
        #lua_code_cache off;

        location ~* ^/product {
                log_subrequest on;
                rewrite ^/product(.*)$ $1 break;
                proxy_set_header Host $host;
                proxy_set_header X-Real-IP $remote_addr;
                proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
                proxy_pass http://product;
                access_log logs/product-upstream.log;
        }

        location ~* ^/test {
                log_subrequest on;
                rewrite ^/test(.*)$ $1 break;
                proxy_set_header Host $host;
                proxy_set_header X-Real-IP $remote_addr;
                proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
                proxy_pass http://test;
                access_log logs/test-upstream.log;
        }

        location ~* ^/(.*)$ {
                client_body_buffer_size 2m;
                set $svr    "on";              #开启或关闭copy功能
                content_by_lua_file conf/vhost/copy_req.lua;
        }
}

此文件很重要，这里备注的是本人自己的理解，^/product，^/test主要就是对这两个路径访问的url进行转发，一个转发到生产，一个到测试，多了一个rewrite是为了重写请求地址，下面会讲到，
^/(.*)$才是重点，是将所有非product，test请求进行请求复制转发。

以上面配置为例，实际使用的流程如下：
1、请求地址：http://ip:8000/hello/req.do
2、nginx不匹配product和test会走最后一个，通过Lua配置会变成两个请求/product/hello/req.do和/test/hello/req.do
3、这时会被nginx的product和test拦截到，进行转发到生产和测试环境，此时地址是不对的，所以使用rewrite进行url重写，
rewrite ^/product(.*)$ $1 break; 匹配/product/hello/req.do会变成/product（/hello/req.do），$1代表/hello/req.do，重写后的地址就会变成我们想要的地址，转发后就变成http://product/hello/req.do。