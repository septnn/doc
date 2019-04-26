> [php] [简历]

# 简历工具

## 1 描述

- [我的简历](/zh/1_简历/简历.md)
- todo list
    - [x] 个人简历，markdown格式
    - [x] 生成html脚本，调用[github-api](https://developer.github.com/v3/markdown/)
    - [ ] 生成pdf

## 2 生成html
1. 获取username和token，username就是github的登录账号，[获取token](https://github.com/settings/tokens)
2. clone
```sh
git clone git@github.com:sepntt/SeptnnDoc.git
cd ./简历/
```
3. 编辑简历.md，根据github md语法。  
4. 修改 `vi tohtml.php` 内 `token` 和 `username`变量  
```php
$username = 'github账号';
$token = 'github token';
``` 
5. 获取html文件 `php tohtml.php username`

