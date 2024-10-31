<?php

if ( ! defined( 'ABSPATH' ) )
	exit;



?>

<?php add_thickbox(); ?>


<div id="wrap">


    <?php

    require PANDASMS_UYGULAMA_YOLU . 'templates/header.php';

    ?>


    <div class="ps-title">

        <h3>Yeni WooCommmerce Sipariş Bildirimi Oluştur</h3>

    </div>


    <div class="ps-card">


        <form action="<?php echo add_query_arg(['islem'=>'duzenle-submit'], $sayfa_url); ?>" method="POST">

		    <?php echo wp_nonce_field('pandasms-sablon-duzenle', 'security'); ?>

            <input name="duzenlenen_sablon_id" type="hidden" value="<?php echo $sablon_id; ?>" />


            <table border="1" class="yeni-sablon-olustur-tablo">

                <tr>

                    <th>
                        Aktif Mi?

                        <span class="pandasms_wc_help_tip" title="Bu şablonu dilediğiniz zaman aktif edebilir, veya devre dışı yapabilirsiniz.">?</span>

                    </th>
                    <td>

                        <input <?php if(isset($sablon_detaylari)){ echo ($sablon_detaylari->aktif_mi) ? 'checked' : ''; } ?> type="checkbox" name="aktif_mi" value="1" autocomplete="off" />

                    </td>

                </tr>
                <tr>

                    <th>
                        Mesaj İçeriği:

                        <span class="pandasms_wc_help_tip" title="Gönderilecek mesaj içeriğini buraya yazınız. Dilerseniz mesaj içeriğinde değişkenler kullanabilirsiniz. Bu şablonun hangi durumlarda gönderilmesini istiyorsanız, o durumların / tetikleyicilerin kısa kodlarını kullanmalısınız.">?</span>
                    </th>
                    <td>

                        <textarea maxlength="913" rows="4" name="icerik" autocomplete="off" ><?php if(isset($sablon_detaylari)){ echo $sablon_detaylari->mesaj; } ?></textarea>

                    </td>

                </tr>

                <tr>

                    <th>

                        Tetikleyici

                        <span class="pandasms_wc_help_tip" title="Sipariş için oluşturduğunuz bildirimin hangi durumda yapacağını belirler.">?</span>

                    </th>
                    <td>



                        <table>

                            <thead>

                            <tr>

                                <th style="width:10% !important;">Aktif Mi?</th>
                                <th>Tetikleyici</th>
                                <th>Servis Sağlayıcı</th>

                            </tr>

                            </thead>

						    <?php foreach(pandasms_wc_get_siparis_bildirim_tetikleyici_actions() as $action => $tetikleyici_bilgileri){ ?>
                                <tr>

                                    <td><input <?php echo ($action == $mevcut_tetikleyici_action) ? 'checked' : ''; ?> name="pandasms_wc_order_actions[]" type="radio" class="pandasms_wc_siparis_tetikleyici" value="<?php echo $action; ?>" /></td>
                                    <td width="50%"><?php echo $tetikleyici_bilgileri['tanim']; ?>

									    <?php

									    /**
									     * $modal_key
									     */

									    $modal_baslik = sprintf("<span style='color:blue'>%s</span> Özel Sipariş Tetikleyicisine ait Kısa Kodlar", $tetikleyici_bilgileri['tanim']);

									    $modal_key = $action;

									    $modal_siparis_durum_degisikligi_kisa_kodlari = $tetikleyici_bilgileri['kisa_kodlar'];



									    ?>


                                        <div id="<?php echo $modal_key; ?>" style="display:none">

                                            <?php

                                            require PANDASMS_UYGULAMA_YOLU . 'html/sablon/kisa-kodlar.php';

                                            ?>

                                        </div>




                                    </td>

                                    <td width="45%"><?php echo $tetikleyici_bilgileri['saglayici']; ?></td>

                                </tr>
						    <?php } ?>


                        </table>




                    </td>

                </tr>

                <tr>

                    <th>Her Mesajın Kopyası Yöneticiye de Gönderilsin (opsiyonel):</th>
                    <td>

                        <input type="text" name="pandasms_wc_admin_phone_numbers" value="<?php if(isset($sablon_detaylari)){ echo implode(",", (array) unserialize($sablon_detaylari->yonetici_gsm_numaralari)); } ?>" />
                        <label>numaraları 5320000000,5320000000 formatında araya virgül koyarak yazabilirsiniz.</label>

                    </td>

                </tr>

                <tr>

                    <td></td>
                    <td id="kisa-kod-icerik-alani">


                    <?php

                    $mevcut_tetikleyici_bilgileri = array_key_exists($mevcut_tetikleyici_action, pandasms_wc_get_siparis_bildirim_tetikleyici_actions()) ? pandasms_wc_get_siparis_bildirim_tetikleyici_actions()[$mevcut_tetikleyici_action] : false;

                    if(is_array($mevcut_tetikleyici_bilgileri)) {

	                    $modal_baslik = sprintf("<span style='color:blue'>%s</span> Özel Sipariş Tetikleyicisine ait Kısa Kodlar", $mevcut_tetikleyici_bilgileri['tanim']);

	                    $modal_key = $action;

	                    $modal_siparis_durum_degisikligi_kisa_kodlari = $mevcut_tetikleyici_bilgileri['kisa_kodlar'];

	                    if ($mevcut_tetikleyici_bilgileri)
		                    require PANDASMS_UYGULAMA_YOLU . 'html/sablon/kisa-kodlar.php';

                    }

                    ?>


                    </td>

                </tr>

            </table>


            <button class="button" type="submit">Oluştur</button>

        </form>






    </div>

</div>



<script>

    jQuery(document).ready(function($){




        $('.pandasms_wc_siparis_tetikleyici').change(function(){

           var secim = jQuery(this).val();

           $('#kisa-kod-icerik-alani').html();

           $('#kisa-kod-icerik-alani').html($('#'+secim).html());


        });





        jQuery('.pandasms_wc_help_tip').tooltipster({

            triggerClose: {

                mouseLeave: true,
                touchleave: true,
                tap: true

            }

        });




        jQuery('body').on('mouseover mouseenter', '.kisa-kod', function () {


            jQuery(this).find('.kisa-kod-kopyala-btn').show();


        });


        jQuery('body').on('mouseout', '.kisa-kod', function () {


            jQuery(this).find('.kisa-kod-kopyala-btn').hide();


        });




        new ClipboardJS('.kisa-kod-kopyala-btn');


        jQuery('body').on('click', '.kisa-kod-kopyala-btn', function(){


            var icerik = jQuery(this).parents('.kisa-kod').find('.kisa-kod-icerik').select();


            document.execCommand("copy");

            alert("Kısa kod panoya kopyalandı, mesaj içeriğine yapıştırabilirsiniz.");


        });

    });


</script>