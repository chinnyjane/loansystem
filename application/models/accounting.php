	<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Accounting extends CI_Model {
		
		
		function getAcctGroups(){
			$this->db->select('*');
			$this->db->where("active", '1');
			$this->db->order_by("coa_code", "asc");
			return $this->db->get('gl_coacategory');
		}
		
		function acct_group_details($coacode){
			$this->db->select('*');
			$this->db->where("active", '1');
			$this->db->where("coa_id",$coacode);
			$this->db->order_by("coa_code", "asc");
			return $this->db->get('gl_coacategory');
		}
		
		function addAcctGroup(){
			
		}
		
		function UpdateAcctGroup(){
			
		}
		
		function ChartOfAccounts(){
			$this->db->select('gl_coa.*, gl_coacategory.coa_codeprefix');
			$this->db->join('gl_coacategory', "gl_coacategory.coa_code = gl_coa.coa_category","left");
			$this->db->where("gl_coa.active", '1');
			$this->db->where("gl_coa.coa_parent", '');
			$this->db->order_by("gl_coa.coa_code", "asc");
			return $this->db->get('gl_coa');			
		}
		
		function addAccount(){
			
		}
		
		function UpdateAccount(){
			
		}
		
		function getAccountByCategory($cat){
			$this->db->select('gl_coa.*, gl_coacategory.coa_codeprefix');
			$this->db->join('gl_coacategory', "gl_coacategory.coa_code = gl_coa.coa_category","left");
			$this->db->where("gl_coa.active", '1');
			$this->db->where("gl_coa.coa_category", $cat);
			$this->db->where("gl_coa.coa_parent", '');
			$this->db->order_by("gl_coa.coa_code", "asc");
			return $this->db->get('gl_coa');
		}
		
		function coa_details($coacode){
			$this->db->select('gl_coa.*, gl_coacategory.coa_codeprefix, gl_coacategory.coa_name as category');
			$this->db->join('gl_coacategory', "gl_coacategory.coa_code = gl_coa.coa_category","left");
			$this->db->where("gl_coa.active", '1');
			$this->db->where("gl_coa.coa_id", $coacode);
			$this->db->order_by("gl_coa.coa_code", "asc");
			return $this->db->get('gl_coa');
		}
		
		
		function addEntry($data){
			//add GL Entry
			
			//add GL Entry Items
		}
		
		function addEntryItems($data){
			
		}
		
		function addTransaction(){
		}
		
	
		function branchGL($branchid){
			$this->db->select('gl_coa.*, gl_coacategory.coa_codeprefix');
			$this->db->join('gl_coacategory', "gl_coacategory.coa_code = gl_coa.coa_category","left");
			$this->db->where("gl_coa.active", '1');
			$this->db->where("gl_coa.branch_id", $branchid);
			//$this->db->or_where("gl_coa.branch_id", '0');
			$this->db->order_by("gl_coa.coa_code", "asc");
			return $this->db->get('gl_coa');
		}
		
		function getLastSubCode($coa_code){
			$this->db->SELECT('COUNT(*) as lastcode');
			$this->db->WHERE('coa_code',$coa_code);
			$this->db->where('branch_id <>','0');
			$code =  $this->db->get('gl_coa');
			
			if($code){
				$c = $code->row()->lastcode;
				$c += 1;
				return str_pad($c, 4, "0", STR_PAD_LEFT);
			}
			
		}
		
	}		