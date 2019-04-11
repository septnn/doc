# 测试号测试群发调研

## json拼接
```json
{"touser":["ommkBxCy4xYp_WqgBo5s-SZIUJ-g"],"mpnews":{     "media_id":"HvdzLEmCGgBG-uV0XclOE-y3VMnBY1MB5RbkHH16Nj8"},"msgtype":"mpnews","send_ignore_reprint":0}


{"type":"news","offset":0,"count":20}

{"user_list":[{"openid":"ommkBxEAkKTYOrr7L8ciD2shngYI","lang":"zh_CN"},{"openid":"ommkBxKTnPvHPNxYbVplHJE2kt3E","lang":"zh_CN"},{"openid":"ommkBxDbjK6Q2C8lATegqmf74w5g","lang":"zh_CN"},{"openid":"ommkBxMrmpW0v6ApPqIV_sEqHu08","lang":"zh_CN"},{"openid":"ommkBxG3FovRkTuZntFkU829TQlo","lang":"zh_CN"},{"openid":"ommkBxGyuIIOpx8FAam770Hag-bc","lang":"zh_CN"},{"openid":"ommkBxBpqQG8HcmExg4rKU7AI-_o","lang":"zh_CN"},{"openid":"ommkBxEaXW8R_c3RQenpMphKmDk0","lang":"zh_CN"},{"openid":"ommkBxPyuuJg1ZC2jwAUBQjAvc9k","lang":"zh_CN"},{"openid":"ommkBxIla-qodp5M6ChHBwCQfNyU","lang":"zh_CN"}]}
```

## 群发测试代码

```php
public function executeTest(Application $application, Request $request)
    {
 $token = WeiXinIndex::getAccessToken();
$url1="https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$token;
$data1='{"type":"news","offset":0,"count":20}';
        $url2 = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$token;
$data2='{"touser":["ommkBxCy4xYp_WqgBo5s-SZIUJ-g","ommkBxIlhSmkDpbTxh2jlm7CdPms"],"mpnews":{     "media_id":"HvdzLEmCGgBG-uV0XclOE-y3VMnBY1MB5RbkHH16Nj8"},"msgtype":"mpnews","send_ignore_repr
int":0}';
$url3="https://api.weixin.qq.com/cgi-bin/user/get?access_token={$token}&next_openid=";
$url4="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid=OPENID&lang=zh_CN";
$url5="https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token={$token}";
$data5='{"user_list":[{"openid":"ommkBxN8fPlWMRbsVdha75Z4RmrA","lang":"zh_CN"},{"openid":"ommkBxI7KJ4Ji2fY-m06TBgBQXF0","lang":"zh_CN"},{"openid":"ommkBxNR0yxeDaWpXMJvRV18z_pY","lang":"zh_CN"
},{"openid":"ommkBxHxAXbBhLlSvOQ3weXblcjU","lang":"zh_CN"},{"openid":"ommkBxERCmRf8CsdVFGdy49hvou0","lang":"zh_CN"},{"openid":"ommkBxEnJKUimyrWmncj3lQGgYfI","lang":"zh_CN"},{"openid":"ommkBxA
eA-6ih0LZD9vVgWRESgAk","lang":"zh_CN"},{"openid":"ommkBxPJ85tl0j69dzXaniIj5oB8","lang":"zh_CN"},{"openid":"ommkBxL80THRVJ-QtT0DjJcpr5iY","lang":"zh_CN"},{"openid":"ommkBxIlhSmkDpbTxh2jlm7CdPm
s","lang":"zh_CN"}]}';

$url = $url2;
$data =$data2;

$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if($data) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
echo ($output);exit;
}
```
## 测试公众号的粉丝
```json
{"total":122,"count":122,"data":{"openid":["ommkBxNCMz708qI7Ao8YRJY28ER0","ommkBxIW_ywxpvZDj6apYyaX4RAY","ommkBxKm8L7_zByRa6Pvq-z2p9E0","ommkBxF2xH-HTjzlwuHoZ2_ECZbk","ommkBxOU7v1Dlnp7a06uKqewEboI","ommkBxJh_B7qcSHzVtNZ29tjjhl8","ommkBxKXEqeihY37vT0apEOPeSE0","ommkBxCm1GfTIaTGoZ_OGGzE-fX8","ommkBxFmSvrCEPdITb5YZcMyQjjY","ommkBxAw4lB4_3Odnl8Mh6Ifmm1I","ommkBxCsrTo9L1YmZBnOdDE39dLc","ommkBxLas0YcRUYaV1ROVlXlm7rY","ommkBxHTUfpzm9gTZviHlHYTRB6k","ommkBxG-EfXabK8aeAddP1NkfKFw","ommkBxEh39BxWlj-A2BRTHwez9Cw","ommkBxA5ZY3OvNwAnIBnMDDPcSzw","ommkBxNmgSWLF7RGpdqM-i5mXuTA","ommkBxHpxXtSbyAEtyxkTwWe5p38","ommkBxGCRImpApBwXarHVW6EhEVQ","ommkBxBDryOgmwqh2Frs7REqBLbs","ommkBxInAiUK06TpZm76-DoOfAFc","ommkBxLZlZyX39gCNaJWeN1mw7Xk","ommkBxGd3VP_o_GhJC2oll91Gv2E","ommkBxH4seBiZHfO2w69q6X4GzW8","ommkBxDY-XeRCany7r6VFa5ZnePU","ommkBxE4EB1FnyrqWEIVNsZ_ihzg","ommkBxJ98licg5_Re3whuo1WcYB0","ommkBxG-4QWJxa506io9VzlFf9Q8","ommkBxEhz_bcLl2rNNp1XHg6rfYc","ommkBxHG3C7z7IlBZ37e6xUstxSQ","ommkBxBwrkuPOuaRtx6K9B6fpRdM","ommkBxOJJ4pqoI9RhIH9SRtnRK_s","ommkBxDzwRrBjC1NHiwnYS7bW48M","ommkBxKoVfc4_gbrCu-YdfxA-wow","ommkBxDo7jaKU89DICnwpsEI3wzk","ommkBxKFAw9JkeeFjJs6z7FaHByM","ommkBxHb8HUl507aAJO8GDzwfXG0","ommkBxNHYY0m60kBxEzUWtj7C784","ommkBxOQm8_NH0sxtdSN4rKUW3xk","ommkBxPX_JDCP3TWUYcxjvTS-UNU","ommkBxN0YFzayyMIHfSerCFH0iN0","ommkBxITnaMj84-npn7NF0bZ6QcM","ommkBxOfLA0M4CI4ikG40CM7zpLs","ommkBxCFJm6O_jaUV5dtkbZj3N30","ommkBxLyq_DLhHt5kK1sycNpNAro","ommkBxEBfkGUHV-mgRe-jwYwEknE","ommkBxKD8llVTFpA0r69nsjfKAtE","ommkBxKpjF941ws4CsITnAmRsyAQ","ommkBxDQ5aUNbqRRNxPO8zqZ7Cdw","ommkBxDyWSxw7dP_9jVuei2IfcAA","ommkBxKs4yQVVgbnxUI9gH0He4Bg","ommkBxIQ6NgjmiseycArcNxyNdbI","ommkBxIxb-9egMXKo4Zry97MPYGw","ommkBxEz1iAWm2KAVuZKdxtAM-as","ommkBxFGS8-G0CSyVI-P555yTHvw","ommkBxPEI4Wo07Z8ZmW9DEdOb-ys","ommkBxIQtlHijwdPUhbx9v2CrbEU","ommkBxHRgKIf_QhoiHXhJ3fTa-Ns","ommkBxEtMObdN7HKfG8k9lsY1xx0","ommkBxH79hG11aNZh68_6rNmqzLM","ommkBxFKnPFTAsstplx9uPPD5nbA","ommkBxEaB3z5tkQTrxFixx_cCyq0","ommkBxBke9HOjcFoOU9MzuPkoU14","ommkBxG-SlNUKME7W2gyB5kIHVzA","ommkBxGvwW4sbXml1GB8_3ZWzQbE","ommkBxKQ9fbpTXqPUfwdW2HED274","ommkBxPYilDKtUpyTb-P_0qtadk8","ommkBxJpp0iMaL4Z3HOO9C0Ox2qU","ommkBxI9MmeMr2HT6nLTwSlprIrs","ommkBxMbYSiMVKBXDIv9mk5OmQW8","ommkBxOos3TJeVoqC7CB3w9_6K7M","ommkBxLdnxGMCKPe7nHaXC8cNS7Q","ommkBxFc3nI6rmrXzZeaS3pnUUCM","ommkBxPpG_gaYMxdtDI1ajZrPxC0","ommkBxIypsODYknxHBOMgrshNLPI","ommkBxJ1YCecMMVGVVv6KEl1sDwg","ommkBxMGzSS1jIFfnbX0AspQ1PyU","ommkBxFCAziIbit3W4soBK6nYJoM","ommkBxIEFsDoz4DYKIurHob2qW0g","ommkBxAPyOt-YLgbzJ9E9eGF0v_8","ommkBxCgCtuloVxCf3IeVWTf8Hwg","ommkBxLxgDnMY7PPIyoncE-mpVRM","ommkBxInYq84K8Dkl9QH9vVrLNbM","ommkBxCQcLW1LVyJ1844bKiQPGnM","ommkBxM_XXjzsRWfo3LlU1FaL1TQ","ommkBxNlFW_YaVMo0ojJ-HdQ77jE","ommkBxOABYJarUlRJjc1FUfV3vaI","ommkBxBLKTSuVbJ9J2AQhDndYBkU","ommkBxOx-HBLkYcN3YmNVilEqvmA","ommkBxE45Nqw2QxT3CEbePwY2_nU","ommkBxP_DYVMEUy-d36N_8oAVsng","ommkBxHVqycoafnTGlbZ7P8eybO4","ommkBxAs9zKVqYwSiPn9bTY95u8E","ommkBxDwxcKwXVsWQuZ-1ruCetfg","ommkBxOlquWC52gMOut3hPMuvFLw","ommkBxJwpwlc0bp7gCLaJT0pqfPk","ommkBxKl6iYGAZQMV260RUTtNNtY","ommkBxFFenaMOTSSKr3ezUEeynN8","ommkBxIla-qodp5M6ChHBwCQfNyU","ommkBxCy4xYp_WqgBo5s-SZIUJ-g","ommkBxNMxjJgWkx9TqtRGrUx5i6k","ommkBxCYr7jXIGKdj8qald3kRnik","ommkBxIm5VNlaQY7_lsfeGl27_xU","ommkBxN8fPlWMRbsVdha75Z4RmrA","ommkBxI7KJ4Ji2fY-m06TBgBQXF0","ommkBxNR0yxeDaWpXMJvRV18z_pY","ommkBxHxAXbBhLlSvOQ3weXblcjU","ommkBxERCmRf8CsdVFGdy49hvou0","ommkBxEnJKUimyrWmncj3lQGgYfI","ommkBxAeA-6ih0LZD9vVgWRESgAk","ommkBxPJ85tl0j69dzXaniIj5oB8","ommkBxL80THRVJ-QtT0DjJcpr5iY","ommkBxIlhSmkDpbTxh2jlm7CdPms","ommkBxEAkKTYOrr7L8ciD2shngYI","ommkBxKTnPvHPNxYbVplHJE2kt3E","ommkBxDbjK6Q2C8lATegqmf74w5g","ommkBxMrmpW0v6ApPqIV_sEqHu08","ommkBxG3FovRkTuZntFkU829TQlo","ommkBxGyuIIOpx8FAam770Hag-bc","ommkBxBpqQG8HcmExg4rKU7AI-_o","ommkBxEaXW8R_c3RQenpMphKmDk0","ommkBxPyuuJg1ZC2jwAUBQjAvc9k"]},"next_openid":"ommkBxPyuuJg1ZC2jwAUBQjAvc9k"}
```