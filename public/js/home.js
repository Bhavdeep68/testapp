var page = 1;

$(document).ready(function() {
	loadData(page);
});

function loadData(page, search) {
    showLoader();
    ajaxFetch(baseurl() + '/load', { page: page }, formResponse);
}

function formResponse(responseText, statusText) {
	hideLoader();
	if(statusText == 'success') {
		$('#tabledata').html(responseText);
		if($('#ajaxpagingdata').length > 0 && $.trim($('#ajaxpagingdata > td').html()) != '') {

			$('#pagingdata').html($('#ajaxpagingdata > td').html()).show();
			$('#ajaxpagingdata').remove();

			$('#pagingdata a[href]').click(function(event) {
				event.preventDefault();
				var arr = $(this).attr('href').split('page=');
				page = parseInt(arr[arr.length-1]);
				if(isNaN(page)) { page = 1; }
				loadData(page);
			});
		}
		else {
			$('#ajaxpagingdata').remove();
			$('#pagingdata').html('').hide();
		}
	}
	else {
		showError('Unable to communicate with server.');
	}
}
