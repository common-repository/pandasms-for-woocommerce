<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

?>

<h4><?php echo $modal_baslik; ?></h4>

<table border="1" style="border-collapse: collapse; width: 100%">

	<thead>

	<tr>

		<th>Kullanılacak Kısa Kod</th>
		<th>Açıklama</th>

	</tr>

	</thead>


	<?php foreach($modal_siparis_durum_degisikligi_kisa_kodlari as $kisa_kod => $kisa_kod_aciklama ){ ?>

		<tr>

			<td style="height:30px; line-height:30px" class="kisa-kod">


				<input style="width:60%" type="text" value="<?php printf('{%s}', $kisa_kod); ?>" readonly="readonly" class="kisa-kod-icerik" id="<?php printf('%s_%s', $modal_key, $kisa_kod); ?>" />

				<button type="button" title="kopyalandı!" style="display:none;float:right" class="panda-wc-tooltip tooltip kisa-kod-kopyala-btn button button-sm" data-clipboard-target="#<?php printf('%s_%s', $modal_key, $kisa_kod); ?>">kopyala</button>

			</td>
			<td width="40%"><?php echo $kisa_kod_aciklama; ?></td>

		</tr>

	<?php } ?>

</table>
