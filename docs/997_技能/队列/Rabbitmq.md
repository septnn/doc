# Rabbimq

## 基础知识

- Rabbitmq 是消息队列中间件，一个服务
- 一个rabbitmq叫做broker（消息队列服务器实体，又叫中间件）
- 一个vhost叫做broker的一个虚拟消息服务器，又叫虚拟主机

> 主要流程：生产消息->队列->消费消息

- Rabbitmq 分为 exchange 和 queue

> 细化流程：生产消息->exchange->queue->消费消息

- exchange可以配置多个，queue也可以配置多个  
- exchange保存binding 关系的查找表，不同的 routing_key 决定把 消息 分配给哪个queue

> 细化流程：生产消息->exchange->根据routing_key判断->queue->消费消息

- 如果exchange通过routing_key没有匹配到 queue，则消息，会被**丢弃**
- 有4个类型
  - fanout exchange->所有binding的queue
  - direct exchange->binding的queue中指定routing_key的queue
  - topic exchange->binding的queue中模糊匹配routing_key的queue
  - headers exchange->??

> 细化流程：生产消息->exchange->根据类型判断应该怎么分配->根据routing_key判断->queue->消费消息

- Connection tcp连接，数据入队出队都是通过tcp传输数据

> 细化流程：  
> 生产消息->Connection->exchange->根据类型判断应该怎么分配->根据routing_key判断-> **queue** <-Connection<-消费消息

- Channel 虚拟连接，数据流动都是在Channel中进行，负责按照 routing_key 将 message 投递给 queue
- 一个 channel 只能被单独一个操作系统线程使用

> 细化流程：  
> 生产消息 -> 建立Connection -> 建立Channel -> 获取exchange -> 获取exchange type -> 获取binding 的 routing_key -> **Channel发送消息给queue** <- 建立Channel <- 建立Connection <- 消费消息

## php 操作

> AMQPStreamConnection 会自动建立exchange 和 queue 并且绑定exchange queue


## 控制台

- exchange
  - Virtual host:属于哪个Virtual host。
  - Name：名字，同一个Virtual host里面的Name不能重复。
  - Durability： 是否持久化，Durable：持久化。Transient：不持久化。
  - Auto delete：当最后一个绑定（队列或者exchange）被unbind之后，该exchange自动被删除。
  - Internal： 是否是内部专用exchange，是的话，就意味着我们不能往该exchange里面发消息。
  - Arguments： 参数，是AMQP协议留给AMQP实现做扩展使用的。
    - alternate_exchange配置的时候，exchange根据路由路由不到对应的队列的时候，这时候消息被路由到指定的alternate_exchange的value值配置的exchange上。（下面的博客会有说明这参数的具体使用）

### 建立队列的规则

规则

- exchange 和 queue 都设置 durable 属性. exchange和queue消息持久化配置
  - vhost 使用不同vhost
  - durability 使用持久化：Durable
  - type 使用routing_key 分配队列：direct ，如果对应多个队列可以扩展
  - 其它默认
- queue
  - vhost 选择对应exchange的vhost
  - 
- message 设置 persistent 代码：`delivery_mode = 2` . 消息持久化配置
- 入队的时候，指定 exchange queue 配置
- 

扩展

- 不建议所有message持久化，io比ram满，可能差10倍
