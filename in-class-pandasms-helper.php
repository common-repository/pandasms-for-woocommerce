<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

Class In_Class_PandaSMS_Helper {


	static function telefonTemizle($numara){

		$telefon_temiz_format = preg_replace('/^\+?90|^0|\D/', '', ($numara));

		if(preg_match('/^5/', $telefon_temiz_format) and strlen($telefon_temiz_format)==10){
			return $telefon_temiz_format;
		}

	}

	public static function numaralari_temizle( $numaralar ) {
		if ( ! $numaralar ) {
			return array();
		}

		$temiz_numaralar = array();
		$numaralar = self::convert_comma_separated_to_array( $numaralar );

		foreach ( $numaralar as $numara ) {
			$temiz_numara = self::telefonTemizle( $numara );
			if ( $temiz_numara ) {
				$temiz_numaralar[] = $temiz_numara;
			}
		}

		return array_unique( $temiz_numaralar );
	}

	public static function convert_comma_separated_to_array( $variable ) {
		if ( is_string( $variable ) ) {
			return array_map( 'trim', explode( ',', $variable ) );
		}

		return $variable;
	}

}