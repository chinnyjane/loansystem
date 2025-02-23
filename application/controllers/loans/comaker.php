<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comaker extends CI_Controller {
	
	function index(){
		
	}
	
	function add(){
		if($_POST){
			header("content-type:application/json");
			if(isset($_POST['comakerid'])){
				$data = array("loanID"=>$_POST['loanid'],
							"clientID"=>$_POST['comakerid'],
							"active"=>1);
				if($this->Loansmodel->fieldIn("co_maker",$data) == true){	
					$msg['errors'] = $_POST['loanid'].$_POST['comakerid']." Comaker is on our record.";
					$msg['status'] = false;
				}else{
					$data = array("loanID"=>$_POST['loanid'],
							"clientID"=>$_POST['comakerid'],
							"active"=>1,
							"dateAdded"=>$this->auth->localtime(),
							"addedBy"=>$this->auth->user_id());
					$this->Loansmodel->addtotable("co_maker", $data);
					
					$msg['status'] = true;
					//$msg['data'] = '<li><a href="'.base_url().'client/profile/'.$_POST['comaker'].'" target="_blank">'.$_POST['comakername'].'</a></li>';
					$msg['comakerid'] = $_POST['comakerid'];
					$msg['comakername'] = $_POST['comakername'];
				}
			}else{
				if($this->loansetup->validation_client() == true){
					$comakerid = $this->loansetup->addpersonalinfo();
					$data = array('loanID'=>$_POST['loanid'],
									"clientID"=>$comakerid);									
					$this->Loansmodel->addtotable("co_maker", $data);
					//$msg['data'] = '<li><a href="'.base_url().'client/profile/'.$comakerid.'" target="_blank">'.$_POST['lname'].'</a></li>';
					$msg['comakerid'] = $comakerid;
					$msg['comakername'] = $_POST['lname'];
					$msg['status'] = true;	
				}else{
					$msg['errors'] = validation_errors();
					$msg['status'] = false;				
				}
			}
			$msg['er'] = 'error';
			echo json_encode($msg);
		}
	}
	
	function update(){
		
	}
	
	function remove(){
		
	}

}
?>