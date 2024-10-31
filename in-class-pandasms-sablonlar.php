<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

class In_Class_PandaSMS_Sablonlar {


	public static function insert($mesaj, $aktif_mi, $yonetici_gsm_numaralari_arr){


		global $wpdb;

		$tablo_adi = $wpdb->prefix . 'pandasms_gonderim_sablonlari';

		$wpdb->insert($tablo_adi, [

			'mesaj' => $mesaj,
			'aktif_mi' => $aktif_mi,
			'yonetici_gsm_numaralari' => serialize($yonetici_gsm_numaralari_arr),
			'kayit_zaman' => current_time('mysql')

		]);

		return $wpdb->insert_id;


	}


	public static function update($sablon_id, $mesaj, $aktif_mi, $yonetici_gsm_numaralari_arr){


		global $wpdb;

		$tablo_adi = $wpdb->prefix . 'pandasms_gonderim_sablonlari';

		$wpdb->update($tablo_adi, [

			'mesaj' => $mesaj,
			'aktif_mi' => $aktif_mi,
			'yonetici_gsm_numaralari' => serialize($yonetici_gsm_numaralari_arr),

		], [

			'sablon_id' => $sablon_id

		]);

		return $wpdb->insert_id;


	}



	public static function delete($sablon_id){


		global $wpdb;

		$tablo_adi = $wpdb->prefix . 'pandasms_gonderim_sablonlari';

		return $wpdb->delete($tablo_adi, [

			'sablon_id' => $sablon_id

		]);


	}


	public static function getAll(){


		global $wpdb;

		$tablo_adi = $wpdb->prefix . 'pandasms_gonderim_sablonlari';

		$sonuclar = $wpdb->get_results("SELECT * FROM $tablo_adi");

		return $sonuclar;


	}


	public static function get($sablon_id){


		global $wpdb;

		$tablo_adi = $wpdb->prefix . 'pandasms_gonderim_sablonlari';

		$sonuclar = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablo_adi WHERE sablon_id=%d", $sablon_id));

		return $sonuclar;


	}


}