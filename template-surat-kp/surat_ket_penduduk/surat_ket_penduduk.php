<div class="content-wrapper">
	<?php $this->load->view("surat/form/breadcrumb.php"); ?>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-body">
						<div class="box-header with-border tdk-permohonan tdk-periksa">
							<a href="<?= site_url("kp_surat") ?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Wilayah">
								<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Cetak Surat
							</a>
						</div>
						<form id="main" name="main" method="POST" class="form-horizontal">
							<?php include("donjo-app/views/kp/surat/form/_cari_nik.php"); ?>
						</form>
						<form id="validasi" action="<?= $form_action ?>" method="POST" target="_blank" class="form-surat form-horizontal">
							<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
							<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat') ?>">
							<input type="hidden" id="is_dari_permohonan" name="is_dari_permohonan" value="<?= $is_dari_permohonan ?>">
							<input type="hidden" id="id_permohonan" name="id_permohonan" value="<?= $id_permohonan ?>">
							<div class="row jar_form">
								<label class="col-sm-3"></label>
								<div class="col-sm-8">
									<input class="required" type="hidden" name="nik" value="<?= $individu['id'] ?>">
								</div>
							</div>
							<?php if ($individu) : ?>
								<?php include("donjo-app/views/kp/surat/form/konfirmasi_pemohon.php"); ?>
							<?php endif; ?>
							<?php include("donjo-app/views/kp/surat/form/nomor_surat.php"); ?>
							<div class="form-group">
								<label for="keterangan" class="col-sm-3 control-label">Keterangan</label>
								<div class="col-sm-8">
									<textarea name="keterangan" id="keterangan" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" placeholder="Keterangan"></textarea>
								</div>
							</div>
							<?php include("donjo-app/views/kp/surat/form/tgl_berlaku.php"); ?>
							<?php include("donjo-app/views/kp/surat/form/_pamong.php"); ?>
							<?php // include("donjo-app/views/kp/surat/form/tampil_foto.php"); ?>
					</div>
					<?php include("donjo-app/views/kp/surat/form/tombol_cetak.php"); ?>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>