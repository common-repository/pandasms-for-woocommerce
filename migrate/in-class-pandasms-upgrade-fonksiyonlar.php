<?php

if ( ! defined( 'ABSPATH' ) ){
	exit;
}

Class In_Class_PandaSMS_Upgrade_Fonksiyonlar {



	function __construct($fonksiyon_adi)
	{

		$this->$fonksiyon_adi();

	}


	function wc_pandasms_v3_0_0_onceki_versiyonlardaki_mesaj_sablonlarinin_silinmesi(){



		delete_option('pandasms_customerAlert_newOrder_msg');
		delete_option('pandasms_customerAlert_newOrder_status');
		delete_option('pandasms_customerAlert_shipped_msg');
		delete_option('pandasms_customerAlert_shipped_status');
		delete_option('pandasms_customerAlert_kurye_teslim_msg');
		delete_option('pandasms_customerAlert_kurye_teslim_durum');
		delete_option('pandasms_customerAlert_send_shipping_url_status');
		delete_option('pandasms_adminAlert_newOrder_msg');
		delete_option('pandasms_adminAlert_newOrder_status');
		delete_option('admin_gsm');
		delete_option('_pandasms_entegre_intense_modul');


	}


	function wc_pandasms_v3_0_0_gonderim_talepleri_tablosuna_yeni_sutun_eklenmesi(){


		global $wpdb;

		$tablo_adi = $wpdb->prefix . 'pandasms_talepleri';

		$wpdb->query("ALTER TABLE $tablo_adi ADD tetikleyici_anahtari VARCHAR(128) AFTER islem_tipi");


	}


	function wc_pandasms_v3_0_0_gonderim_tetikleyicileri_olusturulmasi(){


		global $wpdb;

		$tablo_adi = $wpdb->prefix . 'pandasms_gonderim_tetikleyicileri';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $tablo_adi (
  `tetikleyici_id` int(11) NOT NULL AUTO_INCREMENT,
  `sablon_id` int(11) NOT NULL,
  `tetikleyici_tipi` VARCHAR(64) NOT NULL,
  `tetikleyici_anahtari` VARCHAR(128) NOT NULL,
  `aciklama` VARCHAR(128) NOT NULL,
  `aktif_mi` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`tetikleyici_id`)
) $charset_collate";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );


	}


	function wc_pandasms_v3_0_0_sablonlar_tablosunun_olusturulmasi(){


		global $wpdb;

		$tablo_adi = $wpdb->prefix . 'pandasms_gonderim_sablonlari';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $tablo_adi (
  `sablon_id` int(11) NOT NULL AUTO_INCREMENT,
  `mesaj` text,
  `aktif_mi` tinyint(1) DEFAULT NULL,
  `kayit_zaman` datetime NOT NULL,
  PRIMARY KEY (`sablon_id`)
) $charset_collate";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );


	}


	function wc_pandasms_v2_1_7_gonderim_talepleri_tablosunun_olusturulmasi(){




		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$table = $wpdb->prefix . 'pandasms_talepleri';

		$sql = "CREATE TABLE IF NOT EXISTS $table (
  `talep_id` int(11) NOT NULL AUTO_INCREMENT,
  `wc_order_id` int(11) NOT NULL,
  `islem_tipi` varchar(128) COLLATE utf8_turkish_ci NOT NULL,
  `numara_adedi` int(11) NOT NULL,
  `pandasms_gonderim_id` BIGINT NOT NULL DEFAULT 0,
  `gonderim_tipi` VARCHAR(3) NOT NULL,
  `mesaj` TEXT NOT NULL,
  `tamamlandi` TINYINT NOT NULL DEFAULT 0,
  `islem_zaman` datetime NOT NULL,
  PRIMARY KEY (`talep_id`)
) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );


	}


	function wc_pandasms_v2_1_7_gonderimler_tablosunun_olusturulmasi(){


		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$table = $wpdb->prefix . 'pandasms_gonderimler';

		$sql = "CREATE TABLE IF NOT EXISTS $table (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `talep_id` int(11) NOT NULL,
  `numara` VARCHAR(16) NOT NULL,
  `mesaj` TEXT NOT NULL,
  `islem_zaman` datetime NOT NULL,
  PRIMARY KEY (`id`)
) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );



	}



	function wc_pandasms_v3_10_0_yonetici_gsm_numaralari_icin_sutun_olusturulmasi(){


		global $wpdb;

		$table = $wpdb->prefix . 'pandasms_gonderim_sablonlari';

		$wpdb->query("ALTER TABLE $table ADD `yonetici_gsm_numaralari` TEXT AFTER `aktif_mi`");



	}



}