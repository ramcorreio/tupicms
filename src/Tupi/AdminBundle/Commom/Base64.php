<?php
namespace Tupi\AdminBundle\Commom;

use Symfony\Component\HttpFoundation\File\File;

class Base64 
{
	private function __construct()
	{
		//classe criada apenas para uso de utilitário 
	}
	
	public static function encodePath($filename)
	{
		$stream = fopen($filename, "rb");
		$contents = self::encode($stream);
		fclose($stream);
		
		return $contents;
	}
	
	
	public static function encode($handle)
	{
		$contents = stream_get_contents($handle);
		return base64_encode($contents);
	}
	
	public static function encodeFile(File $file)
	{
		return self::encodePath($file->getRealPath());
	}
}