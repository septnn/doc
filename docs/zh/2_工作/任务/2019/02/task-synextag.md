# 同步标签
```php
public function executeTest(Application $application, Request $request)
    {
        $token = WeiXinIndex::getAccessToken();
        $url1="https://api.weixin.qq.com/cgi-bin/tags/get?access_token=".$token;

        $url = $url1;
        $data =$data1;

        $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
                if($data) {
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $o= curl_exec($curl);
                curl_close($curl);
        $arr = json_decode($o,1);

        $dbRead = "mysql:host=60.205.115.77;port=3306;dbname=user"; // 原始数据
        $dbRead = new PDO($dbRead, 'dbwriter', 'J6%9aKaP');
        $dbRead->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        $dbRead->query('SET NAMES utf8');


$area = array (
        '华东区' => array (
                '上海分公司',
                '江苏分公司',
                '山东分公司',
                '浙江分公司',
                '福建分公司',
                '安徽分公司',
        ), 
        '华中区' => array (
                '湖南分公司',
                '河南分公司',
                '江西分公司',
                '陕西分公司',
                '湖北分公司',
                '山西分公司',
                '河北分公司',
        ), 
        '华南区' => array (
                '广东分公司',
                '广西分公司',
                '粤西分公司',
        ), 
        '西南区' => array (
                '四川分公司',
                '重庆分公司',
                '贵州分公司',
                '云南分公司',
        ), 
        '北方区' => array (
                '北京分公司',
                '天津分公司',
                '辽宁分公司',
                '甘肃分公司',
                '内蒙分公司',
                '黑龙江分公司',
                '吉林分公司',
                '新疆分公司',
        ) 
);
		$a = [];
		foreach($area as $k => $v) {
			foreach ($v as $kk => $vv) {
				$a[$vv] = $k;
			}
		}

        foreach($arr['tags'] as $k => $v) {
                $sql = "insert into wx_tag set  wx_tag_id = '{$v['id']}', wx_tag_name = '{$v['name']}';";
                echo $sql;
                var_dump($dbRead->exec($sql));;
        }
}


```