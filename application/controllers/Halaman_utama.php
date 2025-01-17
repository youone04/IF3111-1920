<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

	class Halaman_utama extends CI_Controller{
		//fungsi menampilkan data dari database
		public function __construct(){
			parent::__construct();
			$this->load->model('Lapor_model');//manggil database
			$this->load->library('form_validation');//validasi form
		}

		public function index(){
			$data['judul1']='Halaman Utama';
			
			//menampilkan data mahsiswa
			// $this->load->model('Mahasiswa_model');//load model ,harus diatas dari filenya
			$data['lapor'] = $this->Lapor_model->getAllData();//file ini berada di "model" dan ini merupakan cara manngilnya
			// $this->load->helper('url'); //mengaktifkan base url,tapi sebaiknya bisa di pindah ke ke autoload pada helper
			
			$this->load->view('templates/header',$data);
			$this->load->view('Lapor/index',$data);
			// $this->load->view('templates/footer');
		}


		//menambahkan data ke database
	   public function InputDataLapor(){

		 $data['judul1']  = 'Form Tambah Laporan';

	   		$this->load->view('templates/header',$data);
			$this->load->view('Lapor/halaman_Laporan');//folder dan file
			$this->load->view('templates/footer');	   	
	   }

	   public function ProsesDataLapor(){
	   	date_default_timezone_set('Asia/Jakarta');
	   	if(isset($_POST['submit'])){
			//input kedatabase
	   	   	if($this->session->userdata('email')==null){
	   	   		$this->session->set_flashdata('anda belum login',"tambah gagal");
	   	   		redirect('Halaman_utama/Login');
	   	   	}else{
	   	   		// $result['pesan'] = '';
	   	   		$lampiran = $_FILES['file_file']['name'];

	   	   		if($lampiran = ''){

	   	   		}else{

	   	   			$config['upload_path']='./lampiran';
	   	   			$config['allowed_types']='JPG|png|pdf|docx|jpg|ppt|pptx|xls|xlsx';
	   	   			
	   	   			$this->load->library('upload',$config);

	   	   			if(!$this->upload->do_upload('file_file')){
	   	   				
						$data2 = [
							"komentar_id" => '',
							"komentar" => $this->input->post('komentar',true),
							"lampiran" => '',
							"waktu" => date('l,d-F-Y  h:i:s'),
							"kategori" => $this->input->post('kategori'),
							"email" =>  $this->session->userdata('email')//yg dilempar gan
						]; 

						$this->Lapor_model->InputContentLaporan($data2);//fungsi mahasiswa,fungsi berada pada controler, dan file Model_mahasiswa

		   	   	
				   		$this->session->set_flashdata('input_laporan',"lapor berhasil");
				   		redirect('Halaman_utama');//dialihkan lagi ke halaman mahasiswa
	   	   			}else{
	   	   				$lampiran = $this->upload->data('file_name');
	   	   			}
	   	   	

	   	   		
				$data2 = [
					"komentar_id" => '',
					"komentar" => $this->input->post('komentar',true),
					"lampiran" => $lampiran,
					"waktu" => date('l,d-F-Y  h:i:s'),
					"kategori" => $this->input->post('kategori'),
					"email" =>  $this->session->userdata('email')//yg dilempar gan
				]; 


	   	   		$this->Lapor_model->InputContentLaporan($data2);//fungsi mahasiswa,fungsi berada pada controler, dan file Model_mahasiswa

		   	   	
		   		$this->session->set_flashdata('input_laporan',"lapor berhasil");
		   		redirect('Halaman_utama');//dialihkan lagi ke halaman mahasiswa

	   	   		}


	   	   	}

	   	   	}
	   		
	   }
	   //Ubah Data Laporan

	   public function UbahDataLaporan($id){

		$data['judul1']  = 'Ubah Laporan';
		$data['lapor'] = $this->Lapor_model->getDataId($id);

	   		$this->load->view('templates/header',$data);
			$this->load->view('Lapor/halaman_Ubah_Laporan');//folder dan file
			$this->load->view('templates/footer');

	   	   	if(isset($_POST['submit'])){
				//ubah kedatabase
		   	   	$this->Lapor_model->UbahContentLaporan();//fungsi mahasiswa,fungsi berada pada controler, dan file Model_mahasiswa
		   		$this->session->set_flashdata('input_laporan',"lapor berhasil");
		   		redirect('Halaman_utama');//dialihkan lagi ke halaman mahasiswa

	   	   	}
	   		
	   	
	   }


	   
	    public function DaftarAkunLaporan(){

	   	$data['judul1']  = 'Daftar Laporan';

	   	$this->form_validation->set_rules('nama','Nama','required');
	   	$this->form_validation->set_rules('email','Email','required|valid_email');
	   	$this->form_validation->set_rules('password','password','required');

	   	if($this->form_validation->run() == FALSE){
	   		//jika gagal
	   		//header diganti menajadi header_daftar/login
	   		$this->load->view('templates/header_daftar_login',$data);
			$this->load->view('Lapor/halaman_registrasi');//folder dan file
			$this->load->view('templates/footer');
	   	}else{

	   		//input kedatabase
	   		// cek email di database apakah sama dengan apa yang diinputkan
	   		$cek = $this->db->query("SELECT * FROM user where email='".$this->input->post('email')."'")->num_rows();

	   		if($cek==1){

	   			$this->session->set_flashdata('email-ada',"daftar ulang");
	    		redirect('Halaman_utama/DaftarAkunLaporan');

	   		}else{

	   		$this->Lapor_model->RegistrasiUser();//fungsi mahasiswa,fungsi berada pada controler, dan file Model_mahasiswa
	   		$this->session->set_flashdata('flas',"daftar");
	   		$this->session->set_flashdata('daftar sukses',"suskses daftar");
	   		redirect('Halaman_utama/Login');//dialihkan lagi ke halaman mahasiswa
	   	

	   		}

	   	}
	
	   	
	   }


	    public function Login(){

	 		$data['judul1']  = 'Login';
	   		//jika gagal
	   		//header diganti menajadi header_daftar/login
	   		$this->load->view('templates/header_daftar_login',$data);
			$this->load->view('Lapor/halaman_login');//folder dan file
			$this->load->view('templates/footer');

	    	$email = $this->input->post('email');
	    	$password = $this->input->post('password');

	    	$user = $this->db->get_where('user',['email' => $email])->row_array();

	    	if(isset($_POST['submit'])){
	    		
	    	if($user==null){
	    		$this->session->set_flashdata('email_tida_ada',"email_salah");
	    		redirect('Halaman_utama/Login');
	    	}
	    	else{

	    		if($password == $user['password'] ){
	    			
	    			

	    			$this->session->set_userdata('email',$email);//menyimpan email untuk dilempar lempar
	    			
	    			$this->session->set_flashdata('login_berhasil',"password benar");
	    			redirect('Halaman_utama/InputDataLapor');

	    		}else{
	    			$this->session->set_flashdata('login_gagal',"password salah");
	    			redirect('Halaman_utama/Login');
	    		}


	    	}//if user ==null
	    		
	    }
	   	
	   }//fungsi

	public function logout(){
		$this->session->sess_destroy();
		redirect('Halaman_utama/login');
	}
	// parameter $id buat nampung id dari url
	public function halaman_selengkapnya($id){
		$data['judul1']  = 'Detail Laporan';
		$data['lapor'] = $this->Lapor_model->getDataId($id);
		//folder = Lapor dan file = halaman_selengkapnya
		$this->load->view('templates/header_daftar_login',$data);
		$this->load->view('Lapor/halaman_selengkapnya',$data);
		$this->load->view('templates/footer');
	}
	// parameter $id buat nampung id dari url
	public function HapusData($id){

		$this->Lapor_model->HapusDataLapor($id);
		$this->session->set_flashdata('hapus berhasil','data hapus ok');
		redirect('Halaman_utama');
	}

 
	}//class



 ?>