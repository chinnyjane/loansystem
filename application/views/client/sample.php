
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fruits Consulting Inc</title>

    <!-- Bootstrap Core CSS -->
    <link href="http://localhost/ycfc_staging/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="http://localhost/ycfc_staging/assets/css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="http://localhost/ycfc_staging/assets/css/plugins/morris.css" rel="stylesheet">
	
	 <!-- MetisMenu CSS -->
    <link href="http://localhost/ycfc_staging/assets/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="http://localhost/ycfc_staging/assets/css/plugins/dataTables.bootstrap.css" rel="stylesheet">
	
	<!-- Datepicker -->
	<link href="http://localhost/ycfc_staging/assets/css/datepicker.css" rel="stylesheet">
	
    <!-- Custom Fonts -->
    <link href="http://localhost/ycfc_staging/assets/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<script src="http://localhost/ycfc_staging/assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="http://localhost/ycfc_staging/assets/js/jquery.tablesorter.min.js"></script>
	<script type="text/javascript" src="http://localhost/ycfc_staging/assets/js/bootstrap-datetimepicker.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">
        <!-- Navigation -->	
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="http://localhost/ycfc_staging/">YCFC</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
				<!-- MSG -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
                    <ul class="dropdown-menu message-dropdown">
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>Name</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>John Smith</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>John Smith</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-footer">
                            <a href="#">Read All New Messages</a>
                        </li>
                    </ul>
                </li>
                <!-- END MSG-->
				
				<!-- ALERT / NOTIFICATIONS -->
				<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
                    <ul class="dropdown-menu alert-dropdown">
                        <li>
                            <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-primary">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-success">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-info">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-warning">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-danger">Alert Badge</span></a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">View All</a>
                        </li>
                    </ul>
                </li>
                <!-- END NOTIFICATIONS-->
				
				<!-- USER MENU -->
				<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Chinny Janes<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="http://localhost/ycfc_staging/account"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                        </li>
                        <li>
                            <a href="http://localhost/ycfc_staging/account/changepassword"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="http://localhost/ycfc_staging/logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
				<!-- END USER MENU -->
				
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li class="active">
                        <a href="http://localhost/ycfc_staging/dashboard"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="#" data-toggle="collapse" data-target="#cashmenu"><i class="fa fa-fw fa-money"></i> Cash Movement <i class="fa fa-fw fa-caret-down"></i></a>						
						<ul id="cashmenu" class="collapse ">
							<li><a href="http://localhost/ycfc_staging/cash/daily">Transactions</a></li><li><a href="http://localhost/ycfc_staging/cash/banks">Banks</a></li><li><a href="http://localhost/ycfc_staging/cash/branches">Branches and Banks</a></li><li><a href="http://localhost/ycfc_staging/cash/setup">Settings</a></li><li><a href="http://localhost/ycfc_staging/cash/newcmc">Create New CMC</a></li>						</ul>
                    </li>
					 <li>
                        <a href="http://localhost/ycfc_staging/loans"><i class="fa fa-fw fa-pencil-square-o"></i> Loan Management </i></a>
                    </li>
                    <li>
                        <a href="#" data-toggle="collapse" data-target="#clientmenu"><i class="fa fa-fw fa-users"></i> Client Management <i class="fa fa-fw fa-caret-down"></i></a>
						<ul id="clientmenu" class="collapse in">
							<li><a href="http://localhost/ycfc_staging/client/addnew">Add New Client</a></li><li><a href="http://localhost/ycfc_staging/client">Masterlist</a></li>						</ul>
                    </li>
                    <li>
                        <a href="http://localhost/ycfc_staging/product"><i class="fa fa-fw fa-cubes"></i> Product Management</a>
                    </li>
                    <!--<li>
                        <a href="bootstrap-elements.html"><i class="fa fa-fw fa-bar-chart-o"></i> Reports</a>
                    </li>-->
                    <li>
                        <a href="#" data-toggle="collapse" data-target="#settingsmenu"><i class="fa fa-fw fa-wrench"></i> Control Panel  <i class="fa fa-fw fa-caret-down"></i></a>
						<ul id="settingsmenu" class="collapse ">
							<li><a href="http://localhost/ycfc_staging/settings/user">User</a></li><li><a href="http://localhost/ycfc_staging/settings/holidays">Holidays</a></li><li><a href="http://localhost/ycfc_staging/settings/user/modules">Modules</a></li><li><a href="http://localhost/ycfc_staging/settings/user/branch">Branches</a></li><li><a href="http://localhost/ycfc_staging/settings/user/roles">Roles</a></li>						</ul>
                    </li>                  
                    <li>
                        <a href="#"><i class="fa fa-fw fa-question"></i> Help</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
        <div id="page-wrapper">
            <div class="container-fluid">
			<!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
					<ol class="breadcrumb">
						                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="http://localhost/ycfc_staging/dashboard">Dashboard</a>
                            </li>
																 <li >
									<i class="fa"></i><a href="http://localhost/ycfc_staging/client">Clients</a>
								</li>
								<li class="active">
									<i class="fa"></i> Profile</a>
								</li>
							                            
                        </ol>
                        <h1 class="page-header">
                           Clients <small>Profile</small><small class=" navbar-right">Head Office</small>
                        </h1>
                        
                    </div>
                </div>
                <!-- /.row -->
			   <!--<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">-->
<div class="main">
<div class="panel panel-green">
	<div class="panel-heading"><b>Client Name :  ABAD, RODOLFO</b></div>
	<div class="panel-body">
		<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li class="active"><a href="#home" role="tab" data-toggle="tab">Personal Info</a></li>
  <li><a href="#profile" role="tab" data-toggle="tab">Loan Info</a></li>
  <li><a href="#messages" role="tab" data-toggle="tab">Pension Info</a></li>
  <li><a href="#settings" role="tab" data-toggle="tab">Settings</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane active" id="home"><h4>Personal Information <a data-toggle="modal" data-target="#personalinfo" href="#"><i class="fa fa-pencil-square-o"></i> </a></h4>
<hr/>

<form action="http://localhost/ycfc_staging/client/profile/updateinfo" method="post" id="clientinfoform">
<div id="personal" class="panel-collapse collapse in"> 
	<div class="panel-body">
	 <table class="table table-condensed tablesort" >
<thead class="header">
<tr>
<th style='width:30%'>CLIENT NAME</th><th>ABAD, RODOLFO CA</th></tr>
</thead>
<tbody>
<tr>
<td><b>Complete Address</b></td><td>#32 St. Cecilia Ave. Dona J, Brgy. brgy. 13, Bacolod City, Negros Occidental</td></tr>
<tr>
<td><b>Date of Birth</b></td><td>June 01, 1947</td></tr>
<tr>
<td><b>Age</b></td><td>67 yrs. old</td></tr>
<tr>
<td><b>Gender</b></td><td>Male</td></tr>
<tr>
<td><b>Civil Status<b></td><td>single</td></tr>
<tr>
<td><b>Contact #</b></td><td>09123132</td></tr>
</tbody>
</table>	
	</div>
</div>
 <h4>Spouse Information <a data-toggle="modal" data-target="#spouseinfo" href="#"><i class="fa fa-pencil-square-o"></i> </a></h4>
 <hr/>
 <div class="panel-body">
  <table class="table table-condensed tablesort" >
<thead class="header">
<tr>
<th style='width:30%'>SPOUSE NAME</th><th>-</th></tr>
</thead>
<tbody>
<tr>
<td>Contact #</td><td></td></tr>
<tr>
<td>Date of Birth</td><td>-</td></tr>
<tr>
<td>Occupation</td><td></td></tr>
<tr>
<td>Company</td><td></td></tr>
<tr>
<td>Salary</td><td></td></tr>
</tbody>
</table> 
 </div>

  <h4>Dependents Information <a data-toggle="modal" data-target="#depinfo" href="#"><i class="fa fa-pencil-square-o"></i> </a></h4>
  <hr/>
 <div class="panel-collapse ">
		<div class="panel-body">
			 No dependents.		   </div>	
  </div>
  <h4>Outstanding Obligations <a data-toggle="modal" data-target="#creditor" href="#"><i class="fa fa-pencil-square-o"></i> </a></h4>
  <hr/>
  <div id="obligations" class="panel-collapse ">
		<div class="panel-body">
			 No Outstanding Obligations			
		   </div>	
  </div>


 <div class="panel-footer">
 <input type="hidden" name="client" value="9460">
 <input class="btn btn-primary btn-sm" id="saveinfo" value="Save Client Info" type="submit" disabled> &nbsp; &nbsp; <a class="btn btn-default btn-sm">Print Client Information</a>
 </div>
 
 </form>

<!-- MODAL PERSONAL INFO -->
<div class="modal fade" id="personalinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<form action="http://localhost/ycfc_staging/client/profile/updateinfo" method="post" class="formpost">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Update Personal Information</h4>
				</div>
				<div class="modal-body">
					<div class="row form-group">
						<div class="col-sm-4"><label>Name: </label>  <div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: firstname</p>
<p>Filename: client/clientinfo.php</p>
<p>Line Number: 143</p>

</div>,  							<input type="text" class="form-control input-sm" placeholder="First name" name="firstname" value="<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: firstname</p>
<p>Filename: client/clientinfo.php</p>
<p>Line Number: 144</p>

</div>" required>
						</div>
						<div class="col-sm-4"><label>Middle Name</label> 
							<input type="text" class="form-control input-sm" placeholder="Middle Name"  name="mname" value="" required>
						</div>
						<div class="col-sm-4"><label>Last Name</label> 
							<input type="text" class="form-control input-sm" placeholder="Last Name"  name="lname" value=""  required>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-sm-4"><label>Date of Birth</label> 
							<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: dob</p>
<p>Filename: client/clientinfo.php</p>
<p>Line Number: 155</p>

</div><input type="text" name="bdate" id="bdate" placeholder="yyyy-mm-dd" class="form-control input-sm datepicker" value=""><script>
				$(function() {
					var datepick = $(".datepicker" ).datepicker({format: 'yyyy-mm-dd',
					changeMonth: true,
					changeYear: true,
					weekStart: 1,
					viewMode: 2,
					minViewMode: 0
					}).on('changeDate', function(ev) {					
					}).data('datepicker');				
				});
				</script>						</div>
						<div class="col-sm-4"><label>Contact Number</label>
							<input type="text" class="form-control input-sm" id="contact" placeholder="Contact Number" name="contact" value=""  required>
						</div>
						<div class="col-sm-4"><label>Civil Status</label>
							<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: civilstatus</p>
<p>Filename: client/clientinfo.php</p>
<p>Line Number: 161</p>

</div><select name="civilstatus" id="civilstatus" class="form-control input-sm" value=""><option disabled >Civil Status</option><option value="single">Single</option><option value="married" >Married</option><option value="widow" >Window/Widower</option><option value="separated" >Separated</option></select>						</div>
					</div>
					<div class="row form-group">
						<div class="col-sm-4"><label>Province</label> 
							<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: provid</p>
<p>Filename: client/clientinfo.php</p>
<p>Line Number: 166</p>

</div><select name="province" id="province" class="form-control input-sm"><option disabled >Select Province</option><option value="1" >Abra</option> <option value="2" >Agusan del Norte</option> <option value="3" >Agusan del Sur</option> <option value="4" >Aklan</option> <option value="5" >Albay</option> <option value="6" >Antique</option> <option value="7" >Apayao</option> <option value="8" >Aurora</option> <option value="9" >Basilan</option> <option value="10" >Bataan</option> <option value="11" >Batanes</option> <option value="12" >Batangas</option> <option value="13" >Benguet</option> <option value="14" >Biliran</option> <option value="15" >Bohol</option> <option value="16" >Bukidnon</option> <option value="17" >Bulacan</option> <option value="18" >Cagayan</option> <option value="19" >Camarines Norte</option> <option value="20" >Camarines Sur</option> <option value="21" >Camiguin</option> <option value="22" >Capiz</option> <option value="23" >Catanduanes</option> <option value="24" >Cavite</option> <option value="25" >Cebu</option> <option value="26" >Compostela Valley</option> <option value="27" >Cotabato</option> <option value="28" >Davao del Norte</option> <option value="29" >Davao del Sur</option> <option value="30" >Davao Oriental</option> <option value="31" >Eastern Samar</option> <option value="32" >Guimaras</option> <option value="33" >Ifugao</option> <option value="34" >Ilocos Norte</option> <option value="35" >Ilocos Sur</option> <option value="36" >Iloilo</option> <option value="37" >Isabela</option> <option value="38" >Kalinga</option> <option value="39" >La Union</option> <option value="40" >Laguna</option> <option value="41" >Lanao del Norte</option> <option value="42" >Lanao del Sur</option> <option value="43" >Leyte</option> <option value="44" >Maguindanao</option> <option value="45" >Marinduque</option> <option value="46" >Masbate</option> <option value="47" >Metro Manila</option> <option value="48" >Misamis Occidental</option> <option value="49" >Misamis Oriental</option> <option value="50" >Mountain Province</option> <option value="51" >Negros Occidental</option> <option value="52" >Negros Oriental</option> <option value="53" >Northern Samar</option> <option value="54" >Nueva Ecija</option> <option value="55" >Nueva Vizcaya</option> <option value="56" >Occidental Mindoro</option> <option value="57" >Oriental Mindoro</option> <option value="58" >Palawan</option> <option value="59" >Pampanga</option> <option value="60" >Pangasinan</option> <option value="61" >Quezon</option> <option value="62" >Quirino</option> <option value="63" >Rizal</option> <option value="64" >Romblon</option> <option value="65" >Samar</option> <option value="66" >Sarangani</option> <option value="67" >Siquijor</option> <option value="68" >Sorsogon</option> <option value="69" >South Cotabato</option> <option value="70" >Southern Leyte</option> <option value="71" >Sultan Kudarat</option> <option value="72" >Sulu</option> <option value="73" >Surigao del Norte</option> <option value="74" >Surigao del Sur</option> <option value="75" >Tarlac</option> <option value="76" >Tawi-Tawi</option> <option value="77" >Zambales</option> <option value="78" >Zamboanga del Norte</option> <option value="79" >Zamboanga del Sur</option> <option value="80" >Zamboanga Sibugay</option> </select>						</div>
						<div class="col-sm-4"><label>City</label>
							<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: cityid</p>
<p>Filename: client/clientinfo.php</p>
<p>Line Number: 169</p>

</div><div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: provid</p>
<p>Filename: client/clientinfo.php</p>
<p>Line Number: 169</p>

</div><select name="city"  class="form-control input-sm" id="city"></select>						</div>
						<div class="col-sm-4"><label>Barangay</label>
							<input type="text" class="form-control input-sm" placeholder="Barangay" name="brgy"   value="<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: barangay</p>
<p>Filename: client/clientinfo.php</p>
<p>Line Number: 172</p>

</div>" required>
						</div>
					</div>	
					<div class="row form-group"> 
						<div class="col-sm-8">
							<label>House #, Street</label>
							<input type="text" class="form-control input-sm" placeholder="House No., Street, Barangay" name="address" value="<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: address</p>
<p>Filename: client/clientinfo.php</p>
<p>Line Number: 178</p>

</div>"  required>
						</div>
						<div class="col-sm-4">
							<label>Gender</label>
							<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: gender</p>
<p>Filename: client/clientinfo.php</p>
<p>Line Number: 182</p>

</div><select name="gender" class="form-control input-sm" value=""><option value="F">Female</option><option value="M">Male</option></select>						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
					<input type="hidden" name="client" value="9460">
					<input class="btn btn-primary btn-sm" value="Save Client Info" type="submit" >
				</div>
			</div>
		</form>
	</div>	
</div>	

<!-- MODAL SPOUSE -->
<div class="modal fade" id="spouseinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<form action="http://localhost/ycfc_staging/client/profile/updateinfo" method="post" class="formpost">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Update Spouse Information</h4>
				</div>
				<div class="modal-body">	  			
					<label>Spouse Name</label>
					<div class="row form-group">
						<div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="First name" name="spfirstname" value="" id="spfirstname" > </div>
						<div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Middle Name"  name="spmname" value="" id="spmname"></div>
						<div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Last Name" name="splname" value=""  id="splname"></div>
					</div>
					<div class="row form-group">
						<div class="col-sm-4"><label>Occupation</label><input type="text" class="form-control input-sm" placeholder="Occupation" name="spwork" id="spwork" value=""> </div>
						<div class="col-sm-4"><label>Company</label><input type="text" class="form-control input-sm" placeholder="Company"  name="spcompany"  id="spcompany" value=""></div>
						<div class="col-sm-4"><label>Salary</label><input type="text" class="form-control input-sm" placeholder="Salary" name="spsalary" id="spsalary"  value=""></div>
					</div>		 
					<div class="row form-group">
						<div class="col-sm-4"><label>Contact Number</label>
							<input type="text" class="form-control input-sm" placeholder="contact number" name="spcontact" id="spcontact" value="" ></div>	
						<div class="col-sm-4"><label>Date of Birth</label> 
							<input type="text" name="spbdate" id="spbdate" placeholder="yyyy-mm-dd" class="form-control input-sm datepicker" value="-"><script>
				$(function() {
					var datepick = $(".datepicker" ).datepicker({format: 'yyyy-mm-dd',
					changeMonth: true,
					changeYear: true,
					weekStart: 1,
					viewMode: 2,
					minViewMode: 0
					}).on('changeDate', function(ev) {					
					}).data('datepicker');				
				});
				</script>						</div> 
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
					<input type="hidden" name="client" value="9460">
					<input type="hidden" name="info" value="spouse">
					<input class="btn btn-primary btn-sm" value="Save Spouse Info" type="submit" >
				</div>
		   </div>
		</form>
	</div>
</div>
<!-- MODAL SPOUSE ENDS HERE -->

<!-- MODAL Dependents -->

<div class="modal fade" id="depinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog modal-lg"><form action="http://localhost/ycfc_staging/client/profile/updateinfo" method="post" class="formpost"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="myModalLabel">Update Dependents</h4></div><div class="modal-body"><div  id="dependents">	
	<div class="row form-group">
		<div class="col-md-3"><input type="button" id="adddep" class="btn btn-sm" value="Add Dependent"></div>						
	</div>
	No dependents.			
</div>
</div><div class="modal-footer"><button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button> &nbsp;<input type="hidden" name="client" value="9460"><input type="hidden" name="info" value="dependents"><input class="btn btn-primary btn-sm" value="Save Dependents" type="submit" ></div></div></form></div></div>

</div>
  <div class="tab-pane" id="profile">
<div id="loandetails">
<h4>Loan Information</h4>
<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#loan">New Loan</button>
	No Loans yet<div class='panel-body'><h4>Records from Old System</h4><hr/><table class="table table-striped table-condensed table-hover">
<thead>
<tr>
<th>PN</th><th>Status</th><th>Type</th><th>Term/MOP</th><th>Rel Date</th><th>Maturity Date</th><th>Amount</th><th>Cur Balance</th></tr>
</thead>
<tbody>
<tr>
<td>24162-A</td><td>closed</td><td>REM</td><td>4</td><td>2013-10-29</td><td>2014-02-28</td><td>100,000.00</td><td>0.00</td></tr>
<tr>
<td>24013</td><td>closed</td><td>REM</td><td>3</td><td>2013-01-11</td><td>2013-10-11</td><td>100,000.00</td><td>0.00</td></tr>
</tbody>
</table></div></div>



<div class="modal fade  " id="loan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">New Loan</h4>
		</div>
		<div class="modal-body" >
		<form method="post" action="http://localhost/ycfc_staging/loans/new">
			<div class="row form-group" align="center">
				<label> Choose Loan Product</label>
			</div>
			<div class="row  form-group" align="center">
				<select name='loantype' id='loantype' class=' input-lg'><option disabled selected>Select Loan Type</option><option value='1'>Real Estate Mortgage</option><option value='2'>Chattel Mortgage</option><option value='3'>Pension Loan</option><option value='4'>Salary Loan</option><option value='5'>Others</option></select>			</div>
			<div class="row  form-group" align="center">
				<input type="hidden" name="clientid" value="9460">
				<input type="submit" name="submit" value="Proceed to Loan Application" class="btn btn-success" disabled id="loansubmit">
			</div>
			</form>
		</div>  
		</div>
	</div>
 </div></div>
  <div class="tab-pane" id="messages">...</div>
  <div class="tab-pane" id="settings">...</div>
</div>
	</div>
</div></div>
			</div>
			<!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <script src="http://localhost/ycfc_staging/assets/js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="http://localhost/ycfc_staging/assets/js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="http://localhost/ycfc_staging/assets/js/plugins/morris/raphael.min.js"></script>
    <script src="http://localhost/ycfc_staging/assets/js/plugins/morris/morris.min.js"></script>
    <script src="http://localhost/ycfc_staging/assets/js/plugins/morris/morris-data.js"></script>
	<script src="http://localhost/ycfc_staging/assets/js/ui.js"></script>
    <script src="http://localhost/ycfc_staging/assets/js/bootbox.min.js"></script>
    <script src="http://localhost/ycfc_staging/assets/js/docs.min.js"></script>
	<script src="http://localhost/ycfc_staging/assets/js/bootstrap-datepicker.js"></script>
	<SCRIPT language="javascript" src="http://localhost/ycfc_staging/assets/js/check.js"></SCRIPT>  


</body>

</html>
