# PHP

## 二进制安全

- php在内部操作二进制数据的时候，能保证达到预期的效果
- php使用zval结构存放所有数据结构，type存储数据类型，value存放数据值，对于value操作不涉及到二进制特殊字符操作

## 执行过程
- nginx->fast-cgi->9000端口->php-fpm->php-cgi->sapi->zend-api->zend->解析源码->语言片段->表达式->编译成opcode->执行opcode

## 字符串连接
- 生成新的变量，重新分配新内存

## 多进程和多线程
- 多进程，利用pcntl_fork方法，
- 多线程
    - 线程实例继承thread，实现线程run方法
    - 

## 安全

### save_mode 安全模式

php.ini safe_mode 有很多函数首档影响，例如执行权限，执行系统函数等

利用safe_mode_exec_dir加载可执行文件
利用safe_mode_include_dir包含可包含文件
利用safe_mode_allowed_env_vars添加可是用环境变量

### expose_php 