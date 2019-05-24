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