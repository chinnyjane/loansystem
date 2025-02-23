<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fees extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
	
	function add(){
		
		$data =array("fee_name"=>$_POST['feename'],
					"gl_account"=>$_POST['accountid'],
					"var_name"=>$_POST['var_name'],
					"transtype"=>$_POST['transtype'],
					"dc"=>$_POST['dc'],
					"productID"=>$_POST['productID'],
					"date_added"=>$this->auth->localtime(),
					"active"=>1);

		$this->db->insert("fees", $data);
		if($this->db->insert_id())
			return true;
		else return false;
	}
	
	function getall(){
		$this->db->select('*');
		$this->db->join('gl_coa', 'gl_coa.coa_id=fees.gl_account','left');
		$this->db->where("fees.active", 1);
		return $this->db->get("fees");
	}
	
	function update(){
		$data =array("fee_name"=>$_POST['feename'],
					"gl_account"=>$_POST['accountid'],
					"transtype"=>$_POST['transtype'],
					"productID"=>$_POST['productID'],
					"active"=>$_POST['active'],
					"date_modified"=>$this->auth->localtime(),
					"modified_by"=>$this->auth->user_id());
		if($this->db->update("fees", $data))
			return true;
		else return false;
	}
	
	function getFeeByTrans($transid){		
		$this->db->select('*');
		$this->db->join('gl_coa', 'gl_coa.coa_id=fees.gl_account','left');
		$this->db->where("fees.active", 1);
		$this->db->where("transtype", $transid);		
		return $this->db->get("fees");
	}
	
	function getFee($where){
		$sql = "SELECT 
				  * 
				FROM
				  fees 
				  LEFT JOIN gl_coa 
					ON gl_coa.coa_id = fees.gl_account 
				  LEFT JOIN product 
					ON product.productID = fees.productID 
				  LEFT JOIN transcategory
					on transcategory.transCatID = fees.transtype					
				WHERE ".$where;
		//echo $sql;
		return $this->db->query($sql);
	}	
		
	
}