<?php

if (!defined('ABSPATH'))
	exit;

use PandaSMS\Admin\Option;

Class In_Class_PandaSMS_Connect {
	public static function send_api_v10($phone_numbers, $message){
		$username = get_option('pandasms_username');
		$password = get_option('pandasms_password');
		$apikey = get_option('pandasms_apikey');
		$no_duplicate = (bool) Option::get( 'no_duplicate', 1 );

		$authorization = base64_encode( $username . ':' . $password );

		$body = [
			'sender' => get_option('pandasms_originator'),
			'message'=>$message,
			'phones' => $phone_numbers,
			'no_duplicate' => $no_duplicate
		];

		$body = wp_json_encode( $body );

		$options = [
			'body'              => $body,
			'headers'           => [
				'Content-type'  => 'application/json',
				'Accept' => 'application/json',
				'Api-Key' => $apikey,
				'Authorization' => 'Basic ' . $authorization
			],
			'timeout' => 15,
			'data_format'       => 'body'

		];

		return wp_remote_post( 'https://api.pandasms.com/v10/basic/sms', $options );
	}

	public static function get_user_detials_api_v10($check_balance=false){
		$username = get_option('pandasms_username');
		$password = get_option('pandasms_password');
		$apikey = get_option('pandasms_apikey');

		$authorization = base64_encode( $username . ':' . $password );

		$body = [
			'check_balance' => $check_balance
		];

		$options = [
			'body'              => $body,
			'headers'           => [
				'Content-type'  => 'application/json',
				'Accept' => 'application/json',
				'Api-Key' => $apikey,
				'Authorization' => 'Basic ' . $authorization
			],
			'timeout' => 15,
			'data_format'       => 'body'

		];

		return wp_remote_get( 'https://api.pandasms.com/v10/basic/customer', $options );
	}

	public static function gonder($numaralar, $msgIcerik)
	{

		$body = [

			"veri" => [

				"kullaniciAdi"=>get_option("pandasms_username"),
				"sifre"=>get_option("pandasms_password"),
				"apiAnahtar"=>get_option("pandasms_apikey"),
				"originator"=>get_option("pandasms_originator"),
				"numaralar"=>$numaralar,
				"mesaj"=>$msgIcerik

			]

		];

		$body = wp_json_encode( $body );

		$options = [

			'body'              => $body,
			'headers'           => [
				'Content-type'  => 'application/json'
			],
			'timeout' => 15,
			'data_format'       => 'body'

		];

		$result_raw = wp_remote_post( 'https://api.pandasms.com/v3/mesajGonder', $options );


		if( !$result_raw || is_wp_error($result_raw))
			return false;

		$result = json_decode( $result_raw['body'] );

		return $result;


	}



	public static function sorgula($gonderimNo)
	{

		$body = [

			"veri"  =>  [

				"kullaniciAdi"=>get_option("pandasms_username"),
				"sifre"=>get_option("pandasms_password"),
				"apiAnahtar"=>get_option("pandasms_apikey"),
				"gonderimNo"=>$gonderimNo,
				"msgGoster"=>true,

			]

		];


		$body = wp_json_encode( $body );

		$options = [

			'body'              => $body,
			'headers'           => [
				'Content-type'  => 'application/json'
			],
			'timeout' => 60,
			'data_format'       => 'body'

		];

		$result_raw = wp_remote_post( 'https://api.pandasms.com/v3/gonderimSorgula', $options );

		if( !$result_raw || is_wp_error($result_raw))
			return false;

		$result = json_decode( $result_raw['body'] );

		return $result;


	}



}