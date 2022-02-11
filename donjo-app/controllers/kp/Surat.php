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

class Surat extends Admin_Controller {

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

		$data['surat_url'] = rtrim($_SERVER['REQUEST_URI'], "/clear");
		$data['form_action'] = site_url("kp/surat/doc/$url");
		$data['masa_berlaku'] = $this->surat_model->masa_berlaku_surat($url);

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
		redirect("surat");
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
			// get log surat detil 
			$get_log_surat_detil = $this->db
				->where('id_log_surat', $id_log_surat)
				->get('akp_log_surat_detil')->row();

			$get_detil_format_surat = $this->db
				->where('id', $get_log_surat->id_format_surat)
				->get('tweb_surat_format')->row();

			$nama_surat = $get_detil_format_surat->url_surat;

			$file_format_surat = "./template-surat-kp/" . $nama_surat . "/print_" . $nama_surat . ".php";

			$get_desa = $this->db
				->where('id', 1)
				->get('config')->row_array();

			$desa = $get_desa;


			$data_detil_surat = $this->db
				->where('log_surat.id', $id_log_surat)
				->join('tweb_penduduk', 'log_surat.id_pend = tweb_penduduk.id')	
				->get('log_surat')->row_array();

			$input = $data_detil_surat;
			$data = $data_detil_surat;


			include($file_format_surat);

			if ($to_pdf) {
				$html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'en');

				$html2pdf->writeHTML($get_log_surat_detil->detil);
				$html2pdf->output();
			}

		} else {
			exit('Arsip surat tidak ditemukan..');
		}



	}
}
