<?php

if ( ! defined( 'ABSPATH' ) )
	exit;


use PandaSMS\Admin\Option;

class In_Class_PandaSMS_Ayarlar{

	function __construct()
	{

		if(!is_admin())
			return;

		/**
		 * Uygulama Menü
		 */
		add_action( 'admin_menu', array( $this, 'menu' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'pandasms_js_dosyalari' ) );

		add_action( 'admin_init', array( $this, 'register_pandasms_abonelik_ayarlar' ) );



	}





	function menu() {
		add_menu_page('PandaSMS', 'PandaSMS', 'manage_woocommerce', 'pandasms_msg_ayarlar', array( $this, 'pandasms_current_balance') , '');

		add_submenu_page('pandasms_msg_ayarlar', 'Güncel Bakiye', 'Güncel Bakiye', 'manage_woocommerce', 'pandasms_msg_ayarlar', array( $this, 'pandasms_current_balance') );

		add_submenu_page('pandasms_msg_ayarlar', 'İşlem Kuyruğu', 'İşlem Kuyruğu', 'manage_woocommerce', 'pandasms_queue', array( $this, 'pandasms_raporlar') );

		add_submenu_page('pandasms_msg_ayarlar', 'Abone Bilgileri', 'Abone Bilgileri', 'manage_woocommerce', 'pandasms_abonelik_ayarlar', array( $this, 'pandasms_abonelik_ayarlar') );
		
		add_submenu_page('pandasms_msg_ayarlar', 'Gönderim Testi', 'Gönderim Testi', 'manage_woocommerce', 'pandasms_sms_sending_test', array( $this, 'pandasms_sms_sending_test') );

		add_submenu_page('pandasms_msg_ayarlar', 'Mesaj Şablonları', 'Mesaj Şablonları', 'manage_woocommerce', 'pandasms_msg_sablonlari', array( $this, 'pandasms_msg_sablonlari') );

		add_submenu_page('pandasms_msg_ayarlar', 'Ayarlar', 'Ayarlar', 'manage_woocommerce', 'pandasms_ayarlar', array( $this, 'pandasms_ayarlar') );

	}

	public function pandasms_current_balance(){
		$check_balance_response = In_Class_PandaSMS_Connect::get_user_detials_api_v10(true);

		$response = wp_remote_retrieve_body($check_balance_response);

		$response_arr = json_decode($response, true);

		if( $response && $response_arr && isset($response_arr['balance']) ){
			$balance = $response_arr['balance'];
		}
		
		require_once PANDASMS_UYGULAMA_YOLU . 'html/current-balance.php';
	}

	public function pandasms_sms_sending_test(){
		if( isset( $_POST['phone_number'] ) ) {
			if( !(isset( $_POST['pandasms-security'])) || !( wp_verify_nonce( $_POST['pandasms-security'], 'pandasms-sms-sending-test' ) ) ){
				wp_nonce_ays( '' );
			}else{
				$show_result_card = true;
				$message = isset($_POST['message']) ? sanitize_text_field(wp_unslash($_POST['message'])) : '';
				$phone_number = sanitize_text_field($_POST['phone_number']);
			}
		}

		require_once PANDASMS_UYGULAMA_YOLU . 'html/sms-sending-test.php';
	}

	function pandasms_ayarlar(){

		$updated = false;

		if( isset($_POST['guvenlik']) && wp_verify_nonce( $_POST['guvenlik'], 'ayarlari_kaydet' ) ){

			Option::set( 'admin_new_order_phones', $_POST['admin_new_order_phones'] );
			Option::set( 'admin_new_order_msg', wp_unslash($_POST['admin_new_order_msg']) );

			$is_active = array_key_exists('admin_new_order_sms_is_active', $_POST) ? (bool) $_POST['admin_new_order_sms_is_active'] : 0;
			Option::set( 'admin_new_order_sms_is_active', $is_active );

			$no_duplicate_mode = array_key_exists('no_duplicate', $_POST) ? (bool) $_POST['no_duplicate'] : 0;
			Option::set( 'no_duplicate', $no_duplicate_mode );

			$updated = true;

		}

		$admin_new_order_phones = Option::get( 'admin_new_order_phones', '' );
		$admin_new_order_msg = Option::get( 'admin_new_order_msg', '' );
		$admin_new_order_sms_is_active = Option::get( 'admin_new_order_sms_is_active', 0 );
		$no_duplicate_mode = Option::get( 'no_duplicate', 1 );


		require_once PANDASMS_UYGULAMA_YOLU . 'html/ayarlar.php';

	}



	function pandasms_msg_sablonlari(){

		$islem = isset($_GET['islem']) ? sanitize_text_field($_GET['islem']) : 'liste';

		$sayfa_url = add_query_arg(['page' => 'pandasms_msg_sablonlari'], get_admin_url() . 'admin.php');


		switch($islem){

			case "do_update_wc_pandasms":


				$nonce = $_REQUEST['_wpnonce'];

				if(! wp_verify_nonce($nonce, 'pandasms_wc_db_upgrade_preview') )
					die();


				require_once PANDASMS_UYGULAMA_YOLU . 'html/db-upgrade/upgrade-preview.php';



				break;




			case "do_update_wc_pandasms_submit":


				if( !isset($_POST['pandasms_wc_db_upgrade_nonce']) || ! wp_verify_nonce($_POST['pandasms_wc_db_upgrade_nonce'], 'pandasms_wc_db_upgrade') )
					exit;


				In_Class_PandaSMS_Aktivasyon_Deaktivasyon::pandasms_deaktivasyon();

				In_Class_PandaSMS_Aktivasyon_Deaktivasyon::pandasms_aktivasyon();


				require_once PANDASMS_UYGULAMA_YOLU . 'html/db-upgrade/upgrade-success.php';


				break;



			case "duzenle-submit":


				if(!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'pandasms-sablon-duzenle'))
					exit;


				$duzenlenen_sablon_id = isset($_POST['duzenlenen_sablon_id']) ? $_POST['duzenlenen_sablon_id'] : false;


				if(!$duzenlenen_sablon_id)
					return false;


				$yonetici_gsm_numaralari = isset($_POST['pandasms_wc_admin_phone_numbers']) ? sanitize_text_field($_POST['pandasms_wc_admin_phone_numbers']) : '';
				$icerik = isset($_POST['icerik']) ? sanitize_text_field(wp_unslash($_POST['icerik'])) : '';
				$aktif_mi = isset($_POST['aktif_mi']) ? intval($_POST['aktif_mi']) : 0;

				$yonetici_gsm_numaralari = In_Class_PandaSMS_Helper::numaralari_temizle( $yonetici_gsm_numaralari );

				if($duzenlenen_sablon_id=='yeni')
					$sablon_id = In_Class_PandaSMS_Sablonlar::insert($icerik, $aktif_mi, $yonetici_gsm_numaralari);
				else if(is_numeric($duzenlenen_sablon_id)){


					/**
					 *
					 * Şablon detaylarını düzenle
					 *
					 */
					In_Class_PandaSMS_Sablonlar::update($duzenlenen_sablon_id, $icerik, $aktif_mi, $yonetici_gsm_numaralari);


					$sablon_id = $duzenlenen_sablon_id;


					/**
					 *
					 * Şablona ait mevcut tetikleyicileri temizle
					 *
					 */

					In_Class_PandaSMS_Gonderim_Tetikleyicileri::delete_by_sablon_id($duzenlenen_sablon_id);


				}




				/**
				 *
				 * PandaSMS bildirimleri için WooCommerce özel sipariş tetikleyicilerini kaydet
				 *
				 */
				$tetikleyici_actions = $_POST['pandasms_wc_order_actions'];



				$tum_actions = pandasms_wc_get_siparis_bildirim_tetikleyici_actions();



				foreach($tetikleyici_actions as $action_key){

					In_Class_PandaSMS_Gonderim_Tetikleyicileri::insert($sablon_id, 'pandasms_wc_siparis_bildirim_fonksiyon', $action_key, $tum_actions[$action_key]['tanim'], $aktif_mi);

				}





				wp_redirect(add_query_arg(['basarili'=>'yeni-sablon'], $sayfa_url));


				break;

			case "duzenle":

				$sablon_id = isset($_GET['sablon_id']) ? $_GET['sablon_id'] : 0;

				if(!$sablon_id)
					return;

				$mevcut_tetikleyici_action = '';


				if(is_numeric($sablon_id)){

					$sablon_detaylari = In_Class_PandaSMS_Sablonlar::get($sablon_id);

					$tetikleyiciler = In_Class_PandaSMS_Gonderim_Tetikleyicileri::get_by_sablon_id($sablon_id);


					foreach($tetikleyiciler as $tetikleyici){


						if($tetikleyici->tetikleyici_tipi=='pandasms_wc_siparis_bildirim_fonksiyon')
							$mevcut_tetikleyici_action = $tetikleyici->tetikleyici_anahtari;

					}

				}

				require_once PANDASMS_UYGULAMA_YOLU . 'html/sablon/olustur.php';

				break;


			case "liste":

				$sablonlar = In_Class_PandaSMS_Sablonlar::getAll();

				require_once PANDASMS_UYGULAMA_YOLU . 'html/sablon/liste.php';

				break;


			case "sil_submit":


				if( ! isset($_POST['security']) || ! wp_verify_nonce($_POST['security'], 'pandasms-sablon-sil'))
					exit;


				$silinecek_sablon_id = isset($_POST['silinecek_sablon_id']) ? intval($_POST['silinecek_sablon_id']) : 0;

				if (!$silinecek_sablon_id)
					return;


				/** Şablonu sil */
				In_Class_PandaSMS_Sablonlar::delete($silinecek_sablon_id);


				/** Şablona ait Gönderim tetikleyicilerini sil */
				In_Class_PandaSMS_Gonderim_Tetikleyicileri::delete_by_sablon_id($silinecek_sablon_id);


				wp_redirect(add_query_arg(['basarili' => 'sablon-sil'], $sayfa_url));





				break;

		}


	}



	function pandasms_raporlar(){

		switch($_GET['islem']){

			case "bekleyen-talepleri-iptal-et":

				if(!isset($_POST['guvenlik']) || !wp_verify_nonce($_POST['guvenlik'], 'kuyruktaki-bekleyen-talepleri-iptal-et'))
					exit;

				In_Class_PandaSMS_Gonderim_Talebi::bekleyenTumTalepleriIptalEt();

				wp_redirect( get_admin_url() . 'admin.php?page=pandasms_queue' );
				break;

		}

        $per_page_limit = 100;

		$sayfa = isset( $_GET['sayfa'] ) ? intval( $_GET['sayfa'] ) : 1;

		$offset = ($sayfa - 1) * $per_page_limit;

        $toplam = In_Class_PandaSMS_Gonderim_Talebi::getTotal();
		$talepler = In_Class_PandaSMS_Gonderim_Talebi::getTumTalepler( $offset, $per_page_limit );
		$toplam_sayfa = ceil($toplam / $per_page_limit);

		?>

        <div id="wrap">


			<?php

			require PANDASMS_UYGULAMA_YOLU . 'templates/header.php';

			?>


            <div class="ps-title">

                <h3>PandaSMS Gönderim Kuyruğu ( Yeşil: Gönderim başlatıldı, Sarı: Sisteminiz üzerinde, kuyrukta beklemektedir. )</h3>

            </div>

            <div class="ps-card">

                <div style="float:left">

                    <form action="<?php echo get_admin_url() . 'admin.php?page=pandasms_queue&islem=bekleyen-talepleri-iptal-et'; ?>" method="POST">

	                    <?php wp_nonce_field( 'kuyruktaki-bekleyen-talepleri-iptal-et', 'guvenlik' ); ?>
                        <button type="submit" onclick="return confirm('Dikkat! Tamamlanmamış, gönderim bekleyen tüm talepler kalıcı olarak iptal edilecektir ve gönderimi engellenecektir. Devam etmek istediğinize emin misiniz?')" class="button button-pandasms-alert">Bekleyen Gönderimleri İptal Et</button>

                    </form>

                </div>

                <div style="float:right">

                    <form action="<?php echo get_admin_url() . 'admin.php'; ?>" method="GET">
                        <input type="hidden" name="page" value="pandasms_queue" />
                        <table>
                            <tr>
                                <td>
                                    Toplam Kayıt: <?php echo $toplam; ?>
                                </td>
                                <td>
                                    <select name="sayfa">
						                <?php for($i=1; $i<=$toplam_sayfa; $i++){ ?>
                                            <option <?php if( $sayfa == $i ) echo 'selected'; ?> value="<?php echo $i; ?>"><?php printf("%d. sayfa", $i); ?></option>
						                <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <button class="button">Git</button>
                                </td>
                            </tr>
                        </table>

                    </form>

                </div>

                <table width="90%" border="1px" style="border-collapse: collapse; margin-right:15px; margin-top:15px">

                    <thead>

                    <tr>

                        <th>Sipariş ID</th>
                        <th>Sipariş No</th>
                        <th>İşlem Tipi</th>
                        <th>Numara Sayısı</th>
                        <th>PandaSMS Rapor ID</th>
                        <th>İşlem Zamanı</th>

                    </tr>

                    </thead>

					<?php foreach($talepler as $talep){ ?>
                        <tr style="<?php
                        if($talep->tamamlandi==1) echo "background: #1b861b; color:#ffffff";
                        elseif($talep->tamamlandi==2) echo "background: #ff0000; color: #ffffff";
                        elseif($talep->tamamlandi==0) echo "background: yellow";
						elseif($talep->tamamlandi==3) echo "background: #ad0000; color: #ffffff";
                        ?>" >

                            <td><?php echo $talep->wc_order_id; ?></td>
                            <td>
                                <a target="_blank" class="<?php echo ($talep->tamamlandi==1) ? 'a-green-background' : 'a-green-yellow'; ?>" href="<?php printf('%spost.php?post=%s&action=edit', get_admin_url(), $talep->wc_order_id); ?>">

									<?php

									$order = wc_get_order($talep->wc_order_id);

									echo $order ? $order->get_order_number() : 'Sipariş bulunamadı';

									?>

                                </a>
                            </td>
                            <td><?php echo $talep->islem_tipi; ?></td>
                            <td><?php echo $talep->numara_adedi; ?></td>
                            <td>
								<?php
								if($talep->tamamlandi==1)
									echo $talep->pandasms_gonderim_id;
								elseif($talep->tamamlandi==0)
									echo 'SMS Gönderim, işlemi işlem kuyruğundadır.';
                                elseif($talep->tamamlandi==2)
	                                echo 'Gönderim, tarafınızdan iptal edilmiştir.';
								elseif($talep->tamamlandi==3)
									echo 'Gönderim otomatik olarak iptal edilmiştir.';
								?>
                            </td>
                            <td><?php echo $talep->islem_zaman; ?></td>

                        </tr>
					<?php } ?>

                </table>

            </div>

        </div>


        <style>

            tr td {padding:5px 10px}

            .a-green-background {color:#ffffff; text-decoration:none}
            .a-green-background:hover {color:#ffffff; text-decoration:none}
            .a-green-yellow {color: #393939; text-decoration:none}
            .a-green-yellow:hover {color: #393939; text-decoration:none}

        </style>

		<?php


	}



	function pandasms_js_dosyalari($hook){

		if ( 'post.php' !== $hook && 'woocommerce_page_wc-orders' !== $hook )
			return;

		wp_enqueue_script( 'pandasms', PANDASMS_UYGULAMA_URL.'js/widget.js?time='.time(), array('jquery') );

		wp_localize_script( 'pandasms', 'pandaAjax', array(

			'url' => admin_url('admin-ajax.php'),
			'pandasms_sms_detail_nonce' => wp_create_nonce('pandasms-rapor-sorgula-nonce')

		));

	}





	/**
	 * Abonelik Ayarları
	 */
	function register_pandasms_abonelik_ayarlar() {

		register_setting( 'pandasms-user-settings', 'pandasms_username' );
		register_setting( 'pandasms-user-settings', 'pandasms_password' );
		register_setting( 'pandasms-user-settings', 'pandasms_apikey' );
		register_setting( 'pandasms-user-settings', 'pandasms_originator' );
	}


	/**
	 * Abonelik Ayarları, Views
	 */
	function pandasms_abonelik_ayarlar()
	{
		?>
        <div id="wrap">


			<?php

			require PANDASMS_UYGULAMA_YOLU . 'templates/header.php';

			?>


            <div class="ps-title">

                <h3>PandaSMS Abonelik Bilgileriniz</h3>

            </div>

            <div class="ps-card">

                <form method="post" action="options.php">
					<?php settings_fields( 'pandasms-user-settings' ); ?>
					<?php do_settings_sections( 'pandasms-user-settings' ); ?>
                    <table style="width:80%" class="form-table">
                        <tr valign="top">
                            <th width="30%" scope="row">PandaSMS Kullanıcı Adınız:</th>
                            <td width="70%"><input style="width:40%" type="text" name="pandasms_username" value="<?php echo esc_attr( get_option('pandasms_username') ); ?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">PandaSMS Şifreniz:</th>
                            <td><input style="width:40%" type="password" name="pandasms_password" value="<?php echo esc_attr( get_option('pandasms_password') ); ?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">PandaSMS API KEY:</th>
                            <td><input style="width:40%" type="text" name="pandasms_apikey" value="<?php echo esc_attr( get_option('pandasms_apikey') ); ?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">PandaSMS Originator (Gönderim Başlığı):</th>
                            <td><input style="width:40%" type="text" name="pandasms_originator" value="<?php echo esc_attr( get_option('pandasms_originator') ); ?>" /></td>
                        </tr>
                    </table>

					<?php submit_button(); ?>

                </form>

            </div>

        </div>

		<?php
	}

}

new In_Class_PandaSMS_Ayarlar();