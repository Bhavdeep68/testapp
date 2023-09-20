$(document).ready(function() {
	$('#formregister').validate({
		rules: {
			name: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			password: {
				required: true
			},
			password_confirmation: {
				required: true,
				equalTo : "#password"
			},
		},
		messages: {
			name: {
				required: 'Please enter name.'
			},
			email: {
				required: 'Please enter email address.',
				email: 'Please enter a valid email address.'
			},
			password: {
				required: 'Please enter password.'
			},
			password_confirmation: {
				required: 'Please enter confirm password.',
				equalTo: 'Password mismatch.'
			},
		}
	});
});

function formResponse(responseText, statusText){
	var form = $('#formregister');

	hideLoader();
	enableFormButton(form);

	if(statusText == 'success') {
		if(responseText.type == 'success') {
			window.location.href = responseText.redirectUrl;
		}
		else {
			showError(responseText.caption);
			if(responseText.errorfields !== undefined) {
				highlightInvalidFields(form, responseText.errorfields);
			}
		}
	}
	else {
		showError('Unable to communicate with server.');
	}
}
