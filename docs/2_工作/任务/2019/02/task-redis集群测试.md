# redis集群测试
 

- redis集群配置文件
- 批处理脚本跑数据
- 数据抽样校验
- 开关


##活动api

- 开发分支  redis_cluster 

http://devapi-activity.juewei.com:31011/bi/test/user
代码
var_dump($this->C_coupon->get_user_info(294839));exit;

##会员api

- 开发分支  redis_cluster 

http://devapi-user.juewei.com:31011/bi/test/user
代码
var_dump($this->C_user->get_user_info_by_uid(294839));exit;

## bi

- 开发分支  redis_cluster 

注意：如果redis连不上，会报错，程序不会继续执行。替换的集群同样的问题。
修改start.php ALI_REDIS_FOR_USER 值 1

手动测试流程
api/weixin/actions.php
executeGetInfoByCode方法添加代码
$userInfo = Auth::loginByOpenid('ommkBxKD8llVTFpA0r69nsjfKAtE');
            var_dump($userInfo);exit;


删除方法测试
        $userId = $auth->userModel->delUserIdForRedisByPhone($userInfo['phone']);
        $userId = $auth->userModel->delUserInfoForRedisByUserid($userInfo['user_id']);
        $userId = $auth->userModel->delUserIdForRedisByOpenid($userInfo['openid']);
更新方法测试updataUserInfoForRedis
	1改代码，进入auth::flushUserInfo $auth->userModel->updateUser 逻辑
	2注释user::updateUser stats判断
测试getUserIdForRedisByPhone
	直接调用getUserIdForRedisByPhone


## 阿里云返回值

ALERT: Redis getUserIdForRedisByOpenid:"295060"
ALERT: Redis getUserInfoForRedisByUserid:{"user_id":"295060","tenant_id":"1","nick_name":"Evildoer\u3002","phone":"18701350807","head_img":"http:\/\/thirdwx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTJG8vu0F1Ukiby6GGoIYIQyulHXUeZ8WfOnrED7nKEw80NSZzrmDFzZ2SFyQibibuy6kfZEOO2FONW2g\/132","openid":"ommkBxKD8llVTFpA0r69nsjfKAtE","sex":"2","birthday":"0000-00-00","identitycard":"","province_id":"0","city_id":"0","region_id":"0","wechat":"Evildoer\u3002","rand":"9889","password":"1b9f954cb7fef3a8be67a1260874adfb","is_vip":"0","real_name":"","jobs":"","eat_scene":"","wxminiopenid":"","unionid":"","status":"1"}
ALERT: Redis setUserInfoForRedis:true
ALERT: Redis setUserIdForRedisByPhone:true
ALERT: Redis setUserIdForRedisByOpenid:true
ALERT: Redis getUserIdForRedisByOpenid:"295060"
ALERT: Redis getUserInfoForRedisByUserid:{"user_id":"295060","tenant_id":"1","nick_name":"Evildoer\u3002","phone":"18701350807","head_img":"http:\/\/thirdwx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTJG8vu0F1Ukiby6GGoIYIQyulHXUeZ8WfOnrED7nKEw80NSZzrmDFzZ2SFyQibibuy6kfZEOO2FONW2g\/132","openid":"ommkBxKD8llVTFpA0r69nsjfKAtE","sex":"2","birthday":"0000-00-00","identitycard":"","province_id":"0","city_id":"0","region_id":"0","wechat":"Evildoer\u3002","rand":"9889","password":"1b9f954cb7fef3a8be67a1260874adfb","is_vip":"0","real_name":"","jobs":"","eat_scene":"","wxminiopenid":"","unionid":"","status":"1"}
ALERT: Redis updataUserInfoForRedis:{"user_id":"295060","tenant_id":"1","nick_name":"Evildoer\u3002","phone":"18701350807","head_img":"http:\/\/thirdwx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTJG8vu0F1Ukiby6GGoIYIQyulHXUeZ8WfOnrED7nKEw80NSZzrmDFzZ2SFyQibibuy6kfZEOO2FONW2g\/132","openid":"ommkBxKD8llVTFpA0r69nsjfKAtE","sex":"2","birthday":"0000-00-00","identitycard":"","province_id":"0","city_id":"0","region_id":"0","wechat":"Evildoer\u3002","rand":"9889","password":"1b9f954cb7fef3a8be67a1260874adfb","is_vip":"0","real_name":"","jobs":"","eat_scene":"","wxminiopenid":"","unionid":"","status":"1"}
ALERT: Redis updataUserInfoForRedis:true
ALERT: Redis setUserInfoForRedis:true
ALERT: Redis setUserIdForRedisByPhone:true
ALERT: Redis setUserIdForRedisByOpenid:true
ALERT: Redis getUserIdForRedisByPhone:"295060"
ALERT: Redis delUserIdForRedisByPhone:1
ALERT: Redis delUserInfoForRedisByUserid:1
ALERT: Redis delUserIdForRedisByOpenid:[1]


## redis集群返回值

ALERT: RedisCluster getUserIdForRedisByOpenid:"295060"
ALERT: RedisCluster getUserInfoForRedisByUserid:{"user_id":"295060","nick_name":"Evildoer\u3002","phone":"18701350807","head_img":"http:\/\/thirdwx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTJG8vu0F1Ukiby6GGoIYIQyulHXUeZ8WfOnrED7nKEw80NSZzrmDFzZ2SFyQibibuy6kfZEOO2FONW2g\/132","openid":"ommkBxKD8llVTFpA0r69nsjfKAtE","sex":"2","birthday":"0000-00-00","province_id":"0","city_id":"0","region_id":"0","rand":"9889","password":"1b9f954cb7fef3a8be67a1260874adfb","is_vip":"0","real_name":"","jobs":"","eat_scene":"","status":"1"}
ALERT: RedisCluster setUserInfoForRedis:true
ALERT: RedisCluster setUserInfoForRedis:"295060"
ALERT: RedisCluster setUserInfoForRedis:{"user_id":"295060","nick_name":"Evildoer\u3002","phone":"18701350807","head_img":"http:\/\/thirdwx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTJG8vu0F1Ukiby6GGoIYIQyulHXUeZ8WfOnrED7nKEw80NSZzrmDFzZ2SFyQibibuy6kfZEOO2FONW2g\/132","openid":"ommkBxKD8llVTFpA0r69nsjfKAtE","sex":"2","birthday":"0000-00-00","province_id":"0","city_id":"0","region_id":"0","rand":"9889","password":"1b9f954cb7fef3a8be67a1260874adfb","is_vip":"0","real_name":"","jobs":"","eat_scene":"","status":"1"}
ALERT: RedisCluster setUserIdForRedisByPhone:true
ALERT: RedisCluster setUserIdForRedisByOpenid:true
ALERT: RedisCluster getUserIdForRedisByPhone:"295060"
ALERT: RedisCluster updataUserInfoForRedis:{"user_id":"295060","nick_name":"Evildoer\u3002","phone":"18701350807","head_img":"http:\/\/thirdwx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTJG8vu0F1Ukiby6GGoIYIQyulHXUeZ8WfOnrED7nKEw80NSZzrmDFzZ2SFyQibibuy6kfZEOO2FONW2g\/132","openid":"ommkBxKD8llVTFpA0r69nsjfKAtE","sex":"2","birthday":"0000-00-00","province_id":"0","city_id":"0","region_id":"0","rand":"9889","password":"1b9f954cb7fef3a8be67a1260874adfb","is_vip":"0","real_name":"","jobs":"","eat_scene":"","status":"1"}
ALERT: RedisCluster updataUserInfoForRedis:true
ALERT: RedisCluster delUserIdForRedisByPhone:1
2019-02-21 17:36:46  ALERT: RedisCluster Start
ALERT: RedisCluster delUserInfoForRedisByUserid:1
2019-02-21 17:36:46  ALERT: RedisCluster Start
ALERT: RedisCluster delUserIdForRedisByOpenid:[1]