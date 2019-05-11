# TCP

- 慢启动机制，为了高效率和统一性
    - 先发送一个包，如果不丢包，就快速发送，如果丢包，则降速发送
- tcp建立链接的过程，c是客户端，s是服务端
    - connect 三次握手 
        - c SYN_SEND 发送syn，给s
        - s SYN_RCVD 确认后，发送ack和syn，给c
        - c established 建立链接，发送ack，给c
        - s established 建立链接
    - write
        - c established 
    - close 四次分手
        - c 发送fin和ack，给s
        - s 确认后，发送ack，给s
        - c time_wait
        - s close后，发送fin和ack，给c
        - c close后，发送ack，给s
