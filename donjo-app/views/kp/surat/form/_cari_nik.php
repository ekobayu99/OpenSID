<div class="form-group cari_nik">
	<label for="nik" class="col-sm-3 control-label">NIK / Nama <?= $pemohon ?></label>
	<div class="col-sm-6 col-lg-4">
		<select class="form-control required input-sm select2-nik-ajax readonly-permohonan readonly-periksa" id="nik" name="nik" style="width:100%;" data-filter-sex="<?= $filter_sex ?>" data-url="<?= site_url('surat/list_penduduk_ajax') ?>" onchange="formAction('main')">
			<?php if ($individu) : ?>
				<option value="<?= $individu['id'] ?>" selected><?= $individu['nik'] . ' - ' . $individu['tag_id_card'] . ' - ' . $individu['nama'] ?></option>
			<?php endif; ?>
		</select>
	</div>
	<?php if ($this->uri->segment(1) == "kp_surat") : ?>
		<div class="col-sm-2 col-lg-2">
			<a href="" class="btn btn-warning btn-sm btn-flat" onclick="return new_penduduk();"><i class="fa fa-plus"></i> Tambah Penduduk</a>
		</div>
	<?php endif ?>
</div>
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

	function new_penduduk() {
		$.ajax({
			type: "GET",
			url: base_url + 'index.php/kp_surat/get_data_master',
			success: function(res) {
				let opt_agama = '<option value="">-</option>';
				let opt_pendidikan = '<option value="">-</option>';
				let opt_pekerjaan = '<option value="">-</option>';

				$.each(res.results.agama, function(index, val) {
					opt_agama += '<option value="' + val.NO + '">' + val.DESCRIP + '</option>';
				});
				$.each(res.results.pekerjaan, function(index, val) {
					opt_pekerjaan += '<option value="' + val.NO + '">' + val.DESCRIP + '</option>';
				});
				$.each(res.results.pendidikan, function(index, val) {
					opt_pendidikan += '<option value="' + val.NO + '">' + val.DESCRIP + '</option>';
				});

				$("#cek_agama").html(opt_agama);
				$("#cek_pddk_akh").html(opt_pekerjaan);
				$("#cek_jenis_pkrjn").html(opt_pendidikan);

				$("#cek_agama").select2({
					dropdownParent: $("#modal_new_penduduk")
				});
				$("#cek_pddk_akh").select2({
					dropdownParent: $("#modal_new_penduduk")
				});
				$("#cek_jenis_pkrjn").select2({
					dropdownParent: $("#modal_new_penduduk")
				});


				$("#modal_new_penduduk").modal('show');
			},
			error: function(xhr) {
				console.log(xhr.responseText);
			}
		});
		return false;
	}

	function cek_penduduk() {
		// let data = $("#modal_new_penduduk_form");
		var formData = new FormData();
		formData.append('nik', $("#cek_nik").val());
		formData.append('nama_lgkp', $("#cek_nama_lgkp").val());
		formData.append('alamat', $("#cek_alamat").val());
		formData.append('jenis_klmin', $("#cek_jenis_klmin").val());
		formData.append('tmpt_lhr', $("#cek_tmpt_lahir").val());
		formData.append('tgl_lhr', $("#cek_tgl_lhr").val());
		formData.append('agama', $("#cek_agama").val());
		formData.append('pddk_akh', $("#cek_pddk_akh").val());
		formData.append('jenis_pkrjn', $("#cek_jenis_pkrjn").val());

		$.ajax({
			type: "POST",
			url: base_url + 'index.php/kp_surat/validasi_penduduk',
			data: formData,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$("#modal_new_penduduk_form input, select").attr('disabled', true);
			},
			success: function(res) {
				$("#modal_new_penduduk_form input, select").attr('disabled', false);
				if (res.status_nama_lgkp && res.status_nik) {
					// alert('OK');
					simpan_penduduk();
				} else {
					alert('NIP tidak ditemukan');
				}
			},
			error: function(xhr) {
				console.log(xhr.responseText);
			}
		});

		return false;
	}

	function simpan_penduduk() {
		// let data = $("#modal_new_penduduk_form");
		var formData = new FormData();
		formData.append('nik', $("#cek_nik").val());
		formData.append('nama_lgkp', $("#cek_nama_lgkp").val());
		formData.append('alamat', $("#cek_alamat").val());
		formData.append('jenis_klmin', $("#cek_jenis_klmin").val());
		formData.append('tmpt_lhr', $("#cek_tmpt_lahir").val());
		formData.append('tgl_lhr', $("#cek_tgl_lhr").val());
		formData.append('agama', $("#cek_agama").val());
		formData.append('pddk_akh', $("#cek_pddk_akh").val());
		formData.append('jenis_pkrjn', $("#cek_jenis_pkrjn").val());

		$.ajax({
			type: "POST",
			url: base_url + 'index.php/kp_surat/simpan_penduduk',
			data: formData,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$("#modal_new_penduduk_form input, select").attr('disabled', true);
			},
			success: function(res) {
				$("#modal_new_penduduk_form input, select").attr('disabled', false);
				if (res.success) {
					var data = {
						id: res.insert_id,
						text: res.nama_lgkp
					};
					if ($('#nik').find("option[value='" + data.id + "']").length) {
						$('#nik').val(data.id).trigger('change');
					} else {
						// Create a DOM Option and pre-select by default
						var newOption = new Option(data.text, data.id, true, true);
						// Append it to the select
						$('#nik').append(newOption).trigger('change');
					}

					// var newOption = new Option(data.text, data.id, false, false);
					// $('#nik').append(newOption);
				}
			},
			error: function(xhr) {
				console.log(xhr.responseText);
			}
		});

		return false;
	}

	function edit_alamat(id_penduduk) {
		$("#modal_edit_alamat_id_pend").val(id_penduduk);

		$.ajax({
			type: "GET",
			url: base_url + 'index.php/kp_surat/get_data_wilayah',
			success: function(res) {
				let opt_alamat = '<option value="">-</option>';

				$.each(res, function(index, val) {
					opt_alamat += '<option value="' + val.id + '">RT : ' + val.rt + ', RW : ' + val.rw + ', Dusun : ' + val.dusun + '</option>';
				});

				$("#modal_edit_alamat_id_cluster").html(opt_alamat);
				$("#modal_edit_alamat_id_cluster").select2({
					dropdownParent: $("#modal_edit_alamat")
				});

				$("#modal_edit_alamat").modal('show');
			},
			error: function(xhr) {
				console.log(xhr.responseText);
			}
		});
		return false;
	}

	function update_penduduk() {
		var formData = new FormData();
		formData.append('id_pend', $("#modal_edit_alamat_id_pend").val());
		formData.append('id_cluster', $("#modal_edit_alamat_id_cluster").val());

		$.ajax({
			type: "POST",
			url: base_url + 'index.php/kp_surat/update_alamat',
			data: formData,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$("#btn_simpan").attr('disabled', true);
				$("#btn_simpan").html('<i class="fa fa-spin fa-spinner"></i> Menyimpan');
			},
			success: function(res) {
				alert(res.message);

				$("#btn_simpan").attr('disabled', false);
				$("#btn_simpan").html('<i class="fa fa-check"></i> Simpan');

				if (res.success) {
					var data = {
						id: res.id_pend,
						text: res.nama_lgkp
					};
					if ($('#nik').find("option[value='" + data.id + "']").length) {
						$('#nik').val(data.id).trigger('change');
					} else {
						// Create a DOM Option and pre-select by default
						var newOption = new Option(data.text, data.id, true, true);
						// Append it to the select
						$('#nik').append(newOption).trigger('change');
					}
				}
			},
			error: function(xhr) {
				$("#btn_simpan").attr('disabled', false);
				$("#btn_simpan").html('<i class="fa fa-check"></i> Simpan');
				console.log(xhr.responseText);

			}
		});
	}
</script>

<div class='modal fade' id='modal_new_penduduk' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<form action="#" method="post" id="modal_new_penduduk_form">
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
					<h4 class='modal-title' id='myModalLabel'><i class='fa fa-search'></i> &nbsp;&nbsp;Tambah Penduduk</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-lg-3 col-md-3">NIK</label>
						<div class="col-lg-9 col-md-9">
							<input type="text" class="form-control input-sm" name="cek_penduduk_nik" value="" id="cek_nik" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3 col-md-3">Nama Lengkap</label>
						<div class="col-lg-9 col-md-9">
							<input type="text" class="form-control input-sm" name="cek_penduduk_nama_lgkp" value="" id="cek_nama_lgkp" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3 col-md-3">Alamat</label>
						<div class="col-lg-9 col-md-9">
							<input type="text" class="form-control input-sm" name="cek_penduduk_alamat" id="cek_alamat">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3 col-md-3">Jenis Kelamin</label>
						<div class="col-lg-9 col-md-9">
							<?= form_dropdown('cek_penduduk_jenis_klmin', ['1' => 'LAKI-LAKI', '2' => 'PEREMPUAN'], '', 'class="form-control input-sm" id="cek_jenis_klmin"'); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3 col-md-3">Tempat Lahir</label>
						<div class="col-lg-9 col-md-9">
							<input type="text" class="form-control input-sm" name="cek_penduduk_tmpt_lhr" id="cek_tmpt_lahir">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3 col-md-3">Tanggal Lahir</label>
						<div class="col-lg-9 col-md-9">
							<input type="date" class="form-control input-sm" name="cek_penduduk_tgl_lhr" id="cek_tgl_lhr">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3 col-md-3">Agama</label>
						<div class="col-lg-9 col-md-9">
							<select class="form-control input-sm" name="cek_penduduk_agama" id="cek_agama">
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3 col-md-3">Pendidikan</label>
						<div class="col-lg-9 col-md-9">
							<select class="form-control input-sm" name="cek_penduduk_pddk_akh" id="cek_pddk_akh">
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3 col-md-3">Jenis Pekerjaan</label>
						<div class="col-lg-9 col-md-9">
							<select class="form-control input-sm" name="cek_penduduk_jenis_pkrjn" id="cek_jenis_pkrjn">
							</select>
						</div>
					</div>
				</div>
				<div class='modal-footer'>
					<button type="button" class="btn btn-flat btn-warning btn-sm" id="btn_cek" onclick="return cek_penduduk();"><i class='fa fa-sign-out'></i> Cek NIK</button>
				</div>
			</form>
		</div>
	</div>
</div>



<div class='modal fade' id='modal_edit_alamat' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<form action="#" method="post" id="modal_edit_alamat_form">
				<input type="hidden" id="modal_edit_alamat_id_pend" name="modal_edit_alamat_id_pend">
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
					<h4 class='modal-title' id='myModalLabel'><i class='fa fa-edit'></i> &nbsp;&nbsp;Edit Alamat</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-lg-3 col-md-3">Alamat</label>
						<div class="col-lg-9 col-md-9">
							<?= form_dropdown('modal_edit_alamat_id_cluster', [], '', 'class="form-control input-sm" id="modal_edit_alamat_id_cluster"'); ?>
						</div>
					</div>
				</div>
				<div class='modal-footer'>
					<button type="button" class="btn btn-flat btn-warning btn-sm" id="btn_simpan" onclick="return update_penduduk();"><i class='fa fa-check'></i> Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>