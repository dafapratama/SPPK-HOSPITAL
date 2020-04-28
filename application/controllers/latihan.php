
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class latihan extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('latihan_model');
		$this->load->library('session');
	}

	public function index(){
		if ($this->latihan_model->logged_id()) {
			$data['pos']   = $this->latihan_model->hitung("positif");
			$data['odp']   = $this->latihan_model->hitung("odp");
			$data['sembuh']   = $this->latihan_model->hitung("sembuh");
			$data['meninggal']   = $this->latihan_model->hitung("meninggal");
			$data["list_sharing"] = $this->latihan_model->view_all_data('sharing');
			$this->load->view('home', $data);
		} else {
			redirect('latihan/logout');
		}
	}

	public function home(){
		if ($this->latihan_model->logged_id()) {
			$data['pos']   = $this->latihan_model->hitung("positif");
			$data['odp']   = $this->latihan_model->hitung("odp");
			$data['sembuh']   = $this->latihan_model->hitung("sembuh");
			$data['meninggal']   = $this->latihan_model->hitung("meninggal");
			$data["list_sharing"] = $this->latihan_model->view_all_data('sharing');
			$this->load->view('home', $data);	
		} else {
			redirect('latihan/logout');
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		$this->session->set_flashdata('log', 'ok');
        redirect('login\index');
	}

	public function master_data_sharing()
	{
		if ($this->latihan_model->logged_id()) {
			$data["list_sharing"] = $this->latihan_model->view_all_data('sharing');
			$this->load->view('latihan_view_sharing', $data);
		} else {
			redirect('latihan/logout');
		}
	}
	public function tambah_data_sharing()
	{
		$this->load->view('tambah_data_sharing');
	}
	public function tambah_sharing()
	{
		$config['upload_path']          = './image-sharing/';
		$config['allowed_types']        = 'png|jpg|gif|jpeg';
		$config['max_size']             = 2000000;
		$config['max_width']            = 40000;
		$config['max_height']           = 40000;
		
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('userfile')) {
			$file = 'no-image.jpg';
		} else {
			$upload_data = $this->upload->data();
			$file = $upload_data['file_name'];
		}
		$id				= (trim(html_escape($this->input->post('id'))));
		$nama 			= (trim(html_escape($this->input->post('nama'))));
		$pesan 			= (trim(html_escape($this->input->post('pesan'))));
		$tanggal 		= (trim(html_escape($this->input->post('tanggal'))));

		$this->latihan_model->tambahbarusharing($id, $nama, $file, $tanggal, $pesan);
		redirect('latihan/master_data_sharing');
	}

	public function arah_edit_data_sharing()
	{
		$id = $this->uri->segment(3);
		$data['data_edit'] = $this->latihan_model->view_per_data('sharing', 'id', $id);

		$this->load->view('edit_data_sharing', $data);
	}
	public function edit_sharing()
	{
		$config['upload_path']          = './image-sharing/';
		$config['allowed_types']        = 'png|jpg|gif|jpeg';
		$config['max_size']             = 2000000;
		$config['max_width']            = 40000;
		$config['max_height']           = 40000;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('userfile')) {
			$file = $this->input->post('nama_file');
		} else {
			$upload_data = $this->upload->data();
			$file = $upload_data['file_name'];
		}
		$id				= (trim(html_escape($this->input->post('id'))));
		$nama 			= (trim(html_escape($this->input->post('nama'))));
		$tanggal 		= (trim(html_escape($this->input->post('tanggal'))));
		$pesan 			= (trim(html_escape($this->input->post('pesan'))));

		$this->latihan_model->editsharing($id, $nama, $file, $tanggal, $pesan);
		redirect("latihan/master_data_sharing");
	}
	public function hapus_sharing()
	{
		$id = $this->uri->segment(3);
		$this->latihan_model->hapus_sharing('sharing', 'id', $id);
		redirect('latihan/master_data_sharing');
	}
	public function tambah_comment()
	{
		$id				= (trim(html_escape($this->input->post('id'))));
		$nama 			= (trim(html_escape($this->input->post('nama'))));
		$tanggal 		= (trim(html_escape($this->input->post('tanggal'))));
		$comment 		= (trim(html_escape($this->input->post('comment'))));
		$this->latihan_model->tambahbarucomment($id, $nama, $tanggal, $comment);
		redirect('latihan');
	}
}
?>