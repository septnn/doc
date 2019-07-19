#soap 调用源码

```php
<?php

return [
    'params' => [
        'sap' => [
            'url' =>'http://xxxxxx/sap/bc/srt/wsdl/bndg_5CE7FE53496314FFE1000000C0A80A07/wsdl11/allinone/standard/document?sap-client=800',
            'login' => 'xxx',
            'password' => 'xxx',
            'location' => 'http://xxxxxx/sap/bc/srt/rfc/sap/zws_bs01/800/zws_bs01/zws_bs01',
            'uri' => 'http://xxxxxx/sap/bc/srt/wsdl/bndg_5CE7FE53496314FFE1000000C0A80A07/wsdl11/allinone/standard/document',
            'connection_timeout' => 60, // 
            'cache_wsdl' => WSDL_CACHE_NONE,
            'keep_alive' => false,
            'trace' => true,
            'encoding' => 'UTF-8',
            'exceptions' => true,
            'soap_version' => SOAP_1_1,
        ],
    ],
];

```

```php
<?php

namespace app\services\sap;

class SapService
{
    public static $soap;

    public static function soapHandle($config = [])
    {
        if(!is_null(self::$soap));
        if(empty($config)) {
            $config = \Yii::$app->params['sap']??[];
        }
        if(empty($config)) {
            throw new Exception(__CLASS__." Config is empty", 50000);
        }
        try {
            ini_set('default_socket_timeout', $config['connection_timeout']);
            ini_set('max_input_time', $config['connection_timeout']);
            $params = [
                'login'             => $config['login'],
                'password'          => $config['password'],
                // 将WSDL URL粘贴到Web浏览器中，打开地址搜索location
                'location'          => $config['location'],
                // 将将WSDL URL 去掉参数
                'uri'               => $config['uri'],
                'connection_timeout'       => $config['connection_timeout'],
                'cache_wsdl'               => $config['cache_wsdl'],
                'keep_alive'               => $config['keep_alive'],
                'trace' => $config['trace'],
                'encoding' => $config['encoding'],
                'soap_version' => $config['soap_version'],
                'exceptions' => $config['exceptions'],
                'stream_context' => stream_context_create([
                    'http' => [
                        'timeout' => $config['connection_timeout'],
                    ]
                ]),
                // 'proxy_host' => '127.0.0.1',
                // 'proxy_port' => 8888,
            ];
            dump($params);
            self::$soap = new \SoapClient($config['url'], $params);
            if(YII_DEBUG) {
                dump(self::$soap->__getFunctions());
                dump(self::$soap->__getTypes());
            }
            return self::$soap;
        } catch (\Throwable $th) {
            dd($th);
            throw new Exception(__CLASS__." SoapClient Error:".$th->getMessage(), $th->getCode());
        }
    }

    public function call($action, $params = [])
    {
        
        $res = [];
        try {
            $soap = self::soapHandle();
            $res = $soap->__soapCall($action,$params);
        } catch (\Throwable $th) {
            dump($th);
        }
        dump($soap->__getLastRequest());
        dump($soap->__getLastRequestHeaders());
        dump($soap->__getLastResponse());
        dump($soap->__getLastResponseHeaders());
        return $res;
    }
    /**
     * 报货
     *
     * @param [type] $shopId
     * @param [type] $date  2019-06-20
     * @return void
     */
    public static function ZeSdBs01Bh($shopId = '0000112295', $date = '2019-06-20')
    {
        $res = self::call('ZeSdBs01Bh', [
            [
                'IvDate' => $date,
                'IvKunnr' => self::zeroCompensation($shopId),
                'OtItems' => ''
            ]
        ]);
        if(!isset($res->OtItems->item)) {
            return [];
        }
        return json_decode(json_encode($res->OtItems->item),1);
    }
    /**
     * 出货
     *
     * @param [type] $shopId
     * @param [type] $date  2019-06-20
     * @return void
     */
    public static function ZeSdBs01Ch($shopId = '0000112295', $date = '2019-06-20')
    {
        $res = '';
        $res = self::call('ZeSdBs01Ch',[
            [
                'IvDate' => $date,
                'IvKunnr' => self::zeroCompensation($shopId),
                'OtItems' => ''
            ]
        ]);
        if(!isset($res->OtItems->item)) {
            return [];
        }
        return json_decode(json_encode($res->OtItems->item),1);
    }

    public static function zeroCompensation($shopId)
    {
        // 10位
        return str_pad($shopId, 10, 0, STR_PAD_LEFT);
    }
}
```


```php
// 报货
        $res = SapService::ZeSdBs01Bh('112295','2019-06-20');
        // 出货
        // $res = SapService::ZeSdBs01Ch('112295','2019-06-20');
        dump($res);
```