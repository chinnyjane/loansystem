$(document).ready(function() {
		//var base_url = "http://maquiling.info/ycfc-staging/live/";
		//var base_url = "http://localhost/bitbucket/ycfc-cash/";
		var base_url = "http://demo.fruitsconsulting.com/ycfcsystems/";
		// Support for AJAX loaded modal window.
		// Focuses on first input textbox after it loads the window.
		$('[data-toggle="modal"]').click(function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			if (url.indexOf('#') == 0) {
				$(url).modal('open');
			} else {
				$.get(url, function(data) {
					$('<div class="modal fade" id="collection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">' + data + '</div>').modal();
				}).success(function() { $('input:text:visible:first').focus(); });
			}
		});
		
		$("[name='lock']").bootstrapSwitch();
		
		$("#removefromform").click(function(e) {
			e.preventDefault();
			var form_url = $("#updateform").attr('action');
			bootbox.dialog({
			  message: "Are you sure you want to remove this transaction?",
			  title: "Remove Transaction",
			  buttons: {
				success: {
				  label: "Yes",
				  className: "btn-danger",
				  callback: function() {
					var info = new Array( $('#updateform').serialize(), "submit=Remove" );
					$.ajax({
						type: "POST",
						url: form_url, //process to mail
						data:  info ,
						success: function(msg){
							alert(msg);								
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
				}
			  }
			});
		});
		
		$('[data-toggle="reverse"]').click(function(e) {
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
		
		$('[data-toggle="remove"]').click(function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			bootbox.dialog({
			  message: "Are you sure you want to remove this transaction?",
			  title: "Remove Transaction",
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
		
		$('[data-toggle="verify"]').click(function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			var div = $(this).attr("name");
			var act = $(this).attr("id");
			bootbox.dialog({
			  message: "This is to confirm that this transaction is valid and correct. No corrections will be made after verification. Please click Yes to verify. ",
			  title: "Verify Transaction",
			  buttons: {
				success: {
				  label: "Yes",
				  className: "btn-danger",
				  callback: function() {
					$.ajax(url)
						.done(function( msg ) {
							//bootbox.alert(msg);
								if(msg == "ok"){
									bootbox.alert("Transaction was verified.",
									function(){
										$("#"+div).html("<span class='glyphicon glyphicon-check'></span>");
										$("#"+act).html("<span class='glyphicon glyphicon-lock'></span>");
									});
								}else{
									bootbox.alert("Transaction was not verified. Please try again.")
								}
						 });					
				  }
				},
				danger: {
				  label: "No",
				  className: "btn-default",
				}
			  }
			});
		});
		
		$('#myTab a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		})
		$('#myTab a:first').tab('show') // Select first tab
		
		$('#locktrans').click(function(e) {
				e.preventDefault();
				
				var currentForm = this;
				
				var clicked = $("#adjustpost").attr('value');
				var form_url = $("#managetrans").attr("action");
				bootbox.dialog({
				  message: "You are about to close the CMC.  Are you sure all details are correct?",
				  title: "Lock CMC Transaction",
				  buttons: {
					success: {
					  label: "Yes",
					  className: "btn-danger",
					  callback: function() {
						 $.ajax({
							type: "POST",
							url: form_url, //process to mail
							data: $('#managetrans').serialize(),
							success: function(msg){							
									bootbox.alert(msg,
									function(){
										window.location = form_url;
									});								
							},
							error: function(){
								bootbox.alert(msg+'Please try again.');
							}
						});
						
					  }
					},
					danger: {
					  label: "No",
					  className: "btn-default",
					}
				  }
				});			
								
		});
		
		
		$('#approvetrans').click(function(e) {
				e.preventDefault();
				
				var currentForm = this;				
				var clicked = $("#adjustpost").attr('value');
				var form_url = $("#managetrans").attr("action");
				bootbox.dialog({
				  message: "Are you sure you want to approve this transaction?",
				  title: "Approve CMC Transaction",
				  buttons: {
					success: {
					  label: "Yes",
					  className: "btn-danger",
					  callback: function() {
						 $.ajax({
							type: "POST",
							url: form_url, //process to mail
							data: $('#managetrans').serialize(),
							success: function(msg){							
									bootbox.alert(msg,
									function(){
										window.location = form_url;
									});								
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
					}
				  }
				});			
								
		});
		
		$('#opentrans').click(function(e) {
				e.preventDefault();
				
				var currentForm = this;
				var clicked = $("#adjustpost").attr('value');
				var form_url = $("#opentransaction").attr("action");
				bootbox.dialog({
				  message: "You are about to reopen the CMC.  ",
				  title: "Open CMC Transaction",
				  buttons: {
					success: {
					  label: "Yes",
					  className: "btn-danger",
					  callback: function() {
						 $.ajax({
							type: "POST",
							url: form_url, //process to mail
							data: $('#opentransaction').serialize(),
							success: function(msg){							
									bootbox.alert("Transaction was opened.",
									function(){
										window.location = form_url;
									});								
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
					}
				  }
				});			
								
		});
		
		$('#verifytrans').click(function(e) {
				e.preventDefault();
				$('#alert').hide();
				$('#alert').html('');
				var currentForm = this;
				var clicked = $("#adjustpost").attr('value');
				var form_url = $("#verifytransaction").attr("action");
				bootbox.dialog({
				  message: "This is to confirm that this transaction is valid and correct. No corrections will be made after verification. Please click Yes to verify",
				  title: "Verify Transaction",
				  buttons: {
					success: {
					  label: "Yes",
					  className: "btn-danger",
					  callback: function() {
						 $.ajax({
							type: "POST",
							url: form_url, //process to mail
							data: $('#verifytransaction').serialize(),
							success: function(msg){
								bootbox.alert("Transaction was verified.",
								function(){
									window.location = form_url;
								});								
							},
							error: function(){
								bootbox.alert("error");
							}
						});
						
					  }
					},
					danger: {
					  label: "No",
					  className: "btn-default",
					}
				  }
				});							
		});
		
		$('#adjustpost').click(function(e) {
				var btn = $(this);
				btn.button('loading');
				e.preventDefault();
				var currentForm = this;
				var clicked = $("#adjustpost").attr('value');
				var form_url = $("#adjustmentform").attr("action");
				
				//bootbox.alert('<center><img src="'+base_url+'assets/img/loader.gif"><br/>Loading... Please wait.</center>');
				//$('<div class="modal fade" id="adjustconfirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>').modal();
				  $.ajax({
					type: "POST",
					url: form_url, //process to mail
					data: $('#adjustmentform').serialize(),
					success: function(msg){
						$(".modal").modal('hide');
						//if(msg == true){
							bootbox.dialog({
							  message: msg,
							  title: "Verify Transaction",
							  buttons: {
								success: {
								  label: "Add Transaction",
								  className: "btn-danger",
								  callback: function() {
									 $.ajax({
										type: "POST",
										url: base_url+"cash/addtransaction", //process to mail
										data: $('#adjustmentform').serialize() ,
										success: function(msgs){
											if(msgs == "Transaction was posted."){
												bootbox.alert(msgs,
												function(){
													//$("#coltable").append("<tr><td>Refresh Page to see the changes</td></tr>");
													//location.reload(true);
													$.ajax({
														type: "POST",
														url: base_url+"cash/daily/trans",
														data: {transid: $("#transid").val(), 
																status: $('#status').val(),
																trans: 'adjustment'},
														success: function(msg){
															$('#adj').html(msg);
														}
													});
												});
											}else{
												bootbox.alert(msgs,
												function(){
													$("#adjust").modal();
												});
											}
																			
										},
										error: function(){
											bootbox.alert("error");
										}
									});
									
								  }
								},
								danger: {
								  label: "Back",
								  className: "btn-default",
								  callback: function(){
									$('#adjust').modal();
								  }
								}
							  }
							});	
						//}else{
							//$('<div class="modal fade" id="adjustconfirm" tabindex="-1" role="dialog" //aria-labelledby="myModalLabel" aria-hidden="true">' + msg + '</div>').modal();
						//}
						btn.button('reset');
					},
					error: function(){
						bootbox.alert('Internet Connection Problem. Please try again.');
						btn.button('reset');
					}
				});
								
		});	
	
		$('#collectionpost').click(function(e) {
				e.preventDefault();
				var btn = $(this);
				btn.button('loading');
				var currentForm = this;
				var clicked = $("#collectionpost").attr('value');
				var form_url = $("#collectionform").attr("action");
				var info = new Array( $('#collectionform').serialize(), "submit=Add Collection" );
				 $.ajax({
					type: "POST",
					url: form_url, //process to mail
					data: $('#collectionform').serialize(),
					success: function(msg){
						$(".modal").modal('hide');
						//if(msg == true){
							bootbox.dialog({
							  message: msg,
							  title: "Verify Transaction",
							  buttons: {
								success: {
								  label: "Add Transaction",
								  className: "btn-danger",
								  callback: function() {
									 $.ajax({
										type: "POST",
										url: base_url+"cash/addtransaction", //process to mail
										data: $('#collectionform').serialize() ,
										success: function(msgs){
											if(msgs == "Transaction was posted."){
												bootbox.alert(msgs,
												function(){
													//$("#coltable").append("<tr><td>Refresh Page to see the changes</td></tr>");
													//location.reload(true);
													$.ajax({
														type: "POST",
														url: base_url+"cash/daily/trans",
														data: {transid: $("#transid").val(), 
																status: $('#status').val(),
																trans: 'collection'},
														success: function(msg){
															$('#col').html(msg);
														}
													});
												});
											}else{
												bootbox.alert(msgs,
												function(){
													$("#collection").modal();
												});
											}
																			
										},
										error: function(){
											bootbox.alert("error");
										}
									});
									
								  }
								},
								danger: {
								  label: "Back",
								  className: "btn-default",
								  callback: function(){
									$('#collection').modal();
								  }
								}
							  }
							});	
						//}else{
							//$('<div class="modal fade" id="adjustconfirm" tabindex="-1" role="dialog" //aria-labelledby="myModalLabel" aria-hidden="true">' + msg + '</div>').modal();
						//}
						btn.button('reset');
					},
					error: function(){
						bootbox.alert('Internet Connection Problem. Please try again.');
						btn.button('reset');
					}
				});
				//bootbox.alert('<center><img src="'+base_url+'assets/img/loader.gif"></center>');
				 /*$.ajax({
					type: "POST",
					url: form_url, //process to mail
					data: $('#collectionform').serialize(),
					success: function(msg){
						$(".modal").modal('hide');
						$('<div class="modal fade" id="adjustconfirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' + msg + '</div>').modal();
						btn.button('reset');
					},
					error: function(){
						bootbox.alert('Internet Connection Problem. Please try again.');
						btn.button('reset');
					}
				});*/
								
		});
		
		$('#disbursepost').click(function(e) {
				e.preventDefault();
				var btn = $(this);
				btn.button('loading');
				var currentForm = this;
				var clicked = $("#disbursepost").attr('value');
				var form_url = $("#disburseform").attr("action");
				var info = new Array( $('#disburseform').serialize(), "submit=Add Disbursement" );
				//bootbox.alert('<center><img src="'+base_url+'assets/img/loader.gif"></center>');
				 $.ajax({
					type: "POST",
					url: form_url, //process to mail
					data: $('#disburseform').serialize(),
					success: function(msg){
						$(".modal").modal('hide');
						//if(msg == true){
							bootbox.dialog({
							  message: msg,
							  title: "Verify Transaction",
							  buttons: {
								success: {
								  label: "Add Transaction",
								  className: "btn-danger",
								  callback: function() {
									 $.ajax({
										type: "POST",
										url: base_url+"cash/addtransaction", //process to mail
										data: $('#disburseform').serialize() ,
										success: function(msgs){
											if(msgs == "Transaction was posted."){
												bootbox.alert(msgs,
												function(){
													//$("#coltable").append("<tr><td>Refresh Page to see the changes</td></tr>");
													//location.reload(true);
													$.ajax({
														type: "POST",
														url: base_url+"cash/daily/trans",
														data: {transid: $("#transid").val(), 
																status: $('#status').val(),
																trans: 'disbursement'},
														success: function(msg){
															$('#dis').html(msg);
														}
													});
												});
											}else{
												bootbox.alert(msgs,
												function(){
													$("#disburse").modal();
												});
											}
																			
										},
										error: function(){
											bootbox.alert("error");
										}
									});
									
								  }
								},
								danger: {
								  label: "Back",
								  className: "btn-default",
								  callback: function(){
									$('#disburse').modal();
								  }
								}
							  }
							});	
						//}else{
							//$('<div class="modal fade" id="adjustconfirm" tabindex="-1" role="dialog" //aria-labelledby="myModalLabel" aria-hidden="true">' + msg + '</div>').modal();
						//}
						btn.button('reset');
					},
					error: function(){
						bootbox.alert('Internet Connection Problem. Please try again.');
						btn.button('reset');
					}
				});
								
		});
		
		$("#finalpost").click(function(e) {
			e.preventDefault();
			var btn = $(this);
				btn.button('loading');
			var form_url = $("#finalform").attr("action");
			//bootbox.alert('<center><img src="'+base_url+'assets/img/loader.gif"></center>');
			$.ajax({
				type: "POST",
				url: form_url, //process to mail
				data: $('#finalform').serialize(),
				success: function(msg){
					bootbox.alert('Transaction was posted.');
					btn.button('reset');
				},
				error: function(){
					bootbox.alert('Internet Connection Problem. Please try again.');
					btn.button('reset');
				}
			});
		});
		
		$('.formpost').on('submit', function (event) {
			event.preventDefault();
			var form = $(this);
			$.ajax({
				type: "POST",
				url: form.attr("action"), //process to mail
				data: form.serialize(),
				success: function(msg){
					$(".modal").modal('hide');
					if(msg == '1')
						location.reload();
					else{
						bootbox.alert(msg, function(){
						location.reload();
						});
					}
					//btn.button('reset');
				},
				error: function(msg){
					bootbox.alert(msg);
					//btn.button('reset');
				}
					
			});
		});
		
		$('.jquerypost').on('submit', function (event) {
			event.preventDefault();
			var form = $(this);
			$.ajax({
				type: "POST",
				url: form.attr("action"), //process to mail
				data: form.serialize(),
				success: function(msg){
					$(".modal").modal('hide');
					if(msg['stat'] == '1')
						bootbox.alert(msg['msg'], function(){
							location.reload();
						});
					else{
						bootbox.alert(msg['msg']);
					}
					//btn.button('reset');
				},
				error: function(msg){
					bootbox.alert(msg);
					//btn.button('reset');
				}
					
			});
		});
		
		$('.modal').on('hidden.bs.modal', function () {
			 //location.reload();
		})
		
		$('.postform').on('submit', function (event) {
			event.preventDefault();
			var form = $(this);
			$.ajax({
				type: "POST",
				url: form.attr("action"), //process to mail
				data: form.serialize(),
				success: function(msg){
					$(".modal").modal('hide');
					$('<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' + msg + '</div>').modal();
					btn.button('reset');
				},
				error: function(){
					bootbox.alert(msg);
					btn.button('reset');
				}
					
			});
		});
	
	 var hash = window.location.hash;
		  hash && $('ul.nav a[href="' + hash + '"]').tab('show');

		  $('.nav-tabs a').click(function (e) {
			$(this).tab('show');
			var scrollmem = $('body').scrollTop();
			window.location.hash = this.hash;
			$('html,body').scrollTop(scrollmem);
		  });
	
	
	
	$('#viewInterest').on('submit', function (event) {
		event.preventDefault();
		var form = $(this);
		var data = {
			  "action": "test"
		};
		data = $(this).serialize() + "&" + $.param(data);
		$("#intbody").empty();
		$("#intbody").append("<tr></tr>");
		$.ajax({
			type: "POST",
      		dataType: "json",
			url: form.attr("action"), //process to mail
			data: data,
			success: function(msg){
				//$(".modal").modal('hide');
				//$("#interestTable").html(msg['loancode']);
				//bootbox.alert(msg['sql']);
				var term = msg['lastterm'];
				var minterm ;
				$('#loancode').val(msg['loancode']);
				$('#method').val(msg['method']);
				
				$('#addinterest').show();
				
				if(msg['json'] == 'false'){
					var content;					
					content =  "<div class='row'><div class='col-md-4'>No Interest Rate Yet.</div></div>";
					$('#interesTable >  tbody').append(content);									
				}else{
					//$('#interesTable').html('');	
					//$('#interestTable').append('<table class="table table-border table-hover table-condensed">');				
					$.each(msg['int'], function(key, val) {
						if(val.term != null)
					   $('#interesTable > tbody:last').append('<tr><td>' + val.term + '</td><td> ' + val.interest +'</td><td><a href="#editinterest"  data-toggle="modal" >Update</a></td></tr>');
					   else
					   $('#interesTable >  tbody').append('<div>No Interest Rates yet.</div>');
					   minterm = val.minTerm;
					});	
					//$('#interestTable').append('<table>');	
				}
				if(term=='' ) 
					term = minterm;
				if(term <= 0)
					term = 1;
				
				$('#term').val(term);
			},
			error: function(msg){
				bootbox.alert(msg);				
			}
				
		});
	});
	
	
	
	$('#alert').hide();
	
	$('#addinterest').on('submit', function (event) {
		event.preventDefault();		
		var data = $(this).serialize();
		$.ajax({
			type: "POST",
      		dataType: "json",
			url: $(this).attr("action"), //process to mail
			data: data,
			success: function(msg){;
				if(msg['status'] == 1)			
				clearForm($('#addinterest'));
				else
				{
					//$.each(msg['errors'], function(key, val) {
						$('#alert').html(msg['errors']);
					//});
					updateInterest();
					$('#alert').show('slow');	
				}
			},
			error: function(msg){
				$('#alert').html(msg);
				$('#alert').show('slow');				
			}
		});
	});
	
	 $('#addinterest button.btn').prop('disabled', 'disabled');   // disables button
	function clearForm(form)
	{
		
		$(':input', form).not(':button, :submit, :reset, :hidden, :checkbox, :radio').val('');
		$(':checkbox, :radio', form).prop('checked', false);
	}
	
	 $('#addinterest input').on('keyup blur', function () { // fires on every keyup & blur
        if ($('#addinterest').valid()) {                   // checks form for validity
            $('#addinterest button.btn').prop('disabled', false);        // enables button
        } else {
            $('#addinterest button.btn').prop('disabled', 'disabled');   // disables button
        }
    });
	
	
	
	function updateInterest(){
		var data = {
			  "action": "test"
		};
		data = $('#viewInterest').serialize() + "&" + $.param(data);
		$.ajax({
			type: "POST",
      		dataType: "json",
			url: $('#viewInterest').attr("action"), //process to mail
			data: data,
			success: function(msg){
			
				var term = msg['lastterm'];
				var minterm ;
				$('#loancode').val(msg['loancode']);
				$('#method').val(msg['method']);
				
				$('#addinterest').show();
				
				if(msg['json'] == 'false'){
					var content;					
					content =  "<div class='row'><div class='col-md-4'>No Interest Rate Yet.</div></div>";
					$('#interesTable >  tbody').append(content);									
				}else{
								
					$.each(msg['int'], function(key, val) {
						if(val.term != null)
					   $('#interesTable > tbody:last').append('<tr><td>' + val.term + '</td><td> ' + val.interest +'</td><td><a href="#editinterest"  data-toggle="modal" >Update</a></td></tr>');
					   else
					   $('#interesTable >  tbody').append('<div>No Interest Rates yet.</div>');
					   minterm = val.minTerm;
					});						
				}
				if(term=='' ) 
					term = minterm;
				if(term <= 0)
					term = 1;
				
				$('#term').val(term);
			},
			error: function(msg){
				bootbox.alert(msg);				
			}
				
		});	
	}
	
	
	
});