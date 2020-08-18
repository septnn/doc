# 源码阅读

## 环境

- 使用winubuntu子系统
- `clone` 源码
- `apt install build-essential` c/c++依赖

## 最精简编译

### `buildconf` 构建前验证基础环境和配置文件

- 源码

```sh
min_version=$(sed -n 's/AC_PREREQ(\[\(.*\)\])/\1/p' configure.ac)
ac_version=$($PHP_AUTOCONF --version 2>/dev/null|head -n 1|sed -e 's/^[^0-9]*//' -e 's/[a-z]* *$//')
# 貌似是判断 当前php 需要autoconf的最低版本
# 判断autoconf是否存在
```

- `./buildconf`

```sh
buildconf: Checking installation
buildconf: autoconf version 2.69 (ok)
buildconf: Cleaning cache and configure files
buildconf: Rebuilding configure
buildconf: Rebuilding main/php_config.h.in
buildconf: Run ./configure to proceed with customizing the PHP build.
```

### `./configure --disable-all` 配置

- `./configure --disable-all` 最精简php

## 关键词记录

| 关键词 | 说明 | 备注 |
|----|----|----|
| ## | 连接符 | 可以重复使用宏，减少代码密度 |
| # | 字符串化操作 | 将语⾔符号(Token)转化为字符串 |
| #define | 宏 | #define 标识符 替换列表 |
| #ifdef | 条件编译 | 判断某个宏是否被定义，若已定义，执行随后的语句 |
| #line | 条件编译 | 用于强制指定新的行号和编译文件名,并对源程序的代码重新编号 |
