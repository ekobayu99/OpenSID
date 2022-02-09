<html>
<?php $this->load->view('print/headjs.php'); ?>

<body>
	<div id="content" class="container_12 clearfix">
		<div id="content-main" class="grid_7">
			<link href="<?= base_url() ?>assets/css/surat.css" rel="stylesheet" type="text/css" />
			<div>
				<table width="100%">
					<?php $this->load->view('print/kop'); ?>
					<div align="center"><u>
							<h4 class="kop">SURAT KETERANGAN</h4>
						</u></div>
					<div align="center">
						<h4 class="kop">Nomor : <?= $input['nomor'] ?></h4>
					</div>
				</table>
				<table width="100%">
					<tr>
						<td>
							<p>Yang bertanda tangan di bawah ini :</p>
						</td>
					</tr>
				</table>
				<div id="isi3">
					<tr></tr>
					<tr></tr>
					<tr></tr>
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td width="20%">Nama</td>
									<td width="2%">:</td>
									<td width="64%"><?= unpenetration($input['pamong']) ?></td>
								</tr>
								<tr>
									<td>Jabatan</td>
									<td>:</td>
									<td><?= unpenetration($input['jabatan']) ?></td>
								</tr>
							</table>
						</td>
					</tr>
				</div>
				<table width="100%">
					<tr>
						<td>
							<p>dengan ini menerangkan bahwa :</p>
						</td>
					</tr>
				</table>
				<div id="isi3">
					<table width="100%">
						<tr></tr>
						<tr></tr>
						<tr></tr>
						<tr>
							<td>
								<table width="100%">
									<tr>
										<td width="20%">Nama Lengkap</td>
										<td width="2%">:</td>
										<td width="64%"><?= unpenetration($data['nama']) ?></td>
									</tr>
									<tr>
										<td>Nomor KTP</td>
										<td>:</td>
										<td><?= $data['nik'] ?></td>
									</tr>
									<!-- <tr>
										<td>Nomor KTP</td>
										<td>:</td>
										<td><?= $input['nik'] ?></td>
									</tr> -->
									<tr>
										<td>Tempat dan Tgl. Lahir </td>
										<td>:</td>
										<td><?= ($data['tempatlahir']) ?>, <?= tgl_indo($data['tanggallahir']) ?> </td>
									</tr>
									<tr>
										<td>Jenis Kelamin</td>
										<td>:</td>
										<td><?= $data['sex'] ?></td>
									</tr>
									<tr>
										<td>Alamat / Tempat Tinggal</td>
										<td>:</td>
										<td>RT. <?= $data['no_rt'] ?>, RW. <?= $data['no_rw'] ?>, Pedukuhan <?= ununderscore(unpenetration($data['alamat_sekarang'])) ?>, <?= ucwords($this->setting->sebutan_data) ?> <?= unpenetration($data['kel_name']) ?>, <?= ucwords($this->setting->sebutan_kecamatan_singkat) ?> <?= strtoupper(unpenetration($data['kec_name'])) ?>, <?= ucwords($this->setting->sebutan_kabupaten_singkat) ?> <?= unpenetration($data['kab_name']) ?></td>
									</tr>
									<tr>
										<td>Agama</td>
										<td>:</td>
										<td><?= $data['agama'] ?></td>
									</tr>
									<tr>
										<td>Status</td>
										<td>:</td>
										<td><?= $data['status_kawin'] ?></td>
									</tr>
									<tr>
										<td>Pendidikan</td>
										<td>:</td>
										<td><?= $data['pendidikan'] ?></td>
									</tr>
									<tr>
										<td>Pekerjaan</td>
										<td>:</td>
										<td><?= $data['pekerjaan'] ?></td>
									</tr>
									<tr>
										<td>Kewarganegaraan </td>
										<td>:</td>
										<td><?= $data['warganegara'] ?></td>
									</tr>
								</table>
								<table width="100%">
									<tr>
										<td width="20%">Keperluan</td>
										<td width="2%">:</td>
										<td width="64%"><?= unpenetration($input['keperluan']) ?></td>
									</tr>
									<tr>
										<td>Keterangan</td>
										<td>:</td>
										<td><?= $input['keterangan'] ?></td>
									</tr>
								</table>
								<table width="100%">
									<tr></tr>
									<tr></tr>
									<tr></tr>
									<tr>
										<td>Demikian surat ini kami buat untuk dapat dipergunakan sebagaimana mestinya. </td>
									</tr>
									<tr></tr>
									<tr></tr>
									<tr></tr>
									<tr></tr>
									<tr></tr>
									<tr></tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
				<?php $this->load->view('print/ttd.php'); ?>

			</div>
		</div>
		<div id="aside"></div>
		<div id="footer" class="container_12"></div>
	</div>
</body>

</html>