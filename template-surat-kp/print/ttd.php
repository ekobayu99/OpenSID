<div style="margin-left: 50%; margin-top: 20pt">
    <?= unpenetration($desa['nama_desa']) ?>, <?= $tanggal_sekarang ?><br>
    <?= unpenetration($data['pilih_atas_nama']) ?><br>
    <span style="color:#999">Ditandatangani secara elektronik oleh : </span><br/>
    <?= unpenetration($data['jabatan']) ?> <?= unpenetration($desa['nama_desa']) ?><br /><br />
    <img src="<?= $alamat_qr_code; ?>"><br /><br />
    <b style="text-transform: uppercase"><?= unpenetration($data['pamong']) ?></b>
</div>