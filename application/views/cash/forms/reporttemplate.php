<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="<?php echo base_url();?>assets/img/logo.png">
<title>Fruits Consulting Inc</title>
<!-- Bootstrap core CSS -->
<link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="<?php echo base_url();?>assets/css/dashboard.css" rel="stylesheet">
<!-- Datepicker -->
<link href="<?php echo base_url();?>assets/css/datepicker.css" rel="stylesheet">
<!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="<?php echo base_url();?>assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style id="holderjs-style" type="text/css"></style>
<style>
.reporttable 
{
border-collapse:collapse;
width: 100%;
font-size: 10px;
}
.reporttable table, td, th
{
border:1px solid black;

}
</style>
</head>
<body style="">
<div class="container-fluid">

<?php //if(isset($menu)){$this->load->view($menu);} ?>
<?php $this->load->view($main); ?>

</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
     <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
	 <script src="<?php echo base_url();?>assets/js/ui.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/docs.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/bootstrap-datepicker.js"></script>
	<SCRIPT language="javascript" src="<?php echo base_url();?>assets/js/check.js"></SCRIPT>
  
</body></html>