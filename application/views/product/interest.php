<div class="panel panel-default">
	<div class="panel-heading">
    	<b>Interest Rates</b>
    </div>
    <div class="panel-body">    	
        <div class="col-md-3 well">
            <div class="col-md-12">
                <form action="<?php echo base_url();?>product/interest/getInterestTable" id="viewInterest" method="post" >
                <div class="row form-group">
                    <label>Select Product : </label> &nbsp; &nbsp;
                    <select name="loancode" class="form-control input-sm"  >
                     <?php 
                     $count =1;
                        foreach ($products->result() as $pro) {   
                                                     
                           echo '<option value="'.$pro->LoanCode.'" >'.$pro->LoanName.'</option>';
                        }
                     ?>	
                     </select>	
                 </div>	
                 <div class="row form-group">
                   <label>Payment Method</label>
                    <select name="method" class="form-control input-sm" >                	
                        <option value="M" >Monthly</option>
                        <option value="L" >Lumpsum</option>
                    </select>
                 </div>
                 <div class="row form-group">
                   <button class="btn btn-sm btn-primary">Submit</button>
                 </div>
                 </form>
            </div>
        </div>
        <div class="col-md-9"  style="padding:15px;">
        	<div class="alert alert-danger" role="alert" id="alert">
                       
              
            </div>
      		<form action="<?php echo base_url();?>product/interest/addInterest" method='post'  id="addinterest" style="display:none">
            	<div class='row form-group'>
                	<div class='col-md-3'>
                    	<label>No of months</label>
                        <input type='number' name='term' id="term" class='input-sm form-control'  required>
                    </div>
                    <div class='col-md-3'>
                    	<label>Interest in %</label>
                        <input type='number' name='interest' id="interest" class='input-sm form-control' required>
                    </div>
                    <div class='col-md-3'>
                    	<input type="hidden" name="loancode" id="loancode" >
                        <input type="hidden" name="method" id="method">
                        <input type="hidden" name="lastterm" id="lastterm">
                        <label>&nbsp;</label>
                    	<button class='btn btn-sm btn-success form-control'>Add Interest</button>
                    </div>
                </div>
             </form>
             <div class="col-md-12" >
             	<table id="interesTable" class="table table-border table-hover table-condensed">
                	<thead>
                    	<th># of months</th>
                        <th>Interest in %</th>
                    </thead>
                    <tbody id="intbody">
                    	<tr>
                        </tr>
                    </tbody>
                </table>
             </div>
        </div>

</div>
    <div class="panel-footer">
    </div>
</div>


<script src="<?php echo base_url();?>assets/js/modaljs.js" type="text/javascript"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.js" type="text/javascript"></script>
<script>
	$('#addinterest').validate();
</script>

