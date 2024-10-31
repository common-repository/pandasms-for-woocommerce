<?php

if ( ! defined( 'ABSPATH' ) ){
	exit;
}

class In_Class_PandaSMS_Aktivasyon_Deaktivasyon{


	private static $db_updates = [

		'2.1.7' => [
			'wc_pandasms_v2_1_7_gonderim_talepleri_tablosunun_olusturulmasi',
			'wc_pandasms_v2_1_7_gonderimler_tablosunun_olusturulmasi',
		],
		'3.0.0' => [
			'wc_pandasms_v3_0_0_sablonlar_tablosunun_olusturulmasi',
			'wc_pandasms_v3_0_0_gonderim_tetikleyicileri_olusturulmasi',
			'wc_pandasms_v3_0_0_gonderim_talepleri_tablosuna_yeni_sutun_eklenmesi',
			'wc_pandasms_v3_0_0_onceki_versiyonlardaki_mesaj_sablonlarinin_silinmesi',
		],
		'3.10.0' => [
			'wc_pandasms_v3_10_0_yonetici_gsm_numaralari_icin_sutun_olusturulmasi'
		]

	];

	public static function db_upgrade_needed() {
		$mevcut_version = get_option('wc_pandasms_versiyon');

		if( version_compare( $mevcut_version, PANDASMS_VERSIYON, '>=' )) {
			return false;
		}

		include_once PANDASMS_UYGULAMA_YOLU.'migrate/in-class-pandasms-upgrade-fonksiyonlar.php';

		foreach( self::$db_updates as $version => $db_upgrade_functions ){
			if( version_compare( $version, $mevcut_version, '>' ) ){
				return true;
			}
		}

		return false;
	}


	public static function db_upgrade(){


		include_once PANDASMS_UYGULAMA_YOLU.'migrate/in-class-pandasms-upgrade-fonksiyonlar.php';

		foreach( self::$db_updates as $version => $db_upgrade_functions ){

			$mevcut_version = get_option('wc_pandasms_versiyon');

			foreach($db_upgrade_functions as $db_upgrade_function_adi){

				if( version_compare( $version, $mevcut_version, '>' ) ){


					new In_Class_PandaSMS_Upgrade_Fonksiyonlar($db_upgrade_function_adi);


				}

			}


		}


	}


    static function pandasms_aktivasyon()
    {


		// veritabanı güncellemelerini başlat.
    	self::db_upgrade();

        update_option( 'wc_pandasms_versiyon', PANDASMS_VERSIYON );


	    if (!wp_next_scheduled('pandasms_sms_gonderimleri')) {

		    wp_schedule_event( time() + 30, 'her_15_dk', 'pandasms_sms_gonderimleri');

	    }



    }

    static function pandasms_deaktivasyon(){


	    wp_clear_scheduled_hook('pandasms_sms_gonderimleri');


    }

}