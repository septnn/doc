# 增删改查

## 增

```
POST user_info/_doc
{
    "user_id": "123"
}
```

## 改

```
# 直接修改
POST user_info/_doc/dTRM4moBLJ867DwTWabX/_update
{
  "doc" : {
    "tag_id": [4,5,6]
  }
}
# 数组追加
POST user_info/_doc/dTRM4moBLJ867DwTWabX/_update
{
  "script": {
    "source": "ctx._source.tag_id.add(params.tag)",
    "lang": "painless",
    "params": {
      "tag": 7
    }
  }
}
# 删除一个数组元素，按照数组的key删除
POST user_info/_doc/RzRZ4moBLJ867DwTn66e/_update
{
  "script" : {
        "source": "ctx._source.tag_id.remove(params.tag)",
        "lang": "painless",
        "params" : {
            "tag" : 3
        }
    }
}
# 删除一个数组元素，按照数组的value删除
# 当数组内有重复数据的时候，ctx._source.tag_id.indexOf获取的key的方式是，先入先出
POST user_info/_doc/RzRZ4moBLJ867DwTn66e/_update
{
  "script" : {
        "source": "ctx._source.tag_id.remove(ctx._source.tag_id.indexOf(params.tag))",
        "lang": "painless",
        "params" : {
            "tag" : 3
        }
    }
}
```

# 一些mapping操作

## 创建

```
PUT user_info
{
    "mappings": {
        "_doc": {
            "dynamic": "strict",  // 不可直接追加字段
            "properties": {
                "openid": {
                    "type": "keyword"
                }, 
                "phone": {
                    "type": "keyword"
                }, 
                "source": {
                    "type": "long"
                }, 
                "unionid": {
                    "type": "keyword"
                }, 
                "user_id": {
                    "type": "keyword"
                },
                "wx_tag_id": {
                    "type": "keyword"
                }
            }
        }
    }
}
```

## 追加字段

```
PUT user_info/_doc/_mapping
{
  "properties": {
    "tag_id": {
      "type": "keyword" 
    }
  }
}
```

# 快捷接口

## 快捷接口

```sh
# 获取scroll_id
curl -H "Content-Type: application/json" -X GET http://127.0.0.1:9200/test/_search?scroll=1m -d '{"size": 10,"query": {"match_all" : {}}}'
# 递归获取数据
curl -H "Content-Type: application/json" -X POST http://127.0.0.1:9200/_search/scroll -d '{"scroll" : "1m","scroll_id" : "scroll_id"}'
# 搜索总数
curl -H "Content-Type: application/json" -XGET http://127.0.0.1:9200/test/_count?pretty -d '{"query":{"match_all":{}}}'
curl -H "Content-Type: application/json" -XGET http://127.0.0.1:9200/test/people/_count?pretty -d '{"query":{"match":{"tag_id":"mkit"}}}'
curl -H "Content-Type: application/json" -XGET http://127.0.0.1:9200/test/_count?pretty -d '{"query":{"match_all":{}}}'
# 搜索数据
curl -H "Content-Type: application/json" -XGET http://127.0.0.1:9200/test/_count?pretty -d '{"query":{"match_all":{}}}'
# 且
curl -H "Content-Type: application/json" -XGET http://127.0.0.1:9200/test/people/_count?pretty -d '{"query":{"bool":{"must":[{"term":{"tag_id":"mkit"}},{"term":{"tag_id":"iutj"}}]}}}'
# 非
curl -H "Content-Type: application/json" -XGET http://127.0.0.1:9200/test/people/_count?pretty -d '{"query":{"bool":{"must_not":[{"term":{"tag_id":"mkit"}},{"term":{"tag_id":"iutj"}}]}}}'
# 或
curl -H "Content-Type: application/json" -XGET http://127.0.0.1:9200/test/people/_count?pretty -d '{"query":{"bool":{"should":[{"term":{"tag_id":"mkit"}},{"term":{"tag_id":"iutj"}}]}}}'

curl -H "Content-Type: application/json" -XGET http://127.0.0.1:9201/user_info/_doc/_count?pretty -d '{"query":{"bool":{"should":[{"term":{"user_id":"295159"}},{"term":{"user_id":"295158"}}]}}}'
# 查看索引
curl http://127.0.0.1:9201/_cat/indices
# 创建索引_mapping
curl -X PUT http://127.0.0.1:9201/user_info  -H "Content-Type: application/json" -d '{"mappings":{"_doc":{"dynamic":"strict","properties":{"openid":{"type":"keyword"},"phone":{"type":"keyword"},"source":{"type":"long"},"unionid":{"type":"keyword"},"user_id":{"type":"keyword"},"wx_tag_id":{"type":"keyword"}}}}}'
# 查看索引mapping
curl http://127.0.0.1:9201/user_info/_mapping
# 查看健康情况
curl http://127.0.0.1:9200/_cat/health
# 查看节点信息，负载，内存，cpu
curl http://127.0.0.1:9201/_cat/nodes?v
curl "http://127.0.0.1:9201/_cat/nodes?v&h=id,disk.total,disk.used,ram.max,ram.current,ram.percent,load_15m,segments.memory,heap.current"
# 查看节点健康
curl http://127.0.0.1:9200/_cluster/health
# 磁盘情况 索引 磁盘
curl http://127.0.0.1:9200/_cat/shards
curl http://127.0.0.1:9201/_cat/shards/user_info?v
curl  http://127.0.0.1:9201/_cat/allocation?v
# 删除索引
curl -X DELETE http://127.0.0.1:9201/user_info-d
# 索引状态
curl  http://127.0.0.1:9201/user_info/_stats?pretty
# 段信息 segments
curl  http://127.0.0.1:9201/_cat/segments?v
curl http://127.0.0.1:9201/_cat/segments/user_info?v
# 索引信息
curl  "http://127.0.0.1:9201/_cat/indices"
# 索引信息
curl http://127.0.0.1:9201/_cat/indices/user_info?v
```

## 优化

> 没有索引，可以设置分片和副本。如果已经有索引，只能操作副本。

> 一个segment是一个完备的lucene倒排索引，而倒排索引是通过词典(Term Dictionary)到文档列表(Postings List)的映射关系，快速做查询的。所以每个segment都有会一些索引数据驻留在heap里。
> 因此segment越多，瓜分掉的heap也越多，并且这部分heap是无法被GC掉的 https://blog.csdn.net/javastart/article/details/52528415 

- 合并段segments 

> 强制段合并，设置为1时，是期望最终只有1个索引段。但实际情况是，合并的结果是段的总数会减少，但仍大于1，可以多次执行强制合并的命令。 设置的的目标值越小。合并消耗的时间会越久.
> 段合并会消耗较多的磁盘IO资源，不要在大量建立索引时，查询较多时，执行该操作

```sh
curl -X POST http://172.30.0.97:9200/test3/_forcemerge?max_num_segments=1
```

- 删除已经删除的文档

> es 默认删除文档，不会马上在硬盘上除去，而是在es索引中产生一个.del的文件，而在检索过程中这部分数据也会参与检索，es在检索过程会判断是否删除了，如果删除了在过滤掉。这样也会降低检索效率

```sh
curl -X POST http://172.30.0.97:9200/test3/_forcemerge?only_expunge_deletes=true 
```

## 批量操作

> 建议1000-5000doc。总大小5M-15M。

