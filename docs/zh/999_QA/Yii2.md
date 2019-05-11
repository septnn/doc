# 数据库表结构的schema缓存

```php
'enableSchemaCache' => true,  
'schemaCacheDuration' => 86400, // time in seconds
# 清除缓存
Yii::$app->db->schema->refresh(); 
Yii::$app->db->schema->refreshTableSchema($tableName); 
# 清除所有
Yii::$app->cache->flush();  
# 命令行清除
./yii cache/flush-all  
```

#