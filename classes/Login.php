<?php
require_once '../config.php';
class Login extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;

		parent::__construct();
		ini_set('display_error', 1);
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function index(){
		echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
	}
	public function login(){
		extract($_POST);
		$stmt = $this->conn->prepare("SELECT * from users where username = ? and password = ? ");
		$pw = md5($password);
		$stmt->bind_param('ss',$username,$pw);
		$stmt->execute();
		$qry = $stmt->get_result();
		if($qry->num_rows > 0){
			$res = $qry->fetch_array();
			if($res['status'] != 1){
				return json_encode(array('status'=>'notverified'));
			}
			foreach($res as $k => $v){
				if(!is_numeric($k) && $k != 'password'){
					$this->settings->set_userdata($k,$v);
				}
			}
			
			$this->settings->set_userdata('login_type',1);
			$this->settings->set_userdata('cost_center_id',$res['cost_center_id']);
			$this->settings->set_userdata('year_id',$year_id);
			
			if ($year_id == 6){
				$this->settings->set_userdata('fy','2025-2026');
				$this->settings->set_userdata('from_date','2025-07-01');
				$this->settings->set_userdata('to_date','2026-06-30');
			}
			
			if ($year_id == 5){
				$this->settings->set_userdata('fy','2024-2025');
				$this->settings->set_userdata('from_date','2024-07-01');
				$this->settings->set_userdata('to_date','2025-06-30');
			}
			
			if ($year_id == 4){
				$this->settings->set_userdata('fy','2023-2024');
				$this->settings->set_userdata('from_date','2023-07-01');
				$this->settings->set_userdata('to_date','2024-06-30');
			}
			if ($year_id == 3){
				$this->settings->set_userdata('fy','2022-2023');
				$this->settings->set_userdata('from_date','2022-07-01');
				$this->settings->set_userdata('to_date','2023-06-30');
			}
			if ($year_id == 2){
				$this->settings->set_userdata('fy','2021-2022');
				$this->settings->set_userdata('from_date','2021-07-01');
				$this->settings->set_userdata('to_date','2022-06-30');
			}
			if ($year_id == 1){
				$this->settings->set_userdata('fy','2020-2021');
				$this->settings->set_userdata('from_date','2020-07-01');
				$this->settings->set_userdata('to_date','2021-06-30');
			}
			
			
			
		 return json_encode(array('status'=>'success'));
		}else{
		return json_encode(array('status'=>'incorrect','error'=>$this->conn->error));
		}
	}
	
	public function logout(){
		if($this->settings->sess_des()){
			redirect('admin/login.php');
		}
	}
	function client_login(){
		extract($_POST);
		$stmt = $this->conn->prepare("SELECT *,concat(lastname,', ',firstname,' ',middlename) as fullname from client_list where email = ? and `password` = ? ");
		$pw = md5($password);
		$stmt->bind_param('ss',$email,$pw);
		$stmt->execute();
		$qry = $stmt->get_result();
		if($this->conn->error){
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred while fetching data. Error:". $this->conn->error;
		}else{
		if($qry->num_rows > 0){
			$res = $qry->fetch_array();
			if($res['status'] == 1){
				foreach($res as $k => $v){
					$this->settings->set_userdata($k,$v);
				}
				$this->settings->set_userdata('login_type',2);
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "Your Account has been blocked. Contact the management.";
			}
			
		}else{
		$resp['status'] = 'failed';
		$resp['msg'] = "Invalid email or password.";
		}
		}
		return json_encode($resp);
	}
	public function client_logout(){
		if($this->settings->sess_des()){
			redirect('./');
		}
	}
}
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
	case 'login':
		echo $auth->login();
		break;
	case 'logout':
		echo $auth->logout();
		break;
	case 'client_login':
		echo $auth->client_login();
		break;
	case 'client_logout':
		echo $auth->client_logout();
		break;
	default:
		echo $auth->index();
		break;
}

