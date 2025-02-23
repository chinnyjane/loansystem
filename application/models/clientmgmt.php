<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clientmgmt extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	//getclients
	function get_clients($search, $start, $limit){
		if(!empty($search))
		{
			$s = "";
			$list = $search;
			$this->db->like( "CONCAT( firstname,  ' ', LastName )",$list );
		}
		if(!empty($start))
		{
			$this->db->limit($start, $limit);
		}
		
		$this->db->from('clientinfo');
		$this->db->order_by("LastName", "ASC");
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		$this->db->where('active', 1);
		return $this->db->get();
	}
	
	function getclientinfoByID($id){
		$select = "select  clientinfo.*, cities.name as cityname, provinces.id as province, provinces.name as provname from clientinfo 
		left join cities on cities.id = clientinfo.city
		left join provinces on provinces.id = cities.province_id
		where clientid='".$id."' ";
		//echo $select;
		return $this->db->query($select);
	}
	
	function getspouse($id){
		$this->db->select('*');
		$this->db->from('spouseinfo');
		$this->db->where('clientID', $id);
		$this->db->where('active', 1);
		return $this->db->get();
	}
	
	function getdependents($id){
		$this->db->select('*');
		$this->db->from('dependents');
		$this->db->where('clientID', $id);
		$this->db->where('active', 1);
		return $this->db->get();
	}
	
	function getcreditor($id){
		$this->db->select('*');
		$this->db->from('creditobligations');
		$this->db->where('clientID', $id);
		$this->db->where('active', 1);
		return $this->db->get();
	}
	
	function getIncomeExpense($id){
		$this->db->select('*');
		$this->db->from('income_expense');
		$this->db->where('clientID', $id);	
		$this->db->where('active', 1);
		$this->db->order_by('type','ASC');			
		return $this->db->get();
	}
	
	function getEmployer($id){
		$this->db->select('*');
		$this->db->where('clientID', $id);
		$this->db->where('active', 1);
		return $this->db->get('employmentinfo');
	}
	
	function save($data, $isNew = false){
		
		if(!$isNew){			
			$id = $data['ClientID'];
			$res = $this->db->update($data, 'clientinfo');		
		}else{
			$this->db->insert('clientinfo', $data);
			$res = $this->db->insert_id();
		}
		
		return $res;
		
	}
		
		
	
	function planalysis($pensionid){
		$sql = "SELECT loanschedule.`DueDate`, loanschedule.`Paid`, loanschedule.`AmountDue`, loanapplication.`PN`, loanapplication.`loanID` FROM pensioninfo
		JOIN loanapplication ON loanapplication.`pensionID` = pensioninfo.`PensionID`
		JOIN loanschedule ON loanschedule.`loanID` = loanapplication.`loanID`
		join loantypes on loantypes.loanTypeID = loanapplication.LoanType
			join product on product.productID = loantypes.productID
		WHERE pensioninfo.`PensionID` = '$pensionid' and productCode = 'PL'
		AND loanschedule.`DueDate` > NOW() and loanapplication.status <> 'canceled' and loanapplication.status <> 'closed' and loanschedule.Active='1' and loanapplication.active='1'
		ORDER BY DueDate ASC";
		$pl = $this->db->query($sql);
		return $pl;
	}
	
	function updateinfo(){
		
		if(isset($_POST['info'])){
			
			if($_POST['info'] == 'spouse'){
				$content ='';
				if($this->loansetup->validate_spouse() == false){
					$content .= validation_errors();
					//$footer = '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#spouseinfo" data-dismiss="modal">Back '.$_POST['info'].'</button>';
					//echo $this->form->modal(validation_errors(), $footer);				
				}else{					
					$table = "spouseinfo";
					$where = array("clientID"=>$_POST['clientid']);
					if($this->Loansmodel->get_data_from($table, $where)->num_rows() > 0){
						$sp = array("firstname" => $_POST['spfirstname'],
							"middlename" => $_POST['spmname'],
							"lastname" => $_POST['splname'],
							"dateOfBirth" => $_POST['spbdate'],
							"occupation" => $_POST['spwork'],
							"companyname" => $_POST['spcompany'],
							"salary" => $_POST['spsalary'],
							"contact" => $_POST['spcontact'],
							"dateModified" => $this->auth->localtime(),
							"modifiedBy" => $this->auth->user_id(),
							"active"=> 1);					
						if($this->Loansmodel->update_data($table, $where, $sp) == true){
							$clientprofile = base_url() . "client/profile/".$_POST['clientid'];
							$footer = '<a href="'.$clientprofile.'" class="btn btn-default btn-sm">Ok</a>';
							$content .= '<div class="alert alert-success">Spouse Information was updated.</div>';
							//echo $this->form->modal($content, $footer);
						}else{
							$footer = '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#spouseinfo" data-dismiss="modal">Back</button>';
							$content .= '<div class="alert alert-warning">Error Updating Spouse Information. Please try again.</div>';
							//echo $this->form->modal($content, $footer);
						}
					}else{
						$sp = array("firstname" => $_POST['spfirstname'],
							"middlename" => $_POST['spmname'],
							"lastname" => $_POST['splname'],
							"dateOfBirth" => $_POST['spbdate'],
							"occupation" => $_POST['spwork'],
							"companyname" => $_POST['spcompany'],
							"salary" => $_POST['spsalary'],
							"contact" => $_POST['spcontact'],
							"dateAdded" => $this->auth->localtime(),
							"addedBY" => $this->auth->user_id(),
							"clientID"=>$_POST['clientid'],
							"active"=> 1);
						if($this->Loansmodel->addtotable($table, $sp) != false){
							$clientprofile = base_url() . "client/profile/".$_POST['clientid'];
							$footer = '<a href="'.$clientprofile.'" class="btn btn-default btn-sm">Ok</a>';
							$content .= '<div class="alert alert-success">Spouse Information was updated.</div>';
							//echo $this->form->modal($content, $footer);
						}else{
							$footer = '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#spouseinfo" data-dismiss="modal">Back</button>';
							$content .= '<div class="alert alert-warning">Error Updating Spouse Information. Please try again.</div>';
							//echo $this->form->modal($content, $footer);
						}
					
					}
					
					echo $content;
				}
				
				return $content;
			}elseif($_POST['info'] == 'dependents'){				
				$this->loansetup->add_dependents($_POST['client']);
				$this->loansetup->update_dependents();
				$clientprofile = base_url() . "client/profile/".$_POST['client'];
				$footer = '<a href="'.$clientprofile.'" class="btn btn-default btn-sm">Ok</a>';
				$content = '<div class="alert alert-success">Dependents Information was updated.</div>';
				echo $this->form->modal($content, $footer);
			}elseif($_POST['info'] == 'credit'){				
				$cre =  $this->loansetup->add_creditor($_POST['client']);
				$this->loansetup->update_creditor();
				$clientprofile = base_url() . "client/profile/".$_POST['client'];
				$footer = '<a href="'.$clientprofile.'" class="btn btn-default btn-sm">Ok</a>';
				if($cre == true)
				$content = '<div class="alert alert-success">Creditors Information was updated.</div>';
				else $content = '<div class="alert alert-success">Creditors Information was not updated.</div>';
				echo $this->form->modal($content, $footer);
			}elseif($_POST['info'] == 'image'){			
			
				$config['upload_path'] = './assets/img/clients/';
				//var_dump($config['upload_path']); 
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']	= '100';
				$config['max_width'] = '1024';
				$config['max_height'] = '768';
				$this->load->library('image_lib');
				$this->load->library('upload', $config);
				// Alternately you can set preferences by calling the initialize function. Useful if you auto-load the class:
				$this->upload->initialize($config);

				if ( ! $this->upload->do_upload('userfile'))
				{
					$p = array('error' => $this->upload->display_errors());
					$content = $this->upload->display_errors();
				}
				else
				{
					$image=$this->upload->data();
					$content = $config['upload_path'].$image['file_name'];
					
					$img = array("image" => $content,
								"dateModified" => $this->auth->localtime(),
								"modifiedBy" => $this->auth->user_id());
					$where = array("ClientID"=>$_POST['client']);	
					$table = 'clientinfo';				
					if($this->Loansmodel->update_data($table, $where, $img) == true){
						$content = '<img src="'.$content.'" width="200px">';
					}else $content = 'Image was not stored to client\'s record.';
					
				}
			
				$clientprofile = base_url() . "client/profile/".$_POST['client'];
				$footer = '<a href="'.$clientprofile.'" class="btn btn-default btn-sm">Ok</a>';				
				echo $this->form->modal($content, $footer);
			}
		}else{
			
			if($this->loansetup->validation_client() == true){
				if($_POST['bdate'] == '0000-00-00')
				$error[] = "Birthday is invalid.";
					if(!isset($error)){			
						$clientid = $_POST['client'];
						if($this->loansetup->update_clientinfo($clientid) == true){
							$clientprofile = base_url() . "client/profile/".$_POST['client'];
							$footer = '<a href="'.$clientprofile.'" class="btn btn-default btn-sm">Ok</a>';
							$content = '<div class="alert alert-success">Client Information was updated.</div>';
							//echo $this->form->modal($content, $footer);						
						}else{
							$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
							$content = '<div class="alert alert-danger">Failed to Update client info. Please try again.</div>';
							//echo $this->form->modal($content, $footer);							
						}
					
					}else{
						foreach($error as $e){
							//echo $e."<br/>";
						}
					}	
				//update_data($table, $id, $data)
			}else{					
				$footer = '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#personalinfo" data-dismiss="modal">Back</button>';
				$content = validation_errors();
				//echo $this->form->modal($content, $footer);
			}
		}
		
	}
	
	function getAllPension($branchID){
		$sql = 'SELECT 
		  firstname,
		  lastname,
		  PensionType,
		  pensioninfo.BankID,
		  atm_pb,
		  monthlyPension,
		  PensionType,
		  atmnum, Bankaccount,
		  pensioninfo.pensionID, bankCode, 
		  pensionDate,
		  clientinfo.ClientID as cid
		FROM
		  pensioninfo 
		  JOIN clientinfo 
			ON clientinfo.`ClientID` = pensioninfo.`clientID` 
		  JOIN loanapplication ON loanapplication.`pensionID` = pensioninfo.`PensionID`
		  JOIN loantypes ON loantypes.`loanTypeID` = loanapplication.`LoanType`
		  JOIN product ON product.`productID` = loantypes.`productID`
		  JOIN banks ON banks.`bankID` = pensioninfo.`BankID`
		WHERE product.`productCode`="PL" AND clientinfo.`branchID`="'.$branchID.'"
		GROUP BY pensioninfo.`PensionID`';
		return $this->db->query($sql);		
	}
	
	function getScheduledPension($branchID, $date){
		$sql = 'SELECT 
		  firstname,
		  lastname,
		  PensionType,
		  pensioninfo.BankID,
		  atm_pb,
		  monthlyPension,
		  PensionType,
		  atmnum, Bankaccount,
		  pensioninfo.pensionID, bankCode, 
		  pensionDate,
		  clientinfo.ClientID as cid
		FROM
		  pensioninfo 
		  JOIN clientinfo 
			ON clientinfo.`ClientID` = pensioninfo.`clientID` 
		  JOIN loanapplication ON loanapplication.`pensionID` = pensioninfo.`PensionID`
		  JOIN loantypes ON loantypes.`loanTypeID` = loanapplication.`LoanType`
		  JOIN product ON product.`productID` = loantypes.`productID`
		  JOIN banks ON banks.`bankID` = pensioninfo.`BankID`
		WHERE pensionDate = "'.$date.'" AND product.`productCode`="PL" AND LOWER(loanapplication.`status`) <> "closed" AND LOWER(loanapplication.`status`) <> "canceled" AND clientinfo.`branchID`="'.$branchID.'"
		GROUP BY pensioninfo.`PensionID`';
		return $this->db->query($sql);		
	}
	
	function lock($lock, $cid){
		$data = array(
               'lock' => $title,
               'dateModified' => $this->auth->localtime(),
               'modifiedBy' => $this->auth->user_id()
            );

		$this->db->where('ClientID', $cid);
		$this->db->update('clientinfo', $data); 

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */