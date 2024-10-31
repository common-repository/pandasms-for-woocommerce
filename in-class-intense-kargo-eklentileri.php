<?php


Class In_Class_PandaSMS_Intense_Kargo_Eklentileri {


	public function __construct()
	{

		add_filter('pandasms_wc_siparis_bildirim_tetikleyicileri', array($this, 'kisa_kodlari_tanimla'));

		add_filter('pandasms_wc_order_message_shortcodes', array($this, 'siparis_kisa_kod_degisken_degerlerini_tanimla'), 10, 3);

	}



	/**
	 *
	 * Intense Kargo eklentileri için kargo takip bilgilerinin shortcode değerlerine yazılması
	 *
	 */
	public function siparis_kisa_kod_degisken_degerlerini_tanimla($shortcodes, $order=false, $yeni_durum=false){



		if($yeni_durum=='shipping-progress'){

			$kargo_takip_no = $order->get_meta('shipping_number', true);
			$kargo_takip_sirketi = $order->get_meta('shipping_company', true);
			$kargo_takip_linki = $this->takipLinkOlustur($kargo_takip_sirketi, $kargo_takip_no);

			$shortcodes['kargoTakipNo']    = $kargo_takip_no;
			$shortcodes['kargoSirketi']    = $kargo_takip_sirketi;
			$shortcodes['kargoTakipLinki'] = $kargo_takip_linki;



		}else if($yeni_durum=='kargoya-verildi'){

			$kargo_takip_no = $order->get_meta('_intense_kargo_takip_no', true);
			$kargo_takip_sirketi = $order->get_meta('_intense_kargo_firmasi', true);
			$kargo_takip_linki = $this->takipLinkOlustur($kargo_takip_sirketi, $kargo_takip_no);

			$shortcodes['kargoTakipNo']    = $kargo_takip_no;
			$shortcodes['kargoSirketi']    = $kargo_takip_sirketi;
			$shortcodes['kargoTakipLinki'] = $kargo_takip_linki;



		}


		return $shortcodes;



	}


	public function kisa_kodlari_tanimla($tetikleyiciler){


		foreach($tetikleyiciler as $anahtar => $detaylar ){


			if($anahtar=='ps_wc_siparis_durum_degisiklik_wc-kargoya-verildi' || $anahtar=='ps_wc_siparis_durum_degisiklik_wc-shipping-progress'){

				$tetikleyiciler[$anahtar]['kisa_kodlar']['kargoTakipNo'] = 'Kargo Takip No ifadesi. (Intense Kargo Eklentileri için geçerlidir.)';
				$tetikleyiciler[$anahtar]['kisa_kodlar']['kargoSirketi'] = 'Kargo Şirketi Adı (Intense Kargo Eklentileri için geçerlidir.)';
				$tetikleyiciler[$anahtar]['kisa_kodlar']['kargoTakipLinki'] = 'Kargo Takip Linki (Intense Kargo Eklentileri için geçerlidir.)';


			}
		}

		return $tetikleyiciler;


	}



	private function takipLinkOlustur($shipping_company,$shipping_number)
	{
		if($shipping_number!=""):
			switch($shipping_company)
			{
				case "MNG Kargo":
					$trackingURL = "http://service.mngkargo.com.tr/iactive/popup/kargotakip.asp?k=".$shipping_number;
					break;

				case "Yurtiçi Kargo":
					$trackingURL = "https://yurticikargo.com/tr/online-servisler/gonderi-sorgula?code=".$shipping_number;
					break;

				case "Aras Kargo":
					$trackingURL = "http://kargotakip.araskargo.com.tr/mainpage.aspx?code=".$shipping_number;
					break;

				case "UPS Kargo":
					$trackingURL = "https://www.ups.com/track?loc=tr_TR&tracknum=".$shipping_number."&requester=WT/trackdetails";
					break;

				case "SÜRAT Kargo":
					$trackingURL = "http://www.suratkargo.com.tr/kargoweb/bireysel.aspx?no=".$shipping_number;
					break;

				case "Sürat Kargo":
					$trackingURL = "http://www.suratkargo.com.tr/kargoweb/bireysel.aspx?no=".$shipping_number;
					break;

				case "PTT Kargo":
					$trackingURL = "http://service.pttkargo.com.tr/iactive/popup/kargotakip.asp?k=".$shipping_number;
					break;
			}
		endif;

		if(isset($trackingURL))
			return $trackingURL;
		else
			return False;
	}


}


new In_Class_PandaSMS_Intense_Kargo_Eklentileri();