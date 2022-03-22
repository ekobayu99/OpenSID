<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * File ini:
 *
 * Controller untuk modul Layanan Surat
 *
 * donjo-app/controllers/Surat.php
 *
 */

/*
 * File ini bagian dari:
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

require('./vendor/html2pdf/vendor/spipu/html2pdf/src/Html2Pdf.php');
use \Spipu\Html2Pdf\Html2Pdf;

class Kp_surat extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('penduduk_model');
		$this->load->model('keluarga_model');
		$this->load->model('surat_model');
		$this->load->model('keluar_model');
		$this->load->model('config_model');
		$this->load->model('referensi_model');
		$this->load->model('penomoran_surat_model');
		$this->load->model('permohonan_surat_model');
		$this->modul_ini = 4;
		$this->sub_modul_ini = 31;
	}

	public function index()
	{

		$data['menu_surat'] = $this->surat_model->list_surat();
		$data['menu_surat2'] = $this->surat_model->list_surat2();
		$data['surat_favorit'] = $this->surat_model->list_surat_fav();

		// Reset untuk surat yang menggunakan session variable
		unset($_SESSION['id_pria']);
		unset($_SESSION['id_wanita']);
		unset($_SESSION['id_ibu']);
		unset($_SESSION['id_bayi']);
		unset($_SESSION['id_saksi1']);
		unset($_SESSION['id_saksi2']);
		unset($_SESSION['id_pelapor']);
		unset($_SESSION['id_diberi_izin']);
		unset($_SESSION['post']);
		unset($_SESSION['id_pemberi_kuasa']);
		unset($_SESSION['id_penerima_kuasa']);
		unset($_SESSION['qrcode']);

		$this->render('kp/surat/format_surat', $data);
	}

	public function panduan()
	{
		$this->sub_modul_ini = 33;

		$this->render('surat/panduan');
	}

	public function form($url = '', $clear = '')
	{
		$data['url'] = $url;
		$data['anchor'] = $this->input->post('anchor');
		if (!empty($_POST['nik']))
		{
			$data['individu'] = $this->surat_model->get_penduduk($_POST['nik']);
			$data['anggota'] = $this->keluarga_model->list_anggota($data['individu']['id_kk']);
		}
		else
		{
			$data['individu'] = NULL;
			$data['anggota'] = NULL;
		}
		$this->get_data_untuk_form($url, $data);
		$this->load->helper('form');

		$data['surat_url'] = rtrim($_SERVER['REQUEST_URI'], "/clear");
		$data['form_action'] = site_url("kp/surat/doc/$url");
		$data['masa_berlaku'] = $this->surat_model->masa_berlaku_surat($url);
		$data['is_dari_permohonan'] = 0;
		$data['id_permohonan'] = 0;

		$this->set_minsidebar(1);
		$this->render("kp/surat/form_surat", $data);
	}

	public function periksa_doc($id, $url)
	{
		// Ganti status menjadi 'Menunggu Tandatangan'
		$this->permohonan_surat_model->proses($id, 2);
		$this->cetak_doc($url);
	}

	public function doc($url = '')
	{
		$this->cetak_doc($url);
	}

	private function cetak_doc($url)
	{
		$format = $this->surat_model->get_surat($url);
		$log_surat['url_surat'] = $format['id'];
		$log_surat['id_pamong'] = $_POST['pamong_id'];
		$log_surat['id_user'] = $_SESSION['user'];
		$log_surat['no_surat'] = $_POST['nomor'];
		$id = $_POST['nik'];
		$keperluan = $_POST['keperluan'];
		$keterangan = $_POST['keterangan'];
		switch ($url)
		{
			case 'surat_ket_kelahiran':
				// surat_ket_kelahiran id-nya ibu atau bayi
				if (!$id) $id = $_SESSION['id_ibu'];
				if (!$id) $id = $_SESSION['id_bayi'];
				break;
			case 'surat_ket_nikah':
				// id-nya calon pasangan pria atau wanita
				if (!$id) $id = $_POST['id_pria'];
				if (!$id) $id = $_POST['id_wanita'];
				break;
			case 'surat_kuasa':
				// id-nya pemberi kuasa atau penerima kuasa
				if (!$id) $id = $_POST['id_pemberi_kuasa'];
				if (!$id) $id = $_POST['id_penerima_kuasa'];
				break;
			default:
				# code...
				break;
		}

		if ($id)
		{
			$log_surat['id_pend'] = $id;
			$nik = $this->db->select('nik')->where('id', $id)->get('tweb_penduduk')
					->row()->nik;
		}
		else
		{
			// Surat untuk non-warga
			$log_surat['nama_non_warga'] = $_POST['nama_non_warga'];
			$log_surat['nik_non_warga'] = $_POST['nik_non_warga'];
			$nik = $log_surat['nik_non_warga'];
		}

		$log_surat['keterangan'] = $keterangan ? $keterangan : $keperluan;
		$nama_surat = $this->keluar_model->nama_surat_arsip($url, $nik, $_POST['nomor']);
		$log_surat['nama_surat'] = $nama_surat;
		if ($format['lampiran'])
		{
			$lampiran = pathinfo($nama_surat, PATHINFO_FILENAME)."_lampiran.pdf";
			$log_surat['lampiran'] = $lampiran;
		}
		$this->keluar_model->log_surat($log_surat);
		$this->surat_model->buat_surat($url, $nama_surat, $lampiran);

		if ($this->input->post('submit_cetak') == 'cetak_pdf')
			$nama_surat = pathinfo($nama_surat, PATHINFO_FILENAME).".pdf";
		else
			$nama_surat = pathinfo($nama_surat, PATHINFO_FILENAME).".rtf";

		if ($lampiran)
		{
			$nama_file = pathinfo($nama_surat, PATHINFO_FILENAME).".zip";
			$berkas_zip = array();
			$berkas_zip[] = LOKASI_ARSIP.$nama_surat;
			$berkas_zip[] = LOKASI_ARSIP.$lampiran;
			# Masukkan semua berkas ke dalam zip
			$berkas_zip = masukkan_zip($berkas_zip);
			# Unduh berkas zip
			header('Content-disposition: attachment; filename='.$nama_file);
			header('Content-type: application/zip');
			header($this->security->get_csrf_token_name().':'.$this->security->get_csrf_hash());
			readfile($berkas_zip);
		}
		else
		{
			header($this->security->get_csrf_token_name().':'.$this->security->get_csrf_hash());
			header("location:".base_url(LOKASI_ARSIP.$nama_surat));
		}
	}

	public function nomor_surat_duplikat()
	{
		$hasil = $this->penomoran_surat_model->nomor_surat_duplikat('log_surat', $_POST['nomor'], $_POST['url']);
		echo $hasil ? 'false' : 'true';
	}

	public function search()
	{
		$cari = $this->input->post('nik');
		if ($cari != '')
			redirect("surat/form/$cari");
		else
			redirect('surat');
	}

	private function get_data_untuk_form($url, &$data)
	{
		$this->session->unset_userdata('qrcode');
		$this->load->model('pamong_model');
		$data['surat_terakhir'] = $this->surat_model->get_last_nosurat_log($url);
		$data['surat'] = $this->surat_model->get_surat($url);
		$data['config'] = $this->config_model->get_data();
		$data['input'] = $this->input->post();
		$data['input']['nomor'] = $data['surat_terakhir']['no_surat_berikutnya'];
		$data['format_nomor_surat'] = $this->penomoran_surat_model->format_penomoran_surat($data);
		$data['lokasi'] = $this->config_model->get_data();
		$data['penduduk'] = $this->surat_model->list_penduduk();
		$data['pamong'] = $this->surat_model->list_pamong();
		$pamong_ttd = $this->pamong_model->get_ttd();
		$pamong_ub = $this->pamong_model->get_ub();
		$data['perempuan'] = $this->surat_model->list_penduduk_perempuan();
		if ($pamong_ttd)
		{
			$str_ttd = ucwords($pamong_ttd['jabatan'].' '.$data['lokasi']['nama_desa']);
			$data['atas_nama'][] = "a.n {$str_ttd}";
			if ($pamong_ub)
				$data['atas_nama'][] = "u.b {$pamong_ub['jabatan']}";
		}
		$data_form = $this->surat_model->get_data_form($url);
		if (is_file($data_form))
			include($data_form);
	}

	public function favorit($id = 0, $k = 0)
	{
		$this->redirect_hak_akses('u',  $_SERVER['HTTP_REFERER']);
		$this->load->model('surat_master_model');
		$this->surat_master_model->favorit($id, $k);
		redirect("kp_surat");
	}

	/*
		Ajax POST data:
		url -- url surat
		nomor -- nomor surat
	*/
	public function format_nomor_surat()
	{
		$data['surat'] = $this->surat_model->get_surat($this->input->post('url'));
		$data['input']['nomor'] = $this->input->post('nomor');
		$format_nomor = $this->penomoran_surat_model->format_penomoran_surat($data);
		echo json_encode($format_nomor);
	}

	/*
		Ajax url query data:
		q -- kata pencarian
		page -- nomor paginasi
	*/
	public function list_penduduk_ajax()
	{
		$cari = $this->input->get('q');
		$page = $this->input->get('page');
		$filter_sex = $this->input->get('filter_sex');
		$filter['sex'] = ($filter_sex == 'perempuan') ? 2 : $filter_sex;
		$penduduk = $this->surat_model->list_penduduk_ajax($cari, $filter, $page);
		echo json_encode($penduduk);
	}

	// list untuk dropdown arsip layanan tampil hanya yg bersurat saja
	public function list_penduduk_bersurat_ajax()
	{
		$cari = $this->input->get('q');
		$page = $this->input->get('page');
		$penduduk = $this->surat_model->list_penduduk_bersurat_ajax($cari,$page);
		echo json_encode($penduduk);
	}


	// edit kulon progo

	public function simpan_data_surat()
	{
		$p = $this->input->post();

		$id = $p['nik'];
		$url = $p['url_surat'];
		$nik = $this->db->select('nik')->where('id', $id)->get('tweb_penduduk')
		->row()->nik;

		$insert_to_table_log_surat = $this->db->insert('log_surat', [
			'id_format_surat'=>$p['id_surat'],
			'id_pend'=>$p['nik'],
			'id_pamong'=>$p['pamong_id'],
			'id_user'=> $_SESSION['user'],
			'tanggal'=> date('Y-m-d H:i:s'),
			'bulan'=> date('m'),
			'tahun'=> date('Y'),
			'no_surat'=> $_POST['nomor'],
			'nama_surat'=> $this->keluar_model->nama_surat_arsip($url, $nik, $_POST['nomor']),
			'lampiran'=>$p['id_surat'],
			'nik_non_warga'=>$p['nik_non_warga'],
			'nama_non_warga'=>$p['nama_non_warga'],
			'keterangan'=>$p['keterangan'],
		]);

		$last_id = $this->db->insert_id();

		// echo $last_id;

		// insert ke tabel akp_log_surat_detil
		$insert_to_akp_log_surat_detil = $this->db->insert('akp_log_surat_detil', [
			'id_log_surat'=>$last_id,
			'detil'=>json_encode($p),
			'is_ttd' => 0,
			'is_dari_layanan_mandiri'=>$p['is_dari_permohonan'],
			'id_permohonan_surat'=>$p['id_permohonan'],
		]);

		$ret = [
			'success'=>true,
			'id_log_surat'=>$last_id,
			'message'=>'Surat berhasil diinput',
		];

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($ret));
	}

	public function log_surat_preview($id_log_surat, $to_pdf=false)
	{
		// get log surat 
		$get_log_surat = $this->db
			->where('id', $id_log_surat)
			->get('log_surat')->row();

		if (!empty($get_log_surat)) {
			$get_dari_tabel_tte = $this->db
				->where('id_log_surat', $id_log_surat)
				->get('akp_surat_tte')->row();

			if (is_file('./desa/upload/dokumen/' . $get_dari_tabel_tte->file_surat)) {
				header("Content-type: application/pdf");
				header("Content-Disposition: inline; filename=File_surat_unsigned_".$id_log_surat.".pdf");
				@readfile('./desa/upload/dokumen/'.$get_dari_tabel_tte->file_surat);
			} else {
				// exit('File surat tidak ditemukan..');

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
					->join('tweb_penduduk_sex', 'tweb_penduduk.sex = tweb_penduduk_sex.id')
					->join('tweb_penduduk_agama', 'tweb_penduduk.agama_id = tweb_penduduk_agama.id')
					->join('tweb_penduduk_kawin', 'tweb_penduduk.status_kawin = tweb_penduduk_kawin.id')
					->join('tweb_penduduk_pendidikan_kk', 'tweb_penduduk.pendidikan_kk_id = tweb_penduduk_pendidikan_kk.id')
					->join('tweb_penduduk_pekerjaan', 'tweb_penduduk.pekerjaan_id = tweb_penduduk_pekerjaan.id')
					->join('tweb_penduduk_warganegara', 'tweb_penduduk.warganegara_id = tweb_penduduk_warganegara.id')
					->join('tweb_wil_clusterdesa', 'tweb_penduduk.id_cluster = tweb_wil_clusterdesa.id', 'left')
					->select('
					log_surat.*,
					tweb_penduduk.nama,
					tweb_penduduk.nik,
					tweb_penduduk.tempatlahir,
					tweb_penduduk.tanggallahir,
					tweb_penduduk.sex,
					tweb_penduduk.id_kk,
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

				$data_kk = $this->db
				->where('tweb_keluarga.id', $data_detil_surat['id_kk'])
				->join('tweb_penduduk', 'tweb_keluarga.nik_kepala = tweb_penduduk.id')
				->join('tweb_wil_clusterdesa', 'tweb_keluarga.id_cluster = tweb_keluarga.id_cluster')
				->select('
					tweb_keluarga.*,
					tweb_penduduk.nama nama_kepala_kk,
					tweb_wil_clusterdesa.dusun alamat_kk
				')
				->get('tweb_keluarga')->row_array();

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
			}
		} else {
			exit('Arsip surat tidak ditemukan..');
		}



	}

	public function get_data_master()
	{
		$this->load->helper('kp_helper');
		
		$get_data_master = curl_get(config_item('api_data_master'));

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($get_data_master));
	}

	public function validasi_penduduk()
	{
		$post = $this->input->post();

		$this->load->helper('kp_helper');
		$pc_tgl_lahir = explode("-", $post['tgl_lhr']);
		$new_tgl_lahir = $pc_tgl_lahir[2]."-".$pc_tgl_lahir[1]."-".$pc_tgl_lahir[0];

		$data_divalidasi = [
			'nik' => $post['nik'],
			'nama_lgkp' => strtoupper($post['nama_lgkp']),
			'alamat' => strtoupper($post['alamat']),
			'jenis_klmin' => strtoupper($post['jenis_klmin']),
			'tmpt_lhr' => strtoupper($post['tmpt_lhr']),
			'tgl_lhr' => $new_tgl_lahir,
			'agama' => strtoupper($post['agama']),
			// 'agama'=> 'ISLAM',
			'pddk_akh' => strtoupper($post['pddk_akh']),
			'jenis_pkrjn' => strtoupper($post['jenis_pkrjn']),

			'user_id' => config_item('dukcapil_api_username'),
			'password' => config_item('dukcapil_api_password'),
			'threshold' => config_item('dukcapil_api_treshold'),
		];

		$cek_penduduk = cek_penduduk($data_divalidasi);

		
		$nik_ok = false;
		$nama_lgkp_ok = false;
		$alamat_ok = false;
		$jenis_klmin_ok = false;
		$tmpt_lhr_ok = false;
		$tgl_lhr_ok = false;
		$agama_ok = false;
		$pddk_akh_ok = false;
		$jenis_pkrjn_ok = false;

		$status_lain = false;
		if (!empty($cek_penduduk['result']['content'][0]['nik'])) {
			$status_nik = $cek_penduduk['result']['content'][0]['nik'];
			if ($post['nik'] == $status_nik) {
				$nik_ok = true;
				$status_lain = $cek_penduduk['result']['content'][0];
				
				if (substr($status_lain['nama_lgkp'], 0, 6) == "Sesuai") {
					$nama_lgkp_ok = true;
				}
			}
		}

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode([
			'success'=>true,
			'status_nik'=>$nik_ok,
			'status_nama_lgkp'=>$nama_lgkp_ok,
			'status_lain'=> $status_lain,
			// 'data_dikirim'=> $data_divalidasi,
		]));

		// echo var_dump($cek_penduduk['result']['content'][0]['nik']);
		// echo json_encode($cek_penduduk->result->content[0]);
	}

	public function simpan_penduduk()
	{
		$post = $this->input->post();
		// echo json_encode($this->session->userdata());
		// exit;

		$data_to_tweb_penduduk = [
			'nik'=>$post['nik'],
			'nama'=>strtoupper($post['nama_lgkp']),
			'alamat_sekarang'=>strtoupper($post['alamat']),
			'sex'=>$post['jenis_klmin'],
			'tempatlahir'=>strtoupper($post['tmpt_lhr']),
			'tanggallahir'=>$post['tgl_lhr'],
			'agama_id'=>$post['agama'],
			'pendidikan_kk_id'=>$post['pddk_akh'],
			'pekerjaan_id'=>$post['jenis_pkrjn'],
			'warganegara_id'=>1,
			'id_cluster'=>0,
			'created_by'=>$this->session->userdata('user'),
		];

		// cek nik sudah ada 
		$cek_nik = $this->db->where('nik', $post['nik'])->get('tweb_penduduk')->row();

		$insert = false;
		if (empty($cek_nik)) {
			$insert = $this->db->insert('tweb_penduduk', $data_to_tweb_penduduk);
		} 

		if ($insert) {
			$ret = [
				'success'=>true,
				'message'=>'Berhasil disimpan',
				'nik'=>$post['nik'],
				'nama_lgkp'=>$post['nama_lgkp'],
				'insert_id'=>$this->db->insert_id(),
			];
		} else {
			$ret = [
				'success' => true,
				'message' => 'Gagal disimpan',
				'nik'=>$cek_nik->nik,
				'nama_lgkp'=> $cek_nik->nama,
				'insert_id'=> $cek_nik->id,
			];
		}


		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($ret));

	}
}
