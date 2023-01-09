<?php if (empty($this->input->get('is_manual'))) : ?>
    <div style="margin-left: 60%; margin-top: 20pt">
        <?= unpenetration($desa['nama_desa']) ?>, <?= $tanggal_sekarang ?><br>
        <?= unpenetration($data['pilih_atas_nama']) ?><br>
        <span style="color:#999">Ditandatangani secara elektronik oleh : </span><br />
        <?= unpenetration($data['jabatan']) ?> <?= unpenetration($desa['nama_desa']) ?><br /><br />
        <img src="<?= $data['alamat_qr_code']; ?>"><br /><br />
        <b style="text-transform: uppercase"><?= unpenetration($data['pamong']) ?></b>
    </div>

<?php else : ?>
    <div style="display: inline; float: left; margin-top: 40pt; margin-left: 50pt">
        <img src="<?= $data['alamat_qr_code']; ?>">
    </div>
    <div style="margin-left: 50%; margin-top: 20pt">
        <?= unpenetration($desa['nama_desa']) ?>, <?= $tanggal_sekarang ?><br>
        <?= unpenetration($data['pilih_atas_nama']) ?><br>
        <span>Ditandatangani oleh : </span><br />
        <?= unpenetration($data['jabatan']) ?> <?= unpenetration($desa['nama_desa']) ?><br /><br />
        <br /><br />
        <br /><br />
        <br /><br />
        <b style="text-transform: uppercase"><?= unpenetration($data['pamong']) ?></b>
    </div>

<?php endif ?>