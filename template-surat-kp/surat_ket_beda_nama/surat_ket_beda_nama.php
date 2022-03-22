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
							<div class="col-md-12">
								<?php include("donjo-app/views/kp/surat/form/_cari_nik.php"); ?>
							</div>
						</form>
						<form id="validasi" action="<?= $form_action ?>" method="POST" target="_blank" class="form-surat form-horizontal">
							<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
							<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat') ?>">
							<input type="hidden" id="is_dari_permohonan" name="is_dari_permohonan" value="<?= $is_dari_permohonan ?>">
							<input type="hidden" id="id_permohonan" name="id_permohonan" value="<?= $id_permohonan ?>">
							<div class="col-md-12">
								<div class="row jar_form">
									<label for="nomor" class="col-sm-3"></label>
									<div class="col-sm-8">
										<input class="required" type="hidden" name="nik" value="<?= $individu['id'] ?>">
									</div>
								</div>
								<?php if ($individu) : ?>
									<?php include("donjo-app/views/kp/surat/form/konfirmasi_pemohon.php"); ?>
								<?php endif; ?>
								<?php include("donjo-app/views/kp/surat/form/nomor_surat.php"); ?>

								<div class="form-group subtitle_head">
									<label class="text-left"><strong>IDENTITAS 1</strong></label>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Nama Kartu Identitas</label>
									<div class="col-sm-8">
										<input type="text" name="kartu1" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" placeholder="Nama Kartu Identitas"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Identitas yang berbeda</label>
									<div class="col-sm-8">
										<?= form_dropdown('opsi_perbedaan1', ['' => '', 'Nama' => 'Nama', 'TTL' => 'TTL', 'Alamat' => 'Alamat'], '', 'class="form-control input-sm"'); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Perbedaan</label>
									<div class="col-sm-8">
										<?= form_input('perbedaan1', '', 'class="form-control input-sm"'); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
									<div class="col-sm-2">
										<?= form_input('tempatlahir1', '', 'class="form-control input-sm"'); ?>
									</div>
									<div class="col-sm-2">
										<input type="date" name="tanggallahir1" value="" class="form-control input-sm">
									</div>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Sebab terjadi perbedaan</label>
									<div class="col-sm-8">
										<?= form_input('sebab1', '', 'class="form-control input-sm"'); ?>
									</div>
								</div>


								<div class="form-group subtitle_head">
									<label class="text-left"><strong>IDENTITAS 2</strong></label>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Nama Kartu Identitas</label>
									<div class="col-sm-8">
										<input type="text" name="kartu2" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" placeholder="Nama Kartu Identitas"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Identitas yang berbeda</label>
									<div class="col-sm-8">
										<?= form_dropdown('opsi_perbedaan2', ['' => '', 'Nama' => 'Nama', 'TTL' => 'TTL', 'Alamat' => 'Alamat'], '', 'class="form-control input-sm"'); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Perbedaan</label>
									<div class="col-sm-8">
										<?= form_input('perbedaan2', '', 'class="form-control input-sm"'); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
									<div class="col-sm-2">
										<?= form_input('tempatlahir2', '', 'class="form-control input-sm"'); ?>
									</div>
									<div class="col-sm-2">
										<input type="date" name="tanggallahir2" value="" class="form-control input-sm">
									</div>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Sebab terjadi perbedaan</label>
									<div class="col-sm-8">
										<?= form_input('sebab2', '', 'class="form-control input-sm"'); ?>
									</div>
								</div>


								<div class="form-group subtitle_head">
									<label class="text-left"><strong>IDENTITAS 3</strong></label>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Nama Kartu Identitas</label>
									<div class="col-sm-8">
										<input type="text" name="kartu3" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" placeholder="Nama Kartu Identitas"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Identitas yang berbeda</label>
									<div class="col-sm-8">
										<?= form_dropdown('opsi_perbedaan3', ['' => '', 'Nama' => 'Nama', 'TTL' => 'TTL', 'Alamat' => 'Alamat'], '', 'class="form-control input-sm"'); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Perbedaan</label>
									<div class="col-sm-8">
										<?= form_input('perbedaan3', '', 'class="form-control input-sm"'); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
									<div class="col-sm-2">
										<?= form_input('tempatlahir3', '', 'class="form-control input-sm"'); ?>
									</div>
									<div class="col-sm-2">
										<input type="date" name="tanggallahir3" value="" class="form-control input-sm">
									</div>
								</div>
								<div class="form-group">
									<label for="kartu" class="col-sm-3 control-label">Sebab terjadi perbedaan</label>
									<div class="col-sm-8">
										<?= form_input('sebab3', '', 'class="form-control input-sm"'); ?>
									</div>
								</div>

								<div class="form-group subtitle_head tdk-permohonan tdk-periksa">
									<label class="text-left"><strong>PENANDA TANGAN</strong></label>
								</div>
								<?php include("donjo-app/views/kp/surat/form/_pamong.php"); ?>
							</div>
						</form>
					</div>
					<?php include("donjo-app/views/kp/surat/form/tombol_cetak.php"); ?>
				</div>
			</div>
		</div>
	</section>
</div>