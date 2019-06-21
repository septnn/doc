# 安装 windows terminal

- [github地址](https://github.com/microsoft/terminal)
- [参考安装方法](https://github.com/microsoft/terminal/issues/489)

## 安装方法

- 下载vs2019，[地址](https://visualstudio.microsoft.com/zh-hans/downloads/?utm_medium=post-banner&utm_source=microsoft.com&utm_campaign=channel+banner&utm_content=launch+vs2019&rr=https%3A%2F%2Fwww.microsoft.com%2Fzh-cn%2Fdownload%2Fdeveloper-tools)
  - 下载的个人版
- git clone 源码
- 进入源码目录
- git submodule update --init --recursive
- 安装nuget，[地址](https://dist.nuget.org/win-x86-commandline/latest/nuget.exe)
  - 把nuget.exe 添加到环境变量
- nuget restore OpenConsole.sln
- vs2019 打开 OpenConsole.sln，提示安装14个G的依赖，等着吧
  - 为了这个terminal 装了将近20g的其他东西，我也是醉了
- 安装依赖完事后，右键解决方案，选择部署解决方案
  - 配置release  x64 解决方案

- 部署解决方案的时候报错
  - 5.1 C2220和代码页警告：所有错误和警告都是unicode字符的编码错误，一个有用的解决方法是修改相应的文件编码UTF-8 BOM（建议使用记事本+ +）
  - 5.2中的错误main.cpp：在addtion，你也应该补充u8的线串文字之前的前缀395，398，401和404