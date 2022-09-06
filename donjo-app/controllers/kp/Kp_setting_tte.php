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

class Kp_setting_tte extends Admin_Controller
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
        $this->load->helper('form');
		$this->modul_ini = 4;
		$this->sub_modul_ini = 333;
	}

	public function index()
	{
		$data['main'] = $this->db
            ->join('tweb_desa_pamong', 'akp_user_pamong.pamong_id = tweb_desa_pamong.pamong_id')
            ->join('user', 'akp_user_pamong.user_id = user.id')
            ->select('
                akp_user_pamong.id,
                tweb_desa_pamong.pamong_nama,
                user.nama,
                user.username
            ')
			->get('akp_user_pamong')->result_array();
			
		$data['list_data'] = [];
		$this->render('kp/surat/setting_tte', $data);
	}

    public function add()
    {   
        $list_user_penandatangan = $this->db 
            ->where('id_grup', 6)
            ->get('user')->result_array();

        
        $data['p_list_user_penandatangan'] = [''=>'-'];
        if (!empty($list_user_penandatangan)) {
            foreach ($list_user_penandatangan as $lup) {
                $idx = $lup['id'];
                $data['p_list_user_penandatangan'][$idx] = $lup['username'];
            }
        }

        $list_pamong = $this->db
            ->get('tweb_desa_pamong')->result_array();

        $data['p_list_pamong'] = ['' => '-'];
        if (!empty($list_pamong)) {
            foreach ($list_pamong as $lup) {
                $idx = $lup['pamong_id'];
                $data['p_list_pamong'][$idx] = $lup['jabatan']." - ". $lup['pamong_nama'];
            }
        }

        $this->render('kp/surat/setting_tte_form', $data);
    }

    public function save_new()
    {
        $p = $this->input->post();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'Username', 'required');
        $this->form_validation->set_rules('pamong_id', 'Pamong', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('info', '<div class="alert alert-danger">'.validation_errors('', '<br/>').'</div>');
            redirect('kp_setting_tte/add');
        } else {

            $cek_sudah_ada = $this->db 
            ->where('user_id', $p['user_id'])
            ->where('pamong_id', $p['pamong_id'])
            ->get('akp_user_pamong')->num_rows();

            if ($cek_sudah_ada < 1) {
                $this->db->insert('akp_user_pamong', [
                    'user_id'=>$p['user_id'],
                    'pamong_id'=>$p['pamong_id'],
                    'esign_username'=>'',
                ]);
            } else {
                $this->session->set_flashdata('info', '<div class="alert alert-danger">Sudah ada</div>');
            }
            
            
            redirect('kp_setting_tte');
        }
    }

    public function edit($id)
    {
        $get_detil_data = $this->db
            ->where('akp_user_pamong.id', $id)
            ->join('tweb_desa_pamong', 'akp_user_pamong.pamong_id = tweb_desa_pamong.pamong_id')
            ->join('user', 'akp_user_pamong.user_id = user.id')
            ->select('
                akp_user_pamong.*,
                user.nama AS nama_user,
                tweb_desa_pamong.pamong_nama
            ')
            ->get('akp_user_pamong')->row();

        $data['edit'] = $get_detil_data;

        $this->render('kp/surat/setting_tte_form_edit', $data);
    }


    public function save_edit()
    {
        $p = $this->input->post();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('id', 'ID', 'required');
        $this->form_validation->set_rules('esign_username', 'Esign Username', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('info', '<div class="alert alert-danger">' . validation_errors('', '<br/>') . '</div>');
            redirect('kp_setting_tte/edit/'.$p['id']);
        } else {

            $this->db
            ->where('id', $p['id'])
            ->update('akp_user_pamong', [
                'esign_username' => $p['esign_username'],
            ]);


            redirect('kp_setting_tte');
        }
    }
}
