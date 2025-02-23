$(document).ready(function() {
	var pro = $( "#province" ).val();
	var base_url = "http://localhost/loans/";
	//var base_url = "http://maquiling.info/ycfc-staging/live/";
	//var base_url = "http://demo.fruitsconsulting.com/ycfcsystems/";
	$('#openloan').on('click', function(){
		var btn = $(this)
		btn.button('loading');
		bootbox.alert("hello");
	});
	
	$(".province").change(function(e) {
		 //alert(pro);
		$.ajax({
			type: "POST",
			url: base_url + "client/addnew/get_cities", //process to mail
			data: {province: $(this).val() },
			success: function(msg){
				$(".city").html(msg);
				//alert(msg);
			},
			error: function(){
				bootbox.alert('Internet Connection Problem. Please try again.');
			}
		});
	});
	
	
	function addcom(){
		alert('hello');
	}
		
	$('#clientinfoform').on('change',function(){
		$('#saveinfo').prop('disabled', false);
	});
	
	$('#saveinfo').on('click',function(e){
		e.preventDefault();
		var btn = $(this);
		btn.button('loading');
		 $.ajax({
				type: "POST",
				url: $('#clientinfoform').attr('action'), //process to mail
				data: $('#clientinfoform').serialize(),
				success: function(msg){
					bootbox.alert(msg);
					btn.button('reset');
				},
				error: function(){
					bootbox.alert(msg);
					btn.button('reset');
				}
			});
	});
	
	$('#civilstatus').change(function(){
		var stat = $('#civilstatus').val();
		if(stat == 'single'){
		$('#spfirstname').prop('disabled', true);
		$('#spmname').prop('disabled', true);
		$('#splname').prop('disabled', true);
		$('#spwork').prop('disabled', true);
		$('#spcompany').prop('disabled', true);
		$('#spsalary').prop('disabled', true);
		$('#spbdate').prop('disabled', true);
		$('#spcontact').prop('disabled', true);
		}else{
		$('#spfirstname').prop('disabled', false);
		$('#spmname').prop('disabled', false);
		$('#splname').prop('disabled', false);
		$('#spwork').prop('disabled', false);
		$('#spcompany').prop('disabled', false);
		$('#spsalary').prop('disabled', false);
		$('#spbdate').prop('disabled', false);
		$('#spcontact').prop('disabled', false);
		}
	});
	
	
	$('#pensionbutton').click(function (e){
		e.preventDefault();
		var btn = $(this);
		var url = base_url + 'loans/info/pension';
		btn.button('loading');
		$.ajax({
			type: "POST",
			url: url, //process to mail
			data: $('#loaninfo').serialize(),
			success: function(msg){
				if(msg == 1){
					bootbox.alert(msg);
					btn.button('reset');
				}else{
					bootbox.alert(msg );
					btn.button('reset');
				}
				
				},
			error: function(){
				bootbox.alert('Internet Connection Problem. Please try again.');
				btn.button('reset');
			}
		});
	});
	
	$('#loandetailsform').on('submit', function (e){
		e.preventDefault();
		var btn = $(this);
		//btn.button('loading');
		$.ajax({
			type: "POST",
			url: $(this).attr('action'), //process to mail
			data: $(this).serialize(),
			success: function(msg){	
					if (msg.indexOf("http") >= 0)
					{
						bootbox.alert("New Loan was Posted",
						function() {
							//window.location = msg;
						});
					}else{
						bootbox.alert(msg);
					}
				},
			error: function(){
				bootbox.alert(msg + "Please try again.");
				btn.button('reset');
			}
		});
	});
	
	$('#clientinfo').submit(function( event ) {
	  //bootbox.alert( "Handler for .submit() called." );
	  event.preventDefault();
	  bootbox.alert('<center><img src="'+base_url+'assets/img/loader.gif"> Loading. Please wait.</center>');
	  $.ajax({
			type: "POST",
			url: $('#clientinfo').attr('action'), //process to mail
			data: $('#clientinfo').serialize(),
			success: function(msg){
				bootbox.alert(msg)
				},
			error: function(){
				bootbox.alert('Internet Connection Problem. Please try again.');
			}
		});
	});
	
	$('#newloanform').hide();
	
	$('#NewLoan').on('click', function( event ) {
		event.preventDefault();
		var btn = $(this);
		btn.button('loading');
		$('#loandetails').hide('slow');
		$('#newloanform').show('slow');
		btn.button('reset');
		
	});
	
		
	$('#LoanInfoList').on('click', function( event ) {
		event.preventDefault();
		$('#newloanform').hide('slow');
		$('#loandetails').show('slow');
	});
	
	$('#totalcharges').tooltip('toggle');
	
				var loan;
				var terms;
				var pension;
				var monthly;
				var notarial ;
				var totalcharges ;
				var net;
				var rfpl;
				var atm ;
				var servicefee;
				var interest;
				var excess;
				var max;
			
			//$('#pension').keyup(compute);			
			$('#loanapplied').on('keyup', function(){
				autocompute();
			});
			
			$('#terms').on('change',function(){					
				computePLMax();
				autocompute();
			});
			
			
				
			$('#loancode').on('change', function(){
				var loanstatus = $('#loanstatus').val();
				if(loanstatus == 'E')
					getTerms();
				else
				getreqs();
				
				//split loan code
				var data = $('#loancode').val();
				var arr = data.split('.');
				
				if(arr[0] == 3){
					$('#pensioninput').show();
					
				}else{
					$('#pensioninput').hide();
				}
			
			});
			
			$('#method').change(autocompute);
			
			
			$('#loanstatus').change(autocompute);
			$('#computation').change(autocompute);
			
			
			
			$('#pensionamount').on('keyup', function(){
						computePLMax();
						autocompute();
			});
			
			$("#extendedTerm").on('change', function(){
				var newterm = Number($('#currentTerm').val()) + Number($("#extendedTerm").val());
				$('#terms').val(newterm);
				$('#term').val(newterm);
				var loanamount = Number($("#extendedTerm").val() * $('#monthly').val());
				$('#loanapplied').val(loanamount);				
				
				$.ajax({
					type: "POST",
					url: base_url + "loans/application/computation", //process to mail
					datatype: 'json',
					data: $('#loanamount').serialize(),
					success: function(msg){						
						//bootbox.alert(msg);
						$("#feedetails").html(msg['fees']);
						$("#pid").val(msg['pid']);
					},
					error: function(msg){						
						bootbox.alert(msg);
					}
					
				});
			});
						
			
			function computePLMax(){
				var maxloan = ($('#pensionamount').val()) * $('#terms').val();
				if($('#method').val() != 'L'){
					if (maxloan < 0) maxloan = 0;
					$('#loanapplied').val(maxloan);
				}else{
					$('#loanapplied').val($('#pensionamount').val());
				}
			}
			
			function getreqs(){
				var loancode = $('#loancode').val();	
				getTerms();
				
				$("#requirementsform").html('<center><img src="'+base_url+'assets/img/loader.gif"> Loading. Please wait.</center>');
				$("#COLForm").html('<center><img src="'+base_url+'assets/img/loader.gif"> Loading. Please wait.</center>');
				$.ajax({
					type: "POST",
					url: base_url + "loans/application/loanrequirements", //process to mail
					datatype: 'json',
					data: { loancode: loancode,
							cno: $("#cno").val(),
							clientid: $("#clientid").val(),
							branchID: $("#branchID").val()
							},
					success: function(msg){						
						$("#requirementsform").html(msg['req']);
						$("#COLForm").html(msg['col']);						
					},
					error: function(msg){
						$("#requirementsform").html(msg);
						
					}
					
				});	
			}
			
			function autocompute(){				
				
				var loancode = $('#loancode').val();	
				var loanstatus = $('#loanstatus').val();
				
				//split loan code
				var data = $('#loancode').val();
				var arr = data.split('.');
				
				if(arr[0] == 3){
					$('#pensioninput').show();
					
				}else{
					$('#pensioninput').hide();
				}
				if(loanstatus == 'E' && arr[0] == 3){
					$('#extendedTerm').show();	
				}else{
					$('#extendedTerm').hide();	
				}
				
				if(arr[0] == 3)
					computePLMax();
				
				getTerms();
				//$("#requirementsform").html('<center><img src="'+base_url+'assets/img/loader.gif"> Loading. Please wait.</center>');
				//$("#COLForm").html('<center><img src="'+base_url+'assets/img/loader.gif"> Loading. Please wait.</center>');
				$("#feedetails").html('<center><img src="'+base_url+'assets/img/loader.gif"> Loading. Please wait.</center>');
				//get requirements form	  
			   $.ajax({
					type: "POST",
					url: base_url + "loans/application/loancomputation", //process to mail
					datatype: 'json',
					data: { loancode: loancode,
							loanstatus: $('#loanstatus').val(),
							method: $('#method').val(),
							computation: $("#computation").val(),
							pensionamount: $("#pensionamount").val(),
							loanapplied: $("#loanapplied").val(),
							cno: $("#cno").val(),
							clientid: $("#clientid").val(),
							branchID: $("#branchID").val(),
							terms: $("#terms").val()},
					success: function(msg){							
						
						$("#feedetails").html(msg['fees']);
						$("#pid").val(msg['pid']);
						
					},
					error: function(msg){						
						
					}
					
				});			
				
								
			}
			
			function getTerms(){				
				
				var start = 1;
				var end = 1;
				var sterm = $('#terms').val();
						
				$.ajax({
					type: "POST",
					url: base_url + "loans/overview/loanterms", //process to mail
					data: { loancode : $('#loancode').val(),
							loanstatus: $('#loanstatus').val(),
							method: $('#method').val(),
							computation: $("#computation").val()},
					success: function(msg){
						startx = msg['min'];
						end = msg['max'];
						$('#terms').empty();
						//alert(startx);
						if($('#loanstatus').val() != 'E'){
							while(start <= end ){
								if(start >= msg['min'] ){
								
									if(sterm == start  )
									$('#terms').append("<option value='"+start+"' selected> "+start+" month(s)</option");
									else
									$('#terms').append("<option value='"+start+"'> "+start+" month(s)</option");
									
								}
								
								start++;	
							}
						}else{
							$('#maxterm').val(end);
						}
						//}						
						
					},
					error: function(msg){
						bootbox.alert(msg);
						
					}
					
				});
			}
			
			
			function compute(){
				loan = $('#loanapplied').val();
				terms = $('#terms').val();
				pension = $('#pension').val() - 100;
				monthly = loan/terms;
				max = pension*24;
				excess = pension-monthly;
				servicefee = 400;
				if(terms <= 12){
					interest = (0.02 * terms) * loan;
				}else{
					interest = ((0.02*12)+((terms-12)*0.01)) * loan;
				}
				//if(max < loan )
				//bootbox.alert('You have reached the maximum loanable amount.');
				rfpl = loan/1000*1.5*terms;
				atm = 15*terms;
				notarial = 100;				
				totalcharges = interest+servicefee+rfpl+atm+notarial;
				net = loan - totalcharges;
				
				$('#monthly').val($('#pension').val().toLocaleString(2));
				$('#maxamount').val(max.toLocaleString(2));
				$('#excess').val(excess.toLocaleString(2));
				$('#interest').val(interest.toLocaleString(2));
				$('#servicefee').val(servicefee.toLocaleString(2));
				$('#rfpl').val(rfpl.toLocaleString(2));
				$('#atm').val(atm.toLocaleString(2));
				$('#notarial').val(notarial.toLocaleString(2));
				$('#totalcharges').val(totalcharges.toLocaleString(2));
				$('#net').val(net.toLocaleString(2));
				$('#loansummary').show('slow');	
				if(excess >= 0  && loan > 0 && net > 0){
					$('#submitloan').prop('disabled', false);
					$('#excess').css('color', 'black');
				}
				else if(excess < 0 ) {
					$('#excess').css('color', 'red');
					$('#submitloan').prop('disabled', true);
				}
				
				}	

  $('#loantype').change(function () {
	    	  
	  var lt = '';
	  var bday = $('#bdate').val();
	   $( "#loantype option:selected" ).each(function() {
		lt = $('#loantype').val() + " ";
		tl = $('#loantype').val();
		$('#typeloan').val(tl);
	  });
	  if(lt == 3){
		  $('#loansubmit').prop('disabled', false);
		  if(bday != '0000-00-00'){
			  $('#loansummary').show('fast');
			  $('#monthlypension').show('fast');
		  }else{
			  bootbox.alert('Please update client\'s birthday.');
		  }
	  }else{
		  $('#loansummary').hide('fast');
		  $('#monthlypension').hide('fast');
		  $('#loansubmit').prop('disabled', true);
		  bootbox.alert("Sorry, this product is not yet available.");
		  }
		  	
	  });

	//CANCEL Loan
	
	$("#cancelloan").click( function () {
		var btn = $(this);
		btn.button("loading");
		bootbox.dialog({
			message: "Are you sure you want to cancel this loan?",
			title: "Cancel Loan Application",
			buttons: {
				success: {
				  label: "Yes",
				  className: "btn-danger",
				  callback: function() {
					$.ajax({
						type: "POST",
						url: base_url + 'loans/action/cancel', //process to mail
						data: { "loanid": $('#loanid').val() } ,
						success: function(msg){
							bootbox.alert('Loan was Cancelled', function(){
								window.location= base_url+'loans';
							});		
							btn.button("reset");					
						},
						error: function(){
							bootbox.alert('Internet Connection Problem. Please try again.');
						}
					});					
				  }
				},
				danger: {
				  label: "No",
				  className: "btn-default",
				  callback: function() {
					btn.button("reset");					
				  }
				}
			  }
		});	
	});
	
	//APPROVE Loan
	
	$("#approveloan").click( function (e) {
		e.preventDefault();
		var btn = $(this);
		btn.button("loading");
		$.ajax({
			type: "POST",
			url: base_url + "loans/application/action/approve", //process to mail
			data: $('#loanappform').serialize(),
			datatype: 'json',
			success: function(msg){
				bootbox.alert(msg['msg'],
					function(){
						location.reload(true);
					});						
				btn.button("reset");
			},
			error: function(msg){
				bootbox.alert('Please try again.');
				btn.button("reset");
			}
		});
		
	});
	
	//update requirements
	$("#reqsubmit").on("click", function() {
		var btn = $(this);
		btn.button("loading");
		$.ajax({
					type: "POST",
					url: base_url + "loans/setup/update/requirements", //process to mail
					data: $('#requirementpost').serialize(),
					success: function(msg){
						bootbox.alert(msg,
							function(){
								location.reload(true);
							});						
						btn.button("reset");
					},
					error: function(){
						bootbox.alert("error");
						btn.button("reset");
					}
				});
		
	});
	
	$("#adddep").click(function (e) {
			//Append a new row of code to the "#items" div			
			$("#dependents").append('<tr>'
				+'<td></td>'
				+'<td><input type="text" class="form-control input-sm" placeholder="First Name" name="depfname[]"  ></td>'
				+'<td><input type="text" class="form-control input-sm" placeholder="Middle Name" name="depmname[]]" ></td>'
				+'<td><input type="text" class="form-control input-sm" placeholder="Last Name" name="deplname[]" ></td>'
				+ '<td><input type="date" name="depbday[]" id="dates" placeholder="yyyy-mm-dd" class="form-control input-sm datepicker" ></td>'
				+'</tr>');
		});
		
		$("#addcreditor").click(function (e) {
			//Append a new row of code to the "#items" div
			$('#credit').append('<tr>'
				+'<td></td>'
				+'<td><input type="text" class="form-control input-sm" placeholder="Name of Creditor" name="creditor[]" id="spcontact" ></td>'
				+'<td><input type="text" class="form-control input-sm" placeholder="Address" name="creditadd[]" id="spcontact" ></td>'
				+'<td><input type="text" class="form-control input-sm" placeholder="Amount" name="creditamount[]" id="spcontact" ></td>'
				+'<td><input type="text" class="form-control input-sm" placeholder="Remarks" name="remarks[]" id="spcontact" ></td>'
				+'</tr>');
		});
		
		$("#addincome").click(function (e) {
			//Append a new row of code to the "#items" div
			$('#source').append('<tr>'
				+'<td></td>'
				+ '<td><input type="text" name="income[nature][]" class="form-control input-sm" placeholder="Enter income nature"></td>'
				+'<td><input type="number" name="income[value][]" class="form-control input-sm" placeholder="Enter Value "></td>'
			+'</tr>');
		});
		
		$("#addexpenses").click(function (e) {
			//Append a new row of code to the "#items" div
			$('#expenses').append('<tr>'
				+'<td></td>'
				+'<td><input type="text" name="expense[nature][]" class="form-control input-sm" placeholder="Enter expense"></td>'
				+'<td><input type="number" name="expense[value][]" class="form-control input-sm" placeholder="Enter Value"></td>'
				+'</tr>');
		});
		
		$("body").on("click", ".delete", function (e) {
			$(this).parent("div").remove();
		}); 
		
		$("body").on("click", ".deletetd", function (e) {
			$(this).parent("tr").remove();
		}); 
		
	$('[data-toggle="release"]').click(function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			bootbox.dialog({
			  message: "Are you sure you want to reopen this transaction?",
			  title: "Reopen Transaction",
			  buttons: {
				success: {
				  label: "Yes",
				  className: "btn-danger",
				  callback: function() {
					window.location= url;
				  }
				},
				danger: {
				  label: "No",
				  className: "btn-default",
				}
			  }
			});
		});
	
	$("#checkrelease").on('submit', function(e){
		bootbox.alert("For Release");
	});
	
	
	
	$('#paymenttype').change(showpayment);
	$('#cash').hide();
	$('#check').hide();
	$('#online').hide();
	$('#POS').show();
	
	function showpayment(){
		var div = $('#paymenttype').val();
		
		switch(div){
			case 'cash':
				$('#cash').show();
				$('#check').hide();
				$('#online').hide();
				$('#POS').hide();
			break;
			case 'check':
				$('#cash').hide();
				$('#check').show();
				$('#online').hide();
				$('#POS').hide();
			break;
			case 'online':
				$('#cash').hide();
				$('#check').hide();
				$('#online').show();
				$('#POS').hide();
			break;
			case 'POS':
				$('#cash').hide();
				$('#check').hide();
				$('#online').hide();
				$('#POS').show();
			break;
		}
		//$("#"+div).show();
	}
	
	function calculatedue() {
		var due = 0;
		var arr = $(this).parents('form').find('input[type=checkbox]:checked').map(function() {	
			return due += parseInt($(this).val());
		});
		
		
		//alert(due);
		due = parseFloat(due).toFixed(2);
		
		//var newdue = due;
		 $('#totaldue').val(due);
		 
		 var beginbal = $('#beginbal').val();
		var withdraw = $('#withdrawn').val();
		var totaldue = $('#totaldue').val();
		var balanceLeft = beginbal - withdraw;
		var excess = withdraw - totaldue;
		
		$('#amountleft').val(balanceLeft.toLocaleString(2));
		$('#excess').val(excess.toLocaleString(2));	
	}
	
	function  MethodOne()
		{
			$.getJSON("<?php echo base_url();?>client/addnew/validate_client", function(response) {
				bootbox.alert(response.first);
			});
		}
	//COMPUTE BANK BALANCE ON ATM and EXCESS OF PENSION
	$('#beginbal').keyup(bankbal);			
	$('#withdrawn').keyup(bankbal);
	$('#warning').hide();
	$('#addcollection').prop('disabled', true);
	$('.number').number( true, 2 );
	$('#beginbal').number( true, 2 );
		$('#withdrawn').number( true, 2 );
		$('#totaldue').number( true, 2 );
		
	calculatedue();
	$('div').delegate('input:checkbox', 'click', calculatedue);
	
	function bankbal(){
		
		var beginbal = $('#beginbal').val();
		var withdraw = $('#withdrawn').val();
		var totaldue = $('#totaldue').val();
		var balanceLeft = beginbal - withdraw;
		var excess = withdraw - totaldue;
		$('#amountleft').val(balanceLeft.toLocaleString(2));
		$('#excess').val(excess.toLocaleString(2));		
		
		if(balanceLeft < 0){
			$('#withdrawn').css('color', 'red');
			$('#warning').html("You entered an invalid amount. ");
			$('#warning').show('slow');
			$('#addcollection').prop('disabled', true);
		}else if(withdraw > 0){
			$('#warning').hide('slow');
			$('#withdrawn').css('color', 'black');
			$('#addcollection').prop('disabled', false);
		}
	}
	//END HERE
	var button = '<button class="btn btn-sm" id="back">Back</button>';
	//ADD COLLECTION
	$('#addcollection').on('click', function(e) {
				e.preventDefault();
				var btn = $(this);
				btn.button('loading');
				var currentForm = this;
				var clicked = $("#addcollection").attr('value');
				var form_url = $("#collectionform").attr("action");
				//bootbox.alert('<center><img src="'+base_url+'assets/img/loader.gif"></center>');
				
				 $.ajax({
					type: "POST",
					url: form_url, //process to mail
					data: $('#collectionform').serialize(),
					success: function(msg){
						$(".modal").modal('hide');
						//$("#somediv").html(msg);
						//$("#somediv").modal(); 
						//$('<div class="modal fade" id="adjustconfirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' + msg + '</div>').modal();
						//$("#collection").modal();
						bootbox.dialog({
							message: msg,
							title: "Add Collection",
							buttons: {
								danger: {
								  label: "Back",
								  className: "btn-default",
								  callback: function() {
									//$(this).modal('hide');
									$("#collection").modal();									
									btn.button("reset");					
								  }
								},
								success: {
								  label: "OK",
								  className: "btn-success",
								  callback: function() {
									location.reload(true);
									btn.button("reset");					
								  }
								}
							  }
						});
						
						btn.button('reset');
					},
					error: function(msg){
						bootbox.alert(msg);
						btn.button('reset');
					}
				});
								
		});
	
	$('#loancollateral').on('submit', function(e){
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: $(this).attr('action'), //process to mail
			data: $(this).serialize(),
			success: function(msg){
				$(".modal").modal('hide');
				if(msg == 1){
					bootbox.alert('Collateral was updated. ', function(){
						location.reload(true);
					});
				}else{
					bootbox.alert('Please check the details. ');
				}
			},
			error: function(msg){
				bootbox.alert(msg);
				btn.button('reset');
			}
		});
		
	});
	
	
	$('#forapproval').on('submit', function(e){
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: $(this).attr('action'), //process to mail
			data: { "loanid": $('#loanid').val(), "submit": 'Submit for Approval' },
			success: function(msg){
				if(msg == 1)
				{
					bootbox.alert("Loan was submitted for approval. ", function(){
						location.reload(true);
					});
				}else{
					bootbox.alert(msg);
				}
			},
			error: function(msg){
				bootbox.alert($('#loanid').val());				
			}
		});
	});
	
	
	//END HERE
});