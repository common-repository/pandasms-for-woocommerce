<?php

if (!defined('ABSPATH'))
	exit;


Class In_Class_PandaSMS_Gonderim_Talebi
{

	public static function bekleyenTumTalepleriIptalEt(){

		global $wpdb;

		$wpdb->update( self::gonderim_talepleri_tablosu(),
			[
				'tamamlandi' => 2
			],
			[
				'tamamlandi' => 0
			]
		);

	}

	public static function talebi_iptal_et( $talep_id ) {
		global $wpdb;

		$wpdb->update( self::gonderim_talepleri_tablosu(),
			[
				'tamamlandi' => 3 // eklenti tarafından otomatik olarak iptal edildiğini belirtir.
			],
			[
				'talep_id' => $talep_id
			]
		);
	}

	public static function getTotal(){

		global $wpdb;

		$gonderim_talepleri_tablo = self::gonderim_talepleri_tablosu();

		$toplam_talep = $wpdb->get_var( "SELECT COUNT(*) FROM $gonderim_talepleri_tablo" );

		return $toplam_talep;

	}

	public static function getTumTalepler($offset=0, $limit=50){


		global $wpdb;

		$gonderim_talepleri_tablo = self::gonderim_talepleri_tablosu();

		$tum_talepler = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $gonderim_talepleri_tablo ORDER BY talep_id DESC LIMIT %d, %d", $offset, $limit ) );

		return $tum_talepler;


	}


	public static function getSipariseAitTalepler($order_id){

		$order_id = isset($order_id) ? intval($order_id) : 0;

		if(!$order_id)
			return;

		global $wpdb;

		$gonderim_talepleri_tablo = self::gonderim_talepleri_tablosu();

		$query = $wpdb->prepare( "SELECT * FROM $gonderim_talepleri_tablo WHERE wc_order_id='%s'", $order_id );

		return $wpdb->get_results( $query );


	}


	// 25 adet döndürür.
	public static function getBekleyenTalepler(){

		global $wpdb;

		$gonderim_talepleri_tablo = self::gonderim_talepleri_tablosu();

		$bekleyenler = $wpdb->get_results( "SELECT * FROM $gonderim_talepleri_tablo WHERE tamamlandi = 0 LIMIT 25" );

		return $bekleyenler;

	}


	public static function handle( $talep_id ){

		global $wpdb;
		$talep_id = (isset($talep_id) && $talep_id > 0 ) ? $talep_id : 0;

		if(!$talep_id)
			return;

		$gonderim_talepleri_tablo = self::gonderim_talepleri_tablosu();

		$talep_detaylari = In_Class_PandaSMS_Gonderim_Talebi::getTalepDetaylari( $talep_id );


		if( ! isset($talep_detaylari) || $talep_detaylari->tamamlandi == 1 )
			return;


		$gonderim_yapilacak_kayitlar = In_Class_PandaSMS_Gonderim_Talebi::getGonderimler( $talep_id );

		$gonderim_yapilacak_numaralar = [];


		/**
		 *
		 * Şimdilik sadece 1 to n formatında gönderim desteklenmektedir. ( aynı mesajın n kişiye gönderimi )
		 * Gelecek versiyonlarda n to n ( n farklı mesajların n kişiye gönderilmesi ) planlanmaktadır.
		 *
		 */
		foreach( $gonderim_yapilacak_kayitlar as $detay ){


			$gonderim_yapilacak_numaralar[] = $detay->numara;


		}


		if(!count($gonderim_yapilacak_numaralar) > 0){



			// talebi başarılı olarak kaydet ve gönderim numarasını işle.

			$wpdb->update( $gonderim_talepleri_tablo, [

				'pandasms_gonderim_id' => 0,
				'tamamlandi' => 1

			], [ 'talep_id' => $talep_id ] );


			return;


		}


		$mesaj = $talep_detaylari->mesaj;

		$sms_gonderim_detaylari = In_Class_PandaSMS_Connect::send_api_v10($gonderim_yapilacak_numaralar, $mesaj);


		if(is_wp_error( $sms_gonderim_detaylari ))
			return false;

		$sms_gonderim_detaylari = json_decode( wp_remote_retrieve_body( $sms_gonderim_detaylari ), true );

		if ( isset( $sms_gonderim_detaylari['error']['code'] ) && 50200002 !== $sms_gonderim_detaylari['error']['code']  ) {
			self::talebi_iptal_et( $talep_id );
			return false;
		}

		if ( isset( $sms_gonderim_detaylari['report_id'] ) && $sms_gonderim_detaylari['report_id'] > 0 ) {

			// talebi başarılı olarak kaydet ve gönderim numarasını işle.

			$wpdb->update( $gonderim_talepleri_tablo, [

				'pandasms_gonderim_id' => $sms_gonderim_detaylari['report_id'],
				'tamamlandi' => 1

			], [ 'talep_id' => $talep_id ] );

		}


	}



	public static function getTalepDetaylari($talep_id){

		global $wpdb;

		$gonderim_talepleri_tablo = self::gonderim_talepleri_tablosu();

		$gonderim_talepleri_query = $wpdb->prepare("SELECT * FROM $gonderim_talepleri_tablo WHERE talep_id = '%s'", $talep_id);

		$gonderim_talebi = $wpdb->get_row( $gonderim_talepleri_query );

		return $gonderim_talebi;

	}


	public static function getGonderimler( $talep_id ){

		global $wpdb;

		$gonderimler_tablo = $wpdb->prefix . 'pandasms_gonderimler';

		$gonderimler_query = $wpdb->prepare("SELECT * FROM $gonderimler_tablo WHERE talep_id = '%s'", $talep_id);

		$gonderimler = $wpdb->get_results( $gonderimler_query );

		return $gonderimler;


	}


	public static function push($orderId, $islemTipi, $msg, $numaralar, $tetikleyiciAnahtari='')
	{

		$orderId      = isset($orderId) ? intval($orderId) : 0;
		$islemTipi    = isset($islemTipi) ? sanitize_text_field($islemTipi) : false;
		$msg          = isset($islemTipi) ? $msg : false;
		$temizNumaralar = isset($islemTipi) ? In_Class_PandaSMS_Helper::numaralari_temizle( $numaralar ) : false;


		if (!$orderId || !$islemTipi || !$msg || !$temizNumaralar)
			return;

		global $wpdb;

		$wpdb->insert(self::gonderim_talepleri_tablosu(), [

			'wc_order_id' => $orderId,
			'islem_tipi' => $islemTipi,
			'tetikleyici_anahtari' => $tetikleyiciAnahtari,
			'numara_adedi' => count($temizNumaralar),
			'islem_zaman' => current_time('mysql'),
			'gonderim_tipi' => '1:n',
			'mesaj' => $msg

		]);


		$talep_id = $wpdb->insert_id;


		$gonderimler_tablo = $wpdb->prefix . 'pandasms_gonderimler';

		foreach ($temizNumaralar as $numara) {

			$wpdb->insert($gonderimler_tablo, [

				'talep_id' => $talep_id,
				'numara' => $numara,
				'mesaj' => $msg,
				'islem_zaman' => current_time('mysql')

			]);

		}



		return $talep_id;


	}

	private static function gonderim_talepleri_tablosu() {
		global $wpdb;
		return $wpdb->prefix . 'pandasms_talepleri';
	}

}