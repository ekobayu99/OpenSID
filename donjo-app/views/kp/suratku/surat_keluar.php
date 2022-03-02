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
                                            <a href="<?=site_url('kp_suratku_surat_keluar/add');?>" class="btn btn-primary btn-flat btn-sm"><i class="fa fa-plus"></i> Kirim Surat</a>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="box-tools">
                                                <div class="input-group input-group-sm pull-right">
                                                    <input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?= $cari ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("surat_masuk/search") ?>');$('#'+'mainform').submit();}">
                                                    <div class="input-group-btn">
                                                        <button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("surat_masuk/search") ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
                                                    </div>
                                                </div>
                                                <div class="input-group input-group-sm pull-right">
                                                    <?= form_dropdown('tahun', [
                                                        '' => '-Pilih tahun masuk-',
                                                        '2022' => 'Surat Masuk Tahun 2022',
                                                        '2021' => 'Surat Masuk Tahun 2021',
                                                        '2020' => 'Surat Masuk Tahun 2020'
                                                    ], date('Y'), 'id="tahun" onchange="return dt_surat();" class="form-control"'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-sm-12">
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
                                                        <?php if (!empty($main)) {?>
                                                        <?php foreach ($main as $data) : ?>
                                                            <tr>
                                                                <td><?=$no;?></td>
                                                                <td>
                                                                    <a href="#" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-edit"></i> Edit</a>
                                                                    <a href="#" class="btn btn-danger btn-sm btn-flat"><i class="fa fa-times"></i> Hapus</a>
                                                                    <a href="#" class="btn btn-warning btn-sm btn-flat"><i class="fa fa-send"></i> Ke Penandatangan</a>
                                                                </td>
                                                                <td><?=$data['nomor_urut'];?></td>
                                                                <td><?=$data['nomor_surat'];?></td>
                                                                <td><?=$data['kode_surat'];?></td>
                                                                <td><?= tgl_indo_out($data['tanggal_surat']);?></td>
                                                                <td><?=$data['isi_singkat'];?></td>
                                                            </tr>
                                                        
                                                        <?php $no++; ?>
                                                        <?php endforeach ?>
                                                        <?php } else { ?>
                                                            <tr><td colspan="7">data tidak ditemukan</td></tr>
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
            <form action="#" method="post" onsubmit="return simpan_nomor();" id="mdl_detil_surat_form">
                <input type="hidden" name="mdl_detil_surat_id_surat" id="mdl_detil_surat_id_surat">
                <input type="hidden" name="mdl_detil_surat_penerima_id_instansi" id="mdl_detil_surat_penerima_id_instansi">
                <input type="hidden" name="mdl_detil_surat_penerima_id_user" id="mdl_detil_surat_penerima_id_user">
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h4 class='modal-title' id='myModalLabel'><i class='fa fa-search'></i> &nbsp;&nbsp;Detil Surat</h4>
                </div>
                <div class='modal-body' id="mdl_detil_surat_detil">

                </div>
                <div class='modal-footer'>
                    <button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                    <button type="submit" class="btn btn-social btn-flat btn-success btn-sm" id="btn_simpan"><i class='fa fa-check'></i> Simpan Nomor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('global/confirm_delete'); ?>


<script type="text/javascript">
    // dt_surat();

</script>