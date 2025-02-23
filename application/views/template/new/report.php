<html>
<head>
  <title><?php echo $formtitle;?></title>
   <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
body {
	margin-top: -10px;	
	font-size: 11px;
	//font-family: "Courier New", Courier, monospace;
}
h3 {
	font-size: 11px;
	font-weight: bold;
	font-family: Arial;
	margin: 0;
	line-height: 5px;
}
h1{
	font-size: 20px;
	font-weight: bold;
	font-family:  Old English;
	margin: 2px;
}
h2{
	font-size: 35px;
	font-weight: bold;
	font-family: ARIAL;
	margin: 2px;
}

h4{
	font-size: 11px;
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
hr{
	margin: 0px;
}
table 
{
border-collapse:collapse;
width: 100%;
font-size: 11px;
font-family: Arial;
margin-bottom: 5px;
}
.table-border td, th
{
border:.001px solid black;
padding:3px;
}
.table-noborder td,th 
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

.underline{
	font-weight: bold;
	text-decoration: underline;
}

.productCode {
	position: absolute; float: right; right:20; top: 50; width: 100px;	
}
</style>
<body onload="printpage()">
<p >
  <h1 align="center">
  <?php echo $this->auth->company;?></h1>
  </p>
   <hr/>
   <h3 align="center">
   REAL ESTATE MORTGAGE &nbsp; &nbsp; &nbsp; • &nbsp; &nbsp; &nbsp; CHATTEL MORTGAGE &nbsp; &nbsp; &nbsp; • &nbsp; &nbsp; &nbsp; PENSION LOANS
   </h3>
   <hr/>
  <?php echo $this->load->view($main); ?>
 
   
</body>
<script type="text/javascript">
  <!--
  function printpage() {
  window.print();
  }
  //-->  </script>