# IO多路复用模式-select-epoll-poll
- select
    - 支持1024个文件描述符
    - 用户进程轮询监听socket
- epoll
    - 支持linux ulimit 打开文件上限个文件描述符
    - socket回调，用户进程

# IO模式
对于一次IO访问（这回以read举例），数据会先被拷贝到操作系统内核的缓冲区中，然后才会从操作系统内核的缓冲区拷贝到应用程序的缓冲区，最后交给进程。所以说，当一个read操作发生时，它会经历两个阶段：  
1. 等待数据准备 (Waiting for the data to be ready)
2. 将数据从内核拷贝到进程中 (Copying the data from the kernel to the process)