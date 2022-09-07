<div class="content-wrapper">
    <section class="content-header">
        <h1>Detil Surat Keluar</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Detil Surat Keluar</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        Detil Surat Keluar
                    </div>
                    <div class="box-body">

                        <a href="<?= site_url('kp_suratku_surat_keluar'); ?>" class="btn btn-warning btn-flat"><i class="fa fa-arrow-left"></i> Kembali</a>

                        <?php if ($detil_status_kirim->pemeriksa_id == $this->session->userdata('user') && $detil_status_kirim->is_pemeriksa_setuju == 0) : ?>
                            <form style="display: inline;" id="mdl_detil_surat_form" onsubmit="return setujui();" method="post">
                                <input type="hidden" name="mdl_detil_surat_id_surat_keluar" id="mdl_detil_surat_id_surat_keluar" value="<?= $surat_keluar->id; ?>">
                                <button class="btn btn-success btn-flat"><i class="fa fa-check"></i> Setujui</button>
                            </form>

                        <?php endif ?>
                        <br>
                        <br>

                        <div class="row">
                            <div class="col-lg-6">
                                <label for="">File Surat</label>
                                <iframe style="width: 100%; height: 1000px; border: none" src="<?= site_url('kp_suratku_surat_keluar/lihat_file_surat_keluar/' . $surat_keluar->id); ?>"></iframe>
                            </div>

                            <div class="col-lg-6">
                                <h4>Tujuan Surat</h4>
                                <hr>
                                <?php if (!empty($detil_tujuan_surat)) : ?>
                                    <ol style="margin-top: 0px; margin-left: -10px">
                                        <?php foreach ($detil_tujuan_surat as $tujuan) : ?>
                                            <li><?= $tujuan->teks; ?></li>
                                        <?php endforeach ?>
                                    </ol>
                                <?php endif ?>
                                <hr>
                                <h4>Detil Surat</h4>
                                <hr>
                                <div class="form-group">
                                    <label for="">Nomor Urut</label>
                                    <p><?= $surat_keluar->nomor_urut; ?></p>
                                </div>
                                <div class="form-group">
                                    <label for="">Klasifikasi Surat</label>
                                    <p><?= $surat_keluar->kode_surat; ?></p>
                                </div>
                                <div class="form-group">
                                    <label for="">Nomor Surat</label>
                                    <p><?= $surat_keluar->nomor_surat; ?></p>
                                </div>
                                <div class="form-group">
                                    <label for="">Tanggal Surat</label>
                                    <p><?= $surat_keluar->tanggal_surat; ?></p>
                                </div>
                                <div class="form-group">
                                    <label for="">Perihal Surat</label>
                                    <p><?= $surat_keluar->isi_singkat; ?></p>
                                </div>
                                <?php if ($detil_status_kirim->is_kirim == 1) : ?>
                                    <div class="form-group">
                                        <label for="">ID Surat Suratku <small class="text-danger">(untuk pelacakan surat di aplikasi suratku)</small></label>
                                        <p><strong><?= $detil_status_kirim->id_surat_suratku;?></strong></p>
                                    </div>
                                <?php endif ?>
                                <hr>
                                <h4>Detil Verifikasi Surat</h4>
                                <hr>
                                <div class="form-group">
                                    <label for="">Verifikator</label>
                                    <p><?= $detil_status_kirim->nama_pemeriksa; ?></p>
                                </div>
                                <div class="form-group">
                                    <label for="">Verifikator Setuju</label>
                                    <p>
                                        <?php if ($detil_status_kirim->is_pemeriksa_setuju == 1) : ?>
                                            <i class="fa fa-check text-success"></i> Disetujui pada <?= $detil_status_kirim->tgl_setuju; ?>
                                        <?php else : ?>
                                            <i class="fa fa-minus-circle text-danger"></i> Belum disetujui
                                        <?php endif ?>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label for="">Sudah Dikirimkan</label>
                                    <p>
                                        <?php if ($detil_status_kirim->is_kirim == 1) : ?>
                                            <i class="fa fa-check text-success"></i> Dikirim pada <?= $detil_status_kirim->tgl_kirim; ?>
                                        <?php else : ?>
                                            <i class="fa fa-minus-circle text-danger"></i> Belum dikirim
                                        <?php endif ?>
                                    </p>
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


<script type="text/javascript">
    getLabel();

    function getLabel() {
        let isi = $("#opd_tujuan option:selected").text();
        $("#opd_tujuan_nama").val(isi);
    }

    let base_url_app = "<?= base_url(); ?>";

    function setujui() {
        if (confirm('Anda yakin..?')) {
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
        }

        return false;
    }
</script>