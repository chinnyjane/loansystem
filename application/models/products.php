<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Model {
	
	function addpro($data){
		if ($this->db->insert('product', $data))
		return true;
		else
		return false;	
	}
	
	function get($data, $where){
		$this->db->select($data);
		$this->db->where($where);		
		return $this->db->get('product');
	}
	
	function update($data, $where){
		$this->db->where($where);
		$this->db->update("product",$data);			
		return true;
	}
	
	function getInterestByPID($pid){
		$where = array("productID"=>$pid,
								"active"=>1);
		
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('term', 'ASC');
		return  $this->db->get('interestrates');
	}
	
	function addInterestByPID( $data){
		if ($this->db->insert('interestrates', $data))
		return true;
		else
		return false;
	}
	
	function addInterest($productCode, $payment, $term, $interest){
		
		$data = array("productCode"=>$productCode,
					"payment"=>$payment,
					"term"=>$term,
					"interest"=>$interest,
					"addedBy"=>$this->auth->user_id(),
					"dateAdded"=>$this->auth->localtime(),
					"active"=>1);
		
		if ($this->db->insert('interestrates', $data))
		return true;
		else
		return false;	
	}
	
	function removeInterest($interestID){
		
		$data = array("interestID"=>$interestID,
					"active"=>0,
					"is_deleted"=>1,
					"modifiedBy"=>$this->auth->user_id(),
					"dateModified"=>$this->auth->localtime());
		$this->db->update("interestrates",$data);		
		return true;
	}
	
	function updateInterest($data,$where){
		$this->db->where($where);
		$this->db->update("interestrates",$data);			
		return true;
	}
	
	
	function checkInterestExist($data){		
		
		$this->db->select("*");
		$this->db->where($data);
	
		$res = $this->db->get("interestrates");
		
		if($res->num_rows() > 0){
			return true;	
		}else{
			return false;
		}
			
	}
	
	function minmaxTerm($term){
		
		$this->db->select('*');
		$this->db->where($term);
		$this->db->from("loantypes");
		$t = $this->db->get();	
		if($t->num_rows() > 0)
			return true;
		else
			return false;
			
	}
	
	function getcidata($pid){
		$this->db->select('ci_id, ci_name, datatype');
		$this->db->from('ci_and_appraisal');
		$this->db->where('active', 1);
		$this->db->where('productid', $pid);
		
		return $this->db->get();
	}
	
	function addcidetails($post,$loanid){
		foreach($post as $cid=>$val){
			$data[] = array('ci_id'=>$cid,
						'loanid'=>$loanid,
						'value'=>$val,
						'addedBy'=>$this->auth->user_is(),
						'dateAdded'=>$this->auth->localtime(),
						'active'=>1);
		}
		$this->db->insert_batch('ci_details', $data);
	}
	
	function getcireport($loanid){
		$this->db->select('*');
		$this->db->from('ci_details');
		$this->db->join('ci_and_appraisal', 'ci_and_appraisal.ci_id = ci_details.ci_id', 'left');
		$this->db->where('loanid', $loanid);
		$this->db->where('ci_details.active', 1);
		
		return $this->db->get();
	}
	
	
	function addproductrole($data){
		return $this->db->insert('product_roles', $data);		
	}
	
	//updating or removing productroles
	function updateproductrole($data){
		return $this->db->update('product_roles', $data, 'prid');		
	}
	
	
	//add product role definitions
	function addprdefinition($data){
		return $this->db->insert('productrole_definition', $data);
	}
	
	//updating or removing productroles
	function updateprdefinition($data){
		return $this->db->update('productrole_definition', $data, 'prdid');		
	}
	
	//add product role assignments
	function addpr_assignment($data){
		return $this->db->insert('productrole_assignment', $data);
	}
	
	//updating or removing productroles assignment
	function updatepr_assignment($data){
		return $this->db->update('productrole_assignment', $data, 'praid');		
	}
	
	function getCollateralsOfClient($clientid){		
		$this->db->where('collaterals.clientID', $clientid);
		$this->db->where('collaterals_details.active', 1);
		$this->db->where('productcollateral.active', 1);
		$this->db->join('collaterals_details', 'collaterals_details.collateralID=collaterals.collateralID', 'left');
		$this->db->join('productcollateral', 'productcollateral.procolID=collaterals_details.procolID', 'left');
		return $this->db->get('collaterals');	
	}
	
	function getCollateralsbyClient($clientid, $loantype){		
		$this->db->where('collaterals.clientID', $clientid);
		$this->db->where('collaterals_details.active', 1);
		$this->db->where('productcollateral.active', 1);
		if($loantype != '')
		$this->db->where('product.productID', $loantype);
		$this->db->where('primary', 1);
		$this->db->join('collaterals_details', 'collaterals_details.collateralID=collaterals.collateralID', 'left');
		$this->db->join('productcollateral', 'productcollateral.procolID=collaterals_details.procolID', 'left');
		$this->db->join('loantypes','loantypes.loanTypeID =collaterals.loantypeID','left');
		$this->db->join('product','product.productID= loantypes.productID','left');
		return $this->db->get('collaterals');	
	}
	
	function getCollaterals($loanid){
		$this->db->where('collaterals_details.loanID', $loanid);
		$this->db->where('collaterals_details.active', 1);
		$this->db->where('productcollateral.active', 1);
		$this->db->join('collaterals_details', 'collaterals_details.collateralID=productcollateral.procolID', 'left');
		return $this->db->get('productcollateral');	
	}
	
	function add($table,$data){
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}
	
	function getProCollaterals($pid){
		$this->db->where("productcollateral.productID",$pid);
		$this->db->where("productcollateral.active",1);
		$this->db->join("product", "product.productID=productcollateral.productID");
		return $this->db->get("productcollateral");
	}
	
	function addCollateralType($data){
		return $this->db->insert('productcollateral', $data);
	}
	
	function updateCollateralType($data){
		return $this->db->update('productcollateral', $data, 'procolID');
	}
	
	function addCollateralDetails($data){
		return $this->db->insert_batch('collaterals_details', $data);
	}
	
	function updateCollateralDetails($data){
		return $this->db->update_batch('collaterals_details', $data, 'colID');
	}
	
	function getInterestRates($loancode, $loanmethod, $productID){
		$sql = "SELECT 
				  term,
				  interest,
				  minTerm,
				  maxTerm ,
				  interestID
				FROM
				  loantypes  
				  LEFT JOIN  interestrates
					ON interestrates.productCode = loantypes.LoanCode
					AND interestrates.payment = loantypes.PaymentTerm
				WHERE LoanCode = '".$_POST['loancode']."' 
				  AND PaymentTerm = '".$_POST['method']."' 
				  and interestrates.active =1
				ORDER BY term ASC ";
	  $return['sql']= $sql;
	  $res = $this->db->query($sql);
	  $return['loancode'] = $_POST['loancode'];
	  $return['method'] = $_POST['method'];
	  
	  if($res->num_rows() > 0){
			foreach($res->result() as $r){
				$return['int'][] = array("term"=>$r->term,
								"interest"=>$r->interest,
								"minTerm"=>$r->minTerm,
								"maxTerm"=>$r->maxTerm,
								"interestID"=>$r->interestID
								); 
				if($r->term == '')
				$return['lastterm'] ='';
				else
				$return['lastterm'] = $r->term;
			}
	  }else{
		$return['lastterm'] = '';
		$return["json"] = "false";
	  }   
	 	
	}
	
	function addLoanApproval($data){
		$this->db->insert('loanapproval', $data);
		return true;
	}
	
	function updateLoanApproval($data, $where){
		$this->db->where($where);
		$this->db->update('loanapproval', $data);
		return true;
	}
	
	function allLoanApproval($proid){
		$sql = "select * from loanapproval left join user on user.id = loanapproval.userid
				left join branches on branches.id = loanapproval.branchID
				where productID = '$proid' order by branches.id asc";
		return $this->db->query($sql);
	}
	
	function getCharges(){
		$this->db->where("active",1);
		$this->db->order_by("charge_type", "ASC");
		$this->db->from("loan_charges");
		return $this->db->get();
	}
	
	
	function addCharges($data){
		$this->db->insert('loan_charges', $data);
		return true;
	}
	
	function UpdateCharges($data,$where){
		$this->db->where($where);
		return $this->db->update('loan_charges', $data);
	}
	
	function getChargeByID($id){
		$this->db->where("id",$id);
		return $this->db->get("loan_charges");
	}
	
	function getChargeByCat($cat){
		$this->db->where("charge_type",$cat);
		$this->db->order_by("charge_name","ASC");
		return $this->db->get("loan_charges");
	}
	
	function getLoanCharges($loanid){
		$sql = " SELECT sum(loanfees.`value`) as value, loan_charges.`id` FROM loan_charges
					LEFT JOIN productfees ON productfees.`charge_type_ID` = loan_charges.`id`
					LEFT JOIN loanfees ON loanfees.`feeID` = productfees.`feeID`
					WHERE loanID='$loanid' GROUP BY id";
		return $this->db->query($sql);
		
	}
}