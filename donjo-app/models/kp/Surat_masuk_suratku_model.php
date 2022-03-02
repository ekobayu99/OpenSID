<?php 
class Surat_masuk_suratku_model extends CI_Model {
    // Konfigurasi untuk library 'upload'
    protected $uploadConfig = array();

    public function __construct() {
        parent::__construct();
        $this->uri_suratku = config_item('suratku_base_uri');
    }

    public function get_dashboard($username) {
        
        // set post fields
        $post = [
            'username' => $username,
            'password' => $username,
        ];

        $ch = curl_init($this->uri_suratku.'get_dashboard');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpcode != 200) {
            return false;
        } else {
            return json_decode($response, true);
        }
        return false;
    }

    public function get_list_surat_masuk($username, $tahun="") {
        // set post fields
        $post = [
            'username' => $username,
            'password' => $username,
        ];

        $url = '';
        if ($tahun == "2022") {
            $url = $this->uri_suratku.'index.php/surat_desa/get_list_surat';
        } else if ($tahun == "2021") {
            $url = $this->uri_suratku.'2021/index.php/surat_desa/get_list_surat';
        } else if ($tahun == "2020") {
            $url = $this->uri_suratku.'2020/index.php/surat_desa/get_list_surat';
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpcode != 200) {
            return $response;
        } else {
            return json_decode($response, true);
        }
    }

    public function get_list_surat_masuk_detil($username, $params, $tahun="") {
        // set post fields
        $post = [
            'username' => $username,
            'password' => $username,
            'id_surat'=>$params['id_surat'],
            'penerima_id_instansi'=>$params['penerima_id_instansi'],
            'penerima_id_user'=>$params['penerima_id_user'],
        ];
        
        if ($tahun == "2022" || empty($tahun)) {
            $ch = curl_init($this->uri_suratku.'index.php/surat_desa/get_detil_surat');
        } else if ($tahun == "2021" || empty($tahun)) {
            $ch = curl_init($this->uri_suratku.'2021/index.php/surat_desa/get_detil_surat');
        } else if ($tahun == "2020") {
            $ch = curl_init($this->uri_suratku.'2020/index.php/surat_desa/get_detil_surat');
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpcode != 200) {
            return false;
        } else {
            return json_decode($response, true);
        }
    }

    public function set_status_baca($username, $params, $tahun="") {
        // set post fields
        $post = [
            'username' => $username,
            'password' => $username,
            'id_surat'=>$params['id_surat'],
            'penerima_id_instansi'=>$params['penerima_id_instansi'],
            'penerima_id_user'=>$params['penerima_id_user'],
        ];
        
        if ($tahun == "2022" || empty($tahun)) {
            $ch = curl_init($this->uri_suratku.'index.php/surat_desa/set_status_baca');
        } else if ($tahun == "2021" || empty($tahun)) {
            $ch = curl_init($this->uri_suratku.'2021/index.php/surat_desa/set_status_baca');
        } else if ($tahun == "2020") {
            $ch = curl_init($this->uri_suratku.'2020/index.php/surat_desa/set_status_baca');
        }

        // $ch = curl_init($this->uri_suratku.'set_status_baca');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpcode != 200) {
            return $response;
        } else {
            return json_decode($response, true);
        }
    }

    public function set_status_berinomor($username, $params, $tahun="") {
        // set post fields
        $post = [
            'username' => $username,
            'password' => $username,
            'id_surat'=>$params['id_surat'],
            'penerima_id_instansi'=>$params['penerima_id_instansi'],
            'penerima_id_user'=>$params['penerima_id_user'],
        ];
        
        
        if ($tahun == "2022" || empty($tahun)) {
            $ch = curl_init($this->uri_suratku.'index.php/surat_desa/set_status_berinomor');
        } else if ($tahun == "2021" || empty($tahun)) {
            $ch = curl_init($this->uri_suratku.'2021/index.php/surat_desa/set_status_berinomor');
        } else if ($tahun == "2020") {
            $ch = curl_init($this->uri_suratku.'2020/index.php/surat_desa/set_status_berinomor');
        }

        // $ch = curl_init($this->uri_suratku.'set_status_berinomor');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpcode != 200) {
            return false;
        } else {
            return json_decode($response, true);
        }
    }

    public function get_list_opd($username, $tahun = "")
    {
        // set post fields
        $post = [
            'username' => $username,
            'password' => $username,
        ];

        if ($tahun == "2022" || empty($tahun)) {
            $ch = curl_init($this->uri_suratku . 'index.php/surat_desa/get_list_opd');
        } else if ($tahun == "2021" || empty($tahun)) {
            $ch = curl_init($this->uri_suratku . '2021/index.php/surat_desa/get_list_opd');
        } else if ($tahun == "2020") {
            $ch = curl_init($this->uri_suratku . '2020/index.php/surat_desa/get_list_opd');
        }

        // $ch = curl_init($this->uri_suratku.'set_status_berinomor');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode != 200) {
            return false;
        } else {
            return json_decode($response, true);
        }
    }


    public function insert()
    {
        // Ambil semua data dari var. global $_POST
        $data = $this->input->post(NULL);
        unset($data['mdl_detil_surat_id_surat']);
        unset($data['mdl_detil_surat_penerima_id_instansi']);
        unset($data['mdl_detil_surat_penerima_id_user']);

        // file_put_contents("Tmpfile.zip", fopen("http://someurl/file.zip", 'r'));

        // Berkas lampiran
        // $data['berkas_scan'] = ;

        // penerapan transcation karena insert ke 2 tabel
        $this->db->trans_start();

        // $indikatorSukses = is_null($uploadError) && $this->db->insert('surat_masuk', $data);
        $indikatorSukses = $this->db->insert('surat_masuk', $data);

        $insert_id = $this->db->insert_id();

        // insert ke tabel disposisi surat masuk
        // $this->insert_disposisi_surat_masuk($insert_id, $jabatan);

        // transaction selesai
        $this->db->trans_complete();

        // set status sudah diberi nomor..

        $ret = [
            'success' => $indikatorSukses,
            'message' => "Surat berhasil disimpan", 
        ];
        return $ret;

        // Set session berdasarkan hasil operasi
        // $_SESSION['success'] = $indikatorSukses ? 1 : -1;
        // $_SESSION['error_msg'] = $_SESSION['success'] === 1 ? NULL : ' -> '.$uploadError;
    }
}
