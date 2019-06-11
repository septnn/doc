# fwrite(): send of 13 bytes failed with errno=32 Broken pipe

fwrite(): send of 21 bytes failed with errno=104 Connection reset by peer
 

用 rabbitmq 做消息队列时报上面的错误，当消费队列一启动，Unacked 瞬间达到好几百。经查：RabbitMQ服务器在短时间内发送大量的消息给Consumer，如果你没有来得及Ack的话，那么服务端会积压大量的UnAcked消息，而Consumer如果来不急处理也会处于假死或程序崩溃。

后果就是Consmer崩溃后，UnAcked消息又ReQueue不断消耗MQ的资源
 

解决方案：

$connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);

$channel = $connection->channel();

$channel->queue_declare(‘qos_queue‘, false, true, false, false);

$channel->basic_qos(null, 10, null); //加上这个就好了 这个10 就是Unacked 里面的值，表示预先取出多少值

# 心跳检测

[心跳检测](https://www.rabbitmq.com/heartbeats.html)