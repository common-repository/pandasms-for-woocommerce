<?php

if ( ! defined( 'ABSPATH' ) )
	exit;
?>

<div id="wrap">
	<?php
		require PANDASMS_UYGULAMA_YOLU . 'templates/header.php';
	?>

	<div class="ps-title">
		<h4>Güncel Bakiye Sorgulama Ekranı</h4>
	</div>

	<div class="ps-card">
		<?php if(isset($balance)) { ?>
			<div style="background:#3d5efd; color:white; width:300px;padding:15px">
				Güncel Bakiyeniz:
				<p style="font-weight:bold; font-size:18px"><?php echo $balance; ?></p>
			</div>
		<?php }else{ ?>
			<div class="notice notice-error inline"><p>Bakiye sorgulanamadı, lütfen abone bilgilerinizi kontrol ediniz. Abone bilgilerinizin doğru olduğunu düşünüyorsanız, SMS Gönderim testi yapınız.</p></div>
		<?php } ?>
	</div>
</div>




