<?php

namespace PandaSMS\Admin;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Option
{


	private static $option_adi = 'pandasms_ayarlar';



	public static function get($key, $default = false ){

		$values = get_option(self::$option_adi);

		if(is_array($values) && array_key_exists($key, $values))
			return $values[$key];

		return $default;

	}


	public static function set($key, $value){


		$option_array = get_option(self::$option_adi, array());

		$option_array[ $key ] = $value;

		return update_option( self::$option_adi, $option_array, true );


	}

}