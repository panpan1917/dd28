<?php

class AgentPayDecryption
{
  private $key = 'passfordesnishiwode&0082';

  public function __toString()
  {
    return $this->key;
  }

  public function decrypt($encrypted)
  {
    $str = $this->hexToStr($encrypted);
    $str = mcrypt_decrypt(MCRYPT_DES, substr($this->key, 0, 8), $str, MCRYPT_MODE_ECB);
    $str = $this->pkcs5Unpad($str);
    $str = urldecode($str);
    return $str;
  }

  function hexToStr($hex)
  {
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
  }

  function pkcs5Unpad($text)
  {
    $pad = ord($text {strlen($text) - 1});
    if ($pad > strlen($text))
        return false;

    if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
        return false;

    return substr($text, 0, - 1 * $pad);
  }

}
