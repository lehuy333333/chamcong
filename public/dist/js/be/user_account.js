jQuery(document).ready(function () {
    jQuery('#input-country').select2();

    jQuery('#select_avatar').fileupload({
        dataType: 'json',
        add: function (e, data) {
            jQuery('#frm-avatar').removeAttr('src').attr('src', geckoso.loadingUPLOADPHOTO);
            data.submit();
        },
        done: function (e, data) {
            window.location.reload(true);
        }
    }).prop('disabled', !jQuery.support.fileInput)
        .parent().addClass(jQuery.support.fileInput ? undefined : 'disabled');
});

function validateAccount() {
    var name = jQuery('#input-name').val().trim();
    var email = jQuery('#input-email').val().trim();
    var valid = true;

    jQuery('#frm-info .alert').addClass('hidden');
    if (!name || name === '') {
        jQuery('#err-name').removeClass('hidden');
        valid = false;
    }
    if (!email || email === '') {
        jQuery('#err-email').text('Hãy nhập email hợp lệ.').removeClass('hidden');
        valid = false;
    }

    if (!valid) {
        return false;
    }

    jQuery('#modal-account').modal('show');
    return false;
}

function saveAccount() {
    jQuery.ajax({
        url: geckoso.baseURL + '/usr/check-info',
        type: 'post',
        data: {
            _token: jQuery('#_tks').val(),
            email: jQuery('#input-email').val().trim(),
            id: jQuery('#input-id').val().trim(),
        },
        beforeSend: function () {
            modalLoading('modal-account');
        },
        success: function (response) {
            var message = geckoso.successCHANGE;
            if (response.VALID_EMAIL) {
                jQuery('#frm-info').removeAttr('onsubmit');
                jQuery('#frm-info .btn-confirm')[0].click();
            } else {
                message = "Địa chỉ email đã được sử dụng.";
                jQuery('#err-email').text(message).removeClass('hidden');

                modalRefresh('modal-account');

                jQuery('html,body').animate({
                    scrollTop: jQuery('#err-email').offset().top - 100,
                }, 'slow');
            }

        },
    });
}

function validatePassword() {
    var password = jQuery('#input-password').val().trim();
    var valid = true;

    jQuery('#frm-password .alert').addClass('hidden');
    if (!password || password === '') {
        jQuery('#err-password').removeClass('hidden');
        valid = false;
    }

    if (!valid) {
        return false;
    }

    jQuery('#modal-password').modal('show');
    return false;
}

function savePassword() {
    jQuery.ajax({
        url: geckoso.baseURL + '/usr/account/save-password',
        type: 'post',
        data: {
            _token: jQuery('#_tks').val(),
            password: jQuery('#input-password').val().trim(),
        },
        beforeSend: function () {
            jQuery('#modal-password .modal-footer').hide();
            jQuery('#modal-password .modal-footer').after(geckoso.loadingIMG);
            jQuery('#modal-password .js-loading').addClass('text-right');
        },
        success: function (response) {
            var message = geckoso.successCHANGE;
            if (response.VALID) {
                jQuery('#modal-password .frm-loading').remove();
                var html = '<div class="alert alert-success frm-message">' + message + '</div>';
                jQuery('#modal-password .modal-footer').after(html);
            } else {

            }

            setTimeout(function () {
                window.location.reload(true);
            }, 1300);
        },
    });
}

function uploadAvatar() {
    jQuery('#select_avatar')[0].click();
}
