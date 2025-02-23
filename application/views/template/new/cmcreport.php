<html>
<head>
  <title><?php echo $filetitle;?></title>
   <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
body {
	//margin-top: -20px;	
	font-size: 12px;
	//font-family: "Courier New", Courier, monospace;
}
h3 {
	margin: 0px;
	font-family: serif;
}
h4{
	font-size: 13px;
	font-weight: bold;
	font-family: Arial;
}
footer {
  // position:absolute;
  // bottom:0px;
   width:100%;
   height:10px;   /* Height of the footer */
   font-size: 10px;
   font-family: "Courier New", Courier, monospace;
  // background:#6cf;
}
table 
{
border-collapse:collapse;
width: 100%;
font-size: 10px;
font-family: Arial;
color: #000;
}
.table-border td, .table-border  th
{
border:.001px solid black;
padding:2px;
}
.table-noborder td, .table-noborder th 
{
border:0px solid black;
padding:2px;
}
.signed
{
	text-align: center;
	vertical-align: bottom;
}
p {
	text-align: justify;
}
.bottom td, .bottom td{
	border-bottom: 1px solid #000;
}
header,hr {
	margin: 0;
}
</style>
<body onload="printpage()">
<header style="text-align:center">
  <h4>FRUITS CONSULTING INC</h4>
  
  </header>
 
  <p align="center"><b><?php echo strtoupper($formtitle);?></b></p>
  <?php echo $this->load->view($main); ?>
 
   
</body>
<script type="text/javascript">
  <!--
  function printpage() {
  window.print();
  }
  //-->  </script>