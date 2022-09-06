<div class="content-wrapper">
    <section class="content-header">
        <h1>Setting TTE</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Setting TTE</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        Setting TTE
                    </div>
                    <div class="box-body">
                        <a href="<?= base_url('index.php/kp_setting_tte/add'); ?>" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Tambahkan</a>
                        <a href="<?= base_url('index.php/man_user'); ?>" class="btn btn-primary btn-flat" target="_blank"><i class="fa fa-users"></i> Tambahkan User</a>

                        <?= $this->session->flashdata('info'); ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered dataTable table-striped table-hover">
                                                    <thead class="bg-gray disabled color-palette">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Aksi</th>
                                                            <th>Nama Pamong</th>
                                                            <th>Username</th>
                                                            <th>Nama Username</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($main as $data) : ?>
                                                            <tr>
                                                                <td><?= $data['id'] ?></td>
                                                                <td>
                                                                    <a href="<?=base_url('index.php/kp_setting_tte/edit/'.$data['id']);?>" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-edit"></i> Edit Esign Username</a>
                                                                    <a href="<?=base_url('index.php/kp_setting_tte/hapus/'.$data['id']);?>" class="btn btn-danger btn-sm btn-flat" onclick="return confirm('Anda yakin..?');"><i class="fa fa-times"></i> Hapus</a>

                                                                </td>
                                                                <td><?= $data['pamong_nama'] ?></td>
                                                                <td><?= $data['username'] ?></td>
                                                                <td><?= $data['nama'] ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
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