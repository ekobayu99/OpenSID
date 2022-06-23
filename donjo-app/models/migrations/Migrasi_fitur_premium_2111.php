<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2111.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 */

class Migrasi_fitur_premium_2111 extends MY_Model
{
    public function up()
    {
        log_message('error', 'Jalankan ' . get_class($this));
        $hasil = true;

        $hasil = $hasil && $this->migrasi_2021100171($hasil);
        $hasil = $hasil && $this->migrasi_2021101051($hasil);
        $hasil = $hasil && $this->migrasi_2021101572($hasil);
        $hasil = $hasil && $this->migrasi_2021101351($hasil);
        $hasil = $hasil && $this->migrasi_2021101871($hasil);
        $hasil = $hasil && $this->migrasi_2021101872($hasil);
        $hasil = $hasil && $this->migrasi_2021102071($hasil);
        $hasil = $hasil && $this->migrasi_2021102271($hasil);
        $hasil = $hasil && $this->migrasi_2021102371($hasil);
        $hasil = $hasil && $this->migrasi_2021102451($hasil);

        // Hapus dimigrasi selanjutnya
        $hasil = $hasil && $this->migrasi_2021111251($hasil);
        $hasil = $hasil && $this->migrasi_2021111451($hasil);
        $hasil = $hasil && $this->migrasi_2021111551($hasil);
        $hasil = $hasil && $this->migrasi_2021111552($hasil);

        status_sukses($hasil);
        return $hasil;
    }

    protected function migrasi_2021100171($hasil)
    {
        $hasil = $hasil && $this->tambah_setting([
            'key' => 'telegram_token',
            'value' => '',
            'keterangan' => 'Telgram token',
            'kategori' => 'sistem',
        ]);

        $hasil = $hasil && $this->tambah_setting([
            'key' => 'telegram_user_id',
            'value' => '',
            'keterangan' => 'Telgram user id untuk notifikasi ke pengguna',
            'kategori' => 'sistem',
        ]);

        return $hasil;
    }

    protected function migrasi_2021101051($hasil)
    {
        $fields = [
            'kode_pos' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
                'default' => null
            ],
        ];

        $hasil = $hasil && $this->dbforge->modify_column('config', $fields);

        return $hasil;
    }

    protected function migrasi_2021101351($hasil)
    {
        $hasil = $hasil && $this->hapus_indeks('log_keluarga', 'id_kk');
        if (!$this->cek_indeks('log_keluarga', 'id_kk')) {
            $hasil = $hasil && $this->db->query("ALTER TABLE log_keluarga ADD UNIQUE id_kk (id_kk, id_peristiwa, tgl_peristiwa, id_pend)");
        }

        return $hasil;
    }

    protected function migrasi_2021101572($hasil)
    {
        return $hasil && $this->ubah_modul(46, ['url'  => 'info_sistem']);
    }

    protected function migrasi_2021101871($hasil)
    {
        // Sesuaikan tabel covid19_pemudik
    
        $this->db->truncate('ref_status_covid');

        $data = [
            [
                'id' => 1,
                'nama' => 'Kasus Suspek',
            ],
            [
                'id' => 2,
                'nama' => 'Kasus Probable',
            ],
            [
                'id' => 3,
                'nama' => 'Kasus Konfirmasi',
            ],
            [
                'id' => 4,
                'nama' => 'Kontak Erat',
            ],
            [
                'id' => 5,
                'nama' => 'Pelaku Perjalanan',
            ],
            [
                'id' => 6,
                'nama' => 'Discarded',
            ],
            [
                'id' => 7,
                'nama' => 'Selesai Isolasi',
            ],
        ];

        $hasil = $hasil && $this->db->insert_batch('ref_status_covid', $data);
    
        // Ganti ODP & PDP jadi Suspek
        $hasil = $hasil && $this->db
            ->where_in('status_covid', ['ODP', 'PDP'])
            ->update('covid19_pemudik', ['status_covid' => 1]);

        $hasil = $hasil && $this->db
            ->where_in('status_covid', ['ODP', 'PDP'])
            ->update('covid19_pantau', ['status_covid' => 1]);

        // Ganti ODR & OTG jadi Kontak Erat
        $hasil = $hasil && $this->db
            ->where_in('status_covid', ['ODR', 'OTG'])
            ->update('covid19_pemudik', ['status_covid' => 4]);
    
        $hasil = $hasil && $this->db
            ->where_in('status_covid', ['ODR', 'OTG'])
            ->update('covid19_pantau', ['status_covid' => 4]);

        // Ganti POSITIF jadi Kasus konfirmasi
        $hasil = $hasil && $this->db
            ->where_in('status_covid', ['POSITIF'])
            ->update('covid19_pemudik', ['status_covid' => 3]);

        $hasil = $hasil && $this->db
            ->where_in('status_covid', ['POSITIF'])
            ->update('covid19_pantau', ['status_covid' => 3]);

        // Karena di table ref_status_covid sebelumny tdk ada DLL namu di form pilihan ada,
        // Maka DLL dinyatakan sebagai Selesai isolasi.
        $hasil = $hasil && $this->db
            ->where_in('status_covid', ['DLL'])
            ->update('covid19_pemudik', ['status_covid' => 7]);

        $hasil = $hasil && $this->db
            ->where_in('status_covid', ['DLL'])
            ->update('covid19_pantau', ['status_covid' => 7]);

        return $hasil;
    }

    protected function migrasi_2021101872($hasil)
    {
        return $hasil && $this->ubah_modul(220, ['url'  => 'admin_pembangunan']);
    }

    protected function migrasi_2021102071($hasil)
    {
        return $hasil && $this->db->where('link', 'wilayah')->update('menu', ['link' => 'data-wilayah']);
    }

    protected function migrasi_2021102271($hasil)
    {
        $cache_lama = FCPATH . 'cache';
        $cache_desa = DESAPATH . 'cache';
        if (is_dir($cache_lama)) {
            // Paksa supaya error_get_last() menangkap error
            // var_dump or anything else, as this will never be called because of the 0
            set_error_handler('var_dump', 0);
            if (!is_dir($cache_desa)) {
                $hasil = $hasil && rename($cache_lama, $cache_desa);
                if (!$hasil) {
                    log_message('error', print_r(error_get_last(), true));
                }
            } else {
                // Kalau folder desa/cache sudah ada, pindahkan file dari cache lama dan hapus cache lama
                $files = scandir($cache_lama);
                foreach ($files as $fname) {
                    if ($fname != '.' && $fname != '..') {
                        $hasil = $hasil && rename($cache_lama . '/' . $fname, $cache_desa . '/' . $fname);
                        if (! $hasil) {
                            log_message('error', print_r(error_get_last(), true));
                        }
                    }
                }
                $hasil = $hasil && rmdir($cache_lama);
                if (! $hasil) {
                    log_message('error', print_r(error_get_last(), true));
                }
            }
            // Kembalikan error_handler;
            restore_error_handler();
        }
        return $hasil;
    }

    protected function migrasi_2021102371($hasil)
    {
        if (! $this->db->field_exists('referensi', 'analisis_indikator')) {
            $fields = [
                'referensi' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => null
                ]
            ];
            $hasil = $hasil && $this->dbforge->add_column('analisis_indikator', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2021102451($hasil)
    {
        // Sesuaikan struktur kolom no_kk untuk No KK Sementara
        $fields = [
            'no_kk' => [
                'type' => 'VARCHAR',
                'constraint' => 16,
            ]
        ];
        $hasil = $hasil && $this->dbforge->modify_column('tweb_keluarga', $fields);
        $fields = [
            'no_kk' => [
                'type' => 'VARCHAR',
                'constraint' => 16,
                'default' => null
            ]
        ];
        $hasil = $hasil && $this->dbforge->modify_column('log_penduduk', $fields);

        // Ubah No. KK 0 jadi 0[kode-desa-10-digit];
        $list_data = $this->db->select('id, no_kk')->get_where('tweb_keluarga', ['no_kk' => '0'])->result();
        if ($list_data) {
            foreach ($list_data as $data) {
                $nokk_sementara = $this->keluarga_model->nokk_sementara();
                $hasil = $hasil && $this->db->where('id', $data->id)->update('tweb_keluarga', ['no_kk' => $nokk_sementara]);
            }
        }

        $hasil = $hasil && $this->tambah_indeks('tweb_keluarga', 'no_kk');

        return $hasil;
    }

    // Hapus dimigrasi selanjutnya
    protected function migrasi_2021111251($hasil)
    {
        // Ubah default kk_level menjadi null; tadinya 0
        $fields = [
            'kk_level' => [
                'type' => 'TINYINT',
                'constraint' => 2,
                'null' => true,
                'default' => null
            ],
        ];
        $hasil = $hasil && $this->dbforge->modify_column('tweb_penduduk', $fields);

        $hasil = $hasil && $this->db
            ->set('kk_level', null)
            ->where('kk_level', 0)
            ->update('tweb_penduduk');

        // Ubah rentang umur kategori TUA untuk kasus salah pengisian tanggal lahir
        $hasil = $hasil && $this->db
            ->set('sampai', '99999')
            ->where('id', 4)
            ->update('tweb_penduduk_umur');


        // Ubah cara_kb_id yg nilainya tidak valid
        $hasil = $hasil && $this->db
            ->set('cara_kb_id', null)
            ->where_not_in('cara_kb_id', [1, 2, 3, 4, 5, 6, 7, 99])
            ->update('tweb_penduduk');

        return $hasil;
    }

    protected function migrasi_2021111451($hasil)
    {
        // Ubah judul status hubungan dalam keluarga
        return $hasil && $this->db->where('id', 9)->update('tweb_penduduk_hubungan', ['nama' => 'FAMILI LAIN']);
    }

    protected function migrasi_2021111551($hasil)
    {
        // Hapus data analisis_parameter dengan responden 0 untuk tipe pertanyaan 3 dan 4
        $this->load->model('analisis_statistik_jawaban_model');
        return $hasil && $this->analisis_statistik_jawaban_model->hapus_data_kosong();
    }

    protected function migrasi_2021111552($hasil)
    {
        // Tambah lampiran untuk Surat Keterangan Kelahiran
        return $hasil && $this->db->where('url_surat', 'surat_ket_kelahiran')->update('tweb_surat_format', ['lampiran' => 'f-2.01.php']);
    }
}
