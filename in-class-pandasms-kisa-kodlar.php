<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

Class In_Class_PandaSMS_Kisa_Kodlar {


	public static $siparis_durum_degisikligi_kisa_kodlari = [

		'siparisNo' => 'WooCommerce Sipariş No (Görünen Sipariş No)',
		'faturaTelefonu' => 'WooCommerce Fatura Telefonu (billing_phone)',
		'faturaEmail' => 'WooCommerce Fatura E-Posta (billing_email)',
		'faturaAd' => 'WooCommerce Fatura Ad (billing_first_name)',
		'faturaSoyad' => 'WooCommerce Fatura Soyad (billing_last_name)',
		'faturaFirma' => 'WooCommerce Fatura Firma Adı (billing_company)',
		'faturaAdres1' => 'WooCommerce Fatura Adres1 (billing_address_1)',
		'faturaAdres2' => 'WooCommerce Fatura Adres2 (billing_address_2)',
		'faturaIl' => 'WooCommerce Fatura İl (billing_state)',
		'faturaIlce' => 'WooCommerce Fatura İlçe (billing_city)',
		'teslimatAd' => 'WooCommerce Teslimat Ad (shipping_first_name)',
		'teslimatSoyad' => 'WooCommerce Teslimat Soyad (shipping_last_name)',
		'teslimatFirma' => 'WooCommerce Teslimat Firma Adı (shipping_company)',
		'teslimatAdres1' => 'WooCommerce Teslimat Adres1 (shipping_address_1)',
		'teslimatAdres2' => 'WooCommerce Teslimat Adres2 (shipping_address_2)',
		'teslimatIl' => 'WooCommerce Teslimat İl (billing_state)',
		'teslimatIlce' => 'WooCommerce Teslimat İlçe (billing_city)',
		'siparisGenelToplam' => 'WooCommerce Sipariş Genel Toplamı',
		'siparisSecilenGonderimMetodu' => 'WooCommerce Siparişin Seçilen Gönderim Metod Adı',
		'siparisOlusmaZamani' => 'WooCommerce Sipariş Oluşma Zamanı',
		'siparisOdemeMetodu' => 'WooCommerce Sipariş Ödeme Yöntemi Adı',
		'siparisId' => 'WooCommerce POST ID ( Bu ifadeyi bilmiyorsanız, en üstteki {siparisNo} degiskenini kullaniniz.',
		'siparisMusteriNotu' => 'WooCommerce Siparişe Müşteri Tarafından Tanımlanmış Not',

	];


	public static function getSiparisDurumDegisikligiKisaKodlari(){


		return self::$siparis_durum_degisikligi_kisa_kodlari;


	}



	public static function getSiparisDegiskenler($order){

		$order = ( $order instanceof WC_Abstract_Order ) ?  $order : false;

		if(!$order)
			return false;


		$kisa_kodlar = In_Class_PandaSMS_Kisa_Kodlar::getSiparisDurumDegisikligiKisaKodlari();

		$msg_degiskenler = [];


		foreach(array_keys($kisa_kodlar) as $key){


			$key_format = $key;


			switch($key){


				case "siparisNo":

					$msg_degiskenler[$key_format] = $order->get_order_number();

				break;

				case "faturaTelefonu":

					$msg_degiskenler[$key_format] = $order->get_billing_phone();

				break;

				case "faturaEmail":

					$msg_degiskenler[$key_format] = $order->get_billing_email();

				break;

				case "faturaAd":

					$msg_degiskenler[$key_format] = $order->get_billing_first_name();

				break;

				case "faturaSoyad":

					$msg_degiskenler[$key_format] = $order->get_billing_last_name();

				break;

				case "faturaFirma":

					$msg_degiskenler[$key_format] = $order->get_billing_company();

				break;

				case "faturaAdres1":

					$msg_degiskenler[$key_format] = $order->get_billing_address_1();

				break;

				case "faturaAdres2":

					$msg_degiskenler[$key_format] = $order->get_billing_address_2();

				break;

				case "faturaIl":

					$shipping_state = $order->get_billing_state();
					$countries_obj = new WC_Countries();
					$country_states_array = $countries_obj->get_states();
					$cities = $country_states_array['TR'];

					$msg_degiskenler[$key_format] = array_key_exists($shipping_state, $cities) ? $cities[$shipping_state] : '';


				break;

				case "faturaIlce":

					$msg_degiskenler[$key_format] = $order->get_billing_city();

				break;

				case "teslimatAd":

					$msg_degiskenler[$key_format] = $order->get_shipping_first_name();

				break;

				case "teslimatSoyad":

					$msg_degiskenler[$key_format] = $order->get_shipping_last_name();

				break;

				case "teslimatFirma":

					$msg_degiskenler[$key_format] = $order->get_shipping_company();

				break;

				case "teslimatAdres1":

					$msg_degiskenler[$key_format] = $order->get_shipping_address_1();

				break;

				case "teslimatAdres2":

					$msg_degiskenler[$key_format] = $order->get_shipping_address_2();

				break;

				case "teslimatIl":

					$shipping_state = $order->get_shipping_state();
					$countries_obj = new WC_Countries();
					$country_states_array = $countries_obj->get_states();
					$cities = $country_states_array['TR'];

					$msg_degiskenler[$key_format] = array_key_exists($shipping_state, $cities) ? $cities[$shipping_state] : '';

				break;

				case "teslimatIlce":

					$msg_degiskenler[$key_format] = $order->get_shipping_city();

				break;

				case "siparisGenelToplam":

					switch($order->get_currency()){

						case "TRY":

							$msg_degiskenler[$key_format] = sprintf("%sTL", number_format($order->get_total(), 2, ',', '.'));

						break;


						default:

							$msg_degiskenler[$key_format] = $order->get_total();

						break;


					}


				break;

				case "siparisSecilenGonderimMetodu":

					$msg_degiskenler[$key_format] = $order->get_shipping_method();

				break;

				case "siparisOlusmaZamani":

					$msg_degiskenler[$key_format] = $order->get_date_created()->date('d/m/Y H:i:s');

				break;

				case "siparisOdemeMetodu":

					$msg_degiskenler[$key_format] = (array_key_exists($order->get_payment_method(), WC()->payment_gateways->get_available_payment_gateways())) ? WC()->payment_gateways->get_available_payment_gateways()[$order->get_payment_method()]->get_method_title() : 'Bilinmiyor';

				break;

				case "siparisId":

					$msg_degiskenler[$key_format] = $order->get_id();

				break;

				case "siparisMusteriNotu":

					$msg_degiskenler[$key_format] = $order->get_customer_note();

				break;

				default:

					return false;

				break;


			}


		}


		/**
		 *
		 * Duruma özel bildirimler için; durum bilgisini döndür.
		 */
		$yeni_durum = $order->get_status();


		return apply_filters('pandasms_wc_order_message_shortcodes', $msg_degiskenler, $order, $yeni_durum);

	}


}