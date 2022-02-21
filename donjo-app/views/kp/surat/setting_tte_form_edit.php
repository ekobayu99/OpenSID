<div class="content-wrapper">
    <section class="content-header">
        <h1>Setting TTE</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Edit Setting TTE</li>
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
                        <?= $this->session->flashdata('info'); ?>
                        <?= form_open(base_url('index.php/kp_setting_tte/save_edit')); ?>
                        <?=form_hidden('id', $edit->id);?>
                        <div class="form-group">
                            <label for="">Username</label><br />
                            <b><?= $edit->nama_user; ?></b>
                        </div>
                        <div class="form-group">
                            <label for="">Nama Pamong</label><br />
                            <b><?= $edit->pamong_nama; ?></b>
                        </div>
                        <div class="form-group">
                            <label for="">Esign Username</label>
                            <?=form_input('esign_username', $edit->esign_username, 'class="form-control" required'); ?>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-flat"> Simpan</button>
                            <a href="<?=base_url('index.php/kp_setting_tte');?>" class="btn btn-warning btn-flat">Batal</a>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>