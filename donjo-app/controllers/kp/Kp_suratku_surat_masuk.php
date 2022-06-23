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

class Kp_suratku_surat_masuk extends Admin_Controller
{

	private $list_session = ['cari', 'tahun', 'bulan', 'jenis', 'nik'];

	public function __construct()
	{
		parent::__construct();
		$this->load->model('keluar_model');
		$this->load->model('surat_model');

		$this->load->helper('download');
		$this->load->helper('form');
		$this->load->model('pamong_model');
		$this->load->model('config_model');
		$this->load->model('kp/surat_masuk_suratku_model');
		$this->modul_ini = 4;
		$this->sub_modul_ini = 334;
	}

	public function index()
	{
		$user_id = intval($this->session->userdata('user'));

		$data['main'] = [];
		$this->render('kp/suratku/surat_masuk', $data);
	}


	public function dashboard()
	{
		$config = $this->config_model->get_data();
		$kode_desa = $config['kode_desa'];

		$username = '003' . $kode_desa;
		$get_dashboard = $this->surat_masuk_suratku_model->get_dashboard($username);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($get_dashboard));
	}

	public function index_ajax($tahun)
	{
		$config = $this->config_model->get_data();
		$kode_desa = $config['kode_desa'];

		if ($tahun == 0) {
			$tahun = date('Y');
		} else {
			$tahun = $tahun;
		}

		$username = '003' . $kode_desa;
		// $username = '003' . $kode_prov . $kode_kab . $kode_kec . $kode_desa;
		$get_surat = $this->surat_masuk_suratku_model->get_list_surat_masuk($username, $tahun);

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($get_surat));
	}

	public function detil_surat($tahun)
	{
		$p = $this->input->post();

		$config = $this->config_model->get_data();
		$kode_desa = $config['kode_desa'];

		$params = [
			'id_surat' => $p['id_surat'],
			'penerima_id_instansi' => $p['penerima_id_instansi'],
			'penerima_id_user' => $p['penerima_id_user']
		];

		if ($tahun == 0) {
			$tahun = date('Y');
		} else {
			$tahun = $tahun;
		}


		$username = '003' . $kode_desa;
		$get_surat = $this->surat_masuk_suratku_model->get_list_surat_masuk_detil($username, $params, $tahun);
		$set_status_baca = $this->surat_masuk_suratku_model->set_status_baca($username, $params, $tahun);



		$get_nomor_surat = $this->penomoran_surat_model->get_surat_terakhir('surat_masuk');
		$get_surat['nomor_surat_preview'] = $get_nomor_surat['no_surat'] + 1;

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($get_surat));
	}

	public function simpan_surat_masuk($tahun)
	{
		$simpan_surat = $this->surat_masuk_suratku_model->insert();

		$p = $this->input->post();

		$config = $this->config_model->get_data();
		$kode_desa = $config['kode_desa'];

		$params = [
			'id_surat' => $p['mdl_detil_surat_id_surat'],
			'penerima_id_instansi' => $p['mdl_detil_surat_penerima_id_instansi'],
			'penerima_id_user' => $p['mdl_detil_surat_penerima_id_user']
		];

		if ($tahun == 0) {
			$tahun = date('Y');
		} else {
			$tahun = $tahun;
		}

		$username = '003' . $kode_desa;
		$set_status_berinomor = $this->surat_masuk_suratku_model->set_status_berinomor($username, $params, $tahun);


		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($simpan_surat));
	}

	
	
}
