<?php 
$tmpl = array ('table_open' => '<table class="table table-condensed table-hover " width="50%" align="center" >',
			'thead_open' => '<thead class="header">'); 
$this->table->set_template($tmpl);
$products = $this->Loansmodel->get_productcodes();

?>
<table class="table table-condensed table-hover " style="width: 50%" align="center" >
	<tr>
    	<td align="right">Select Loan type:</td>
        <td><select name="loancode" class="form-control input-sm" id="loancode" <?php echo $disabled;?>>
         <?php 
         	$count =1;
            foreach ($products->result() as $pro) {                             
                if($pro->LoanCode == $loan->LoanCode)
                $select = 'selected';
                else
                $select = '';
                echo '<option value="'.$pro->LoanCode.'" '.$select.'>'.$pro->LoanName.'</option>';
            }
         ?>	
         </select></td>
    </tr>
</table>