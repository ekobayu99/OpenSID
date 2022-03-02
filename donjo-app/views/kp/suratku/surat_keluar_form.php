<div class="content-wrapper">
    <section class="content-header">
        <h1>Kirim Surat Keluar ke Suratku</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Kirim Surat Keluar ke Suratku</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        Kirim Surat Keluar ke Suratku
                    </div>
                    <div class="box-body">
                        <?=form_open_multipart(site_url('kp_suratku_surat_keluar/insert'));?>
                        <div class="form-group">
                            <label for="">Nomor Urut</label>
                            <?= form_input('nomor_urut', $nomor_urut, 'class="form-control input-sm" required'); ?>
                        </div>
                        <div class="form-group">
                            <label for="">File</label>
                            <div class="input-group input-group-sm col-sm-12">
                                <input type="text" class="form-control" id="file_path">
                                <input type="file" class="hidden" id="file" name="satuan">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Pilih OPD</label>
                            <?= form_dropdown('opd_tujuan', $p_list_opd, '', 'class="form-control select2" required'); ?>
                        </div>
                        <div class="form-group">
                            <label for="">Klasifikasi Surat</label>
                            <?= form_dropdown('kode_surat', $p_list_klasifikasi, '', 'class="form-control select2" required'); ?>
                        </div>
                        <div class="form-group">
                            <label for="">Nomor Surat</label>
                            <?= form_input('nomor_surat', '', 'class="form-control input-sm" required'); ?>
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Surat</label>
                            <input type="date" name="tanggal_surat" class="form-control input-sm" value="">
                        </div>
                        <div class="form-group">
                            <label for="">Perihal Surat</label>
                            <?= form_textarea('isi_singkat', '', 'class="form-control input-sm required" placeholder="Isi Singkat/Perihal" rows="2" style="resize:none; height: 50px !important"'); ?>
                        </div>
                        <div class='form-group'>
                            <!-- <button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Batal</button> -->
                            <button type='submit' class='btn btn-social btn-flat btn-info btn-sm'><i class='fa fa-check'></i> Simpan</button>
                        </div>
                        <?=form_close();?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('global/confirm_delete'); ?>


<script type="text/javascript">
    // dt_surat();
    $("#opd_tujuan").select2();

    function dt_surat() {
        let tahun = $("#tahun").val();

        let uri = "<?= base_url('index.php/kp_suratku_surat_masuk/index_ajax/'); ?>" + tahun;

        $.ajax({
            type: "GET",
            url: uri,
            beforeSend: function() {
                $("#table_surat").html('<tr><td colspan="5"><i class="fa fa-spin fa-spinner"></i> Memuat...</td></tr>');
            },
            success: function(r, textStatus, jqXHR) {
                let htm = '';
                let no = 1;
                let belum_dibaca = 0;
                let belum_diagenda = 0;
                $.each(r.results, function(k, v) {
                    let tgl = v.tgl_surat;
                    let pc_tgl = tgl.split('-');
                    let new_tgl = pc_tgl[2] + "-" + pc_tgl[1] + "-" + pc_tgl[0];
                    let link_detil = '<a href="#" onclick="return detil_surat(\'' + v.id_surat + '\', \'' + v.penerima_id_instansi + '\', \'' + v.penerima_id_user + '\');" class="btn btn-success btn-sm btn-flat"><i class="fa fa-search"></i> Detil</a>';
                    let is_read = (v.is_read == "1") ? '<div class="btn btn-success btn-sm"><i class="fa fa-check"></i></div>' : '<div class="btn btn-warning btn-sm"><i class="fa fa-minus-circle"></i></div>';
                    let is_agenda = (v.is_agenda == "1") ? '<div class="btn btn-success btn-sm"><i class="fa fa-check"></i></div>' : '<div class="btn btn-warning btn-sm"><i class="fa fa-minus-circle"></i></div>';
                    if (v.is_read != "1") {
                        belum_dibaca++;
                    }
                    if (v.is_agenda != "1") {
                        belum_diagenda++;
                    }

                    htm += '<tr>';
                    htm += '<td class="text-center">' + no + '</td>';
                    htm += '<td class="text-center">' + link_detil + '</td>';
                    htm += '<td>' + is_read + '</i></td>';
                    htm += '<td>' + is_agenda + '</i></td>';
                    htm += '<td>' + v.nm_instansi_pengirim + '</td>';
                    htm += '<td>' + new_tgl + '<br><i>No: ' + v.nomor_surat + '</i></td>';
                    htm += '<td>' + v.judul + '<br><i>' + v.deskripsi + '</i></td>';
                    htm += '</tr>';
                    no++;
                });

                $("#belum_dibaca").html(belum_dibaca);
                $("#belum_diagenda").html(belum_diagenda);
                $("#table_surat").html(htm);
            },
            error: function(xhr) {
                console.log(xhr)
            }
        });

    }

    function detil_surat(id_surat, id_instansi_penerima, id_user_penerima) {
        $("#mdl_detil_surat_id_surat").val(id_surat);
        $("#mdl_detil_surat_penerima_id_instansi").val(id_instansi_penerima);
        $("#mdl_detil_surat_penerima_id_user").val(id_user_penerima);

        let tahun = $("#tahun").val();

        let uri = "<?= base_url('index.php/kp_suratku_surat_masuk/detil_surat'); ?>";
        if (tahun == "2020") {
            uri = "<?= base_url('index.php/kp_suratku_surat_masuk/detil_surat/2020'); ?>";
        }

        $.ajax({
            type: "POST",
            data: {
                id_surat: id_surat,
                penerima_id_instansi: id_instansi_penerima,
                penerima_id_user: id_user_penerima
            },
            url: uri,
            success: function(r, textStatus, jqXHR) {
                let tgl = r.results.tgl_surat;
                let pc_tgl = tgl.split('-');
                let new_tgl = pc_tgl[2] + "-" + pc_tgl[1] + "-" + pc_tgl[0];
                let now_tgl = new Date().toISOString().slice(0, 19).replace('T', ' ');

                let lampiran = r.results.surat_lampiran;
                let html_lampiran = '';

                if (lampiran.length > 0) {
                    html_lampiran += '<tr><td>Lampiran</td><td><ol style="margin-left: -20px; margin-top: 10px">';
                    $.each(lampiran, function(k, v) {
                        html_lampiran += '<li><a href="' + v.file_lampiran + '" target="_blank">' + v.file_label + '</a>  [tipe: ' + v.file_type + ', ukuran: ' + v.file_size + ' KB]</li>';
                    });
                    html_lampiran += '</ol></td></tr>';
                }

                let htm = '<table class="table table-bordered table-hover table-condensed">';
                htm += '<tr><td width="30%">Asal Surat</td><td width="70%">' + r.results.nm_instansi_pengirim + '</td></tr>';
                htm += '<tr><td>Perihal</td><td>' + r.results.judul + '<br><i>' + r.results.deskripsi + '</i></td></tr>';
                htm += '<tr><td>Tgl Surat</td><td>' + new_tgl + '</td></tr>';
                htm += '<tr><td>Nomor Surat</td><td>' + r.results.nomor_surat + '</td></tr>';
                htm += '<tr><td>Nomor Surat Desa</td><td>' + r.nomor_surat_preview + '</td></tr>';
                htm += html_lampiran;
                htm += '</table>';
                htm += '<input type="hidden" name="nomor_urut" value="' + r.nomor_surat_preview + '">';
                htm += '<input type="hidden" name="tanggal_penerimaan" value="' + now_tgl + '">';
                htm += '<input type="hidden" name="kode_surat" value="' + r.results.klasifikasi + '">';
                htm += '<input type="hidden" name="nomor_surat" value="' + r.results.nomor_surat + '">';
                htm += '<input type="hidden" name="tanggal_surat" value="' + r.results.tgl_surat + '">';
                htm += '<input type="hidden" name="pengirim" value="' + r.results.nm_instansi_pengirim + '">';
                htm += '<input type="hidden" name="isi_singkat" value="' + r.results.judul + ', ' + r.results.deskripsi + '">';



                htm += '<iframe src="' + r.results.filenya_signed + '" width="100%" height="300" frameBorder="0">Browser not compatible.</iframe>';

                $("#mdl_detil_surat_detil").html(htm);

                if (r.results.is_agenda == "1") {
                    $("#btn_simpan").hide();
                }

                $("#mdl_detil_surat").modal('show');
            },
            error: function(xhr) {
                console.log(xhr)
            }
        });

        return false;
    }

    function simpan_nomor() {
        let data = $("#mdl_detil_surat_form").serialize();

        let tahun = $("#tahun").val();

        let uri = "<?= base_url('index.php/kp_suratku_surat_masuk/simpan_surat_masuk'); ?>";
        if (tahun == "2020") {
            uri = "<?= base_url('index.php/kp_suratku_surat_masuk/simpan_surat_masuk/2020'); ?>";
        }

        $.ajax({
            type: "POST",
            data: data,
            url: uri,
            beforeSend: function() {
                $("#btn_simpan").attr("disabled", true);
            },
            success: function(r, textStatus, jqXHR) {
                $("#btn_simpan").attr("disabled", false);
                if (r.success == false) {
                    alert(r.message);
                } else {
                    alert(r.message);
                    $("#mdl_detil_surat").modal('hide');
                    dt_surat();
                }
            },
            error: function(xhr) {
                $("#btn_simpan").attr("disabled", false);
                console.log(xhr)
            }
        });
        return false;
    }
</script>