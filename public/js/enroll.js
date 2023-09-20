$(document).ready(function() {
	$('#formenroll').validate({
		rules: {
			email: {
				required: true,
				email: true
			},
			name: {
				required: true
			}
		},
		messages: {
			email: {
				required: 'Please enter email address.',
				email: 'Please enter a valid email address.'
			},
			name: {
				required: 'Please enter your name.'
			}
		}
	});

	let stripe = Stripe($('#stripekey').val());
    const elements = stripe.elements()
    const cardElement = elements.create('card', {
    	// hidePostalCode: true,
        style: {
			base: {
				color: '#495057',
				fontWeight: '500',
				fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
				fontSize: '16px',
				fontSmoothing: 'antialiased',
				':-webkit-autofill': {
					color: '#fce883',
				},
			},
			invalid: {
				iconColor: '#ff0000',
				color: '#ff0000',
			},
        }
    })
    const cardForm = document.getElementById('formenroll')
    cardElement.mount('#card')

	$('.enroll-btn').click(async function(e) {
		e.preventDefault();
		if($('#formenroll').valid()) {
			const { paymentMethod, error } = await stripe.createPaymentMethod({
		        type: 'card',
		        card: cardElement,
		        billing_details: {
		            name: $('#name').val(),
		            email: $('#email').val(),
		        }
		    });
		    if (error) {
		        console.log(error)
		    } else {
				$('#payment_method').val(paymentMethod.id);
		        $('#formenroll').submit();
		    }
		}
	});
});


function formResponse(responseText, statusText){
	var form = $('#formenroll');

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
