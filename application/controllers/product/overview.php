<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Overview extends CI_Controller {

	public $page = array ( "pagetitle" => "Product - Yusay Credit and Finance Corporation",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Header.Product");

	public $debug = false; // turn to false if live

	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');
	   $this->auth->restrict();
	}

	public function index()
	{
		$page = $this->page;
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'product/overview';		
		$this->load->view($page['template'], $page);
	}
	
	
	function prolist(){
		$page = $this->page;
		$page['submod'] = 'Product type List';
		$where = array("active"=>1);
		$data = '*';
		$page['products'] = $this->Products->get($data, $where);
		$page['main'] = 'product/list';
		$this->load->view($page['template'], $page);
	}
	
	//=======add main product=============
	
	function add(){
		header("content-type:application/json");
		$this->form_validation->set_rules("pcode", "Product Code", "required|trim|xss_clean|is_unique[product.productCode]");
		$this->form_validation->set_rules("pname", "Product Name", "required|xss_clean|is_unique[product.productName]");
		$this->form_validation->set_rules("pdesc", "Product Description", "required|trim|xss_clean");
		if($this->form_validation->run() === false){
			$result['status']=false;
			$result['msg']=validation_errors();
		}else{
			$data = array("productCode"=>$this->input->post("pcode"),
							"productName"=>$this->input->post('pname'),
							"productDescription"=>$this->input->post('pdesc'),
							"active"=>1,
							"dateAdded"=>$this->auth->localtime(),
							"addedBy"=>$this->auth->user_id());
			$this->Products->addpro($data);
			$result['status'] = true;
			$result['msg']="New Product was added.";
		}
		
		echo json_encode($result);
	}
	
	
	//======add sub product ======
	function addproduct(){
		if($_POST){
			
			if($this->product->add_product() == true)
				echo true;
			else{
				$content = validation_errors();
				$footer = '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addproduct" data-dismiss="modal">Back</button>';
				echo $this->form->modal($content,$footer);
			}

		}

	}

	function product_exist(){
		if(isset($_POST['pid']))
			$data = array("productID"=>$_POST['productID'],
						"LoanSubCode"=>$_POST['psubcode'],
						"PaymentTerm"=>$_POST['paymentterm'],
						"computation"=>$_POST['computation'],
						"loanTypeID <>"=>$_POST['pid'],
						"active"=>1);
		else
			$data = array("productID"=>$_POST['pcode'],
						"LoanSubCode"=>$_POST['psubcode'],
						"PaymentTerm"=>$_POST['paymentterm'],
						"computation"=>$_POST['computation'],
						"active"=>1);
		$table = 'loantypes';

		$pro = $this->Loansmodel->get_data_from($table, $data);
				if($pro->num_rows() > 0)
				{
					$this->form_validation->set_message("product_exist", "Product already exists.");
					return false;
				}else return true;
	}

	function details(){
		$page = $this->page;
		$page['pid'] = $this->uri->segment(3);
		$page['submod'] = "Details";

		$where = array("active"=>1);
		$data = '*';
		$page['products'] = $this->Products->get($data, $where);
		$page['product'] = $this->Loansmodel->getproductsbyID($page['pid'] );
		$page['main'] = 'product/editproducts';
		$this->load->view($page['template'], $page);
	}
	
	function update(){
		header("content-type:application/json");
		$this->form_validation->set_rules("pcode", "Product Code", "required|trim|xss_clean|callback_pro_exist");
		$this->form_validation->set_rules("pname", "Product Name", "required|xss_clean");
		$this->form_validation->set_rules("pdesc", "Product Description", "required|xss_clean");
		if($this->form_validation->run() === false){
			$msg['status']= false;
			$msg['msg']= validation_errors();
		}else{
			$data = array("productCode"=>$this->input->post("pcode"),
							"productName"=>$this->input->post('pname'),
							"productDescription"=>$this->input->post('pdesc'),							
							"dateModified"=>$this->auth->localtime(),
							"modifiedBy"=>$this->auth->user_id(),
							"active"=>$_POST['active']);
			$where = array("productID"=>$_POST['productID']);			
			$this->Products->update($data, $where);
			$msg['status']= true;
			$msg['msg']= "Product info was updated.";
		}
		
		echo json_encode($msg);
	}
	
	function pro_exist(){
		$data = array("productID <>"=>$_POST['productID'],
								"productCode" => $_POST['pcode'],
								"active"=>1);
		$table = "product";
		$pro = $this->Loansmodel->get_data_from($table, $data);
		
		if($pro->num_rows() > 0){
			$this->form_validation->set_message("pro_exist", "Product Code already exists.");
			return false;
		}else{
			return true;
		}
	}

	function info(){
		$page = $this->page;
		$page['pid'] = $this->uri->segment(4);
		$page['submod'] = "Product Details";
		
		$where = array("productID"=>$page['pid']);
		$data = array('*');
		$page['product'] = $this->Products->get($data, $where);

		$page['main'] = 'product/details';
		$this->load->view($page['template'], $page);
	}	

	function update_details(){
		
		$reqs = $this->product->requirements($_POST['pid']);
		//echo $this->db->last_query();
		$col = $this->product->CollateralsDetails($_POST['pid']);
		$ci = $this->product->ciupdates($_POST['pid']);
		if(validation_errors()==''){
			$content = "The product was updated successfully";			
		}else{
			$content = validation_errors();		
		}
		
		echo $content;
	}
	
	function updateproduct(){
		$this->db->trans_begin();
		$pro = $this->product->update_product($_POST['pid']);
		$remfees =$this->product->removefees($_POST['pid']);
		$addfees = $this->product->addfees($_POST['pid']);
		$updatefees = $this->product->updatefees($_POST['pid']);
		$reqs = $this->product->requirements($_POST['pid']);
		$ci = $this->product->ciupdates($_POST['pid']);
		$col = $this->product->CollateralsDetails($_POST['pid']);
		$int = $this->product->updateinterest($_POST['pid']);
		if(validation_errors()==''){
			$content = "The product was updated successfully.";			
		}else{
			$content = validation_errors();		
		}
		
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			//echo "rolled back";
		}
		else
		{
			$this->db->trans_commit();
			//echo "committed";
		}
		
		echo $content;
		

	}

	function feedetails(){
		if($_POST){
			echo "<h4>Loan Fees</h4>";
			$pid = $_POST['pid'];
			$loan = $_POST['loanapplied'];
			$terms = $_POST['terms'];
			$fees = $this->Loansmodel->getfees($pid);
			$monthly = $loan/$terms;
			$totalfees = 0;

			if($fees->num_rows() > 0){
				echo "<ul class='nav'>";
				foreach($fees->result() as $fee){

					if(strpos(strtolower($fee->feeName), 'existing') == false){

						if($fee->comptype == 'fixed'){

							$totalfees += $fee->value;
							echo "<li>".$fee->feeName." : ".$fee->value."</li>";

						}elseif($fee->comptype == 'formula'){

							$formula = str_replace("loan",$loan,$fee->value);
							$formula = str_replace("terms", $terms, $formula);
							eval('$newformula='.$formula.';');
							//echo $newformula;
							$totalfee += $newformula;

							echo "<li>".$fee->feeName." : ".$newformula."</li>";

						}else {

						}

					}

				}
				echo "<li> Total Fees : ".$totalfee."</li>";
				echo "<li> Monthly Due : ".$monthly."</li>";
				echo "</ul>";
			}

		}

	}

	function addfee(){

		if($this->product->addfees($_POST['pid']) == true)
		{
			echo true;
		}else{
			$content = validation_errors();
			echo $this->form->modal($content,"");
		}

	}

	function feeexist(){

		if(count($_POST['feename']) > 0){
			$fname = $_POST['feename'];
			$ftype = $_POST['feetype'];
			$fvalue = $_POST['feevalue'];

			foreach($fname as $key=>$value){
				$data = array("productID"=>$_POST['pid'],
									"feeName"=>$value,
									"active"=>1);
				$table = "productfees";
				$fee = $this->Loansmodel->get_data_from($table, $data);
				if($fee->num_rows() > 0)
				{
					$error[] = "Fee Name ".$value." already exists.";
				}

			}

		}

		if(isset($error))
		{
			$this->form_validation->set_message("feeexist", $error);
			return false;
		}else return true;

	}


	function editfee(){
		if($_POST){

		}else
		$this->load->view('product/editfee');
	}
	
	function addrole(){
		header("content-type:application/json");
		
		if(isset($_POST)){
			$branchid = $_POST['branchID'];
			$userid = $_POST['userID'];
			$fromAmount = $_POST['fromAmount'];
			$toAmount = $_POST['toAmount'];
			$productid = $_POST['pid'];
			$this->form_validation->set_rules('fromAmount',"Approve Amount from", "numeric|required|xss_clean");
			$this->form_validation->set_rules('toAmount', "Approve Amount to", "numeric|required|xss_clean|callback_checkAmount");
			if($this->form_validation->run() === FALSE){
				$return['status'] = false;
				$return['note'] = validation_errors();
				
			}else{
				$return['status'] = true;
				$data = array("branchID"=>$branchid,
									"userid"=>$userid,
									"fromAmount"=>$fromAmount,
									"toAmount"=>$toAmount,
									"productID"=>$productid,
									"dateAdded"=>$this->auth->localtime(),
									"addedBy"=>$this->auth->user_id(),
									"active"=>1);
				$this->Products->addLoanApproval($data);
				$return['note']='ok';
			}
			echo json_encode($return);
		}
	}
	
	function checkAmount(){
		if($_POST['fromAmount'] > $_POST['toAmount']){
			$this->form_validation->set_message("checkAmount", "fromAmount should be less than toAmount");
			return false;
		}else{
			return true;
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */