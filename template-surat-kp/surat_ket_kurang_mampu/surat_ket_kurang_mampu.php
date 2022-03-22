	<script>
		$(function() {
			$('#showData').click(function() {
				$("#kel").removeClass('hide');
				$('#showData').hide();
				$('#hideData').show();
			});

			$('#hideData').click(function() {
				$('#kel').addClass('hide');
				$('#hideData').hide();
				$('#showData').show();
			});
			$('#hideData').hide();
		});
	</script>
	<div class="content-wrapper">
		<?php $this->load->view("surat/form/breadcrumb.php"); ?>
		<section class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border tdk-permohonan tdk-periksa">
							<a href="<?= site_url("kp_surat") ?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Wilayah">
								<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Cetak Surat
							</a>
						</div>
						<div class="box-body">
							<form id="main" name="main" method="POST" class="form-horizontal">
								<?php include("donjo-app/views/kp/surat/form/_cari_nik.php"); ?>
							</form>
							<form id="validasi" action="<?= $form_action ?>" method="POST" target="_blank" class="form-surat form-horizontal">
								<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
								<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat') ?>">
								<input type="hidden" id="is_dari_permohonan" name="is_dari_permohonan" value="<?= $is_dari_permohonan ?>">
								<input type="hidden" id="id_permohonan" name="id_permohonan" value="<?= $id_permohonan ?>">
								<div class="row jar_form">
									<label for="nomor" class="col-sm-3"></label>
									<div class="col-sm-8">
										<input class="required" type="hidden" name="nik" value="<?= $individu['id'] ?>">
									</div>
								</div>
								<?php if ($individu) : ?>
									<?php include("donjo-app/views/kp/surat/form/konfirmasi_pemohon.php"); ?>
									<div class="form-group">
										<label for="keperluan" class="col-sm-3 control-label">Data Keluarga / KK</label>
										<div class="col-sm-8">
											<a id="showData" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-search-plus"></i> Tampilkan</a>
											<a id="hideData" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-search-minus"></i> Sembunyikan</a>
										</div>
									</div>
									<div id="kel" class="form-group hide">
										<label for="pengikut" class="col-sm-3 control-label">Keluarga</label>
										<div class="col-sm-8">
											<div class="table-responsive">
												<table class="table table-bordered dataTable table-hover nowrap">
													<thead class="bg-gray disabled color-palette">
														<tr>
															<th>No</th>
															<th>NIK</th>
															<th>Nama</th>
															<th>Jenis Kelamin</th>
															<th>Tempat Tanggal Lahir</th>
															<th>Hubungan</th>
															<th>Status Kawin</th>
														</tr>
													</thead>
													<tbody>
														<?php if ($anggota != NULL) :
															$i = 0; ?>
															<?php foreach ($anggota as $data) : $i++; ?>
																<tr>
																	<td><?= $i ?></td>
																	<td><?= $data['nik'] ?></td>
																	<td><?= $data['nama'] ?></td>
																	<td><?= $data['sex'] ?></td>
																	<td><?= $data['tempatlahir'] ?>, <?= tgl_indo($data['tanggallahir']) ?></td>
																	<td><?= $data['hubungan'] ?></td>
																	<td><?= $data['status_kawin'] ?></td>
																</tr>
															<?php endforeach; ?>
														<?php endif; ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								<?php endif; ?>
								<?php include("donjo-app/views/kp/surat/form/nomor_surat.php"); ?>
								<!-- Tambahan -->
								<div class="form-group">
									<label for="nik_non_warga" class="col-sm-3 control-label">Identitas (KTP / KK) Pemohon</label>
									<div class="col-sm-4">
										<input class="form-control input-sm required" placeholder="Nomor KTP" name="nik_non_warga" type="text" />
									</div>
									<div class="col-sm-4">
										<input class="form-control input-sm required" placeholder="Nomor KK" name="kk_non_warga" type="text" />
									</div>
								</div>
								<div class="form-group">
									<label for="nik_non_warga" class="col-sm-3 control-label">Nama Pemohon</label>
									<div class="col-sm-4">
										<input class="form-control input-sm required" placeholder="Nama Pemohon" name="nama_non_warga" type="text" />
									</div>
								</div>
								<div class="form-group">
									<label for="nik_non_warga" class="col-sm-3 control-label">Jenis Kelamin</label>
									<div class="col-sm-4">
										<input class="form-control input-sm required" placeholder="Jenis Kelamin" name="sex" type="text" />
									</div>
								</div>
								<div class="form-group">
									<label for="lahir" class="col-sm-3 control-label">Tempat / Tanggal Lahir</label>
									<div class="col-sm-5 col-lg-4">
										<input type="text" name="tempatlahir" class="form-control input-sm required" placeholder="Tempat Lahir"></input>
									</div>
									<div class="col-sm-3 col-lg-2">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control input-sm datepicker required" name="tanggallahir" type="text" />
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="sex" class="col-sm-3 control-label">Jenis Kelamin / Warga Negara / Agama</label>
									<div class="col-sm-2">
										<input type="text" name="warga_negara" class="form-control input-sm required" placeholder="Warga Negara"></input>
										<!-- <select class="form-control input-sm" name="warga_negara" id="warga_negara">
											<option value="">Pilih Warganegara</option>
											<?php foreach ($warganegara as $data) : ?>
												<option value="<?= $data['id'] == '3' ? ucwords(strtolower($data['nama'])) : strtoupper($data['nama']) ?>"><?= $data['nama'] ?></option>
											<?php endforeach; ?>
										</select> -->
									</div>
									<div class="col-sm-3">
										<input type="text" name="agama" class="form-control input-sm required" placeholder="Agama"></input>
										<!-- <select class="form-control input-sm" name="agama" id="agama">
											<option value="">Pilih Agama</option>
											<?php foreach ($agama as $data) : ?>
												<option value="<?= $data['id'] == '7' ? $data['nama'] : ucwords(strtolower($data['nama'])) ?>"><?= $data['nama'] ?></option>
											<?php endforeach; ?>
										</select> -->
									</div>
								</div>
								<div class="form-group">
									<label for="nik_non_warga" class="col-sm-3 control-label">Hubungan Dengan Keluarga</label>
									<div class="col-sm-4">
										<input class="form-control input-sm required" placeholder="Hubungan Dengan Keluarga" name="hub_kel" type="text" />
									</div>
								</div>
								<div class="form-group">
									<label for="nik_non_warga" class="col-sm-3 control-label">Jumlah Anggota Keluarga</label>
									<div class="col-sm-4">
										<input class="form-control input-sm required" placeholder="Jumlah Anggota Keluarga" name="jml_anggota_keluarga" type="number" />
									</div>
								</div>
								<div class="form-group">
									<label for="nik_non_warga" class="col-sm-3 control-label">Penghasilan Per Bulan</label>
									<div class="col-sm-4">
										<input class="form-control input-sm required" placeholder="Penghasilan Per Bulan" name="penghasilan_per_bulan" type="number" />
									</div>
								</div>
								<div class="form-group">
									<label for="nik_non_warga" class="col-sm-3 control-label">Pengeluaran Per Bulan</label>
									<div class="col-sm-4">
										<input class="form-control input-sm required" placeholder="Pengeluaran Per Bulan" name="pengeluaran_per_bulan" type="number" />
									</div>
								</div>
								<!-- Tambahan -->
								<div class="form-group">
									<label for="keperluan" class="col-sm-3 control-label">Keperluan</label>
									<div class="col-sm-8">
										<textarea name="keperluan" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" placeholder="Keperluan"></textarea>
									</div>
								</div>
								<?php include("donjo-app/views/kp/surat/form/_pamong.php"); ?>
							</form>
						</div>
						<?php include("donjo-app/views/kp/surat/form/tombol_cetak.php"); ?>
					</div>
				</div>
			</div>
		</section>
	</div>