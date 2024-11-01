<?php

class SmsiSecurity{

	public function __construct()
	{
		defined( 'ABSPATH' ) or die( 'Oops! dont look like your are allowed to be here silly!' );

	}

	/**
	*Makes our XML Document
	*$xmlInfo : array : Our info
	*@return void
	*/
  protected static function writeXmlDocument( $xmlInfo )
  {
    $file = fopen(SMSI__PLUGIN_DIR."/etc/magic.xml","w");
		$xmlFile = '<?xml version="1.0" encoding="UTF-8"?><xml><key>'.$xmlInfo['key'].'</key></xml>';
		fwrite( $file, $xmlFile );
  }

  /*
  *Reads our key Document
  *@return Object
  */
  protected static function readXmlDocument()
  {
    $fileText = fread(fopen(SMSI__PLUGIN_DIR."/etc/magic.xml", "r"),filesize( SMSI__PLUGIN_DIR."/etc/magic.xml" ));
		return simplexml_load_string( $fileText );
  }

  /*
  *Encrypts a given string
  *@param $text : String : The text we want to encrypt
  *@return void
  */
	protected static function encrypt( $text )
	{
		$key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
    $keySize =  strlen($key);
    $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
                                 $text, MCRYPT_MODE_CBC, $iv);
    $ciphertext = $iv . $ciphertext;
    $ciphertextBase64 = base64_encode($ciphertext);

		$returnValuesArr = array(
			'key' => base64_encode( $key ),
		);

    self::writeXmlDocument( $returnValuesArr );

    return $ciphertextBase64;
	}

  /*
  *Decyrpts a given text
  *@param $encText : String : Our encrypted text we want to decrypt.
  *@param $key : String : Our encryption cipher key in binary format.
  *@retrun String
  */
	protected static function decrypt( $encText, $key )
	{

		$ciphertextDec = base64_decode( $encText );
		$ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $ivDec = substr($ciphertextDec, 0, $ivSize);
    $ciphertextDec = substr($ciphertextDec, $ivSize);
    $plaintextDec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,
                                    $ciphertextDec, MCRYPT_MODE_CBC, $ivDec);

		return $plaintextDec;
	}
}
