<div class="content-wrapper">
    <section class="content-header">
        <h1>Surat Keluar ke Suratku</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Surat Keluar ke Suratku</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        Surat Keluar ke Suratku
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a href="<?= site_url('kp_suratku_surat_keluar/add'); ?>" class="btn btn-primary btn-flat btn-sm"><i class="fa fa-plus"></i> Kirim Surat</a>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="box-tools">
                                                <div class="input-group input-group-sm pull-right">
                                                    <input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?= $cari ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("surat_masuk/search") ?>');$('#'+'mainform').submit();}">
                                                    <div class="input-group-btn">
                                                        <button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("surat_masuk/search") ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <?= $this->session->flashdata('notif'); ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered dataTable table-striped table-hover">
                                                    <thead class="bg-gray disabled color-palette">
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th width="20%">Aksi</th>
                                                            <th width="5%">No. Urut</th>
                                                            <th width="10%">No. Surat</th>
                                                            <th width="10%">Kode Surat</th>
                                                            <th width="10%">Tgl Surat</th>
                                                            <th width="40%">Isi Singkat</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $no = 1; ?>
                                                        <?php if (!empty($main)) { ?>
                                                            <?php foreach ($main as $data) : ?>
                                                                <tr>
                                                                    <td><?= $no; ?></td>
                                                                    <td>
                                                                        <?php if ($data['is_setuju_pembuat'] == 0 && $data['is_pemeriksa_setuju'] == 0) : ?>
                                                                            <a href="#" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-edit"></i> Edit</a>
                                                                            <a href="#" class="btn btn-danger btn-sm btn-flat"><i class="fa fa-times"></i> Hapus</a>
                                                                            <a href="<?= site_url('kp_suratku_surat_keluar/to_pemeriksa/' . $data['id_surat_keluar']); ?>" class="btn btn-warning btn-sm btn-flat"><i class="fa fa-send"></i> Kirim Ke Pemeriksa</a>
                                                                        <?php elseif ($data['is_setuju_pembuat'] == 1 && $data['is_pemeriksa_setuju'] == 0 && $data['pemeriksa_id'] == $this->session->userdata('user')) : ?>
                                                                            <a href="#" class="btn btn-warning btn-sm btn-flat" onclick="return detil_surat(<?= $data['id_surat_keluar']; ?>);"><i class=" fa fa-spin fa-spinner"></i> Periksa Surat</a>
                                                                        <?php elseif ($data['is_setuju_pembuat'] == 1 && $data['is_pemeriksa_setuju'] == 0 && $data['pemeriksa_id'] != $this->session->userdata('user')) : ?>
                                                                            <a href="#" class="btn btn-warning btn-sm btn-flat"><i class=" fa fa-spin fa-spinner"></i> Sedang diperiksa</a>
                                                                        <?php endif ?>
                                                                        <?php if ($data['is_pemeriksa_setuju'] == 1 && $data['is_kirim'] == 0) : ?>
                                                                            <a href="<?= site_url('kp_suratku_surat_keluar/kirim/' . $data['id_surat_keluar']); ?>" class="btn btn-danger btn-sm btn-flat" onclick="return confirm('Anda yakin..?');"><i class=" fa fa-arrow-right"></i> Kirim</a>
                                                                        <?php endif ?>
                                                                        <?php if ($data['is_kirim'] == 1) : ?>
                                                                            <a href="#" title="Dikirim pada <?= $data['tgl_kirim']; ?>" class="btn btn-success btn-sm btn-flat"><i class=" fa fa-check"></i> Terkirim</a>
                                                                        <?php endif ?>

                                                                        <!-- <a href="#" class="btn btn-danger btn-sm btn-flat" onclick="return detil_surat(<?= $data['id_surat_keluar'] ?>);"><i class=" fa fa-spin fa-spinner"></i> Menunggu Disetujui</a> -->
                                                                    </td>
                                                                    <td><?= $data['nomor_urut']; ?> </td>
                                                                    <td><?= $data['nomor_surat']; ?></td>
                                                                    <td><?= $data['kode_surat']; ?></td>
                                                                    <td><?= tgl_indo_out($data['tanggal_surat']); ?></td>
                                                                    <td><?= $data['isi_singkat']; ?></td>
                                                                </tr>

                                                                <?php $no++; ?>
                                                            <?php endforeach ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td colspan="7">data tidak ditemukan</td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<div class='modal fade' id='mdl_detil_surat' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <form action="#" method="post" onsubmit="return setujui();" id="mdl_detil_surat_form">
                <input type="hidden" name="mdl_detil_surat_id_surat_keluar" id="mdl_detil_surat_id_surat_keluar">
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h4 class='modal-title' id='myModalLabel'><i class='fa fa-search'></i> &nbsp;&nbsp;Detil Surat</h4>
                </div>
                <div class='modal-body' id="mdl_detil_surat_detil">

                </div>
                <div class='modal-footer'>
                    <button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                    <button type="submit" class="btn btn-social btn-flat btn-success btn-sm" id="btn_simpan"><i class='fa fa-check'></i> Setujui Surat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('global/confirm_delete'); ?>


<script type="text/javascript">
    let base_url_app = "<?= base_url(); ?>";

    function setujui() {
        let data = $("#mdl_detil_surat_form").serialize();
        $.ajax({
            type: "POST",
            url: base_url_app + 'index.php/kp_suratku_surat_keluar/pemeriksa_ok',
            data: data,
            beforeSend: function() {
                $("#btn_simpan").attr('disabled', true);
            },
            success: function(res) {
                $("#btn_simpan").attr('disabled', false);
                alert(res.message);
                location.reload();
            },
            error: function(xhr) {
                $("#btn_simpan").attr('disabled', false);
                console.log(xhr.responseText);

            }
        });

        return false;
    }

    function detil_surat(id_surat) {
        $("#mdl_detil_surat_id_surat_keluar").val(id_surat);

        $.ajax({
            type: "GET",
            url: "<?= site_url('kp_suratku_surat_keluar/detil_surat_keluar/'); ?>" + id_surat,
            success: function(r, textStatus, jqXHR) {

                let htm = '<table class="table table-bordered table-hover table-condensed">';
                htm += '<tr><td width="30%">Perihal</td><td width="70%">' + r.isi_singkat + '</td></tr>';
                htm += '<tr><td>Tgl Surat</td><td>' + r.tanggal_surat + '</i></td></tr></table>';



                htm += '<iframe src="' + base_url_app + 'desa/upload/surat_keluar/' + r.berkas_scan + '" width="100%" height="300" frameBorder="0">Browser not compatible.</iframe>';

                $("#mdl_detil_surat_detil").html(htm);


                $("#mdl_detil_surat").modal('show');
            },
            error: function(xhr) {
                console.log(xhr)
            }
        });

        return false;
    }
</script>