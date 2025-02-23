<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Form {

	private $logged_in = NULL;
	
	function __construct(){
		$this->ci =& get_instance();
		$this->ip_address = $this->ci->input->ip_address();
		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}
		if (!class_exists('UserMgmt'))
		{
			$this->ci->load->model('usermgmt', 'usermgmt', TRUE);
		}
	}
	
	function datefield($fieldname, $date){
		if($date == '')
		$date = $this->ci->input->post($fieldname);		
		$f = '<input type="date" name="'.$fieldname.'" id="'.$fieldname.'" placeholder="yyyy-mm-dd"  data-mask="9999-99-99" class="form-control input-sm " data-toggle="tooltip" data-placement="bottom" title="Date of Birth is required" value="'.set_value($fieldname,$date).'">';
		$f .= '<script>
				$(function() {
					var datepick = $(".datepicker" ).datepicker({format: \'yyyy-mm-dd\',
					changeMonth: true,
					changeYear: true,
					weekStart: 1,
					viewMode: 2,
					minViewMode: 0
					}).on(\'changeDate\', function(ev) {					
					}).data(\'datepicker\');				
				});
				</script>';
		return $f;
	}
	
	function datetoday($fieldname, $date){
		if($date == '')
		$date = $this->ci->input->post($fieldname);		
		$f = '<input type="text" name="'.$fieldname.'" id="'.$fieldname.'" placeholder="yyyy-mm-dd" data-mask="0000-00-00" class="form-control input-sm datepicker" value="'.set_value($fieldname,$date).'">';
		$f .= '<script>
				$(function() {
					var datepick = $(".datepicker" ).datepicker({format: \'yyyy-mm-dd\',
					changeMonth: true,
					changeYear: true,
					weekStart: 1,
					minViewMode: 0
					}).on(\'changeDate\', function(ev) {					
					}).data(\'datepicker\');				
				});
				</script>';
		return $f;
	}
	
	function civilstatus($fieldname, $value){
		if($value == '')
		$value = $this->ci->input->post($fieldname);
		$f = '<select name="'.$fieldname.'" id="'.$fieldname.'" class="form-control input-sm" value="'.set_value($fieldname,$value).'">';
		$f .= '<option disabled>Civil Status</option>';
		$f .= '<option value="single"'; 
			if($value == 'single') $f.= 'selected'; 
		$f .= '>Single</option>';
		$f .= '<option value="married" ';
				if($value == 'married') $f.= 'selected';
		$f .= '>Married</option>';
		$f .= '<option value="widow" ';
			if($value == 'widow') $f.= 'selected';
		$f .= '>Window/Widower</option>';
		$f .= '<option value="separated" ';
			if($value == 'separated') $f.= 'selected';
		$f .= '>Separated</option>';
		$f .= '</select>';
		return $f;
	}
	
	function gender($fieldname, $value){
		if($value == '')
		$value = $this->ci->input->post($fieldname);
		$f = '<select name="'.$fieldname.'" class="form-control input-sm" value="'.set_value($fieldname,$value).'">';
		$f .= '<option value="F"';
			if($value == "F") $s = true; else $s = false;
			$f .= set_select('gender', $value, $s);
			$f .= '>Female</option>';
		$f .= '<option value="M"';
			if($value == "M") $s = true; else $s = false;
			$f .= set_select('gender', $value, $s);
		$f .= '>Male</option></select>';
		
		return $f;
	}
	
	function provincefield($fieldname, $value){
		$f = '<select name="'.$fieldname.'" class="form-control input-sm province">';
		$f .= '<option disabled >Select Province</option>';
		$province = $this->ci->Loansmodel->get_province();
			 	foreach($province->result() as $pro){
					if ($pro->id == $value)
						$select = true;
					else
						$select = false;
					$f .= '<option value="'.$pro->id.'" '.set_select('province', $value, $select).'>'.$pro->name.'</option> ';
				}	
		$f .= '</select>';
		return $f;
	}
	
	function cityfield($fieldname, $cityid, $provid){
		$f = '<select name="'.$fieldname.'"  class="form-control input-sm city" >';
		$cities = $this->ci->Loansmodel->get_cities_by_prov($provid);
		foreach($cities->result() as $c){
			if ($c->id == $cityid)
				$select = true;
			else
				$select = false;
			$f .= "<option value='".$c->id."'".set_select('city', $cityid, $select).">".$c->name."</option>";
		}
		$f .='</select>';
		return $f;
	}
	
	function formheader($branch){
		$title = "<div align='center'><h4>YUSAY CREDIT and FINANCE CORPORATION</h4></div>";
		//get branch address
		//$title .= 'Address: </div>';
		return $title;
	}
	
	function rfplmonitorysheet($loanid){
		$loaninfo = $this->ci->Loansmodel->getLoanbyID($loanid);
		$loan = $loaninfo->row();
		//echo "<pre>";
		//print_r($loan);
		//echo "</pre>";
		//$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
		//$this->ci->table->set_template($tmpl);
		$this->ci->table->set_heading("Name of Borrower", $loan->LastName.", ".$loan->firstName, "Loan Granted",number_format($loan->approvedAmount,2));
		$this->ci->table->add_row();
		$this->ci->table->add_row("Address", $loan->address, "Term of Loan",$loan->Term);
		$this->ci->table->add_row("Date of Birth", $loan->dateOfBirth, "Date of Release",$loan->DateDisbursed);
		$this->ci->table->add_row("Age", $this->ci->loansetup->get_age($loan->dateOfBirth), "Maturity Date",$loan->MaturityDate);
		$this->ci->table->add_row("Gender", $loan->gender, "RFPL Due",$loan->MaturityDate);
		$this->ci->table->add_row("Civil Status", $loan->civilStatus, "Check no.",$loan->MaturityDate);
		$this->ci->table->add_row("Promissory Note No.", $loan->PNno, " "," ");		
		
		return $this->ci->table->generate();
	}
	
	function promissory($loanid){
		$div = "<div class='col-xs-3'>Amount</div>";
		$div .= "<div class='col-xs-4 col-xs-offset-7'>";
			$div .= '<div class="row">';
				$div .= "<div class='col-xs-6 '>Date Granted:</div><div class='col-xs-6'>here</div>";
			$div .="</div>";
			$div .= '<div class="row">';
				$div .= "<div class='col-xs-6 '>Maturity Date:</div><div class='col-xs-6'>here</div>";
			$div .="</div>";
		$div .="</div>";
	
		return $div;
	}
	
	function bank($fieldname, $value){
		$data = array("active <> "=>0);
		$banks = $this->ci->Loansmodel->get_data_from('banks', $data);
		if($banks->num_rows() >0){
			$div = '<select name="'.$fieldname.'"  class="form-control input-sm" >';
			foreach($banks->result() as $bank){
				if ($bank->bankID == $value)
						$select = true;
					else
						$select = false;
				$div .= "<option value='".$bank->bankID."'".set_select($fieldname, $value, $select).">".$bank->bankCode."</option>";
			}
			$div .='</select>';
			return $div;
		}
	}
	
	function modal($content, $footer){
		$div = '<div class="modal-dialog ">';
		$div .=  '<div class="modal-content ">';
		$div .= '<div class="modal-body ">';
		
		$div .= $content;
		$div .= '</div>';
		$div .= '<div class="modal-footer ">';
		$div .= $footer;
		$div .= '</div>';
		$div .= '</div>';
		$div .= '</div>';
		return $div;
	}
	
	function modallg($content, $footer){
		$div = '<div class="modal-dialog modal-lg">';
		$div .=  '<div class="modal-content ">';
		$div .= '<div class="modal-body ">';
		$div .= $content;
		$div .= '</div>';
		$div .= '<div class="modal-footer ">';
		$div .= $footer;
		$div .= '</div>';
		$div .= '</div>';
		$div .= '</div>';
		return $div;
	}
	
	function modalform_open($formid, $posturl, $formtitle){
	
		$div = '<div class="modal-dialog modal-lg" >';
				$div .= '<form action="'.base_url().$posturl.'" method="post" class="formpost" >';
					$div .= '<div class="modal-content">';
						$div .= '<div class="modal-header">';
							$div .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
							$div .= '<h4 class="modal-title" id="myModalLabel">'.$formtitle.'</h4>';
						$div .= '</div>';
						$div .= '<div class="modal-body">';					
		
		return $div;
	}
	
	
	function modalformopen($modalid, $posturl, $formtitle){
	
		$div = '<div class="modal-dialog modal-lg" >';
				$div .= '<form action="'.base_url().$posturl.'" method="post" class="formpost" >';
					$div .= '<div class="modal-content">';
						$div .= '<div class="modal-header">';
							$div .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
							$div .= '<h4 class="modal-title" id="myModalLabel">'.$formtitle.'</h4>';
						$div .= '</div>';
						$div .= '<div class="modal-body">';					
		
		return $div;
	}
	
	function modalformopennorm($modalid, $posturl, $formtitle){
	
		$div = '<div class="modal-dialog" >';
				$div .= '<form action="'.base_url().$posturl.'" method="post" class="formpost" >';
					$div .= '<div class="modal-content">';
						$div .= '<div class="modal-header">';
							$div .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
							$div .= '<h4 class="modal-title" id="myModalLabel">'.$formtitle.'</h4>';
						$div .= '</div>';
						$div .= '<div class="modal-body">';					
		
		return $div;
	}
	
	function modalformclose($footer){			
						$div = '</div>';
						$div .= '<div class="modal-footer">';
							$div .= $footer;
						$div .= '</div>';
					$div .= '</div>';
				$div .= '</form>';
			$div .= '</div>';
		$div .= '</div>';
		return $div;
	}
	
	function cvfooter(){
		$div = '<input type="hidden" name="transdate" value="'.$this->ci->auth->localdate().'">'
				.'<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>'
				.'<input type="submit" class="btn btn-sm btn-danger " name="submit" id="disbursepost" value="Create Voucher">';
		return $div;
	}
	
	function collateralForm($pid, $loantype){
		$content= $loantype;
		if(strpos($loantype,'PL')  !== false)
		{
			
			$content .= $this->ci->load->view('loans/forms/plform', true);
		}else{
			//echo "no";
			$cols = $this->ci->Products->getProCollaterals($pid);
			//$content = '';
			if($cols->num_rows() > 0){
				foreach($cols->result() as $col){
					$content .= '<div class="row form-group">';
					$content .= '<div class="col-md-4" align="right">';
					$content .= '<label>'.$col->collateralname.'</label>';
					$content .= '</div>';	
					$content .= '<div class="col-md-3">';
					$content .= '<input class="input-sm form-control" type="text" name="col['.$col->procolID.']" >';		
					$content .= '</div>';
					$content .= '</div>';
				}		
				$content .= '<input class="input-sm form-control" type="hidden" name="loantype" value="'.$loantype.'" >';	
			}else{
				$content .= 'No Collaterals for this product.';	
			}
			
		}
		return $content;
	}
}

?>