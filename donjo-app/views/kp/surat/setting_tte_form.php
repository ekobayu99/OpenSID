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
                        <?=$this->session->flashdata('info');?>
                        <?=form_open(base_url('index.php/kp_setting_tte/save_new'));?>
                        <div class="form-group">
                            <label for="">Username</label>
                            <?= form_dropdown('user_id', $p_list_user_penandatangan, '', 'class="form-control"'); ?>
                        </div>
                        <div class="form-group">
                            <label for="">Nama Pamong</label>
                            <?= form_dropdown('pamong_id', $p_list_pamong, '', 'class="form-control"'); ?>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-flat"> Simpan</button>
                        </div>
                        <?=form_close();?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>