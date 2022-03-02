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

class Kp_suratku_surat_keluar extends Admin_Controller
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
		$this->load->model('kp/surat_masuk_suratku_model', 'suratku_model');
		$this->load->model('klasifikasi_model');
		$this->load->model('surat_keluar_model');
		$this->modul_ini = 4;
		$this->sub_modul_ini = 335;
	}

	public function index()
	{
		$user_id = intval($this->session->userdata('user'));

			
		$data['main'] = [];
		$this->render('kp/suratku/surat_keluar', $data);
	}

	public function add()
	{
		$config = $this->config_model->get_data();
		$kode_desa = $config['kode_desa'];
		$username = '003' . $kode_desa;
	
		$get_list_opd = $this->suratku_model->get_list_opd($username, 2022);
		$get_klasifikasi_surat = $this->klasifikasi_model->list_kode();


		// $get_list_opd = json_decode($get_list_opd, true);

		// echo var_dump($get_list_opd);
		// exit;

		$last_surat = $this->penomoran_surat_model->get_surat_terakhir('surat_keluar');
		$data['nomor_urut'] = $last_surat['no_surat'] + 1;

		$p_list_opd = [];
		$p_list_klasifikasi = [''=>'-'];
		if (!empty($get_list_opd['data'])) {
			foreach ($get_list_opd['data'] as $opd) {
				$username_pimpinan = $opd['nip_pimpinan_asli'];
				if (empty($opd['nip_pimpinan_asli'])) {
					$username_pimpinan = $opd['nip_plt'];
				}
				$idx = $opd['instansi_id']."-".$username_pimpinan;
				$p_list_opd[$idx] = $opd['instansi_nama'];
			}
		}

		if (!empty($get_klasifikasi_surat)) {
			foreach ($get_klasifikasi_surat as $klasifikasi) {
				$idx = $klasifikasi['kode'];
				$p_list_klasifikasi[$idx] = $klasifikasi['kode']." - ".$klasifikasi['nama'];
			}
		}

		$data['main'] = [];
		$data['p_list_opd'] = $p_list_opd;
		$data['p_list_klasifikasi'] = $p_list_klasifikasi;

		$this->render('kp/suratku/surat_keluar_form', $data);
	}

	public function insert()
	{
		// $this->redirect_hak_akses('u');
		// $this->surat_keluar_model->insert();

		echo var_dump($_FILES);



		// redirect('surat_keluar');
	}

	
}
