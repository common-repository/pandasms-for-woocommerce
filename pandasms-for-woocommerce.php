<?php
/*
Plugin Name: PandaSMS for WooCommerce
Plugin URI: https://pandasms.com/
Description: PandaSMS Ücretsiz Wordpress & Woocommerce Modülü - Abonelik & Destek: 0 376 213 33 24 - info@pandasms.com
Version: 4.2.3
Author: Intense Yazılım Ltd.
Author URI: http://www.pandasms.com
Text Domain: pandasms-for-woocommerce
WC tested up to:8.2
*/

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	add_action( 'before_woocommerce_init', function() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	} );

	define('PANDASMS_UYGULAMA_URL', plugin_dir_url(__FILE__));
	define('PANDASMS_UYGULAMA_YOLU', plugin_dir_path(__FILE__));
	define('PANDASMS_VERSIYON', "4.2.3");

	if (is_admin()) {
		require_once("in-class-pandasms-raporlar.php");
		require_once("in-class-pandasms-ayarlar.php");

		include_once dirname(__FILE__) . '/in-class-pandasms-aktivasyon-islemleri.php';


		/**
		 * Eklenti aktifleştirmesiyle; eğer kullanıcı önceki ver. 3.0.0 veya yukarısındaysa otomatik db güncellemesi yap.
		 */
		$mevcut_version = get_option('wc_pandasms_versiyon');

		if( $mevcut_version && version_compare( $mevcut_version, '3.0.0', '>=' ) ){

			register_activation_hook(__FILE__, array('In_Class_PandaSMS_Aktivasyon_Deaktivasyon', 'pandasms_aktivasyon'));
			register_deactivation_hook(__FILE__, array('In_Class_PandaSMS_Aktivasyon_Deaktivasyon', 'pandasms_deaktivasyon'));

        }


	}

	require_once("in-class-intense-kargo-eklentileri.php");
	require_once("in-class-pandasms-bildirimler.php");
	require_once("in-pandasms-helpers.php");
	require_once("in-class-pandasms-gonderim-tetikleyicileri.php");
	require_once("in-class-pandasms-connect.php");
	require_once("in-class-pandasms-helper.php");
	require_once("in-class-pandasms-gonderim-talebi.php");
	require_once("in-class-pandasms-sablonlar.php");
	require_once("in-class-pandasms-kisa-kodlar.php");
	require_once("includes/Option.php");


	add_action('pandasms_sms_gonderimleri', 'kuyruktan_talep_islenmesi');


	function kuyruktan_talep_islenmesi(){

		// kuyruktan 25 talep alarak işler.

		$talepler = In_Class_PandaSMS_Gonderim_Talebi::getBekleyenTalepler();

		if( ! isset($talepler) || ! count($talepler) > 0 )
			return;

		foreach( $talepler as $talep ){

			In_Class_PandaSMS_Gonderim_Talebi::handle( $talep->talep_id );

		}

	}


	add_filter('admin_body_class', 'pandasms_admin_body_classes');

	function pandasms_admin_body_classes($classes){


		if(!isset($_GET['page']) || !in_array($_GET['page'], ['pandasms_msg_ayarlar', 'pandasms_sms_sending_test', 'pandasms_abonelik_ayarlar', 'pandasms_msg_sablonlari', 'pandasms_ayarlar'])) {
			return $classes;
		}

		$classes .= ' pandasms_page';

		return $classes;


	}



	function pandasms_wc_assets($hook) {


		wp_enqueue_script( 'clipboard.min.js', 'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js', array("jquery") );


		wp_enqueue_script( 'tooltipster', PANDASMS_UYGULAMA_URL . 'assets/tooltipster/dist/js/tooltipster.bundle.js', array("jquery") );
		wp_enqueue_style( 'tooltipster', PANDASMS_UYGULAMA_URL."assets/tooltipster/dist/css/tooltipster.bundle.css", array(), PANDASMS_VERSIYON);
		wp_enqueue_style( 'pandasms-ui-css', PANDASMS_UYGULAMA_URL."assets/style.css", array(), PANDASMS_VERSIYON);
		wp_enqueue_style( 'pandasms-google-fonts', 'https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500&display=swap', array(), '');


	}

	add_action( 'admin_enqueue_scripts', 'pandasms_wc_assets' );


	add_filter('cron_schedules','pandasms_zamanlayicilar');

	function pandasms_zamanlayicilar($zamanlayicilar){

		$zamanlayicilar["her_15_dk"] = array(
			'interval' => 60*15,
			'display' => "Her 15dk'da bir"
		);
		return $zamanlayicilar;

	}



	if(is_admin()){


		add_action('admin_notices', 'pandasms_sablon_bulunmuyor_uyari', 20);



		function pandasms_sablon_bulunmuyor_uyari(){


			if(In_Class_PandaSMS_Sablonlar::getAll() || In_Class_PandaSMS_Aktivasyon_Deaktivasyon::db_upgrade_needed())
				return;

			?>


			<div class="notice notice-error is-dismissible">

				<p><strong>PandaSMS WooCommerce Eklentisi ile SMS göndermeye hemen şablon oluşturarak başlayın...</strong></p>

				<p>PandaSMS için bir gönderim şablonunuz bulunmuyor. <a href="<?php printf('%sadmin.php?page=pandasms_msg_sablonlari', get_admin_url()); ?>">Buraya tıklayarak</a> hemen şablon oluşturabilirsiniz. </p>

			</div>

			<?php

		}

		add_action('admin_notices', 'pandasms_no_duplicate_mode_incompatibility', 10);

		function pandasms_no_duplicate_mode_incompatibility(){
		    $sablonlar = wp_list_pluck( In_Class_PandaSMS_Sablonlar::getAll(), 'mesaj');

			$show_notification = false;
			foreach( $sablonlar as $sablon ) {
				if( strpos( $sablon, '{siparisNo}' ) === false && strpos( $sablon, '{kargoTakipNo}' ) === false && strpos( $sablon, '{siparisId}' ) === false ) {
					$show_notification = true;
					break;
				}
			}

			if( ! $show_notification ) {
				return;
			}
		    ?>

            <div class="notice notice-error is-dismissible">
                <p><strong>PandaSMS - Tekrarlı SMS Engelleme Modu Hakkında Önemli Uyarı!</strong></p>

                <p>v4.2 ile gelen; tekrarlı SMS yapılmasını engelleyen mod otomatik olarak aktif edilmiştir. Bu mod, aynı telefon numarasına geçmişte aynı mesaj gittiyse, tekrar gönderilmesini engeller.</p>

                <p>Sisteminizdeki PandaSMS şablonları, sipariş no veya kargo takip numarası gibi, gönderilen mesajı benzersiz yapacak değişkenler içermemektedir. Bu durum; sisteminizde aynı numaraya farklı sipariş bildirimlerinin gönderilmesini engelleyecektir. Bu durumu düzeltmek için PandaSMS mesaj şablonlarından mesaj içeriğini sipariş bazında benzersiz yapacak {siparisNo}, {kargoTakipNo} veya {siparisId} değişkenlerinden birini eklemeniz(önerilir), veya "Tekrarlı SMS Gönderim Engelleme" modunu kapatmanız gerekmektedir (önerilmez).</p>

                <p>
                    <a href="<?php echo add_query_arg(['page' => 'pandasms_msg_sablonlari'], get_admin_url() . 'admin.php'); ?>" class="button">Mesaj Şablonları Ekranına Git</a>
                </p>
            </div>
            <?php
        }

		add_action('admin_notices', 'pandasms_db_upgrade_required_notice', 10);

		function pandasms_db_upgrade_required_notice(){



		    if(!In_Class_PandaSMS_Aktivasyon_Deaktivasyon::db_upgrade_needed())
		        return false;


		    ?>

            <div class="notice notice-error is-dismissible">

                <p><strong>PandaSMS - Veritabanı Güncellemesi Gereklidir!</strong></p>

                <p>Eklentiyi kullanabilmeniz için PandaSMS veritabanı güncellemesini tamamlamanız gerekmektedir.</p>

                <p>

                    <a href="<?php echo wp_nonce_url( add_query_arg(['page' => 'pandasms_msg_sablonlari', 'islem' => 'do_update_wc_pandasms'], get_admin_url() . 'admin.php'), 'pandasms_wc_db_upgrade_preview'); ?>" class="button">Veritabanı güncellemesini tamamla</a>

                </p>

            </div>

            <?php


        }


		if(isset($_GET['page']) && in_array($_GET['page'], ['pandasms_msg_ayarlar', 'pandasms_sms_sending_test', 'pandasms_abonelik_ayarlar', 'pandasms_msg_sablonlari', 'pandasms_ayarlar']))
        add_action('in_admin_header', 'admin_notices_gizle');

		function admin_notices_gizle(){

            remove_all_actions('admin_notices');
            remove_all_actions('all_admin_notices');

        }


	}



}