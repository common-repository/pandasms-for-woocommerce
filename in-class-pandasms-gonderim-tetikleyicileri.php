<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

class In_Class_PandaSMS_Gonderim_Tetikleyicileri {


	public static function insert($sablon_id, $tetikleyici_tipi, $key, $aciklama, $aktif_mi){


		global $wpdb;

		$tablo_adi = $wpdb->prefix . 'pandasms_gonderim_tetikleyicileri';

		$wpdb->insert($tablo_adi, [

			'sablon_id' => $sablon_id,
			'tetikleyici_tipi' => $tetikleyici_tipi,
			'tetikleyici_anahtari' => $key,
			'aciklama' => $aciklama,
			'aktif_mi' => $aktif_mi,

		]);

		return $wpdb->insert_id;

	}


	public static function delete_by_sablon_id($sablon_id){


		global $wpdb;

		$tablo_adi = $wpdb->prefix . 'pandasms_gonderim_tetikleyicileri';

		return $wpdb->delete($tablo_adi, [

			'sablon_id' => $sablon_id

		]);


	}


	public static function get_by_sablon_id($sablon_id){


		global $wpdb;

		$tablo_adi = $wpdb->prefix . 'pandasms_gonderim_tetikleyicileri';

		$sonuclar = $wpdb->get_results($wpdb->prepare("SELECT * FROM $tablo_adi WHERE sablon_id=%s", $sablon_id));

		return $sonuclar;


	}


	public static function getAll(){


		global $wpdb;

		$tablo_adi = $wpdb->prefix . 'pandasms_gonderim_tetikleyicileri';

		$sonuclar = $wpdb->get_results("SELECT * FROM $tablo_adi");

		return $sonuclar;


	}



	public static function get_by_action_adi__tetikleyici_tipi($action_adi, $tetikleyici_tipi){


		global $wpdb;

		$tablo_adi = $wpdb->prefix . 'pandasms_gonderim_tetikleyicileri';

		$sonuclar = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablo_adi WHERE tetikleyici_anahtari = %s AND tetikleyici_tipi = %s AND aktif_mi=1", $action_adi, $tetikleyici_tipi));

		return $sonuclar;


	}


	public static function get_by_tetikleyici_tipi($tetikleyici_tipi){


		global $wpdb;

		$tablo_adi = $wpdb->prefix . 'pandasms_gonderim_tetikleyicileri';

		$sonuclar = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablo_adi WHERE tetikleyici_tipi = %s AND aktif_mi=1", $tetikleyici_tipi));

		return $sonuclar;


	}


}