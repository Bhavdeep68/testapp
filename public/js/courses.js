var page = 1;

$(document).ready(function() {
	loadData(page);
});

function loadData(page, search) {
    showLoader();
    ajaxFetch(baseurl() + '/courses/load', { page: page }, formResponse);
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

function deleteEntity(course_id) {
	confirmDialoue("Delete", 'Are you sure you want to delete this course?', function(e){
		if (e) {
			ajaxUpdate(baseurl() + '/courses/destroy', { course_id: course_id }, function(responseText, statusText) {
				hideLoader();
				if(statusText == 'success') {
					if(responseText.type == 'success') {
						showSuccess(responseText.caption, '', function(){
							page = 1;
							loadData(page);
				        });
				    }
				    else {
				        showError(responseText.caption);
				    }
				}
				else {
					showError('Unable to communicate with server.');
				}
			});
		}
		else {
			hideModal();
		}
	});
}