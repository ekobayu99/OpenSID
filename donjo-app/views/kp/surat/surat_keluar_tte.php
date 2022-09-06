<div class="content-wrapper">
    <section class="content-header">
        <h1>Arsip Layanan Surat</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Arsip Layanan Surat</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        List Surat Perlu Ditandatangani
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered dataTable table-striped table-hover">
                                                    <thead class="bg-gray disabled color-palette">
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th width="30%">Aksi</th>
                                                            <th width="65%">Nama Dokumen</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $no = 1; ?>
                                                        <?php if (!empty($main)): ?>
                                                        <?php foreach ($main as $data) : ?>
                                                            <tr>
                                                                <td><?= $no ?></td>
                                                                <td>
                                                                    <?php if (intval($data['is_ttd']) == 0) : ?>
                                                                        <a href="#" class="btn btn-primary btn-flat btn-sm" onclick="return tandatangani(<?= $data['id']; ?>);"><i class="fa fa-edit"></i> Tandatangani</a>
                                                                    <?php else : ?>
                                                                        <a href="#" class="btn btn-success btn-flat btn-sm" onclick="return view_file(<?= $data['id']; ?>);"><i class="fa fa-check"></i> Sudah Ditandatangani, klik untuk melihat</a>

                                                                    <?php endif ?>
                                                                </td>
                                                                <td><?= $data['keterangan']; ?></td>
                                                            </tr>
                                                            <?php $no++; ?>
                                                        <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="3" class="text-secondary">Belum ada surat</td>
                                                            </tr>
                                                        <?php endif ?>
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

<?php $this->load->view('global/confirm_delete'); ?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Tandatangani Surat</h4>
            </div>
            <div class="box-body">
                <?= form_open('#', 'id="form_tte" onsubmit="return tte_ok();"'); ?>
                <input type="hidden" name="id_akp_surat_tte" value="" id="id_akp_surat_tte">
                <div class="form-group">
                    <label for="">Masukkan Passphrase TTE Anda</label>
                    <?= form_password('passphrase', '', 'class="form-control" required'); ?>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-flat" id="tb_tandatangani"><i class="fa fa-edit"></i> Tandatangani</button>
                </div>
                <?= form_close(); ?>
                <iframe style="width: 100%; height: 1000px; border: none" src="#" id="frame_surat"></iframe>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="mdlViewSuratTertandatangani" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Lihat File Surat</h4>
            </div>
            <div class="box-body">
                <iframe style="width: 100%; height: 1000px; border: none" src="#" id="frame_surat_ditandatangani"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    function tandatangani(akp_surat_tte) {
        // console.log(akp_surat_tte);
        $("#id_akp_surat_tte").val(akp_surat_tte);
        $.ajax({
            type: "GET",
            url: '<?= base_url('index.php/kp_tte/detil_surat/'); ?>' + akp_surat_tte,
            success: function(res) {
                $("#frame_surat").attr('src', res.filesurat);
                $("#myModal").modal('show');
            },
            error: function(xhr) {
                alert('Surat tidak temukan');
            }
        });

        return false;
    }

    function view_file(akp_surat_tte) {
        // console.log(akp_surat_tte);
        $("#id_akp_surat_tte").val(akp_surat_tte);
        $.ajax({
            type: "GET",
            url: '<?= base_url('index.php/kp_tte/detil_surat_signed/'); ?>' + akp_surat_tte,
            success: function(res) {
                $("#frame_surat_ditandatangani").attr('src', res.filesurat);
                $("#mdlViewSuratTertandatangani").modal('show');
            },
            error: function(xhr) {
                alert('Surat tidak temukan');
            }
        });

        return false;
    }

    function tte_ok() {
        let data = $("#form_tte").serialize();

        $.ajax({
            type: "POST",
            url: '<?= base_url('index.php/kp_tte/tte_ok'); ?>',
            data: data,
            beforeSend: function() {
                $("#tb_tandatangani").html('<i class="fa fa-spin fa-spinner"></i> Sedang ditandatangani. Mohon menunggu ... ');
                $("#tb_tandatangani").attr('disabled', true);
            },
            success: function(res) {
                $("#tb_tandatangani").html('<i class="fa fa-edit"></i> Tandatangani');
                $("#tb_tandatangani").attr('disabled', false);

                alert(res.message);
                window.open('<?=base_url('index.php/kp_tte');?>', '_self');
            },
            error: function(xhr) {
                $("#tb_tandatangani").html('<i class="fa fa-edit"></i> Tandatangani');
                $("#tb_tandatangani").attr('disabled', false);
            }
        });
        return false;
    }
</script>