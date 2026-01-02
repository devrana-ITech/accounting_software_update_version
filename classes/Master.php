<?php
require_once('../config.php');

Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_group(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `group_list` set {$data} ";
		}else{
			$sql = "UPDATE `group_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `group_list` where `name` = '{$name}' and delete_flag = 0 ".($id > 0 ? " and id != '{$id}'" : ""));
		if($check->num_rows > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Account's Group Name already exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$gid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Account's Group has successfully added.";
				else
					$resp['msg'] = " Account's Group details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	
	function save_pkg(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `pkg` set {$data} ";
			$check = $this->conn->query("SELECT * FROM `pkg` where `pack_name` = '{$pack_name}'");
			if($check->num_rows > 0){
				$resp['status'] = 'failed';
				$resp['msg'] = " Package Name already exists.";
			}
		}else{
			$sql = "UPDATE `pkg` set {$data} where id = '{$id}' ";
		}
/*		$check = $this->conn->query("SELECT * FROM `pkg` where `pack_name` = '{$pack_name}'");
		if($check->num_rows > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Package Name already exists.";
		}else{ */
			$save = $this->conn->query($sql);
			if($save){
				$gid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Package has successfully added.";
				else
					$resp['msg'] = " Package has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
/*		}	*/
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_pkg(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `pkg` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Account's Group has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	
	function delete_group(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `group_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Account's Group has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_account(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `account_list` set {$data} ";
		}else{
			$sql = "UPDATE `account_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `account_list` where `name` ='{$name}' and delete_flag = 0 ".($id > 0 ? " and id != '{$id}' " : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Account's Name already exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Account has successfully added.";
				else
					$resp['msg'] = " Account has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
			if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		}
		return json_encode($resp);
	}
	
	function save_aid(){
		$_POST['year_id'] = $this->settings->userdata('year_id');
		$_POST['cost_center_id'] = $this->settings->userdata('cost_center_id');
		$yearid = $_POST['year_id'];
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `pr_aid` set {$data} ";
		}else{
			$sql = "UPDATE `pr_aid` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `pr_aid` where `account_id` ='{$account_id}' and year_id = '$yearid' and delete_flag = 0 ".($id > 0 ? " and id != '{$id}' " : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Aid already exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Aid has successfully added.";
				else
					$resp['msg'] = " Aid has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
			if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		}
		return json_encode($resp);
	}
	function save_payee(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `payee` set {$data} ";
		}else{
			$sql = "UPDATE `payee` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `payee` where `payee_name` ='{$payee_name}'".($id > 0 ? " and id != '{$id}' " : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Payee info already exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Payee Info has successfully added.";
				else
					$resp['msg'] = " Payee Info has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
			if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		}
		return json_encode($resp);
	}
	
	
	function save_cashforecast(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `cashforecast` set {$data} ";
		}else{
			$sql = "UPDATE `cashforecast` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Cash Forecast has successfully added.";
				else
					$resp['msg'] = " Cash Forecast has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
			if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	
	function save_plannedfund(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `pl_fund` set {$data} ";
		}else{
			$sql = "UPDATE `pl_fund` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Planned Fund has successfully added.";
				else
					$resp['msg'] = " Planned Fund has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
			if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}



	function save_goods(){
    extract($_POST);
    $data = "";

    foreach($_POST as $k => $v){
        // if($k != 'id'){
        //     $v = $this->conn->real_escape_string($v);
        //     if(!empty($data)) $data .= ",";
        //     $data .= "`{$k}` = '{$v}'";
        // }

		if(!in_array($k, array('id')) && !is_numeric($k)){
			if(empty($data)){
				$data .="$k = '$v'";
			}else{
				$data .= ", $k = '$v'";
			}
		}
    }

    if(empty($id)){
        $sql = "INSERT INTO `goods` SET {$data}";
    }else{
        $sql = "UPDATE `goods` SET {$data} WHERE id = '{$id}'";
    }

    $save = $this->conn->query($sql);
    if($save){
        return 1;
    }
    return 0;
}


	// function delete_goodsss(){
	// 	extract($_POST);
	// 	$id = isset($_POST['id']);
	// 	$delete = $this->conn->query("DELETE FROM `goods` where id =" .$id);
	// 	if($delete){
	// 		return 1;
	// 	}
	// 	return 0;
	// }

// 	function delete_goodss(){
//     if(!isset($_POST['id'])) return 0;

//     $stmt = $this->conn->prepare("DELETE FROM goods WHERE id = ?");
//     $stmt->bind_param("i", $_POST['id']);
//     $stmt->execute();

//     if($stmt->affected_rows > 0){
//         return 1;
//     }
//     return 0;
// }

	function delete_goods(){
    if(!isset($_POST['id'])) return 0;

    $id = $this->conn->real_escape_string($_POST['id']);

    $delete = $this->conn->query("DELETE FROM `goods` WHERE id = '{$id}'");

    if($delete){
        return 1;
    }
    return 0;
}


function save_services(){
	extract($_POST);
	$data = "";

	    foreach($_POST as $k => $v){
		if(!in_array($k, array('id')) && !is_numeric($k)){
			if(empty($data)){
				$data .="$k = '$v'";
			}else{
				$data .= ", $k = '$v'";
			}
		}
    }

	if(empty($id)){
		$sql = "INSERT INTO `services` set {$data}";
	}else{
		$sql = "UPDATE `services` set {$data} where id = '{$id}'";
	}


	$save = $this->conn->query($sql);

	if($save){
		return 1;
	}
		return 0;
}


function delete_services(){
	if(!isset($_POST['id'])) return 0;

	$id = $this->conn->real_escape_string($_POST['id']);

	$delete = $this->conn->query("DELETE FROM `services` where id = '{$id}'");

	if($delete){
		return 1;
	}
		return 0;
}








	
	function save_dpp(){
		$_POST['year_id'] = $this->settings->userdata('year_id');
		$_POST['cost_center_id'] = $this->settings->userdata('cost_center_id');
		$yearid = $_POST['year_id'];
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `dpp` set {$data} ";
		}else{
			$sql = "UPDATE `dpp` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `dpp` where `account_id` ='{$account_id}' and year_id = '$yearid' and delete_flag = 0 ".($id > 0 ? " and id != '{$id}' " : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " DPP already exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " DPP has successfully added.";
				else
					$resp['msg'] = " DPP has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
			if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		}
		return json_encode($resp);
	}
	
	
	function delete_account(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `account_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Account has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	
	function delete_aid(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `pr_aid` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Aid has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function delete_cashforecast(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `cashforecast` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Cash Forcast has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function delete_plannedfund(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `pl_fund` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Planned Fund has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function delete_payee(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `payee` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Payee info has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	
	function save_journal(){
		if(empty($_POST['id'])){
			$prefix = date("Ym-");
			$code = sprintf("%'.05d",1);
			while(true){
				$check = $this->conn->query("SELECT * FROM `journal_entries` where `code` = '{$prefix}{$code}' ")->num_rows;
				if($check > 0){
					$code = sprintf("%'.05d",ceil($code) + 1);
				}else{
					break;
				}
			}
			$_POST['code'] = $prefix.$code;
			$_POST['user_id'] = $this->settings->userdata('id');
			$_POST['year_id'] = $this->settings->userdata('year_id');
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))  && !is_array($_POST[$k])){
				if(!is_numeric($v) && !is_null($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				if(!is_null($v))
				$data .= " `{$k}`='{$v}' ";
				else
				$data .= " `{$k}`= NULL ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `journal_entries` set {$data} ";
		}else{
			$sql = "UPDATE `journal_entries` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$jid = !empty($id) ? $id : $this->conn->insert_id;
			$data = "";
			$this->conn->query("DELETE FROM `journal_items` where journal_id = '{$jid}'");
			foreach($account_id as $k=>$v){
				if(!empty($data)) $data .=", ";
				$data .= "('{$jid}','{$v}','{$group_id[$k]}','{$amount[$k]}')";
			}
			if(!empty($data)){
				$sql = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES {$data}";
				$save2 = $this->conn->query($sql);
				if($save2){
					$resp['status'] = 'success';
					if(empty($id)){
						$resp['msg'] = " Journal Entry has successfully added.";
					}else
						$resp['msg'] = " Journal Entry has been updated successfully.";
				}else{
					$resp['status'] = 'failed';
					if(empty($id)){
						$resp['msg'] = " Journal Entry has failed to save.";
						$this->conn->query("DELETE FROM `journal_entries` where id = '{$jid}'");
					}else
						$resp['msg'] = " Journal Entry has failed to update.";
					$resp['error'] = $this->conn->error;
				}
			}else{
				$resp['status'] = 'failed';
				if(empty($id)){
					$resp['msg'] = " Journal Entry has failed to save.";
					$this->conn->query("DELETE FROM `journal_entries` where id = '{$jid}'");
				}else
					$resp['msg'] = " Journal Entry has failed to update.";
				$resp['error'] = "Journal Items is empty";
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	
	
	
	////////////////////////////////////////
	
	function save_journal_3(){
		
		/*$logFile = __DIR__ . '/journal_files_log.txt';
		file_put_contents($logFile, "Starting file deletion process...\n", FILE_APPEND); */
		
		
		if(empty($_POST['id'])){
			$prefix = date("Ym-");
			$code = sprintf("%'.05d",1);
			
			 
			while(true){
				$check = $this->conn->query("SELECT * FROM `journal_entries` where `code` = '{$prefix}{$code}' ")->num_rows;
				if($check > 0){
					$code = sprintf("%'.05d",ceil($code) + 1);
				}else{
					break;
				}
			}
			
			$code1 = sprintf("%'.05d",ceil($code) + 1);
			$code2 = sprintf("%'.05d",ceil($code1) + 1);
			
			$_POST['code'] = $prefix.$code;
			$_POST['user_id'] = $this->settings->userdata('id');
			$_POST['year_id'] = $this->settings->userdata('year_id');
	
			
		}
		extract($_POST);
		$data = "";
		
		$amt_vatitsc = 0;
		$amt_vat = 0;
		$amt_it = 0;
		$amt_sc = 0;
		$amt_vatit = 0;
		$amt_itsc = 0;
		$amt_vatsc = 0;
		
		$flag_sd = 0;
		$amt_sd = 0;
		$flag_com = 0;
		$amt_com = 0;
		
				
		$flag_it = 0;
		$flag_vat = 0;
		$flag_sc = 0;
		
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))  && !is_array($_POST[$k])){
				if(!is_numeric($v) && !is_null($v)){
					$v = $this->conn->real_escape_string($v);
				}
				if(!empty($data)) $data .=",";
				
				if(!is_null($v))
				{
					if(($k === 'vat_deduction') && ($v != 0)){
						$flag_vat = 1;
						$amt_vat = $v;
					}
					if(($k === 'it_deduction') && ($v != 0)){
						$flag_it = 1;
						$amt_it = $v;
					}
					if(($k === 'sc_deduction') && ($v != 0)){
						$flag_sc = 1;
						$amt_sc = $v;
					}
					
					if(($k === 'security_deduction') && ($v != 0)){
						$flag_sd = 1;
						$amt_sd = $v;
					}
					
					if(($k === 'comm_deduction') && ($v != 0)){
						$flag_com = 1;
						$amt_com = $v;
					}
					
					if(($flag_vat === 1) && ($flag_it === 1) && ($flag_sc === 1)){
						$amt_vatitsc = $amt_vat + $amt_it + $amt_sc;
						$description = "VAT, IT and SC Payment";
					}else if(($flag_vat === 1) && ($flag_it === 1)){
						$amt_vatit = $amt_vat + $amt_it;
						$description = "VAT and IT Payment";
					}else if(($flag_vat === 1) && ($flag_sc === 1)){
						$amt_vatsc = $amt_vat + $amt_sc;
					}else if(($flag_it === 1) && ($flag_sc === 1)){
						$amt_itsc = $amt_it + $amt_sc;
					}else if($flag_vat === 1){
						$amt_vat = $amt_vat;
						$description = "VAT Payment";
					}else if($flag_it === 1){
						$amt_it = $amt_it;
						$description = "IT Payment";
					}else if($flag_sc === 1){
						$amt_sc = $amt_sc;
						$description = "Service Charge Payment";
					}

					$data .= " `{$k}`='{$v}' ";
				}
				else
				{
					$data .= " `{$k}`= NULL ";
				}
			}
		}
	
		$code1 = $prefix.$code1;	
		//$voucher_number = $voucher_number + 1;
		$voucher_num_jv = $voucher_number;
		
		if (($flag_vat === 1) && ($flag_it === 1) && ($flag_sc === 1)) 
			$sql_2 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `payee_name`, `gross_amt`, `vat_deduction`, `it_deduction`, `sc_deduction`, `security_deduction`, `chq_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('dv', '0', '111', '1', '{$journal_date}', '{$voucher_number}', '{$description}', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','13','{$amt_vatitsc}','{$vat_deduction}','{$it_deduction}','{$sc_deduction}','{$security_deduction}','{$chq_number_tax}','{$pkg_number}','{$exp_type}', '{$exp_group}','{$code1}','{$user_id}', '{$year_id}' ) ";
		else if (($flag_vat === 1) && ($flag_it === 1)) 
				$sql_2 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `payee_name`, `gross_amt`, `vat_deduction`, `it_deduction`, `sc_deduction`, `security_deduction`, `chq_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('dv', '0', '11', '1', '{$journal_date}', '{$voucher_number}', '{$description}', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','13','{$amt_vatit}','{$vat_deduction}','{$it_deduction}','{$sc_deduction}','{$security_deduction}','{$chq_number_tax}','{$pkg_number}','{$exp_type}', '{$exp_group}','{$code1}','{$user_id}', '{$year_id}' ) ";
		else if (($flag_vat === 1) && ($flag_sc === 1)) 
				$sql_2 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `payee_name`, `gross_amt`, `vat_deduction`, `it_deduction`, `sc_deduction`, `security_deduction`, `chq_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('dv', '0', '11', '1', '{$journal_date}', '{$voucher_number}', '{$description}', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','13','{$amt_vatsc}','{$vat_deduction}','{$it_deduction}','{$sc_deduction}','{$security_deduction}', '{$chq_number_tax}','{$pkg_number}','{$exp_type}', '{$exp_group}','{$code1}','{$user_id}', '{$year_id}' ) ";
		else if (($flag_sc === 1) && ($flag_it === 1)) 
				$sql_2 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `payee_name`, `gross_amt`, `vat_deduction`, `it_deduction`, `sc_deduction`, `security_deduction`, `chq_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('dv', '0', '11', '1', '{$journal_date}', '{$voucher_number}', '{$description}', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','13','{$amt_itsc}','{$vat_deduction}','{$it_deduction}','{$sc_deduction}','{$security_deduction}','{$chq_number_tax}','{$pkg_number}','{$exp_type}', '{$exp_group}','{$code1}','{$user_id}', '{$year_id}' ) ";
		else if ($flag_vat === 1)			
				$sql_2 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `payee_name`, `gross_amt`, `vat_deduction`, `it_deduction`, `sc_deduction`, `security_deduction`, `chq_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('dv', '0', '1', '1', '{$journal_date}', '{$voucher_number}', '{$description}', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','13','{$amt_vat}','{$vat_deduction}','{$it_deduction}','{$sc_deduction}','{$security_deduction}','{$chq_number_tax}','{$pkg_number}','{$exp_type}', '{$exp_group}','{$code1}','{$user_id}', '{$year_id}' ) ";
		else if ($flag_it === 1)			
				$sql_2 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `payee_name`, `gross_amt`, `vat_deduction`, `it_deduction`, `sc_deduction`, `security_deduction`, `chq_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('dv', '0', '1', '1', '{$journal_date}', '{$voucher_number}', '{$description}', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','13','{$amt_it}','{$vat_deduction}','{$it_deduction}','{$sc_deduction}','{$security_deduction}','{$chq_number_tax}','{$pkg_number}','{$exp_type}', '{$exp_group}','{$code1}','{$user_id}', '{$year_id}' ) ";
		else if ($flag_sc === 1)			
				$sql_2 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `payee_name`, `gross_amt`, `vat_deduction`, `it_deduction`, `sc_deduction`, `security_deduction`, `chq_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('dv', '0', '1', '1', '{$journal_date}', '{$voucher_number}', '{$description}', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','13','{$amt_sc}','{$vat_deduction}','{$it_deduction}','{$sc_deduction}','{$security_deduction}','{$chq_number_tax}','{$pkg_number}','{$exp_type}', '{$exp_group}','{$code1}','{$user_id}', '{$year_id}' ) ";
				
		
		$save_2 = $this->conn->query($sql_2);
		if($save_2){
			$jid = !empty($id) ? $id : $this->conn->insert_id;
			if (($flag_vat == 1) && ($flag_it == 1) && ($flag_sc == 1)) {
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '51', '4', '{$amt_vatitsc}' )";
				$save_10 = $this->conn->query($sql_10);
				
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '95', '1', '{$vat_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
				
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '96', '1', '{$it_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
				
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '97', '1', '{$sc_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
			}else if (($flag_vat == 1) && ($flag_it == 1)) {
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '51', '4', '{$amt_vatit}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '95', '1', '{$vat_deduction}' )";
				$save_10 = $this->conn->query($sql_10);	
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '96', '1', '{$it_deduction}' )";
				$save_10 = $this->conn->query($sql_10);	
			}else if (($flag_vat == 1) && ($flag_sc == 1)) {
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '51', '4', '{$amt_vatsc}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '95', '1', '{$vat_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '97', '1', '{$sc_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
			}else if (($flag_sc == 1) && ($flag_it == 1)) {
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '51', '4', '{$amt_itsc}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '97', '1', '{$sc_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '96', '1', '{$it_deduction}' )";
				$save_10 = $this->conn->query($sql_10);	
			}else if ($flag_vat == 1){
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '51', '4', '{$amt_vat}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '95', '1', '{$vat_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
			}else if ($flag_it == 1){
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '51', '4', '{$amt_it}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '96', '1', '{$it_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
			}else if ($flag_sc == 1){
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '51', '4', '{$amt_sc}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '97', '1', '{$sc_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
			}
		}
		
		if ($flag_sd === 1) {
				$sql_3 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `payee_name`, `gross_amt`, `vat_deduction`, `it_deduction`, `sc_deduction`, `security_deduction`, `chq_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('dv', '0', '1', '1', '{$journal_date}', '{$voucher_number}', 'Security Deposit Payment', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','107','{$amt_sd}','{$vat_deduction}','{$it_deduction}','{$sc_deduction}','{$security_deduction}','{$chq_number_tax}','{$pkg_number}','{$exp_type}', '{$exp_group}','{$code1}','{$user_id}', '{$year_id}' ) ";
		
				$save_3 = $this->conn->query($sql_3);
				if($save_3){
					$jid = !empty($id) ? $id : $this->conn->insert_id;
					if ($flag_sd === 1) {
						$sql_11 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '51', '4', '{$amt_sd}' )";
						$save_11 = $this->conn->query($sql_11);
						$sql_11 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '98', '1', '{$amt_sd}' )";
						$save_11 = $this->conn->query($sql_11);
					}
				}
		}
		
		if ($flag_com === 1) {
				$sql_com = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `payee_name`, `gross_amt`, `vat_deduction`, `it_deduction`, `sc_deduction`, `security_deduction`,  `chq_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('dv', '0', '1', '1', '{$journal_date}', '{$voucher_number}', 'Commission Payment', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','64','{$amt_com}','{$vat_deduction}','{$it_deduction}','{$sc_deduction}','{$security_deduction}','{$chq_number_tax}','{$pkg_number}','{$exp_type}', '{$exp_group}','{$code1}','{$user_id}', '{$year_id}' ) ";
		
				$save_com = $this->conn->query($sql_com);
				if($save_com){
					$jid = !empty($id) ? $id : $this->conn->insert_id;
					if ($flag_com === 1) {
						$sql_11 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '51', '4', '{$amt_com}' )";
						$save_11 = $this->conn->query($sql_11);
						$sql_11 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '103', '1', '{$amt_com}' )";
						$save_11 = $this->conn->query($sql_11);
					}
				}
		}
		
		///// JV Starting
		
		$code2 = $prefix.$code2;
		
		
		if (($flag_vat === 1) && ($flag_it === 1) && ($flag_sc === 1)) 
		$sql_2 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('jv', '0', '1', '111', '{$journal_date}', '{$voucher_num_jv}', 'Adjustment of VAT, IT and SC with line expenditure', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','{$pkg_number}','', '','{$code2}','{$user_id}', '{$year_id}' ) ";
		else if (($flag_vat === 1) && ($flag_it === 1)) 
				$sql_2 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('jv', '0', '1', '11', '{$journal_date}', '{$voucher_num_jv}', 'Adjustment of VAT and IT with line expenditure', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','{$pkg_number}','', '','{$code2}','{$user_id}', '{$year_id}' ) ";
		else if (($flag_vat === 1) && ($flag_sc === 1)) 
				$sql_2 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('jv', '0', '1', '11', '{$journal_date}', '{$voucher_num_jv}', 'Adjustment of VAT and SC with line expenditure', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','{$pkg_number}','', '','{$code2}','{$user_id}', '{$year_id}' ) ";
		else if (($flag_sc === 1) && ($flag_it === 1)) 
				$sql_2 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('jv', '0', '1', '11', '{$journal_date}', '{$voucher_num_jv}', 'Adjustment of IT and SC with line expenditure', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','{$pkg_number}','', '','{$code2}','{$user_id}', '{$year_id}' ) ";
		else if ($flag_vat === 1)			
				$sql_2 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('jv', '0', '1', '1', '{$journal_date}', '{$voucher_num_jv}', 'Adjustment of VAT with line expenditure', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','{$pkg_number}','', '','{$code2}','{$user_id}', '{$year_id}' ) ";
		else if ($flag_it === 1)			
				$sql_2 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('jv', '0', '1', '1', '{$journal_date}', '{$voucher_num_jv}', 'Adjustment of IT with line expenditure', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','{$pkg_number}','', '','{$code2}','{$user_id}', '{$year_id}' ) ";
		else if ($flag_sc === 1)			
				$sql_2 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('jv', '0', '1', '1', '{$journal_date}', '{$voucher_num_jv}', 'Adjustment of SC with line expenditure', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','{$pkg_number}','', '','{$code2}','{$user_id}', '{$year_id}' ) ";
		
		
		foreach($account_id as $k=>$v){
				if($group_id[$k] == 1) {
					$acc_id = $v;
					break;
				}
			}
	
		
		$save_2 = $this->conn->query($sql_2);
		if($save_2){
			$jid = !empty($id) ? $id : $this->conn->insert_id;
			if (($flag_vat == 1) && ($flag_it == 1) && ($flag_sc == 1)) {
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '{$acc_id}', '1', '{$amt_vatitsc}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '95', '4', '{$vat_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '96', '4', '{$it_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '97', '4', '{$sc_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
			}else if (($flag_vat == 1) && ($flag_it == 1)) {
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '{$acc_id}', '1', '{$amt_vatit}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '95', '4', '{$vat_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '96', '4', '{$it_deduction}' )";
				$save_10 = $this->conn->query($sql_10);				
			}else if (($flag_vat == 1) && ($flag_sc == 1)) {
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '{$acc_id}', '1', '{$amt_vatsc}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '95', '4', '{$vat_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '97', '4', '{$sc_deduction}' )";
				$save_10 = $this->conn->query($sql_10);				
			}else if (($flag_sc == 1) && ($flag_it == 1)) {
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '{$acc_id}', '1', '{$amt_itsc}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '96', '4', '{$it_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '97', '4', '{$sc_deduction}' )";
				$save_10 = $this->conn->query($sql_10);				
			}else if ($flag_vat == 1){
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '{$acc_id}', '1', '{$amt_vat}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '95', '4', '{$vat_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
			}else if ($flag_it == 1){
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '{$acc_id}', '1', '{$amt_it}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '96', '4', '{$it_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
			}else if ($flag_sc == 1){
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '{$acc_id}', '1', '{$amt_sc}' )";
				$save_10 = $this->conn->query($sql_10);
				$sql_10 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '97', '4', '{$sc_deduction}' )";
				$save_10 = $this->conn->query($sql_10);
			}
		}
		
		if ($flag_sd === 1)	{		
				$sql_4 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('jv', '0', '1', '1', '{$journal_date}', '{$voucher_num_jv}', 'Adjustment of SD with line expenditure', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','{$pkg_number}','', '','{$code2}','{$user_id}', '{$year_id}' ) ";
				$save_5 = $this->conn->query($sql_4);
				if($save_5){
					$jid = !empty($id) ? $id : $this->conn->insert_id;
					if ($flag_sd === 1) {
						$sql_12 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '{$acc_id}', '1', '{$amt_sd}' )";
						$save_12 = $this->conn->query($sql_12);
						$sql_12 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '98', '4', '{$amt_sd}' )";
						$save_12 = $this->conn->query($sql_12);
					}
				}
		}
		
		if ($flag_com === 1){			
				$sql_5 = "INSERT INTO `journal_entries`(`journal_type`, `new`, `debit_count`, `credit_count`, `journal_date`, `voucher_number`, `description`, `source_fund`, `dli_type`, `component_number`, `category_number`, `pkg_number`, `exp_type`, `exp_group`, `code`, `user_id`, `year_id`) VALUES ('jv', '0', '1', '1', '{$journal_date}', '{$voucher_num_jv}', 'Commission adjusted with line expenditure', '{$source_fund}', '{$dli_type}','{$component_number}','{$category_number}','{$pkg_number}','', '','{$code2}','{$user_id}', '{$year_id}' ) ";
				$save_6 = $this->conn->query($sql_5);
				if($save_6){
					$jid = !empty($id) ? $id : $this->conn->insert_id;
					if ($flag_com === 1) {
						$sql_13 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '{$acc_id}', '1', '{$amt_com}' )";
						$save_13 = $this->conn->query($sql_13);
						$sql_13 = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES (  '{$jid}', '103', '4', '{$amt_com}' )";
						$save_13 = $this->conn->query($sql_13);
					}
				}
		}
				
		
		////JV end
		
		
		
		
		
		if(empty($id)){
			$sql = "INSERT INTO `journal_entries` set {$data} ";
		}else{
			$sql = "UPDATE `journal_entries` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		
		
		
		if($save){
			$jid = !empty($id) ? $id : $this->conn->insert_id;
			$data = "";
			//$this->conn->query("DELETE FROM `journal_items` where journal_id = '{$jid}'");
			foreach($account_id as $k=>$v){
				if(!empty($data)) $data .=", ";
				$data .= "('{$jid}','{$v}','{$group_id[$k]}','{$amount[$k]}')";
			}
			if((!empty($data)) && empty($id)) {
				$sql = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES {$data}";
				$save2 = $this->conn->query($sql);
				if($save2){
					$resp['status'] = 'success';
					if(empty($id)){
						$resp['msg'] = " Journal Entry has successfully added.";
					}else
						$resp['msg'] = " Journal Entry has been updated successfully.";
				}else{
					$resp['status'] = 'failed';
					if(empty($id)){
						$resp['msg'] = " Journal Entry has failed to save.";
						$this->conn->query("DELETE FROM `journal_entries` where id = '{$jid}'");
					}else
						$resp['msg'] = " Journal Entry has failed to update.";
					$resp['error'] = $this->conn->error;
				}
			}else{
				$resp['status'] = 'failed';
				if(empty($id)){
					$resp['msg'] = " Journal Entry has failed to save.";
					$this->conn->query("DELETE FROM `journal_entries` where id = '{$jid}'");
				}else
					$resp['msg'] = " Journal Entry has failed to update.";
				$resp['error'] = "Journal Items is empty";
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
}
	
///////////////////////////////////////////////////	
	
	
	
	
	
	
	
	
	function save_journal_dup(){
			$prefix = date("Ym-");
			$code = sprintf("%'.05d",1);
			while(true){
				$check = $this->conn->query("SELECT * FROM `journal_entries` where `code` = '{$prefix}{$code}' ")->num_rows;
				if($check > 0){
					$code = sprintf("%'.05d",ceil($code) + 1);
				}else{
					break;
				}
			}
			$_POST['code'] = $prefix.$code;
			$_POST['user_id'] = $this->settings->userdata('id');
			$_POST['year_id'] = $this->settings->userdata('year_id');
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))  && !is_array($_POST[$k])){
				if(!is_numeric($v) && !is_null($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				if(!is_null($v))
				$data .= " `{$k}`='{$v}' ";
				else
				$data .= " `{$k}`= NULL ";
			}
		}

		$sql = "INSERT INTO `journal_entries` set {$data} ";
		
		$save = $this->conn->query($sql);
		if($save){
			$jid = $this->conn->insert_id;
			$data = "";
			$this->conn->query("DELETE FROM `journal_items` where journal_id = '{$jid}'");
			foreach($account_id as $k=>$v){
				if(!empty($data)) $data .=", ";
				$data .= "('{$jid}','{$v}','{$group_id[$k]}','{$amount[$k]}')";
			}
			if(!empty($data)){
				$sql = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES {$data}";
				$save2 = $this->conn->query($sql);
				if($save2){
					$resp['status'] = 'success';
					$resp['msg'] = " Journal Entry has successfully added.";
				}else{
					$resp['status'] = 'failed';
						$resp['msg'] = " Journal Entry has failed to save.";
					$resp['error'] = $this->conn->error;
				}
			}else{
				$resp['status'] = 'failed';
					$resp['msg'] = " Journal Entry has failed to save.";
				$resp['error'] = "Journal Items is empty";
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	
	
	function delete_journal(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `journal_entries` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Journal Entry has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function cancel_journal(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `journal_entries` set `status` = '3' where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," journaling has successfully cancelled.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_reservation(){
		$_POST['journal'] = $_POST['date'] ." ".$_POST['time'];
		extract($_POST);
		$capacity = $this->conn->query("SELECT `".($seat_type == 1 ? "first_class_capacity" : "economy_capacity")."` FROM group_list where id in (SELECT group_id FROM `journal_entries` where id ='{$journal_id}') ")->fetch_array()[0];
		$reserve = $this->conn->query("SELECT * FROM `reservation_list` where journal_id = '{$journal_id}' and journal='{$journal}' and seat_type='$seat_type'")->num_rows;
		$slot = $capacity - $reserve;
		if(count($firstname) > $slot){
			$resp['status'] = "failed";
			$resp['msg'] = "This journal has only [{$slot}] left for the selected seat type/group";
			return json_encode($resp);
		}
		$data = "";
		$sn = [];
		$prefix = $seat_type == 1 ? "FC-" : "E-";
		$seat = sprintf("%'.03d",1);
		foreach($firstname as $k=>$v){
			while(true){
				$check = $this->conn->query("SELECT * FROM `reservation_list` where journal_id = '{$journal_id}' and journal='{$journal}' and seat_num = '{$prefix}{$seat}' and seat_type='$seat_type'")->num_rows;
				if($check > 0){
					$seat = sprintf("%'.03d",ceil($seat) + 1);
				}else{
					break;
				}
			}
			$seat_num = $prefix.$seat;
			$seat = sprintf("%'.03d",ceil($seat) + 1);
			$sn[] = $seat_num;
			if(!empty($data)) $data .= ", ";
			$data .= "('{$seat_num}','{$journal_id}','{$journal}','{$v}','{$middlename[$k]}','{$lastname[$k]}','{$seat_type}','{$fare_amount}')";
		}
		if(!empty($data)){
			$sql = "INSERT INTO `reservation_list` (`seat_num`,`journal_id`,`journal`,`firstname`,`middlename`,`lastname`,`seat_type`,`fare_amount`) VALUES {$data}";
			$save_all = $this->conn->query($sql);
			if($save_all){
				$resp['status'] = 'success';
				$resp['msg'] = "Reservation successfully submitted.";
				$get_ids = $this->conn->query("SELECT id from `reservation_list` where `journal_id` = '{$journal_id}' and `journal` = '{$journal}' and seat_type='{$seat_type}' and seat_num in ('".(implode("','",$sn))."') ");
				$res = $get_ids->fetch_all(MYSQLI_ASSOC);
				$ids = array_column($res,'id');
				$ids = implode(",",$ids);
				$resp['ids'] = $ids;
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured while saving the data. Error: ".$this->conn->error;
				$resp['sql'] = $sql;
			}
		}else{
			$resp['status'] = "failed";
			$resp['msg'] = "No Data to save.";
		}
		

		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_reservation(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `reservation_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Reservation Details has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function update_reservation_status(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `reservation_list` set `status` = '{$status}' where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"reservation Request status has successfully updated.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_reservation':
		echo $Master->save_reservation();
	break;
	case 'delete_reservation':
		echo $Master->delete_reservation();
	break;
	case 'update_reservation_status':
		echo $Master->update_reservation_status();
	break;
	case 'save_message':
		echo $Master->save_message();
	break;
	case 'delete_message':
		echo $Master->delete_message();
	break;
	case 'save_group':
		echo $Master->save_group();
	break;
	case 'delete_group':
		echo $Master->delete_group();
	break;
	case 'save_pkg':
		echo $Master->save_pkg();
	break;
	case 'delete_pkg':
		echo $Master->delete_pkg();
	break;
	case 'save_account':
		echo $Master->save_account();
	break;
	case 'save_aid':
		echo $Master->save_aid();
	break;
	case 'save_payee':
		echo $Master->save_payee();
	break;
	case 'save_cashforecast':
		echo $Master->save_cashforecast();
	break;
	case 'save_dpp':
		echo $Master->save_dpp();
	break;
	case 'delete_account':
		echo $Master->delete_account();
	break;
	case 'delete_aid':
		echo $Master->delete_aid();
	break;
	case 'delete_cashforecast':
		echo $Master->delete_cashforecast(); 
	break;
	case 'delete_payee':
		echo $Master->delete_payee(); 
	break;
	case 'save_journal':
		echo $Master->save_journal();
	break;
	
	case 'save_journal_3':
		echo $Master->save_journal_3();
	break;
	
	case 'save_journal_dup':
		echo $Master->save_journal_dup();
	break;
	case 'delete_journal':
		echo $Master->delete_journal();
	break;
	case 'save_plannedfund':
		echo $Master->save_plannedfund();
	break;
	case 'delete_plannedfund':
		echo $Master->delete_plannedfund(); 
	break;
	case 'cancel_journal':
		echo $Master->cancel_journal();
	break;
	case 'save_goods':
        echo $Master->save_goods();
	break;
	case 'delete_goods':
		echo $Master->delete_goods();
	break;
	case 'save_services';
		echo $Master->save_services();
	break;
	case 'delete_services';
		echo $Master->delete_services();
	break;
	default:
		// echo $sysset->index();
		break;
}