<?php
	$feeid = $this->uri->segment(4);
	$where = array("feeID"=>$feeid);
	$table = "productfees";
	$feedetail = $this->Loansmodel->get_data_from($table, $where);
	
	$fees = $feedetail->row();
	$posturl = "product/overview/editfee";
		$modalid = "editfee";
		$formtitle = "Update Fee";
		echo $this->form->modalformopennorm($modalid, $posturl, $formtitle);
			echo '<div class="row form-group">';
				echo '<div class="col-md-6">';
					echo '<label>Fee Name</label>';
					echo '<input class="input-sm form-control" name="feename" value="'.$fees->feeName.'" required>';
				echo '</div>';
				echo '<div class="col-md-6">';
					echo '<label>Fee Type</label>';
					echo '<select name="feetype" class="input-sm form-control" required>';
						
						echo '<option value="fixed" ';
								if ($fees->comptype == "fixed") echo "selected";
						echo '>Fixed</option>';
						echo '<option value="%" ';
							if ($fees->comptype == "%") echo "selected";
						echo '>%</option>';
						echo '<option value="formula" ';
							if ($fees->comptype == "formula") echo "selected";
						echo '>Formula</option>';
					echo '</select>';
				echo '</div>';
			echo '</div>';
			echo '<div class="row form-group">';
				echo '<div class="col-md-6">';
					echo '<label>Fee Value</label>';
					echo '<input class="input-sm form-control" name="feevalue" value="'.$fees->value.'" >';
				echo '</div>';
				echo '<div class="col-md-6">';
					echo '<label>Display on Disclosure</label>';
					echo '<p><label> <input type="radio" name="feedisplay" value="1" > Yes </label> &nbsp;';
					echo '<label> <input type="radio" name="feedisplay" value="0" > No </label></p>';
				echo '</div>';
			echo '</div>';
			
		$footer = '<input type="hidden"  name="pid" value="'.$fees->feeID.'">';
		$footer .= '<input type="submit" class="btn btn-primary" name="editfee" value="Update Fee">';
		echo $this->form->modalformclose($footer);
		?>
		
	