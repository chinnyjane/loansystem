<?php
	$loaninfo = $this->Loansmodel->getLoanbyID($loanid);
	$loan = $loaninfo->row();
	$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
	$this->table->set_template($tmpl);
		
	$client = $clientinfo->row();

	$data=array("PN"=>$loan->PNno,
					"isdeleted <>"=>1);
	$cvres = $this->Loansmodel->getTransByPN($loan->PN);
	$cv = $cvres->row();
	$fees = $this->Loansmodel->getLoanFees($loanid);
	$fee = $fees->result();
	$agent = $this->UserMgmt->get_user_byid($cv->addedBy);
	if($agent->num_rows() > 0 ){
		$a = $agent->row();
		$ag= $a->lastname.", ".$a->firstname;
	}
	$c = 0;
	$amount = strtoupper($this->loansetup->convert_number_to_words($cv->Amount_OUT));
	$explanation = $cv->explanation." PER PN No. : ".$cv->PN;
	$explanation .= "<br/><br/><p>NOTE:</p>";
	$explanation .= "<br/><br/><p>TOTAL PN AMOUNT : ".number_format($loan->approvedAmount,2)."</p>";
	$rcve = "Received from FRUITS CONSULTING INC the said amount as full/partial payment for the above explanation.";

?>

<b>KNOWN ALL MEN BY THESE PRESENTS:</b>
<p>I, <?php echo $client->firstname." ".$client->lastName;?>, of legal age <?php echo $client->civil;?>, Filipino and a resident of <?php echo $client->address;?>. for and in consideration of the <?php echo $loan->productname;?> extende to me by the YUSAY CREDIT and FINANCE CORPORATION, with business address at Yusay Arcade, Araneta St., Bacolod City, Negros Occidental, Philippines, in the total amount of <?php echo strtoupper($this->loansetup->convert_number_to_words(round($loan->approvedAmount,2))); echo $loan->approvedAmount;?> hereby commits, undertakes and warrants not to declare and/or report as loss NOR request for replacement my <?php echo "Bank here";?> ATM card with No. <?php echo "(number here)";?> covering my Savings account no. <?php echo "(account number)";?> as issued by the aforestated bank OR transfer my monthly pension benefits from <?php echo "(state SSS or GSIS)";?> to other accredited banks without informing the Yusay Credit and Finance Corporation.</p>

<p>Any violation of the above mentioned undertaking shall make me liable to the Yusay Credit and Finance Corporation pecuniary damages twice the amount of my unpaid obligation.</p>
<p>This undertaking shall be binding upon me and my successors-in-interest until my loan with the Yusay Credit and Finance Corporation shall have been fully paid and/or settled.</p>

<?php 
$this->table->add_row("Signed in the presence of: <br/><br/><br/>___________________________","&nbsp;&nbsp;&nbsp;","<br/><br/><br/>".$client->firstname." ".$client->lastName);
echo $this->table->generate();

?>

<p align="center">ACKNOWLEDGMENT</p>

<p>REPUBLIC  OF THE PHILIPPINES)
<?php echo $loan->city."      )S.S."; ?>
x------------------------------------------------------------x

<p>Before me, this <?php echo $this->auth->localdate();?>, personally appeared <?php echo $client->firstname." ".$client->lastName;?> with <?php echo $client->id_presented;?>, known to me and to me known to be the same person who executed the foregoin instrument and he/she acknowledged that the same is his/her free act and voluntary deed.</p>

<p>
Doc. No. _______;<br/>
Page No. _______;<br/>
Book No. _______; <br/>
Series No. ______; <br/>
</p>