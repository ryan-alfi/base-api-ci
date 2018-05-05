<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
		$this->load->model('User_model');
	}

	public function index() {
		echo "Welcome to REST API Family App";
	}

	public function getUsulan($count = false) {
		if ($this->input->get('opdId'))
			$this->where["target_id"] = $this->input->get('opdId');
		if ($this->input->get('tahun'))
			$this->where["tahun_anggaran"] = $this->input->get('tahun');
		$this->where["stage"] = 4;
		$pageNum = $this->input->get('pageNum');
		$this->Tmp_model->setTable('usulan');
		$this->Tmp_model->setWhere($this->where);
		$totalRow = $this->Tmp_model->getCount();
		$usedRow = $pageNum * 100;
		$recordsLeft = $totalRow - $usedRow;
		if ($recordsLeft > 0) {
			$pageCount = floor($recordsLeft / 100);
		} else {
			$pageCount = 0;
		}
		$this->data["pageCount"] = $pageCount;
		if ($pageCount > 0)
			$this->data["nextPage"] = 1;
		else
			$this->data["nextPage"] = 0;
		$this->Tmp_model->setLimit(100);
		$this->Tmp_model->setOffset($usedRow);
		if (!$count)
			$this->data["usulan"] = $this->Tmp_model->getData();
		else {
			$this->data = [];
			$this->data["usulan"] = $this->Tmp_model->getCount();
		}
		echo json_encode($this->data);
	}

	public function getUrusan() {
		$this->Tmp_model->setTable('urusan');
		$this->data["urusan"] = $this->Tmp_model->getData();
		echo json_encode($this->data);
	}

	public function getBidang() {
		$this->Tmp_model->setTable('bidang_urusan');
		$this->data["bidang"] = $this->Tmp_model->getData();
		echo json_encode($this->data);
	}
}
