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

<!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="<?php echo base_url();?>assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<style>
.reporttable 
{
border-collapse:collapse;
width: 100%;
font-size: 9px;
}
.reporttable table, td, th
{
border:1px solid black;
padding:2px;
}
footer {
   position:absolute;
   bottom:-20px;
   width:100%;
   height:20px;   /* Height of the footer */
   font-size: 12px;
   font-family: "Courier New", Courier, monospace;
  // background:#6cf;
}
</style>
<script language="Javascript1.2">
  <!--
  function printpage() {
  //window.print();
  }
  //-->
</script>
</head>
<body onload="printpage()">
<?php //if(isset($menu)){$this->load->view($menu);} ?>
<?php $this->load->view($main); ?>
   <footer>
  
  <?php 
  //echo "Generated: ".$this->auth->localtime();
  //echo "&nbsp; | &nbsp;";
 // echo "Printed by : ".$this->auth->fullname(); ?>
  </footer>
</body></html>