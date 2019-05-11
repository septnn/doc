#cygwin root

最近在cygwin官网下载了最新的版本2.8.0-1。安装到win7系统下后登录发现不是以root权限登录后。在网上查找了一些修改方法，都说要修改/etc/passwd文件，而我安装的cygwin目录下却没有这个文件。最后发现可以用mkpasswd这个命令去创建后，再修改。所以推测cygwin可能是为了安全考虑，没有将此文件暴露出来，并且默认以常规用户的身份登录。具体修改办法如下：
1. 以默认用户登录后，先在/home目录下创建root目录。
       mkdir /home/root
2. 创建/etc目录下的passwd文件。
      mkpasswd -l > /etc/passwd
3. 将passwd默认的Administrator账号修改为root账号，打开passwd文件修改为如下：
root:*:0:0:U-ZI-PC\Administrator,S-1-5-21-4282381692-2555023074-2984043436-500:/home/root:/bin/bash
      红色字体为要修改的部分。在此说明下passwd文件的格式，便于更好的理解。
用户名:口令:用户标识号:组标识号:注释性描述:主目录:登录Shell
4. 保存后退出cygwin再重新登录。