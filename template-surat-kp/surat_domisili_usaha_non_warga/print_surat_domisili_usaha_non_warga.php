<html>

<head>
	<title>Print Surat Domisili Usaha Non Warga</title>
	<link rel="stylesheet" href="<?= base_url() ?>template-surat-kp/print/print.css">
</head>

<body>
	<table>
		<tr>
			<td>
				<?php include "./template-surat-kp/print/kop.php"; ?>
			</td>
		</tr>
		<tr>
			<td>
				<h4 style="text-align: center">
					<u>SURAT KETERANGAN USAHA</u>
					<br />
					Nomor : <?= $log_surat_detil['nomor'] ?>
				</h4>
			</td>
		</tr>
	</table>
	<table>
		<tr>
			<td>
				<p>Yang bertanda tangan dibawah ini menerangkan dengan sebenarnya bahwa :</p>
			</td>
		</tr>
	</table>
	<div class="menjorok_ke_dalam">
		<table width="100%">
			<tr>
				<td width="37%">Nama Lengkap</td>
				<td width="2%">:</td>
				<td width="61%"><?= $log_surat_detil['nama_non_warga'] ?></td>
			</tr>
			<tr>
				<td>NIK/ No. KTP</td>
				<td>:</td>
				<td><?= $log_surat_detil['nik_non_warga'] ?></td>
			</tr>
			<tr>
				<td>Tempat dan Tgl. Lahir </td>
				<td>:</td>
				<td><?= $log_surat_detil['tempatlahir'] ?>, <?= $log_surat_detil['tanggallahir'] ?> </td>
			</tr>
			<tr>
				<td>Jenis Kelamin</td>
				<td>:</td>
				<td><?= $log_surat_detil['sex'] ?></td>
			</tr>
			<tr>
				<td style="vertical-align: top">Alamat / Tempat Tinggal</td>
				<td style="vertical-align: top">:</td>
				<td style="vertical-align: top"><?= $log_surat_detil['alamat'] ?></td>
			</tr>
			<tr>
				<td>Agama</td>
				<td>:</td>
				<td><?= $log_surat_detil['agama'] ?></td>
			</tr>
			<tr>
				<td>Pekerjaan</td>
				<td>:</td>
				<td><?= $log_surat_detil['pekerjaan'] ?></td>
			</tr>
			<tr>
				<td>Kewarganegaraan </td>
				<td>:</td>
				<td><?= $log_surat_detil['warga_negara'] ?></td>
			</tr>
		</table>
	</div>

	<table>
		<tr>
			<td>Orang tersebut benar-benar memiliki usaha <?= $log_surat_detil['peruntukan_bangunan'] ?> bertempat di <?= $log_surat_detil['alamat_usaha'] ?>.</td>
		</tr>
	</table>

	<div class="menjorok_ke_dalam">
		<table width="100%">
			<tr>
				<td width="37%">Nama Usaha</td>
				<td width="2%">:</td>
				<td width="61%"><?= $log_surat_detil['nama_usaha'] ?></td>
			</tr>
			<tr>
				<td>Nomor Akta</td>
				<td>:</td>
				<td><?= $log_surat_detil['akta_usaha'] ?></td>
			</tr>
			<tr>
				<td>Tahun Akta</td>
				<td>:</td>
				<td><?= $log_surat_detil['akta_tahun'] ?></td>
			</tr>
			<tr>
				<td>Notaris</td>
				<td>:</td>
				<td><?= $log_surat_detil['notaris'] ?></td>
			</tr>
			<tr>
				<td>Jenis Bangunan</td>
				<td>:</td>
				<td><?= $log_surat_detil['bangunan'] ?></td>
			</tr>
			<tr>
				<td>Peruntukan Bangunan</td>
				<td>:</td>
				<td><?= $log_surat_detil['peruntukan_bangunan'] ?></td>
			</tr>
			<tr>
				<td>Status Bangunan </td>
				<td>:</td>
				<td><?= $log_surat_detil['status_bangunan'] ?></td>
			</tr>
		</table>
	</div>

	<table>
		<tr>
			<td>Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.</td>
		</tr>
	</table>

	<?php include "./template-surat-kp/print/ttd.php"; ?>

</body>

</html>