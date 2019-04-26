# 美团开放平台

[美团开放平台](http://developer.waimai.meituan.com/)

## 技术方案

- 接收回调数据

进入队列，[队列介绍](/zh/技能/队列/Rabbitmq.md)，消费者phpcli进行消息消费，进入其它队列和入库操作。