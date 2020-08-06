# readme

## document

> 准实时搜索：`When a document is stored, it is indexed and fully searchable in near real-time--within 1 second.`  

- 首先数据会被写入主分片所在机器的内存中，再触发flush操作，形成一个新的segment数据段，只有flush到磁盘的数据才会被异步拉取到其它副本节点，如果本次搜索命中副本节点且数据没有同步的话，那么是不会被检索到的；es默认flush间隔是1s，也可通过修改refresh_interval参数来调整间隔（为提升性能和体验，一版设置30s-60s）

### 索引

> 默认，索引所有字段中的数据
> 每种字段类型有专属的索引类型

#### inverted index 倒排索引|倒置索引|反向索引

> 创建索引：首先把所有的原始数据进行编号，形成文档列表。把文档数据进行分词，得到很多的词条，以词条为索引。保存包含这些词条的文档的编号信息  
> 搜索时，通过词搜索到文档编号列表

    text 字段使用 inverted idnex。

#### BKD-tree 

    numeric 、 geo 字段使用 BKD

## index

- Index 存储在多个分片中，其中每一个分片都是一个独立的 Lucene Index。这就应该能提醒你，添加新 index 应该有个限度：每个 Lucene Index 都需要消耗一些磁盘，内存和文件描述符。因此，一个大的 index 比多个小 index 效率更高：Lucene Index 的固定开销被摊分到更多文档上了

> 版本7之前，不同type相同字段的数据存储使用一个lucene字段，存储仅有小部分字段相同或者全部字段都不相同的文档，会导致数据稀疏，影响Lucene有效压缩数据的能力。

## segment 最小存储单元

多个小segment可合为一个较大的segment，并但不能拆分

## shards 分片

- 单个节点由于物理机硬件限制，存储的文档是有限的，如果一个索引包含海量文档，则不能在单个节点存储。ES提供分片机制，同一个索引可以存储在不同分片（数据容器）中，这些分片又可以存储在集群中不同节点上
- 分片分为 主分片(primary shard) 以及 从分片(replica shard) 
- 主分片与从分片关系：从分片只是主分片的一个副本，它用于提供数据的冗余副本 
- 从分片应用：在硬件故障时提供数据保护，同时服务于搜索和检索这种只读请求
- 是否可变：**索引中的主分片的数量在索引创建后就固定下来了**，但是从分片的数量可以随时改变
- 索引默认创建的分片：默认设置5个主分片和一组从分片（即每个主分片有一个从分片对应），但是从分片没有被启用（主从分片在同一个节点上没有意义），因此集群健康值显示为黄色(yellow)

### 主分片

- 节点上可以有主分片，也可以没有主分片
- 主分片在索引创建的时候确定，后续不允许修改。除非 Reindex 操作进行修改
- 

## 副本

- 用来备份数据，提高数据的高可用性。副本分片是主分片的拷贝
- 副本分片数，可以动态调整
- 增加副本数，可以一定程度上提高服务读取的吞吐和可用性

## 集群

- 集群由若干节点组成，这些节点在同一个网络内，cluster-name相同

### 节点

- 节点：运行es的实例,

#### Master-eligible Node 和 Master Node

- es 被启动后，默认就是 Master-eligible Node。然后通过参与选主过程，可以成为 Master Node
- Master Node 负责同步集群所有节点信息、所有索引即其 Mapping 和 Setting 信息、所有分片路由信息
- 负责集群中索引的创建、删除以及数据的Rebalance、添加节点到集群或从集群删除节点，跟踪哪些节点是集群的一部分，以及决定将哪些分片分配给哪些节点
- master节点无需参与文档层面的变更和搜索，master节点并不会因流量增长而成为瓶颈
- 任意一个节点都可以成为 master 节点
- 不负责数据的索引和检索，所以负载较轻
- 当Master节点失联或者挂掉的时候，ES集群会自动从其他Master节点选举出一个Leader
- 7xx版本，es系统选举master，cluster.initial_master_nodes初始化集群列表

脑裂现象：如果发生网络中断、节点负载过高或内存回收导致服务器宕机，那么集群会有可能被划分为两个部分，各自有自己的master来管理，那么这就是脑裂

#### Data 数据节点

- 持有数据和倒排索引
- 每个节点都可以通过设定配置文件elasticsearch.yml中的node.data属性为true(默认)成为数据节点
- 数据节点对cpu，内存，io要求较高
- 资源不够的时候，需要在集群中添加新的节点
- 建议和Master节点分开


#### Coordinating 协调节点

- 接收任何 Client 的请求，包括 REST Client 等
- 该节点将请求分发到合适的节点，最终把结果汇集到一起
- 每个节点默认起到了 Coordinating Node 的职责

#### 其它节点

- Hot & Warm Node：不同硬件配置的 Data Node，用来实现冷热数据节点架构，降低运维部署的成本
- Machine Learning Node：负责机器学习的节点
- Tribe Node：负责连接不同的集群。支持跨集群搜索 Cross Cluster Search

### 分布式

es天生支持分布式，配置与使用上与单机版基本没什么区别，可快速扩张至上千台集群规模、支持PB级数据检索；通过内部路由算法将数据储存到不同节点的分片上；当用户发起一次查询时，首先会在各个分片上完成提前批处理，处理后的数据汇总到请求节点再做一次全局处理后返回。



## mapping

> 自定义mapping

- 要区分`全文索引字符串`和`精确值字符串`
- 执行特定于语言的文本分析
- 优化字段用于部分匹配
- 使用定制日期格式
- 使用特定字段，例如`geo_point``geo_shape`，避免`dynamic mapping`
- 枚举类型建议keyword

`GET /kibana_sample_data_ecommerce/_mapping`

```json
{
  "kibana_sample_data_ecommerce" : { 
    "mappings" : {
      "properties" : {
        "category" : {
          "type" : "text",
          "fields" : {
            "keyword" : {
              "type" : "keyword"
            }
          }
        },
        "currency" : {
          "type" : "keyword"
        },
        "customer_birth_date" : {
          "type" : "date"
        },
        "customer_first_name" : {
          "type" : "text",
          "fields" : {
            "keyword" : {
              "type" : "keyword",
              "ignore_above" : 256
            }
          }
        },
        "customer_full_name" : {
          "type" : "text",
          "fields" : {
            "keyword" : {
              "type" : "keyword",
              "ignore_above" : 256
            }
          }
        },
        "customer_gender" : {
          "type" : "keyword"
        },
        "customer_id" : {
          "type" : "keyword"
        },
        "customer_last_name" : {
          "type" : "text",
          "fields" : {
            "keyword" : {
              "type" : "keyword",
              "ignore_above" : 256
            }
          }
        },
        "customer_phone" : {
          "type" : "keyword"
        },
        "day_of_week" : {
          "type" : "keyword"
        },
        "day_of_week_i" : {
          "type" : "integer"
        },
        "email" : {
          "type" : "keyword"
        },
        "geoip" : {
          "properties" : {
            "city_name" : {
              "type" : "keyword"
            },
            "continent_name" : {
              "type" : "keyword"
            },
            "country_iso_code" : {
              "type" : "keyword"
            },
            "location" : {
              "type" : "geo_point"
            },
            "region_name" : {
              "type" : "keyword"
            }
          }
        },
        "manufacturer" : {
          "type" : "text",
          "fields" : {
            "keyword" : {
              "type" : "keyword"
            }
          }
        },
        "order_date" : {
          "type" : "date"
        },
        "order_id" : {
          "type" : "keyword"
        },
        "products" : {
          "properties" : {
            "_id" : {
              "type" : "text",
              "fields" : {
                "keyword" : {
                  "type" : "keyword",
                  "ignore_above" : 256
                }
              }
            },
            "base_price" : {
              "type" : "half_float"
            },
            "base_unit_price" : {
              "type" : "half_float"
            },
            "category" : {
              "type" : "text",
              "fields" : {
                "keyword" : {
                  "type" : "keyword"
                }
              }
            },
            "created_on" : {
              "type" : "date"
            },
            "discount_amount" : {
              "type" : "half_float"
            },
            "discount_percentage" : {
              "type" : "half_float"
            },
            "manufacturer" : {
              "type" : "text",
              "fields" : {
                "keyword" : {
                  "type" : "keyword"
                }
              }
            },
            "min_price" : {
              "type" : "half_float"
            },
            "price" : {
              "type" : "half_float"
            },
            "product_id" : {
              "type" : "long"
            },
            "product_name" : {
              "type" : "text",
              "fields" : {
                "keyword" : {
                  "type" : "keyword"
                }
              },
              "analyzer" : "english"
            },
            "quantity" : {
              "type" : "integer"
            },
            "sku" : {
              "type" : "keyword"
            },
            "tax_amount" : {
              "type" : "half_float"
            },
            "taxful_price" : {
              "type" : "half_float"
            },
            "taxless_price" : {
              "type" : "half_float"
            },
            "unit_discount_amount" : {
              "type" : "half_float"
            }
          }
        },
        "sku" : {
          "type" : "keyword"
        },
        "taxful_total_price" : {
          "type" : "half_float"
        },
        "taxless_total_price" : {
          "type" : "half_float"
        },
        "total_quantity" : {
          "type" : "integer"
        },
        "total_unique_products" : {
          "type" : "integer"
        },
        "type" : {
          "type" : "keyword"
        },
        "user" : {
          "type" : "keyword"
        }
      }
    }
  }
}
```

| 字段 | 说明 |
|----|----
| properties | index属性 |
| type | 字段属性 |
| fields | 字段属性 |

### 核心类型

#### text

- 需要被全文检索的字段
- 会被Lucene分词器（Analyzer）处理为一个个词项
- 使用Lucene倒排索引存储
- 不能被用于排序

#### keyword

- 适合简短、结构化字符串
- 用于过滤、排序、聚合检索、精确查询

#### long、integer、short、byte、double、float、half_float、scaled_float

- long[-2^63,2^63-1]
- integer[-2^31,2^31-1]
- short[-32768,32767]
- byte[-128,127]
- double IEEE 754标准双精度浮点类型，8字节
- float IEEE 754标准单精度浮点类型，4字节
- half_float IEEE 754标准单精度浮点类型，2字节
- scaled_float 缩放类型浮点类型

- 存储数值
- 尽量选择范围较小的数据类型
- 字段长度越短，搜索效率越高
- 建议scaled_float存浮点类型。scaled_float类型，可以通过缩放因子来精确浮点数，12.34可以转换为1234来存储


#### date、date_nanos

- 时间字符串、时间戳
- date、date_nanos底层采用的是时间戳的形式存储

#### boolean

- 常用于检索中的过滤条件

#### binary

- 接受BASE64编码的字符串
- 默认store属性为false
- 不可以被搜索

#### range

- integer_range，可以表示最大的范围为[-2^31, 2^31-1]
- float_range，可以表达IEEE754单精度浮点数范围
- long_range，可以表示最大的范围为[-2^63,2^63-1]
- double_range，可以表达IEEE754双精度浮点数范围
- date_range，可以表达64位时间戳（单位毫秒）范围。

### 复合类型

#### array

[1,2,3]、["a","b","c"]、[{"name":"Trump"},{"name":"Smith"}]是合法

- Lucene底层并不真正支持数组类型
- 动态映射，如果值是空数组，那么会当成null来处理，不会创建该字段的映射

#### object

- Lucene并没有内部对象的概念

#### nested


### 地理类型

#### geo_point

 经纬度类型，通过地理类型的字段，可以用来实现诸如查找在指定地理区域内相关的文档、根据距离排序、根据地理位置修改评分规则等需求
 
- 需要使用到经纬度类型，需要手动定义映射

geo_point接收以下地理位置数据

- 经纬度键值对，lat为纬度，lon为经度
```json
{
  "location": {
    "lat": 30.0,
    "lon": -50.0
  }
}
```
- 用逗号分割的字符串，前者为纬度，后者为经度
```json
{
  "location": "30.0,-50.0"
}
```
- 数组形式坐标
```json
{
  "location": [30.0,-50.0]
}
```

#### 地理区域类型 GeoJSON

### 特殊类型

#### IP类型

IP类型的字段可以用来存储IPv4或者IPv6地址

- 需要存储IP类型的字段，需要手动定义映射

#### join类型

### dynamic

- true 新字段数据，dynamic mapping（动态映射）
- false 新字段数据，忽略
- strict 新字段数据，报错

### properties

### muti_field 一个字段多个索引

### _id

### ignore_above

- (int) 字符串长度

超过 ignore_above 的字符串，analyzer 不会进行处理，

### index

- analyzer 分词
- no_analyzer 不分词直接索引
- no 不索引