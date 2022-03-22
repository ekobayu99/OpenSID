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
							<?php endif; ?>
							<?php include("donjo-app/views/kp/surat/form/nomor_surat.php"); ?>
							<div class="form-group">
								<label for="usaha" class="col-sm-3 control-label">Nama/ Jenis Usaha</label>
								<div class="col-sm-8">
									<input id="usaha" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Nama/ Jenis Usaha" name="usaha">
								</div>
							</div>
							<div class="form-group">
								<label for="usaha" class="col-sm-3 control-label">Lokasi Usaha</label>
								<div class="col-sm-8">
									<?= form_dropdown('jenis_lokasi', ['1' => 'Sama dengan KTP', '2' => 'Lokasi Baru'], '', 'class="form-control input-sm" id="jenis_lokasi"'); ?>
									<?= form_textarea('lokasi_baru', '', 'class="form-control input-sm" placeholder="Masukkan lokasi usaha.." id="lokasi_baru" style="height: 50px;"'); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="usaha" class="col-sm-3 control-label">Tanggal Berdirinya Usaha</label>
								<div class="col-sm-2">
									<input type="date" name="tgl_berdiri" class="form-control input-sm" required>
								</div>
							</div>
							<?php include("donjo-app/views/kp/surat/form/tgl_berlaku.php"); ?>
							<?php include("donjo-app/views/kp/surat/form/_pamong.php"); ?>
						</form>
					</div>
					<?php include("donjo-app/views/kp/surat/form/tombol_cetak.php"); ?>
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
	$("#jenis_lokasi").on('change', function() {
		let jenis_lokasi = $("#jenis_lokasi").val();

		if (jenis_lokasi == "2") {
			// $("#lokasi_baru").show();
			$("#lokasi_baru").focus();
		} else {
			// $("#lokasi_baru").hide();
		}
	})
</script>