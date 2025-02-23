<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h4>New Loan</h4>
<ul class="nav nav-tabs nav-justified">
  <li class="<?php echo $gen;?>"><a href="<?php echo base_url();?>loans/application/newloan">General Information</a></li>
  <li class="<?php echo $peninfo;?>"><a href="<?php echo base_url();?>loans/application/newloan/pensioninfo">Pension Information</a></li>
  <li class="<?php echo $loaninfo;?>"><a href="<?php echo base_url();?>loans/application/newloan/loaninfo">Loan Information</a></li>
  <li  class="<?php echo $loanreq;?>"><a >Loan Requirements</a></li>
  <li class="<?php echo $ci;?>"><a >Credit Investigation</a></li>
  <li class="<?php echo $app;?>"><a >Approval</a></li>
</ul>
<p></p>
<?php $this->load->view($form);?>
</div>
<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://localhost/ycfc_staging/assets/js/jquery.min.js"></script>
    <script src="http://localhost/ycfc_staging/assets/js/bootstrap.min.js"></script>
    <script src="http://localhost/ycfc_staging/assets/js/docs.min.js"></script>


 