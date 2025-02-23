<?php 
	$data = array("loantypes.productID"=>$productID);
	$products = $this->Loansmodel->get_products($data);
	//echo $this->db->last_query();
	if($products->num_rows() > 0)
	{
		$count = 1;
		//$er['postdetails'] = $products;
		//$this->load->view('template/postdetails',$er);	
		$tmpl = array ('table_open' => '<table class="table table-hover table-condensed table-bordered">' );
		$this->table->set_template($tmpl);
		
	?>	
		<div class="panel panel-default">	
			<div class="panel-heading"><b><i class="fa fa-cubes"></i> Product Management </b>&nbsp; <a href="#" data-toggle="modal" data-target="#addpro" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Create New Product</a></div>
			<form method="post" >
			<?php $this->table->set_heading("#", 'Action','Product Code','Sub-Product','Sub Code',"Term", 'Computation','Status');
			$count = 1;
				 foreach ($products->result() as $pro) {
					if($pro->active == 1) $active = "active"; else $active = "Inactive";
					$action = "<a href='".base_url()."product/details/".$pro->loanTypeID."' data-toggle='tooltip' title='View'><i class='fa fa-table'></i></a>";
					$action .= "&nbsp; &nbsp;<a href='' data-toggle='tooltip' title='Remove'><i class='fa fa-times'></i></a>";
					switch($pro->LoanSubCode){
						case 'N':
							$stat = "New";
						break;
						case 'E':
							$stat = "Extension";
						break;
						case 'A':
							$stat = "Additional";
						break;
						case 'R':
							$stat = "Renewal";
						break;
					}
					switch($pro->PaymentTerm){
						case 'M':
							$term = "Monthly";
						break;
						case 'L':
							$term = "Lumpsum";
						break;
						case 'SM':
							$term = "Semi-Monthly";
						break;
					}
					
					if(strpos($pro->LoanCode, "Promo") !== false)
						$sub = '<font color="red">'.$pro->LoanCode.'</font>';
					else $sub = $pro->LoanCode;
					$this->table->add_row($count,$action, $pro->productName,$sub, $stat, $term ,  $pro->computation, $active);
					$count++;
				 }
				 echo $this->table->generate();
			?>			
			</form>
		</div>
		
	<?php } else { echo "No Loan Products was added yet"; } ?>
	
