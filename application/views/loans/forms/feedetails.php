<?php
if(isset($_POST['submit'])){
	if($_POST['submit'] == "Save Loan Information"){
		$ret = '';
		if($_POST['loancode'] == 'PL'){
				if($this->input->post('excess') < 0) {
					$ret .= '<div class="alert alert-danger" role="alert">
							  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							  <span class="sr-only">Error:</span>
							  Monthly Pension cannot pay the monthly amortization. Please choose other terms.
							</div>';
				}
			}
			
			$ret .= '<input type="hidden" name="pid" value="'.$this->input->post('pid').'">';
			$ret .= "<h4>";			
			$ret .= "Loan Computation";
			$ret .= "</h4>";
		
			$this->table->add_row(array("data"=>"<label>Principal </label>", "width"=>"30%"),'<div class="input-group"><span class="input-group-addon">Php</span><input type="text" name="principal" value="'.$this->input->post('principal').'" class="input-sm form-control" readonly/></div>' );
			
			if($this->input->post('method') != 'L'){
				$meth =  '<div class="input-group">'.'<span class="input-group-addon">Php</span>';
					$monthlyamort = array("class"=>'input-sm form-control', "name"=>'monthly', 'value'=>$this->input->post('monthly'), "readonly"=>'readonly');
				$meth .= form_input($monthlyamort);					
				$meth .= '</div>';
				$this->table->add_row("<label>Monthly</label>", $meth);
			}
			
			
			
			if($this->input->post('loancode') == 'PL'){
				$meth =  '<div class="input-group">'.'<span class="input-group-addon">Php</span>';
					$exc = array("class"=>'input-sm form-control', "name"=>'excess', 'value'=>$this->input->post('excess'), "readonly"=>'readonly');
				$meth .= form_input($exc);					
				$meth .= '</div>';
				$this->table->add_row('<label>Excess </label>', $meth);
			}
			
				$ir =  '<div class="input-group">';
				$exc = array("class"=>'input-sm form-control', "name"=>'interest', 'value'=>$this->input->post('interest'), "readonly"=>'readonly');
				$ir .= form_input($exc);					
				$ir .= '<span class="input-group-addon">%</span></div>';
				$this->table->add_row('<label>Interest </label>', $ir);
			
			
			
			$this->table->add_row(array("data"=> "<h4>LOAN FEES</h4>","colspan"=>2));
			
			if($this->input->post('fee') != ''){
				$feename = $this->input->post('feename');
				foreach($this->input->post('fee') as $feeid=>$fee){		

					$meth =  '<div class="input-group">'.'<span class="input-group-addon">Php</span>';
					 $meth .='<input type="text" class="input-sm form-control" name="fee['.$feeid.']" value="'.$fee.'" readonly/>';	
					$meth .= '<input type="hidden" name="feename['.$feeid.']" value="'.$feename[$feeid].' ">';					 
					$meth .= '</div>';
					$this->table->add_row('<label>'.$feename[$feeid].'</label>', $meth);
					
				}
			}
			
			$tf =  '<div class="input-group">';
			$tf .= '<span class="input-group-addon">Php</span>';
			$exc = array("class"=>'input-sm form-control', "name"=>'totalfees', 'value'=>$this->input->post('totalfees'), "readonly"=>'readonly');
			$tf .= form_input($exc);					
			$tf .= '</div>';
			$this->table->add_row('<label>Total Fees</label>', $tf);
			
			$n =  '<div class="input-group">';
			$n .= '<span class="input-group-addon">Php</span>';
			$exc = array("class"=>'input-sm form-control', "name"=>'netproceeds', 'value'=>$this->input->post('netproceeds'),"style"=>"font-weight: bold; font-size:13px; color: red", "readonly"=>'readonly');
			$n .= form_input($exc);					
			$n .= '</div>';
			$this->table->add_row('<label style="color:red">NET PROCEEDS</label>', $n);
				
			echo $this->table->generate();
	}
}	
?>