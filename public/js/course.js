
$(document).ready(function() {
	$('#formcourse').validate({
		rules: {
			name: {
				required: true
			},
			fees: {
				required: true
			},
			duration: {
				required: true
			},
		},
		messages: {
			name: {
				required: 'Please enter course name.'
			},
			fees: {
				required: 'Please enter course fees.'
			},
			duration: {
				required: 'Please enter course duration.'
			},
		}
	});

});

function formResponse(responseText, statusText) {
    var form = $('#formcourse');
    hideLoader();
    enableFormButton(form);
	if(statusText == 'success') {
		if(responseText.type == 'success') {
			showSuccess(responseText.caption, null, function() {
				window.location.href = responseText.redirectUrl;
			});
		}
		else {
			showError(responseText.caption, responseText.errormessage);
			if(responseText.errorfields !== undefined) {
				highlightInvalidFields(form, responseText.errorfields);
			}
		}
	}
    else {
		showError('Unable to communicate with server.');
	}
}
