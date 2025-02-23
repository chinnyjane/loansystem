<?php
$loan = $this->Loansmodel->getLoanDetails($loanid);
$loans = $loan['loaninfo']->row();
$client = $loan['clientinfo']->row();
$comaker = $loan['comaker'];
$branch = $this->UserMgmt->branch($loans->branchID);
$emps = $branch['emps'];

if($emps->num_rows() > 0){
	foreach($emps->result() as $emp){
		if($emp->name == "Branch Manager")
		{
			$position = "Branch Manager";
			$mgr = $emp->firstname." ".$emp->lastname;
		}else if($emp->name == "General Manager" and strpos(strtolower($emp->firstname), "dummy") === false){
			$position = "General Manager";
			$mgr = $emp->firstname." ".$emp->lastname;
		}
	}
}

if(empty($position)){
	$position = "General Manager";
	$mgr = "Rebecca A. Rodelas";
}

	$fees = $loan['fees'];
		$fee = $fees->result();
		
		$feename = array();
		foreach($fee as $f){
			$feename[$f->feeName] = $f->value;
		}
$col = $this->Loansmodel->get_pensioninfo($loans->pensionID)->row();

?>
<p>&nbsp;</p>
<p style="font-weight: bold">KNOW ALL MEN BY THESE PRESENTS:</p>
<p>&nbsp; &nbsp; &nbsp;This <span style="font-weight: bold">AGREEMENT</span> made and executed by and between:</p>
<p>&nbsp; &nbsp; &nbsp;<span style="font-weight: bold">FRUITS CONSULTING INC - <?php echo strtoupper($loans->branchname);?></span> , a corporation duly organized and existing under and by virtue of the laws of the Republic of the Philippines, with principal address at <?php echo $loans->branchaddress.", ".$loans->city;?>, Philippines herein represented by its<span style="font-weight: bold"> <?php echo $position;?>, <?php echo strtoupper($mgr);?></span>, and hereinafter referred to as the <span style="font-weight: bold">“FIRST PARTY”;</span></p>

<p align="center">- and- </p>

<p>&nbsp; &nbsp; &nbsp;<span style="font-weight: bold"><?php echo $client->firstName." ".$client->LastName;?></span> of legal age, Filipino, <?php echo $client->civilStatus;?> and with residential address at <?php echo $client->address;?>, <?php echo $client->barangay;?>, <?php echo $client->cityname;?> and hereinafter referred to as the <span style="font-weight: bold">“SECOND PARTY”</span>; </p>

<p>&nbsp; &nbsp; &nbsp;<span style="font-weight: bold">WITNESSETH THAT:</span>
<p>&nbsp; &nbsp; &nbsp;The <span style="font-weight: bold">FIRST PARTY</span> is a financing corporation primarily engaged in the lending business;
<p>&nbsp; &nbsp; &nbsp;The <span style="font-weight: bold">SECOND PARTY</span> is a pensioner of the <span style="font-weight: bold"><?php echo strtoupper($col->PensionType);?></span> program and is desirous of taking out a loan from the <span style="font-weight: bold">FIRST PARTY</span>;
<p>&nbsp; &nbsp; &nbsp;<span style="font-weight: bold">NOW, THEREFORE</span>, for and in consideration of the foregoing premises, the <span style="font-weight: bold">PARTIES AGREED</span> as they hereby agree as follows:
<p>&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;a.	That the <span style="font-weight: bold">SECOND PARTY</span> had obtained a loan in the amount of <span style="font-weight: bold"> <?php echo strtoupper($this->loansetup->convert_number_to_words($loans->approvedAmount));?> PESOS ( <?php echo number_format($loans->approvedAmount,2);?>)</span> under Promissory Note Number <?php echo $loans->PN;?>;
<p>&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;b.	That the <span style="font-weight: bold">FIRST PARTY</span> shall charged the <span style="font-weight: bold">SECOND PARTY</span> the amount of <span style="font-weight: bold"> <?php echo strtoupper($this->loansetup->convert_number_to_words($feename['RFPL']));?> PESOS ( <?php echo number_format($feename['RFPL'],2);?>)</span> against the loan take out of the latter to be used for the <span style="font-weight: bold"> RESERVE FUND for PENSION LOAN (RFPL);</span>
<p>&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;c.	That the <span style="font-weight: bold">PARTIES</span> agree that the Reserve Fund for Pension Loan (RFPL) shall be used only in the event the <span style="font-weight: bold">SECOND PARTY</span> fails to pay off the loan in case of death and only when the event occurs beyond the two (2) months contestability period to be reckoned from the date of the release of the loan proceeds;
<p>&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;d.	The <span style="font-weight: bold">PARTIES</span> likewise agree that in the event the said Reserve Fund for Pension Loan (RFPL) would be rendered insufficient to fully pay the balance of the loan, the <span style="font-weight: bold">SECOND PARTY’s</span> co-maker shall continue to pay the balance until full payment thereof;
<p>&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;e.	In no case shall the <span style="font-weight: bold">SECOND PARTY’s</span> heirs and/or successors-in-interest have any claim against the RFPL, the purpose of the latter being only to insure the loan of the <span style="font-weight: bold">SECOND PARTY</span> in favour of the <span style="font-weight: bold">FIRST PARTY</span>
<p>&nbsp; &nbsp; &nbsp;<span style="font-weight: bold">IN WITNESS WHEREOF</span>, the parties hereunto set their hands this ___________________________ at <?php echo $loans->city;?>, Philippines.
<p>&nbsp;</p>
<?php
$c = array();
	if(!empty($comaker)){
		if($comaker->num_rows() > 0){
			$count =1;
			foreach($comaker->result() as $com){
				if($com->clientID != ''){
					$clientinfo = $this->Clientmgmt->getclientinfoByID($com->clientID)->row();
					$c[] = $clientinfo->firstName." ".$clientinfo->MiddleName.". ".$clientinfo->LastName."<br/>ID NO : <u> ".$clientinfo->id_presented." </u>";
					$count++;
				}
			}
		}
	}
	if(count($c) > 0){
		$com = $c[0]."<br/>"; 
		if(count($c) > 1)
			$com2 = $c[1]."<br/>"; 
		else $com2 = '';
	}else{
		$com = '';
		$com2 = '';
	}
?>
<table class="table table-no-boredered" width="90%">
	<tr style="font-weight: bold">
		<td style="font-weight: bold">FIRST PARTY	</td>
		<td style="font-weight: bold">SECOND PARTY</td>
	</tr>
	<tr >
		<td height="50px" style="font-weight: bold">Fruits Consulting Inc	</td>
		<td style="font-weight: bold"><?php echo $client->firstName." ".$client->LastName."<br/>ID NO : <u> ".$client->id_presented." </u>";?>		
		</td>
	</tr>
	<tr>
		<td height="50px" colspan="2">Represented by:	</td>
	</tr>
	<tr >
		<td style="padding-top: 10px; font-weight: bold"><?php echo $mgr;?><br/><?php echo $position;?><br/>  	</td>
		<td style="font-weight: bold"><?php echo $com;?><br/>Comaker<br/> 	</td>
	</tr>
</table>
<table class="table table-no-boredered">
	<tr>
	<td colspan="3" align="center">SIGNED IN THE PRESENCE OF: </td>	
	</tr>
	<tr>
	<td style="border-bottom: 1px solid #000; height: 50px"> </td>	
	<td> </td>	
	<td style="border-bottom: 1px solid #000; height: 50px"> </td>	
	</tr>
</table>
						
	


<p align="center" style="font-weight:bold">ACKNOWLEDGMENT</p>
<p style="font-weight:bold">REPUBLIC OF THE PHILIPPINES)<br/><?php echo strtoupper($loans->city);?>  &nbsp; &nbsp; ) S.S. <br/>
X - - - - - - - - - - - - - - - - - - - - -X </p>

<p>&nbsp; &nbsp; &nbsp; <span style="font-weight: bold">BEFORE ME</span>, this ___________________________, in  <?php echo $loans->city;?>, Philippines, personally appeared the above named parties with their corresponding identifications below their names, known to me to be the same persons who executed the foregoing instrument consisting of two (2) pages including this page where this acknowledgment is written and who acknowledged to me that the same is their free act and deed.</p>
	
	<p>&nbsp; &nbsp; &nbsp; <span style="font-weight: bold">IN WITNESS WHEREOF</span>, I have hereunto set my hand and affix my notarial seal, on the day, year and place above written.</p>

<p>
Doc. No. __________;<br/>
Page No. __________;<br/>
Book No. __________;<br/>
Series of 2015.</p>


	