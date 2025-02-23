<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cashbal {
	function __construct(){
		$this->ci =& get_instance();		
	}
	
	function addtransaction(){
		$this->ci->form_validation->set_rules('transaction','transaction name', 'required|xss_clean|callback_checktrans_exist');
		if($this->ci->form_validation->run() != false){
		
		}
	}
	
	function getTransactionType($trans) {
		$this->db->select("transCatID, transTypeID, transType");
		$this->db->from("transcategory");
		$this->db->join("transactiontype","transactiontype.transCategory = transcategory.transCatID");
		$this->db->where("transCatName", $trans);
		return $this->db->get();
	}
}?>