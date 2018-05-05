<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

	header('Content-Type: application/json');


class V1 extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	// public function index()
	// {
	// 	$this->load->view('welcome_message');
	// }

	private $where = array();
	private $data = array();

	function __construct()
	{
		parent:: __construct();
		if (!$this->input->server('PHP_AUTH_USER')) {
			header('WWW-Authenticate: Basic realm="My Realm"');
			header('HTTP/1.0 401 Unauthorized');
			die("You're unauthorized to access this site!");
		} else {
			$user = $this->input->server('PHP_AUTH_USER');
			$pass = $this->input->server('PHP_AUTH_PW');
			if ($user != "api.family" and $pass != "MCpa2bLC")
				die("Invalid credentials!");
		}
		$this->load->model('Database', 'Tmp_model', true);
	}

	public function index() {
		echo "Welcome to REST API Family App";
	}

	public function getFamily(){
		$id = $this->input->get('id');
		$this->Tmp_model->setTable('family');
		if ($id != "") {
			$this->Tmp_model->setWhere('id = '. $id);
		}
		$this->data["family"] = $this->Tmp_model->getData();
		echo json_encode($this->data);
	}

	public function addFamily(){
		$data = [];
		$data['name'] = $this->input->post('name');
		$data['phone'] = $this->input->post('phone');
		$data['address'] = $this->input->post('address');
		
		$this->Tmp_model->setTable('family');
		$this->Tmp_model->setValues($data);
		if ($this->Tmp_model->insertData()){
			$meta = [];
			$meta['code'] = '200';
			$meta['messege'] = "Success";
			$this->data['meta'] = $meta;
		}else{
			$meta = [];
			$meta['code'] = '500';
			$meta['messege'] = "Failed";
			$this->data['meta'] = $meta;
		}

		$meta = [];
		$meta['code'] = '200';
		$meta['messege'] = "Success";
		$this->data['meta'] = $meta;
		echo json_encode($this->data);
	}

	public function updateFamily(){
		$data = array();
		$data["name"] = $this->input->post('name');
		$data["phone"] = $this->input->post('phone');
		$data["address"] = $this->input->post('address');
		$this->Tmp_model->setTable('family');
		$this->Tmp_model->setWhere(array(
			'id' => $this->input->post('id')
		));
		$this->Tmp_model->setValues($data);
		if ($this->Tmp_model->updateData()){
			$meta = [];
			$meta['code'] = '200';
			$meta['messege'] = "Success";
			$this->data['meta'] = $meta;
		}else{
			$meta = [];
			$meta['code'] = '500';
			$meta['messege'] = "Failed";
			$this->data['meta'] = $meta;
		}

		echo json_encode($this->data);
	}

	public function deleteFamily(){
		$data = array();
		$this->Tmp_model->setTable('family');
		$this->Tmp_model->setWhere(array(
			'id' => $this->input->post('id')
		));
		$this->Tmp_model->setValues($data);
		if ($this->Tmp_model->deleteData()){
			$meta = [];
			$meta['code'] = '200';
			$meta['messege'] = "Success";
			$this->data['meta'] = $meta;
		}else{
			$meta = [];
			$meta['code'] = '500';
			$meta['messege'] = "Failed";
			$this->data['meta'] = $meta;
		}

		echo json_encode($this->data);
	}
}
