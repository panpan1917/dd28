<?php
class RsaUtils{
  //加密公钥
  public static function redPukey($encryptionKeyPath)
  {    
    //拼接加密公钥路径
    //$encryptionKeyPath="D:/encryptions.cer";   
    $encryptionKey4Server = file_get_contents($encryptionKeyPath);
 
    $pem = chunk_split(base64_encode($encryptionKey4Server),64,"\n");//转换为pem格式的公钥
    $pem = "-----BEGIN CERTIFICATE-----\n".$pem."-----END CERTIFICATE-----\n";
    $publicKey = openssl_pkey_get_public($pem);
    return $publicKey;
  }
   
  //解密私钥
  public static function redPikey($decryptKeyPath)
  {    
    //拼接解密私钥路径
    //$decryptKeyPath="D:/decrypts.key";   
    $decryptKey4Server = file_get_contents($decryptKeyPath);
 
    $pem = chunk_split($decryptKey4Server,64,"\n");//转换为pem格式的私钥
    $pem = "-----BEGIN PRIVATE KEY-----\n".$pem."-----END PRIVATE KEY-----\n";
    $privateKey = openssl_pkey_get_private($pem);
    return $privateKey;
  }
   
  //签名私钥
  public static function redSignkey($signKeyPath)
  {    
    //拼接签名路径
    //$signKeyPath="D:/DEMO/sign.key";
    $signKey4Server = file_get_contents($signKeyPath);
 
    $pem = chunk_split($signKey4Server,64,"\n");//转换为pem格式的私钥
    $pem = "-----BEGIN PRIVATE KEY-----\n".$pem."-----END PRIVATE KEY-----\n";
    $signKey = openssl_pkey_get_private($pem);
    return $signKey;
  }
   
  //验签公钥
  public static function redVerifykey($verifyKeyPath)
  {    
    //拼接验签路径
    //$verifyKeyPath="D:/DEMO/verify.cer";  
    $verifyKey4Server = file_get_contents($verifyKeyPath);
 
    $pem = chunk_split(base64_encode($verifyKey4Server),64,"\n");//转换为pem格式的公钥
    $pem = "-----BEGIN CERTIFICATE-----\n".$pem."-----END CERTIFICATE-----\n";
    $verifyKey = openssl_pkey_get_public($pem);
    return $verifyKey;
  }
   
  //公钥加密
  public static function pubkeyEncrypt($source_data, $pu_key) {
    $data = "";
    $dataArray = str_split($source_data, 117);
    foreach ($dataArray as $value) {
      $encryptedTemp = ""; 
      openssl_public_encrypt($value,$encryptedTemp,$pu_key);//公钥加密
      $data .= base64_encode($encryptedTemp);
    }
    return $data;
  }
   
  //私钥解密
  public static function pikeyDecrypt($eccryptData,$decryptKey) {
    $decrypted = "";
    $decodeStr = base64_decode($eccryptData);
    $enArray = str_split($decodeStr, 256);
 
    foreach ($enArray as $va) {
      openssl_private_decrypt($va,$decryptedTemp,$decryptKey);//私钥解密
      $decrypted .= $decryptedTemp;
    }
    return $decrypted;
  }
   
  
}
?>
