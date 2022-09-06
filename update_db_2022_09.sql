ALTER TABLE `pembangunan` ADD `slug` VARCHAR(255) NULL DEFAULT NULL AFTER `anggaran`, ADD `perubahan_anggaran` INT(11) NULL DEFAULT '0' AFTER `slug`, ADD `sumber_biaya_pemerintah` BIGINT(20) NULL DEFAULT '0' AFTER `perubahan_anggaran`, ADD `sumber_biaya_provinsi` BIGINT(20) NOT NULL DEFAULT '0' AFTER `sumber_biaya_pemerintah`, ADD `sumber_biaya_kab_kota` BIGINT(20) NOT NULL DEFAULT '0' AFTER `sumber_biaya_provinsi`, ADD `sumber_biaya_swadaya` BIGINT(20) NOT NULL DEFAULT '0' AFTER `sumber_biaya_kab_kota`, ADD `sumber_biaya_jumlah` BIGINT(20) NOT NULL DEFAULT '0' AFTER `sumber_biaya_swadaya`, ADD `manfaat` VARCHAR(100) NULL DEFAULT NULL AFTER `sumber_biaya_jumlah`, ADD `waktu` INT(11) NOT NULL DEFAULT '0' AFTER `manfaat`, ADD `sifat_proyek` VARCHAR(100) NOT NULL DEFAULT 'BARU' AFTER `waktu`;
ALTER TABLE `pembangunan` ADD UNIQUE(`slug`);

ALTER TABLE `tweb_penduduk` ADD `bpjs_ketenagakerjaan` CHAR(100) NULL DEFAULT NULL AFTER `suku`;

ALTER TABLE `kelompok_master` ADD `tipe` VARCHAR(100) NOT NULL DEFAULT 'kelompok' AFTER `deskripsi`;

CREATE TABLE `ref_penduduk_hamil` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `ref_penduduk_hamil` (`id`, `nama`) VALUES (1, 'Hamil');
INSERT INTO `ref_penduduk_hamil` (`id`, `nama`) VALUES (2, 'Tidak Hamil');