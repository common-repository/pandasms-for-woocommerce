<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

?>



<div id="wrap">


	<?php

	require PANDASMS_UYGULAMA_YOLU . 'templates/header.php';

	?>


    <div class="ps-title">

        <h3>Mesaj Şablonları</h3>

    </div>


    <div class="ps-card">

        <a href="<?php echo add_query_arg(['islem'=>'duzenle', 'sablon_id'=>'yeni'], $sayfa_url); ?>" class="button">Yeni WooCommerce Sipariş Bildirimi Oluştur</a>


	    <?php

	    if($sablonlar) {

	    ?>

        <table class="mesaj-sablonlari-tablo" border="1">

            <thead>

                <tr>
                    <th>Mesaj</th>
                    <th>Aktif Mi?</th>
                    <th>Oluşturulma Zamanı</th>
                    <th>İşlemler</th>
                </tr>

            </thead>

            <tbody>

	            <?php foreach ($sablonlar as $sablon) { ?>
                    <tr>
                        <td><?php echo $sablon->mesaj; ?></td>
                        <td><?php echo ($sablon->aktif_mi) ? 'Aktif' : 'Pasif'; ?></td>
                        <td><?php echo date('d/m/Y H:i:s', strtotime($sablon->kayit_zaman)); ?></td>
                        <td>

                            <form action="<?php echo add_query_arg(['islem' => 'sil_submit'], $sayfa_url); ?>"
                                  method="POST">

					            <?php wp_nonce_field('pandasms-sablon-sil', 'security'); ?>

                                <input type="hidden" name="silinecek_sablon_id"
                                       value="<?php echo $sablon->sablon_id; ?>"/>

                                <button onclick="return confirm('Seçilen şablonu silmek istediğinze emin misiniz?')"
                                        type="submit" class="button">Sil
                                </button>

                            </form>

                            <a href="<?php echo add_query_arg(['islem' => 'duzenle', 'sablon_id' => $sablon->sablon_id], $sayfa_url); ?>"
                               class="button">Düzenle</a>


                        </td>

                    </tr>
	            <?php } ?>

            </tbody>

        </table>


        <?php

	    }else {

        ?>


            <p>

                Oluşturduğunuz bir şablon bulunmamaktadır. <a href="<?php echo add_query_arg(['islem'=>'duzenle', 'sablon_id'=>'yeni'], $sayfa_url); ?>">Buradan</a> yeni bir şablon oluşturabilirsiniz.

            </p>


        <?php

	    }

        ?>

    </div>


</div>