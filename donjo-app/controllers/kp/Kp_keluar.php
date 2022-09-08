<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Surat Keluar
 *
 * donjo-app/controllers/Keluar.php
 *
 */
/*
 *  File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */


// require('./vendor/mikehaertl/phpwkhtmltopdf/src/Pdf.php');
// use \Spipu\Html2Pdf\Html2Pdf;


class Kp_keluar extends Admin_Controller
{

	private $list_session = ['cari', 'tahun', 'bulan', 'jenis', 'nik'];

	public function __construct()
	{
		parent::__construct();
		$this->load->model('kp/keluar_model', 'keluar_model');
		$this->load->model('surat_model');

		$this->load->helper('download');
		$this->load->model('pamong_model');
		$this->load->model('config_model');
		$this->modul_ini = 4;
		$this->sub_modul_ini = 32;
	}

	public function clear()
	{
		$this->session->unset_userdata($this->list_session);
		$this->session->set_userdata('per_page', 20);
		redirect('kp_keluar');
	}

	public function index($p = 1, $o = 0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		foreach ($this->list_session as $list) {
			$data[$list] = $this->session->$list ?: '';
		}

		if ($this->input->post('per_page') !== NULL)
			$this->session->per_page = $this->input->post('per_page');

		if (!isset($this->session->tahun)) $this->session->unset_userdata('bulan');

		$data['per_page'] = $this->session->per_pages;

		$data['paging'] = $this->keluar_model->paging($p, $o);
		$data['main'] = $this->keluar_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
		$data['tahun_surat'] = $this->keluar_model->list_tahun_surat();
		$data['bulan_surat'] = ($this->session->tahun == NULL) ? [] :  $this->keluar_model->list_bulan_surat(); //ambil list bulan dari log
		$data['jenis_surat'] = $this->keluar_model->list_jenis_surat();
		$data['keyword'] = $this->keluar_model->autocomplete();

		$this->render('kp/surat/surat_keluar', $data);
	}

	public function edit_keterangan($id = 0)
	{
		$this->redirect_hak_akses('u',  $_SERVER['HTTP_REFERER']);
		$data['data'] = $this->keluar_model->list_data_keterangan($id);
		$data['form_action'] = site_url("keluar/update_keterangan/$id");
		$this->load->view('surat/ajax_edit_keterangan', $data);
	}

	public function update_keterangan($id = '')
	{
		$this->redirect_hak_akses('u',  $_SERVER['HTTP_REFERER']);
		$data = array('keterangan' => $this->input->post('keterangan'));
		$data = $this->security->xss_clean($data);
		$data = html_escape($data);
		$this->keluar_model->update_keterangan($id, $data);
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function delete($p = 1, $o = 0, $id = '')
	{
		$this->redirect_hak_akses('h', "keluar/index/$p/$o");
		session_error_clear();
		$this->keluar_model->delete($id);
		redirect("keluar/index/$p/$o");
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$this->session->cari = $cari;
		else $this->session->session_unset('cari');
	}

	public function perorangan_clear()
	{
		$this->session->unset_userdata($this->list_session);
		$this->session->per_page = 20;
		redirect('keluar/perorangan');
	}

	public function perorangan($nik = '', $p = 1, $o = 0)
	{
		if ($this->input->post('nik') !== null) {
			$nik = $this->input->post('nik');
		}
		if (!empty($nik)) {
			$data['individu'] = $this->surat_model->get_penduduk($nik);
		} else {
			$data['individu'] = null;
		}

		$data['p'] = $p;
		$data['o'] = $o;

		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $this->input->post('per_page');
		$data['per_page'] = $this->session->per_page;

		$data['paging'] = $this->keluar_model->paging_perorangan($nik, $p, $o);
		$data['main'] = $this->keluar_model->list_data_perorangan($nik, $o, $data['paging']->offset, $data['paging']->per_page);

		$data['form_action'] = site_url("sid_surat_keluar/perorangan/$nik");
		$data['nik']['no'] = $nik;
		$this->render('surat/surat_keluar_perorangan', $data);
	}

	public function graph()
	{
		$data['stat'] = $this->keluar_model->grafik();

		$this->render('surat/surat_keluar_graph', $data);
	}

	public function filter($filter)
	{
		$value = $this->input->post($filter);
		if ($filter == 'tahun') $this->session->unset_userdata('bulan'); //hapus filter bulan
		if ($value != '')
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect('keluar');
	}

	public function cetak_surat_keluar($id)
	{
		$berkas = $this->db->select('nama_surat')->where('id', $id)->get('log_surat')->row();
		ambilBerkas($berkas->nama_surat, 'keluar');
	}

	public function unduh_lampiran($id)
	{
		$berkas = $this->db->select('lampiran')->where('id', $id)->get('log_surat')->row();
		ambilBerkas($berkas->lampiran, 'keluar');
	}

	public function dialog_cetak($aksi = '')
	{
		$data['aksi'] = $aksi;
		$data['pamong'] = $this->pamong_model->list_data();
		$data['pamong_ttd'] = $this->pamong_model->get_ub();
		$data['pamong_ketahui'] = $this->pamong_model->get_ttd();
		$data['form_action'] = site_url("keluar/cetak/$aksi");
		$this->load->view('global/ttd_pamong', $data);
	}

	public function cetak($aksi = '')
	{
		$data['aksi'] = $aksi;
		$data['input'] = $this->input->post();
		$data['config'] = $this->header['desa'];
		$data['pamong_ttd'] = $this->pamong_model->get_data($_POST['pamong_ttd']);
		$data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);
		$data['desa'] = $this->config_model->get_data();
		$data['main'] = $this->keluar_model->list_data();

		//pengaturan data untuk format cetak/ unduh
		$data['file'] = "Data Arsip Layanan Desa ";
		$data['isi'] = "surat/cetak";
		$data['letak_ttd'] = ['2', '2', '3'];

		$this->load->view('global/format_cetak', $data);
	}

	public function ajukan_ke_penandatangan($id_log_surat)
	{
		// get log surat 
		$get_log_surat = $this->db
			->where('id', $id_log_surat)
			->get('log_surat')->row();

		if (!empty($get_log_surat)) {
			// get log surat detil 
			$get_log_surat_detil = $this->db
				->where('id_log_surat', $id_log_surat)
				->get('akp_log_surat_detil')->row();
				

			$log_surat_detil = json_decode($get_log_surat_detil->detil, true);

			$get_detil_format_surat = $this->db
				->where('id', $get_log_surat->id_format_surat)
				->get('tweb_surat_format')->row();

			$nama_surat = $get_detil_format_surat->url_surat;

			$file_format_surat = "./template-surat-kp/" . $nama_surat . "/print_" . $nama_surat . ".php";

			$get_desa = $this->db
				->where('id', 1)
				->get('config')->row_array();

			$desa = $get_desa;

			// echo json_encode($desa);
			// exit;

			$data_detil_surat = $this->db
				->where('log_surat.id', $id_log_surat)
				->join('tweb_penduduk', 'log_surat.id_pend = tweb_penduduk.id')
				->join('tweb_desa_pamong', 'log_surat.id_pamong = tweb_desa_pamong.pamong_id')
				->join('tweb_penduduk_sex','tweb_penduduk.sex = tweb_penduduk_sex.id', 'left')
				->join('tweb_penduduk_agama','tweb_penduduk.agama_id = tweb_penduduk_agama.id', 'left')
				->join('tweb_penduduk_kawin', 'tweb_penduduk.status_kawin = tweb_penduduk_kawin.id', 'left')
				->join('tweb_penduduk_pendidikan_kk','tweb_penduduk.pendidikan_kk_id = tweb_penduduk_pendidikan_kk.id', 'left')
				->join('tweb_penduduk_pekerjaan','tweb_penduduk.pekerjaan_id = tweb_penduduk_pekerjaan.id', 'left')
				->join('tweb_penduduk_warganegara','tweb_penduduk.warganegara_id = tweb_penduduk_warganegara.id', 'left')
				->join('tweb_wil_clusterdesa', 'tweb_penduduk.id_cluster = tweb_wil_clusterdesa.id', 'left')
				->select('
					log_surat.*,
					tweb_penduduk.nama,
					tweb_penduduk.nik,
					tweb_penduduk.tempatlahir,
					tweb_penduduk.tanggallahir,
					tweb_penduduk.sex,
					tweb_desa_pamong.pamong_nama AS pamong,
					tweb_desa_pamong.jabatan,
					tweb_desa_pamong.pamong_nik,
					tweb_penduduk_sex.nama sex,
					tweb_penduduk_agama.nama agama,
					tweb_penduduk_kawin.nama status_kawin,
					tweb_penduduk_pendidikan_kk.nama pendidikan,
					tweb_penduduk_pekerjaan.nama pekerjaan,
					tweb_penduduk_warganegara.nama warganegara,
					tweb_wil_clusterdesa.rt no_rt,
					tweb_wil_clusterdesa.rw no_rw,
					tweb_wil_clusterdesa.dusun alamat_sekarang
				')
				->get('log_surat')->row_array();

			$data = $data_detil_surat;
			$tanggal_sekarang = tgl_indo(date('Y-m-d'));

			// QR Code 
			$pathqr = LOKASI_MEDIA;
			$namaqr1 = "qr_surat_" . $id_log_surat;
			$isi_qr = base_url() . "surat/detil/" . $id_log_surat;
			$logoqr1 = gambar_desa($desa['logo'], false, $file = true);

			$create_qr_code = qrcode_generate($pathqr, $namaqr1, $isi_qr, $logoqr1, 3, "#000000");
			$alamat_qr_code = base_url() . "desa/upload/media/" . $namaqr1 . ".png";

			ob_start();
			include $file_format_surat;
			$output = ob_get_clean();

			$format_surat_to_eksport = $output;

			$post_data_field = [
				"input_teks" => $format_surat_to_eksport,
			];

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, config_item('pdf_converter_uri'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 30);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_field);

			$result = curl_exec($ch);

			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if ($httpcode == 200) {

				$filename = md5(date('YmdHis') . microtime()).".pdf";
				$lokasi_save = "./desa/upload/dokumen/";
				file_put_contents($lokasi_save."/".$filename, $result);

				$is_file_benar = is_file($lokasi_save.$filename);

				if ($is_file_benar) {
					$this->db->trans_begin();			
						$update_is_ttd = $this->db
							->where('id_log_surat', $id_log_surat)
							->update('akp_log_surat_detil', [
								'is_ajukan'=>1,
							]);

						// cek sudah ada di tabel akp_surat_tte 
						$cek_sudah_ada_di_tabel_surat_tte = $this->db
							->where('id_log_surat', $id_log_surat)
							->get('akp_surat_tte')
							->num_rows();

						if (intval($get_log_surat_detil->is_dari_layanan_mandiri) == 1) {
							// update status di tabel permohonan surat
							$this->db
							->where('id', $get_log_surat_detil->id_permohonan_surat)
							->update('permohonan_surat', [
								'status'=>2,
							]);
						}

						if ($cek_sudah_ada_di_tabel_surat_tte < 1) {
							$insert_ke_tabel_ttd = $this->db
								->insert('akp_surat_tte', [
									'id_log_surat'=>$id_log_surat,
									'file_surat'=>$filename,
									'keterangan'=> "Surat ".$get_detil_format_surat->nama.', ID : '.$id_log_surat.', nomor : '. $get_log_surat->no_surat,
									'penandatangan_id'=>$data_detil_surat['id_pamong'],
									'is_ttd'=>0,
									'waktu_ajuan'=>date('Y-m-d H:i:s')
								]);
						} else {
							$update_tabel_ttd = $this->db
								->where('id_log_surat', $id_log_surat)
								->update('akp_surat_tte', [
									'file_surat' => $filename,
								]);
						}

					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
					} else {
						$this->db->trans_commit();
					}
				}

				redirect('kp_keluar');
			} else {
				exit('Pembuatan surat gagal..');
			}
		} else {
			exit('Arsip surat tidak ditemukan..');
		}
	}

	public function kosongkan_tabel_log_surat_dan_relasi()
	{
		$this->db->query("TRUNCATE TABLE akp_log_surat_detil");
		$this->db->query("TRUNCATE TABLE akp_log_tte");
		$this->db->query("TRUNCATE TABLE akp_surat_tte");
		$this->db->query("TRUNCATE TABLE akp_surat_tte");
		$this->db->query("TRUNCATE TABLE log_surat");
		$this->db->query("TRUNCATE TABLE permohonan_surat");
		redirect('kp_keluar');
	}
}
