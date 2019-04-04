# Tools
## 工具
### 简历工具

- [简历](Resume.md)

- todo list
- [x] 个人简历，markdown格式
- [x] 生成html脚本，调用[github-api](https://developer.github.com/v3/markdown/)
- [ ] 生成pdf

### How
1. 获取username和token，username就是github的登录账号，[获取token](https://github.com/settings/tokens)
2. clone
    ``` 
    # git clone git@github.com:sepntt/PHPKnowledgeTree.git
    # cd ./resume/
    ```
3. 编辑Resume.md，根据github md语法。
4. 修改./tools/tohtml.php内token成员变量
    ``` 
    # cd ../tools/
    # vi tohtml.php
    ```
    ```php
    public $token = 'token';
    ``` 
5. 获取html文件
    - cli执行，根据提示，获取html文件
        ```
        # php v1.php username
        ```
    - url执行，访问http://127.0.0.1/tohtml.php?username=username，直接输出结果
        ```
        # php -S 127.0.0.1
        ```

