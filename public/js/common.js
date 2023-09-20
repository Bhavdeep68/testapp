// functions for form validation and submission
$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    jQuery.validator.setDefaults({
        debug: false,
        onsubmit: true,
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        errorElement: "div",
        errorPlacement: function(error, element) {
            if (element.attr("class").indexOf("custom-control") != -1) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        invalidHandler: invalidHandler,
        highlight: highlightElement,
        unhighlight: unhighlightElement,
        submitHandler: submitHandler
    });


    $.validator.prototype.checkForm = function() {
        $(".form-group.has-error .error").remove();
        $(".form-group").removeClass('has-error');
        //overriden in a specific page
        this.prepareForm();
        for (var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++) {
            if (this.findByName(elements[i].name).length !== undefined && this.findByName(elements[i].name).length > 1) {
                for (var cnt = 0; cnt < this.findByName(elements[i].name).length; cnt++) {
                    this.check(this.findByName(elements[i].name)[cnt]);
                }
            } else {
                this.check(elements[i]);
            }
        }
        return this.valid();
    };


});


function invalidHandler(event, validator) {
    if (validator.errorList.length == 0) return;
    // showError('One or more invalid inputs found.', validator.errorList);
}

function highlightElement(element) {
    $(element).closest('.form-group').addClass('has-error');
}

function unhighlightElement(element) {
    $(element).closest('.form-group').removeClass('has-error');
}

function highlightInvalidFields(form, fieldnames) {
    $(form).find('input, select, textarea').each(function() {
        var fieldname = $(this).attr('name');
        var index = $.inArray(fieldname, fieldnames);
        if (index == -1) {
            unhighlightElement($(this));
        } else {
            highlightElement($(this));
        }
    });
}

function submitHandler(form) {
    disableFormButton(form);
    showLoader();

    $(form).ajaxSubmit({
        dataType: 'json',
        success: formResponse,
        error: formResponseError
    });
}

function formResponseError(XMLHttpRequest, textStatus, errorThrown) {
    hideLoader();
    enableFormButton($('form'));
    console.log('ERROR: ' + XMLHttpRequest.statusText);
    console.log('ERROR: ' + XMLHttpRequest.errorThrown);
}

function disableFormButton(form) {
    $(form).find('input[type="submit"]').attr('disabled', 'disabled');
    $(form).find('button[type="submit"]').attr('disabled', 'disabled');
}

function enableFormButton(form) {
    $(form).find('input[type="submit"]').removeAttr('disabled');
    $(form).find('button[type="submit"]').removeAttr('disabled');
}


function showSuccess(caption, successList, callback) {
    var successListMsgs = '';

    if (successList && successList.length > 0) {
        successListMsgs += '<ul class="ui-pnotify-ul">';
        for (var i = 0; i < successList.length; i++) {
            successListMsgs += '<li class="ui-pnotify-li">' + successList[i] + '</li>';
        }
        successListMsgs += '</ul>';
    }
    var notice = PNotify.success({
        styling: 'material',
        icons: 'material',
        delay: 3000,
        textTrusted: true,
        title: caption,
        text: successListMsgs,
        modules: {
            Buttons: {
                closerHover: false,
                sticker: false
            }
        }
    });
    notice.on('click', function() {
        notice.close();
    });

    if (callback && typeof callback == 'function') {
        setTimeout(function() {
            callback();
        }, 3000);
    }
}

function showError(caption, errorList) {
    var errorListMsgs = '';

    if (errorList && jQuery.type(errorList) == 'array' && errorList.length > 0) {
        errorListMsgs += '<ul class="ui-pnotify-ul">';
        for (var i = 0; i < errorList.length; i++) {
            errorListMsgs += '<li class="ui-pnotify-li">' + errorList[i] + '</li>';
        }
        errorListMsgs += '</ul>';
    } else if (errorList && jQuery.type(errorList) == 'string') {
        errorListMsgs += '<ul class="ui-pnotify-ul"><li class="ui-pnotify-li">' + errorList + '</li></ul>';
    }

    var notice = PNotify.error({
        styling: 'material',
        icons: 'material',
        delay: 3000,
        textTrusted: true,
        title: caption,
        text: errorListMsgs,
        modules: {
            Buttons: {
                closerHover: false,
                sticker: false
            }
        }
    });
    notice.on('click', function() {
        notice.close();
    });
}

function confirmDialoue(caption, text, callback) {
    var notice = PNotify.notice({
        styling: 'material',
        icons: 'material',
        title: caption,
        text: text,
        hide: false,
        addClass: 'confirm-pnotify',
        stack: {
            'dir1': 'down',
            'modal': true,
            'firstpos1': 25
        },
        modules: {
            Confirm: {
                confirm: true
            },
            Buttons: {
                closer: false,
                sticker: false
            },
            History: {
                history: false
            },
        }
    });
    notice.on('pnotify.confirm', function() {
        callback(true);
    });
    notice.on('pnotify.cancel', function() {
        callback(false);
    });
}

function resetForm(form) {
    $(form).get(0).reset();
}

function refreshPage() {
    window.location.href = '';
}

function showLoader(loadingtext) {
    if (loadingtext === undefined) {
        loadingtext = 'Loading...';
    }
    $('#loader .loader-text').html(loadingtext);
    $('#loader').stop().show();
}

function hideLoader() {
    $('#loader').stop().hide();
}

function scrollwindowTop() {
    $(document).scrollTop(0);
}

function ajaxUpdate(Url, data, callBack, noLoader) {
    if (!noLoader) {
        showLoader();
    }
    $.ajax({
        type: 'POST',
        url: Url,
        cache: false,
        data: data,
        dataType: 'json',
        success: callBack,
        error: formResponseError
    });
}

function ajaxFetch(Url, data, callBack, noLoader) {
    //showLoader();
    if (!noLoader) {
        showLoader();
    }
    $.ajax({
        type: 'POST',
        url: Url,
        data: data,
        dataType: 'html',
        success: callBack,
        error: formResponseError
    });
}

function baseurl() {
    return $.trim($('meta[name="base-url"]').attr('content'));
}

function dd($val) {
    console.log($val);
}