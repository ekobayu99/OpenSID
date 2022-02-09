<div class="box-footer">
	<button type="reset" onclick="window.history.back();" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
	<button type="button" onclick="return simpan_surat();" class="btn btn-social btn-flat bg-fuchsia btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-file-pdf-o"></i> Cetak PDF</button>
</div>
<script type="text/javascript">
	function tambah_elemen_cetak($nilai) {
		$('<input>').attr({
			type: 'hidden',
			name: 'submit_cetak',
			value: $nilai
		}).appendTo($('#validasi'));
	}

	function simpan_surat() {
		let data = $("#validasi").serialize();

		$.ajax({
			type: "POST",
			data: data,
			url: "<?= base_url(); ?>index.php/kp/surat/simpan_surat",
			success: function(res) {

			},
			error: function(xhr) {

			}
		});

		return false;
	}
</script>