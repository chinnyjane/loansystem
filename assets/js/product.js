$(document).ready(function() {
	$('#addrole').on('click', function(e){
		var form = $('#productroleform');
		var btn = $(this)
		btn.button("loading");
		$.ajax({
			type: "POST",
			url: form.attr('action'), //process to mail
			data: form.serialize(),
			success: function(msg){
				$('#roleuser').modal('hide');
				if(msg['status'] == 1){
					bootbox.alert('yeah!!', function() {
						location.reload(true);
						btn.button("reset");
					});
				}else{
					bootbox.alert(msg['note'], function(){
						$('#roleuser').modal('show');
					});
					btn.button("reset");
				}
			},
			error: function(){
				bootbox.alert('Internet Connection Problem. Please try again.');
				btn.button("reset");
			}
		});
	});
	
	$('#saveproduct').on('click', function(e){		
		var btn = $('#saveproduct');
		btn.button("loading");
		$.ajax({
			type: "POST",
			url: $('#updateproduct').attr('action'), //process to mail
			data: $('#updateproduct').serialize(),
			success: function(msg){
				bootbox.alert(msg, function(){
					location.reload(true);
					btn.button("reset");
				});
			},
			error: function(){
				bootbox.alert('Internet Connection Problem. Please try again.');
				btn.button("reset");
			}
		});
	});
});