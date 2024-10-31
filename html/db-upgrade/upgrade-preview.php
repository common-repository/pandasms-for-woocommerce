<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

?>


<div id="wrap">

	<?php

	require PANDASMS_UYGULAMA_YOLU . 'templates/header.php';

	?>

	<div class="ps-title">

		<h3>Veritabanı Güncellemesi</h3>

	</div>


	<div class="ps-card">

		<?php

		$mevcut_version = get_option('wc_pandasms_versiyon');

		?>

		<?php if( version_compare( $mevcut_version, '3.0.0', '<' )){ ?>

			<div class="pandasms-notice pandasms-notice-danger">

				<h3>Bilgilendirme!</h3>

				<p>PandaSMS for WooCommerce 3.0.0 sürümüyle birlikte köklü değişiklik yapıldığından dolayı; güncelleme işlemiyle, <strong>bir defaya mahsus olarak</strong> mesaj şablonlarınız silinecektir.</p>

				<p>Yeniden mesaj şablonlarınızı oluşturmanız gerekecektir.</p>

			</div>

		<?php } ?>

		<div style="margin-top:30px">

			<form action="<?php echo add_query_arg( ['page'=>'pandasms_msg_sablonlari', 'islem'=>'do_update_wc_pandasms_submit'], get_admin_url() . 'admin.php' ); ?>" method="post">

				<?php wp_nonce_field('pandasms_wc_db_upgrade', 'pandasms_wc_db_upgrade_nonce'); ?>

				<button class="button button-pandasms-success">Veritabanı güncellemesini şimdi çalıştır</button>

			</form>

		</div>



	</div>

</div>
