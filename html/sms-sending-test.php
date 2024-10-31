<?php

if ( ! defined( 'ABSPATH' ) )
	exit;
?>

<div id="wrap">
	<?php
	    require PANDASMS_UYGULAMA_YOLU . 'templates/header.php';
	?>

	<div class="ps-title">
		<h4>Bu ekrandan PandaSMS SMS gönderim özelliğini test edebilirsiniz.</h4>
	</div>

	<div class="ps-card">
		<form action="<?php echo get_admin_url() . 'admin.php?page=pandasms_sms_sending_test'; ?>" method="POST">
			<?php wp_nonce_field( 'pandasms-sms-sending-test', 'pandasms-security' ); ?>
			<table>
				<tr>
					<td>Gönderim yapılacak numara:</td>
					<td><input name="phone_number" type="text"</td>
				</tr>
				<tr>
					<td>Mesajınız:</td>
					<td><textarea name="message" cols="80" row="5"></textarea></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" class="button-primary" value="Gönder" /></td>
				</tr>
			</table>
		</form>
	</div>

	<?php if( isset( $show_result_card ) && $show_result_card ) { ?>
	<div style="overflow-x: scroll; max-width:90%" class="card">
		<h3>Aşağıdaki alanı ihtiyaç halinde PandaSMS Destek Hattıyla paylaşınız.</h3>

		<hr>

		<h3 style="margin-top:45px" class="title">Test Verileri</h3>
		<p>
			<table>
				<tr>
					<td>Gönderici:</td>
					<td>
						<?php echo get_option('pandasms_originator'); ?>
					</td>
				</tr>
				<tr>
					<td>Gönderim yapılacak numara:</td>
					<td>
						<?php if(isset($phone_number)) echo $phone_number; ?>
					</td>
				</tr>
				<tr>
					<td>Gönderim yapılacak mesaj:</td>
					<td>
						<?php if(isset($message)) echo $message; ?>
					</td>
				</tr>
			</table>

			<?php 
				$result = In_Class_PandaSMS_Connect::send_api_v10([$phone_number], $message);
				$body = wp_remote_retrieve_body($result);
				$body_arr = json_decode( $body, true );
			?>
			<h3 class="title">Sonuç (HTTP Kodu: <?php echo wp_remote_retrieve_response_code( $result ); ?>)</h3>
			<?php
			if( $body_arr && isset($body_arr['report_id']) && $body_arr['report_id']>0 ){
			?>
				<div class="notice notice-success inline"><p>Gönderim başarılı</p></div>
			<?php
			}else{
			?>
				<div class="notice notice-error inline"><p>Gönderim başarısız</p></div>
			<?php } ?>

			<?php
				echo '<pre style="background:#eeeef0;padding:15px">';
					if( $body_arr ) {
						print_r($body_arr);
					}elseif(is_wp_error($result)){
						print_r($result->get_error_message());
					}else{
						echo 'fallback - bilinmeyen hata';
					}
				echo '</pre>';
			?>
		</p>
	</div>
	<?php } ?>
</div>