<?php

if ( ! defined( 'ABSPATH' ) )
	exit;


Class In_Class_PandaSMS_Bildirimler {


	public function __construct()
	{


		add_action('woocommerce_order_status_changed', array($this, 'siparis_durum_degisiklik_bildirimi'), 10, 3);


	}


	function siparis_durum_degisiklik_bildirimi($order_id, $eski_durum, $yeni_durum){


		foreach(In_Class_PandaSMS_Gonderim_Tetikleyicileri::get_by_tetikleyici_tipi('pandasms_wc_siparis_bildirim_fonksiyon') as $tetikleyici){


			if(sprintf('ps_wc_siparis_durum_degisiklik_wc-%s', $yeni_durum)==$tetikleyici->tetikleyici_anahtari){




				$order = wc_get_order($order_id);


				/**
				 * SMS bildirimini başlat.
				 */
				$bildirim = pandasms_wc_siparis_bildirimi($order, $tetikleyici->tetikleyici_anahtari, array());



			}


		}


	}





}


new In_Class_PandaSMS_Bildirimler();