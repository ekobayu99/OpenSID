<form action="" id="main_cari_nik" name="main" method="POST" class="form-horizontal" onsubmit="return cari_nik_ajax();">
	<div class="form-group">
		<label class="col-sm-3 control-label">
			<font color="blue">DATA DIRI</font>
		</label>
	</div>
	<div class="form-group">
		<label for="nik" class="col-sm-3 control-label">NIK <?= $pemohon ?></label>
		<div class="col-sm-2 col-lg-2">
			<input class="form-control required input-sm" name="nik" id="nik_didapatkan" value="3401065608970001" id="nik" required>
		</div>
		<i><span id="status_nik"></span></i>
	</div>
	<div class="form-group">
		<label for="nama_lgkp" class="col-sm-3 control-label">Nama Lengkap <?= $pemohon ?></label>
		<div class="col-sm-6 col-lg-4">
			<input class="form-control required input-sm" name="nama_lgkp" id="nik_didapatkan" value="ANITA ANGGUNTARI" id="nik" required>
		</div>
		<i><span id="status_nama_lgkp"></span></i>
	</div>
	<div class="form-group">
		<label for="tmpt_lhr" class="col-sm-3 control-label">Tempat Lahir <?= $pemohon ?></label>
		<div class="col-sm-6 col-lg-4">
			<input class="form-control required input-sm" name="tmpt_lhr" id="nik_didapatkan" value="KULON PROGO" id="nik" required>
		</div>
		<i><span id="status_tmpt_lhr"></span></i>
	</div>
	<div class="form-group">
		<label for="tgl_lhr" class="col-sm-3 control-label">Tanggal Lahir <?= $pemohon ?></label>
		<div class="col-sm-6 col-lg-4">
			<input type="date" class="form-control required input-sm" name="tgl_lhr" id="nik_didapatkan" value="1997-08-16" id="nik" required>
		</div>
		<i><span id="status_tgl_lhr"></span></i>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">
			<font color="blue">DATA ALAMAT</font>
		</label>
	</div>
	<div class="form-group">
		<label for="kab_name" class="col-sm-3 control-label">Kabupaten <?= $pemohon ?></label>
		<div class="col-sm-6 col-lg-4">
			<input class="form-control required input-sm" name="kab_name" id="nik_didapatkan" value="KULON PROGO" id="nik" required>
		</div>
		<i><span id="status_kab_name"></span></i>
	</div>
	<div class="form-group">
		<label for="kec_name" class="col-sm-3 control-label">Kecamatan <?= $pemohon ?></label>
		<div class="col-sm-6 col-lg-4">
			<input class="form-control required input-sm" name="kec_name" id="nik_didapatkan" value="SENTOLO" id="nik" required>
		</div>
		<i><span id="status_kec_name"></span></i>
	</div>
	<div class="form-group">
		<label for="kel_name" class="col-sm-3 control-label">Kelurahan <?= $pemohon ?></label>
		<div class="col-sm-6 col-lg-4">
			<input class="form-control required input-sm" name="kel_name" id="nik_didapatkan" value="KALI AGUNG" id="nik" required>
		</div>
		<i><span id="status_kel_name"></span></i>
	</div>
	<div class="form-group">
		<label for="no_rt" class="col-sm-3 control-label">RT <?= $pemohon ?></label>
		<div class="col-sm-1 col-lg-1">
			<input type="number" class="form-control required input-sm" name="no_rt" id="nik_didapatkan" value="23" id="nik" required>
		</div>
		<i><span id="status_no_rt"></span></i>
	</div>
	<div class="form-group">
		<label for="no_rw" class="col-sm-3 control-label">RW <?= $pemohon ?></label>
		<div class="col-sm-1 col-lg-1">
			<input type="number" class="form-control required input-sm" name="no_rw" id="nik_didapatkan" value="12" id="nik" required>
		</div>
		<i><span id="status_no_rw"></span></i>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"></label>
		<div class="col-sm-1 col-lg-1">
			<button type="submit" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-check"></i> Cek NIK</button>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">
			<font color="blue">DATA SURAT</font>
		</label>
	</div>
</form>
<!-- 
<div class="form-group">
	<label for="nik" class="col-sm-3 control-label">NIK / Nama <?= $pemohon ?></label>
	<div class="col-sm-6 col-lg-4">
		<input class="form-control required input-sm" name="nik" id="nik_didapatkan" value="<?= $individu['nik']; ?>" id="nik" required>
	</div>
	<div class="col-sm-6 col-lg-2">
		<button type="submit" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-check"></i> Cek NIK</button>
	</div>
</div> -->

<!-- 
<div class="form-group cari_nik">
	<label for="nik" class="col-sm-3 control-label">NIK / Nama <?= $pemohon ?></label>
	<div class="col-sm-6 col-lg-4">
		<select class="form-control required input-sm select2-nik-ajax readonly-permohonan readonly-periksa" id="nik" name="nik" style="width:100%;" data-filter-sex="<?= $filter_sex ?>" data-url="<?= site_url('surat/list_penduduk_ajax') ?>" onchange="formAction('main')">
			<?php if ($individu) : ?>
				<option value="<?= $individu['id'] ?>" selected><?= $individu['nik'] . ' - ' . $individu['tag_id_card'] . ' - ' . $individu['nama'] ?></option>
			<?php endif; ?>
		</select>
	</div>
</div> -->
<script type="text/javascript">
	$(document).ready(function() {
		// Daftar angka di script berikut adalah key number untuk tombol. Karena dropdown ini memakai select2 maka ketika e_KTP discan hasil pencarian akan otomatis dan default memilih record no. 1. Maka proses harus di delay supaya hasil search tampil terlebih dahulu dengan menghilangkan semua karakter di belakang nomor id yg discan.
		$('#nik').on('select2:open', e => {
			$('.select2-search__field').on('keydown.ajaxfix', e => {
				if (![9, 13, 16, 17, 18, 27, 33, 34, 35, 36, 37, 38, 39, 40].includes(e.which)) {
					$('.select2-results__option').removeClass('select2-results__option--highlighted');
				}
			});
		}).on('select2:closing', e => {
			$('.select2-search__field').off('keydown.ajaxfix');
		});
	});

	function cari_nik_ajax() {
		let data = $("#main_cari_nik").serialize();

		$.ajax({
				url: '<?= base_url(); ?>index.php/kp/surat/cek_nik_baru',
				dataType: 'json',
				data: data,
				method: 'POST',
				beforeSend: function() {
					console.log('Mencari')
				}
			})
			.done(function(r) {
				if (r.success == false) {
					alert(r.message);
				} else {
					// alert(r.data);
					// alert('Berhasil');
					let is_nik_benar = 0;
					let nik = '';

					if (r.data.nik) {
						$("#status_nik").html('NIK Benar');
						$("#status_nik").addClass('text-success');
						is_nik_benar = 1;

						let nik_diinput = $("#nik_didapatkan").val();
						$("#nik_pemohon").val(nik_diinput);
						
					} else {
						$("#status_nik").html('NIK SALAH');
						$("#status_nik").addClass('text-danger');
					}

					if (r.data.nama_lgkp) {
						$("#status_nama_lgkp").html('NAMA LENGKAP Benar');
						$("#status_nama_lgkp").addClass('text-success');
					} else {
						$("#status_nama_lgkp").html('NAMA LENGKAP SALAH');
						$("#status_nama_lgkp").addClass('text-danger');
					}

					if (r.data.tmpt_lhr) {
						$("#status_tmpt_lhr").html('TEMPAT LAHIR Benar');
						$("#status_tmpt_lhr").addClass('text-success');
					} else {
						$("#status_tmpt_lhr").html('TEMPAT LAHIR SALAH');
						$("#status_tmpt_lhr").addClass('text-danger');
					}

					if (r.data.tgl_lhr) {
						$("#status_tgl_lhr").html('TANGGAL LAHIR Benar');
						$("#status_tgl_lhr").addClass('text-success');
					} else {
						$("#status_tgl_lhr").html('TANGGAL LAHIR SALAH');
						$("#status_tgl_lhr").addClass('text-danger');
					}

					if (r.data.kab_name) {
						$("#status_kab_name").html('KABUPATEN Benar');
						$("#status_kab_name").addClass('text-success');
					} else {
						$("#status_kab_name").html('KABUPATEN SALAH');
						$("#status_kab_name").addClass('text-danger');
					}

					if (r.data.kec_name) {
						$("#status_kec_name").html('KECAMATAN Benar');
						$("#status_kec_name").addClass('text-success');
					} else {
						$("#status_kec_name").html('KECAMATAN SALAH');
						$("#status_kec_name").addClass('text-danger');
					}

					if (r.data.kel_name) {
						$("#status_kel_name").html('KELURAHAN Benar');
						$("#status_kel_name").addClass('text-success');
					} else {
						$("#status_kel_name").html('KELURAHAN SALAH');
						$("#status_kel_name").addClass('text-danger');
					}

					if (r.data.no_rt) {
						$("#status_no_rt").html('NO RT Benar');
						$("#status_no_rt").addClass('text-success');
					} else {
						$("#status_no_rt").html('NO RT SALAH');
						$("#status_no_rt").addClass('text-danger');
					}

					if (r.data.no_rw) {
						$("#status_no_rw").html('NO RW Benar');
						$("#status_no_rw").addClass('text-success');
					} else {
						$("#status_no_rw").html('NO RW SALAH');
						$("#status_no_rw").addClass('text-danger');
					}


					$("#tombol_lanjut").show();
				}
			})
			.fail(function() {
				console.log('Gagal');
			});

		return false;
	}
</script>