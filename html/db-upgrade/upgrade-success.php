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


        <div class="pandasms-notice pandasms-notice-success">

            <h3>Veritabanı Güncellemesi Başarılı!</h3>

            <p><a href="<?php echo add_query_arg( ['page' => 'pandasms_msg_sablonlari' ], get_admin_url() . 'admin.php'); ?>">Buraya tıklayarak, şablon yönetim ekranına giderek şablon oluşturabilirsiniz.</a></p>

        </div>



	</div>

</div>
