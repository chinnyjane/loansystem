<?php
$posturl = "product/overview/addfee";
		$modalid = "addfee";
		$formtitle = "Add Fee";
		echo $this->form->modalformopennorm($modalid, $posturl, $formtitle);
			
			echo '<div class="row form-group">';
				echo '<div class="col-md-6">';
					echo '<label>Fee Name</label>';
					echo '<input class="input-sm form-control" name="feename">';
				echo '</div>';
				echo '<div class="col-md-6">';
					echo '<label>Fee Type</label>';
					echo '<select name="feetype" class="input-sm form-control" required>';
						echo '<option value="fixed">Fixed</option>';
						echo '<option value="%" >%</option>';
						echo '<option value="formula">Formula</option>';
					echo '</select>';
				echo '</div>';
			echo '</div>';
			echo '<div class="row form-group">';
				echo '<div class="col-md-6">';
					echo '<label>Fee Value</label>';
					echo '<input class="input-sm form-control" name="feevalue">';
				echo '</div>';
				echo '<div class="col-md-6">';
					echo '<label>Display on Disclosure</label>';
					echo '<p><label> <input type="radio" name="feedisplay" value="1" > Yes </label> &nbsp;';
					echo '<label> <input type="radio" name="feedisplay" value="0" > No </label></p>';
				echo '</div>';
			echo '</div>';
			
		$footer = '<input type="hidden"  name="pid" value="'.$pid.'">';
		$footer .= '<input type="submit" class="btn btn-primary" name="addfee" value="Add Fee">';
		echo $this->form->modalformclose($footer);
		?>