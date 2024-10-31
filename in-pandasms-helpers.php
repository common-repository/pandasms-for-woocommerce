<?php

if ( ! defined( 'ABSPATH' ) )
	exit;


function pandasms_wc_get_siparis_bildirim_tetikleyici_actions(){

	/**
	 *
	 * do_action('pandasms_yk_kargo_subede', 'order_id', $numaralar_arr=array(), $sms_kisa_kodlar=array());
	 *
	 *
	 */

	$tetikleyiciler = [];


	/**
	 * Varsayılan olarak; Sipariş durum değişiklikleri için tetikleyiciler oluştur.
	 */
	foreach(wc_get_order_statuses() as $durum_key => $durum){

		$tetikleyiciler['ps_wc_siparis_durum_degisiklik_'.$durum_key] = [

			'tanim' => sprintf('Sipariş durumu %s olduğunda', $durum),
			'kisa_kodlar' => array(),
			'saglayici' => 'PandaSMS'

		];

	}



	/**
	 * Harici tanımlanmış olanlarla birlikte, tüm tetikleyciler
	 */
	$tetikleyiciler = apply_filters('pandasms_wc_siparis_bildirim_tetikleyicileri', $tetikleyiciler);


	foreach($tetikleyiciler as $tetikleyici_key => $tetikleyici_data ){


		$kisa_kodlar = array_key_exists('kisa_kodlar', $tetikleyici_data) ? $tetikleyici_data['kisa_kodlar'] : false;


		if(!is_array($kisa_kodlar)){

			unset($tetikleyiciler[$tetikleyici_key]);
			continue;

		}



		$pandasms_WC_siparis_kisa_kodlari = In_Class_PandaSMS_Kisa_Kodlar::getSiparisDurumDegisikligiKisaKodlari();


		/** Tüm tetikleyicin kısa kodlarına; default olarak PandaSMS WooCommerce sipariş kısa kodlarını ekle. */
		$tetikleyiciler[$tetikleyici_key]['kisa_kodlar'] = array_merge($kisa_kodlar, $pandasms_WC_siparis_kisa_kodlari);



	}


	/**
	 * Intense YK Entegrasyon 2.4.0-alfa uyumluluğu için
	 */

	unset($tetikleyiciler['intense_yk_kargo_dagitima_cikti']);
	unset($tetikleyiciler['intense_yk_kargo_teslim_edildi']);
	unset($tetikleyiciler['intense_yk_kargo_islem_gordu']);




	return $tetikleyiciler;


}


/**
 * @param $order
 * @param string $tetikleyici_adi
 * @param array $kisa_kodlar
 * @return bool
 */
function pandasms_wc_siparis_bildirimi($order, $tetikleyici_adi='', $kisa_kodlar=array()){

	if(!is_a($order, 'WC_Order'))
		return wp_json_encode(array('hataMsg'=>'Fonksiyonunun ilk parametresi WC_Order object tipinde olmalıdır.'));



	$kullanilabilir_tetikleyiciler = array_keys(pandasms_wc_get_siparis_bildirim_tetikleyici_actions());

	if(!in_array($tetikleyici_adi, $kullanilabilir_tetikleyiciler))
		return wp_json_encode(array('hataMsg'=>'tetikleyici_adi (2.argüman) sistemde tanımlı değil, lütfen öncelikle add_filter ile ilgili tetikleyiciyi tanıtınız.'));



	$order_id = $order->get_id();

	$siparis_sahibi_gsm_numaralari = [

		$order->get_billing_phone()

	];

	$kullanilabilir_tum_kisa_kodlar = array_keys(pandasms_wc_get_siparis_bildirim_tetikleyici_actions()[$tetikleyici_adi]['kisa_kodlar']);




	$tetikleyiciler = In_Class_PandaSMS_Gonderim_Tetikleyicileri::get_by_action_adi__tetikleyici_tipi($tetikleyici_adi, 'pandasms_wc_siparis_bildirim_fonksiyon');

	if( 0 === count( $tetikleyiciler ) ) {
		return false;
	}

	$tetikleyici = $tetikleyiciler[0];


	$sablon_id = $tetikleyici->sablon_id;

	$sablon_detaylari = In_Class_PandaSMS_Sablonlar::get($sablon_id);

	if( is_null( $sablon_detaylari ) ) {
		return false;
	}

	$yonetici_gsm_numaralari = unserialize($sablon_detaylari->yonetici_gsm_numaralari);


	// gönderim yapılacak tüm numaraların hazırlanması (yönetici gsm numaralarının eski versiyonda veritabanında string olarak saklanması dolayısıyla array'e çevir.)
	$numaralar = array_merge( In_Class_PandaSMS_Helper::convert_comma_separated_to_array( $yonetici_gsm_numaralari ), $siparis_sahibi_gsm_numaralari);


	$ham_mesaj = $sablon_detaylari->mesaj;


	/**
	 * Gelen kısa kodlara, varsayılan olarak; PandaSMS WooCommerce sipariş kısa kod değişkenlerini ekle.
	 *
	 */

	$pandasms_wc_siparis_kisa_kodlari = In_Class_PandaSMS_Kisa_Kodlar::getSiparisDegiskenler($order);


	/**
	 * Fonksiyon parametresi olarak gelen kısa kodlar ile PandaSMS varsayılan kısa kodlarını birleştir.
	 */
	$tum_kisa_kodlar = array_merge($pandasms_wc_siparis_kisa_kodlari, $kisa_kodlar);




	/** Filtrede tanımlanan kısa kod keyleri ile fonksiyona gelen keyler aynı mı? */
	if( count(array_diff($kullanilabilir_tum_kisa_kodlar, array_keys($tum_kisa_kodlar))) > 0 )
		return wp_json_encode(array('hataMsg'=>'apply_filter ile tanımladığınız kısa kodlar ile gönderim yapacağınız kısa kod array keyleri aynı olmalıdır. ( kısa kod kullanılmasa dahi, apply_filter ile tanımlanan kısa kod keylerinin tamamı gönderilmelidir.'));


	/**
	 * Tüm kısa kod keylerinin etrafını süslü parantezler ile sar. ( mesaj içeriğinde replace yapabilmek için )
	 */
	$kisa_kod_keyleri = array_map(

		function($kisa_kod){ return sprintf("{%s}", $kisa_kod); },

		array_keys($tum_kisa_kodlar)

	);





	$kisa_kod_values = array_values($tum_kisa_kodlar);


	$mesaj = str_replace($kisa_kod_keyleri, $kisa_kod_values, $ham_mesaj);


	$talep_id = In_Class_PandaSMS_Gonderim_Talebi::push($order_id, $tetikleyici->tetikleyici_tipi, $mesaj, $numaralar, $tetikleyici->tetikleyici_anahtari);

	In_Class_PandaSMS_Gonderim_Talebi::handle( $talep_id );


	return true;


}


use PandaSMS\Admin\Option;

add_action('woocommerce_thankyou', 'pandasms_yonetici_bildirimi', 10, 1);

function pandasms_yonetici_bildirimi($order_id){


	$admin_new_order_sms_is_active = Option::get( 'admin_new_order_sms_is_active', 0 );


	// if not active, devam etme, do not continue
	if(!$admin_new_order_sms_is_active)
		return;



	$order = wc_get_order( $order_id );

	$pandasms_wc_siparis_kisa_kodlari = In_Class_PandaSMS_Kisa_Kodlar::getSiparisDegiskenler($order);

	/**
	 * Tüm kısa kod keylerinin etrafını süslü parantezler ile sar. ( mesaj içeriğinde replace yapabilmek için )
	 */
	$kisa_kod_keyleri = array_map(

		function($kisa_kod){ return sprintf("{%s}", $kisa_kod); },

		array_keys($pandasms_wc_siparis_kisa_kodlari)

	);


	$admin_new_order_phones_string = Option::get('admin_new_order_phones');
	$admin_new_order_msg = Option::get('admin_new_order_msg');



	$kisa_kod_values = array_values($pandasms_wc_siparis_kisa_kodlari);


	$mesaj = str_replace($kisa_kod_keyleri, $kisa_kod_values, $admin_new_order_msg);


	$talep_id = In_Class_PandaSMS_Gonderim_Talebi::push($order_id, 'pandasms_wc_yonetici_siparis_bildirim', $mesaj, $admin_new_order_phones_string, 'thankyou');


	/**
	 *
	 * Kuyrukta bekleyen işlemlerin gönderilmesi
	 */

	In_Class_PandaSMS_Gonderim_Talebi::handle( $talep_id );

}
