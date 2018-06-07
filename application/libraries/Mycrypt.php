<?php

class Mycrypt
{

    public $public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC0dYM3DXkVg9q+WcNjBPWaUwKo
eRMrwdE4p4F6fiztv/Ys6F5AxGCbFW5UfbtbQavMp9Rrg3+8mJ5/Lp8sjf471NFe
6EvbCcVwJ63Q6fA4xVyCAE7mQdfAlpCk9WKN7Qa/HqwO/OM6JDyOyycnjnNi3f3K
2tK/JbWd/SHYOSMEDQIDAQAB
-----END PUBLIC KEY-----';
    
   
    function decrypt($data)
    {
        // 这个函数可用来判断公钥是否是可用的
        $pu_key = openssl_pkey_get_public($this->public_key);
        $b64 = base64_decode($data);
//         echo $b64 . '<br>';
//         echo '<br>len:' . strlen($b64) . '<br>';
        $rs = '';
        for($i = 0;$i*128<strlen($b64);$i++){
            
            openssl_public_decrypt(substr($b64, $i*128,128), $decrypted, $pu_key); // 私钥加密的内容通过公钥可用解密出来
            $rs .= $decrypted;
        }
//         echo $rs, "<br>";
        return $rs;
    }

    function encrypt($data)
    {
        // 这个函数可用来判断公钥是否是可用的
        $pu_key = openssl_pkey_get_public($this->public_key);
        $rs = '';
        for($i = 0;$i*117<=strlen($data);$i++){
           openssl_public_encrypt(substr($data, $i*117,117), $encrypted, $pu_key); // 公钥加密
            $rs .= $encrypted;
        }
        $rs = base64_encode($rs);
        return $rs;
    }
}
 $rsa = new mycrypt();
 echo $rsa->decrypt("ib3kfEdIG/w1pGz4LlskMWRidYW38g8JK6xy6fczdHHViq4P8eLCTg0PYmyUvFdh3x59KzQ4R5b1HMG2TjlLBC7Ux65YfHqdRPxBAR2vgx3xh9FNq/X2f9C4wKz00Ht0O4lqEtZS/I4bf0udXFgzrzQHxMs85Gh2ih7HTWBJ5uk=");

