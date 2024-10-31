<?php

if (!defined('ABSPATH'))
	exit;

?>

<div id="wrap">


	<?php

	require PANDASMS_UYGULAMA_YOLU . 'templates/header.php';

	?>


	<div class="ps-card">

		<?php if ($updated) { ?>
			<div class="notice notice-success inline">
				<p>
					Ayarlar başarıyla düzenlendi.
				</p>
			</div>
		<?php } ?>

		<form style="margin-top:30px" method="post">

			<div style="overflow: hidden">

			<?php wp_nonce_field('ayarlari_kaydet', 'guvenlik'); ?>

				<div class="ps-title">

					<h3>Genel Ayarlar</h3>

				</div>

				<div>
					<label for="no_duplicate">Tekrarlı SMS Gönderimini Engelle</label>
					<input type="checkbox" name="no_duplicate" id="no_duplicate" <?php checked(1, $no_duplicate_mode, true); ?>value="1" />
				</div>

				<div style="margin-top:60px" class="ps-title">

					<h3>Yöneticiye Yeni Sipariş Bildirimi (Ödeme Ekranından Yeni Müşteri Siparişi Geldiğinde Tetiklenir.)</h3>

				</div>

				<table width="50%" style="float:left">

					<tr>

						<th>Aktif Mi?</th>
						<td>

							<input <?php if ($admin_new_order_sms_is_active) echo 'checked'; ?> type="checkbox" name="admin_new_order_sms_is_active" value="1" />

						</td>

					</tr>

					<tr>

						<th>Bildirim Yapılacak Numara(lar)</th>
						<td>
							<textarea name="admin_new_order_phones"><?php echo $admin_new_order_phones; ?></textarea>
							<label>Virgülle ayrılmış olarak numaraları 5 ile başlayarak yazınız.</label>
						</td>

					</tr>

					<tr>

						<th>Yönetici Bildirim Mesajı</th>
						<td>

							<textarea rows="5" name="admin_new_order_msg"><?php echo $admin_new_order_msg; ?></textarea>

						</td>

					</tr>


					<tr>

						<th></th>
						<td>

							<?php

							submit_button($text = null, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null)

							?>

						</td>

					</tr>



				</table>


				<table border="1" style="border-collapse: collapse; width: 50%;float:right">

					<thead>

						<tr>

							<th colspan="2">Kısa kodlar (Opsiyoneldir, mesaj içeriğinde kullanabilirsiniz.)</th>

						</tr>

						<tr>

							<th>Kullanılacak Kısa Kod</th>
							<th>Açıklama</th>

						</tr>

					</thead>


					<?php foreach (In_Class_PandaSMS_Kisa_Kodlar::getSiparisDurumDegisikligiKisaKodlari() as $kisa_kod => $kisa_kod_aciklama) { ?>

						<tr>

							<td style="height:10px; line-height:10px" class="kisa-kod">


								<input style="width:60%" type="text" value="<?php printf('{%s}', $kisa_kod); ?>" readonly="readonly" class="kisa-kod-icerik" id="<?php printf('%s_%s', $modal_key, $kisa_kod); ?>" />

								<button type="button" title="kopyalandı!" style="display:none;float:right" class="panda-wc-tooltip tooltip kisa-kod-kopyala-btn button button-sm" data-clipboard-target="#<?php printf('%s_%s', $modal_key, $kisa_kod); ?>">kopyala</button>

							</td>
							<td width="40%"><?php echo $kisa_kod_aciklama; ?></td>

						</tr>

					<?php } ?>

				</table>

			</div>

		</form>

	</div>


</div>