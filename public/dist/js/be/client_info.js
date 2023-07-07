jQuery(document).ready(function () {
    jQuery('#m-book-user').select2({}).val(geckoso.viewer).change();

    //height
    setTimeout(function () {
        if (jQuery('#ele-info-3').length) {
            setDivHeight();
        }
    }, 1500);
});

function setDivHeight() {
    if (jQuery('#client-info-1').length) {
        jQuery('#client-info-1').attr('style', 'min-height: 463px !important;');
    }

    if (jQuery('#client-info-2').length) {
        jQuery('#client-info-2').attr('style', 'min-height: 403px !important;');
    }
}

function deleteItem(id) {
    jQuery('#delete-item').val(id);
    jQuery('#modalDelete').modal('show');
}

function confirmDeleteItem(value) {
    if (!value) {
        jQuery('#delete-item').val("");
        jQuery('#modalDelete').modal('hide');
    } else {
        jQuery.ajax({
            url: geckoso.baseURL + '/client/delete',
            type: 'post',
            data: {
                id: jQuery('#delete-item').val().trim(),
                _token: jQuery('#_tks').val(),
            },
            beforeSend: function () {
                modalLoading('modalDelete');
            },
            success: function (response) {
                modalSuccess('modalDelete', geckoso.successDEL);
                openPage(geckoso.baseURL + '/clients');
            },
        });
    }
}

function assignItem(id) {
    jQuery('#assign-item').val(id);

    resetClientAssign(id);

    jQuery('#modalAssign').modal('show');
}

function resetClientAssign(id) {
    var popup = jQuery('#modalAssign');
    var bind = jQuery('#client-old-' + id);

    var toIds = [];
    var olds = bind.find('.old-carers').val();
    if (olds) {
        olds = olds.split(',');

        olds.forEach(function (ele, pos) {
            if (parseInt(ele)) {
                toIds.push(parseInt(ele));
            }
        });
    }
    popup.find('#m-client-carers').select2({}).val(toIds).change();

    popup.find('.alert').addClass('hidden');
}

function confirmAssignItem(value) {
    if (!value) {
        jQuery('#assign-item').val("");
        jQuery('#modalAssign').modal('hide');
    } else {
        var client = jQuery('#assign-item').val().trim();
        var carers = jQuery('#m-client-carers').val();

        jQuery('#modalAssign .alert').addClass('hidden');
        var valid = true;

        if (!carers.length) {
            jQuery('#err-carers').removeClass('hidden');
            valid = false;
        }

        if (!valid) {
            return false;
        }

        if (parseInt(client) && carers.length) {
            jQuery('#modal-assign-client-id').val(client);

            modalLoading('modalAssign');

            jQuery('#modal-assign-submit').click();

            return false;
        }
    }
}

function bookCalendar(id) {
    jQuery('#book-item').val(id);
    jQuery('#modalBook').modal('show');
}

function confirmBookCalendar(value) {
    if (!value) {
        jQuery('#book-item').val("");
        jQuery('#modalBook').modal('hide');
    } else {
        var date = jQuery('#frm-date_book').val().trim();
        var valid = true;

        if (!date || date === '') {
            jQuery('#err-date_book').removeClass('hidden');
            valid = false;
        }

        if (!valid) {
            return false;
        }

        jQuery.ajax({
            url: geckoso.baseURL + '/client/book',
            type: 'post',
            data: {
                id: jQuery('#book-item').val().trim(),
                date: date,
                user: jQuery('#m-book-user').val().trim(),
                note: jQuery('#modalBook .note').val().trim(),
                _token: jQuery('#_tks').val(),
            },
            beforeSend: function () {
                modalLoading('modalBook');
            },
            success: function (response) {
                modalSuccess('modalBook', geckoso.successUPDATE);
                refreshPage();
            },
        });
    }
}

function bookResult(id) {
    jQuery('#book-result').val(id);
    jQuery('#modalBookResult').modal('show');
}

function confirmBookResult(value) {
    if (!value) {
        jQuery('#book-result').val("");
        jQuery('#modalBookResult').modal('hide');
    } else {
        var status = jQuery('#frm-book-status').val().trim();
        var valid = true;

        if (!status) {
            jQuery('#err-book-status').removeClass('hidden');
            valid = false;
        }

        if (!valid) {
            return false;
        }

        jQuery.ajax({
            url: geckoso.baseURL + '/client/book-result',
            type: 'post',
            data: {
                id: jQuery('#book-result').val().trim(),
                status: status,
                fee: jQuery('#frm-book-fee').val().trim(),
                note: jQuery('#modalBookResult .note').val().trim(),
                _token: jQuery('#_tks').val(),
            },
            beforeSend: function () {
                modalLoading('modalBookResult');
            },
            success: function (response) {
                modalSuccess('modalBookResult', geckoso.successUPDATE);
                refreshPage();
            },
        });
    }
}

function bookPaid(id) {
    resetBookPaid(id);
    jQuery('#book-paid').val(id);
    jQuery('#modalBookPaid').modal('show');
}

function resetBookPaid(id) {
    var popup = jQuery('#modalBookPaid');
    var bind = jQuery('#book-log-old-' + id);

    popup.find('#frm-paid-title').val("Hoàn trả chi phí gặp khách hàng");
    popup.find('#frm-paid-money').val(bind.find('.old-fee').val());
    popup.find('#frm-paid-date').val('');
    popup.find('.note').val('');

    jQuery('.money_format').simpleMoneyFormat();
}

function confirmBookPaid(value) {
    if (!value) {
        jQuery('#book-paid').val("");
        jQuery('#modalBookPaid').modal('hide');
    } else {
        jQuery('#modalBookPaid .alert').addClass('hidden');
        var title = jQuery('#frm-paid-title').val().trim();
        var money = jQuery('#frm-paid-money').val().trim();
        var date = jQuery('#frm-paid-date').val().trim();
        var status = jQuery('#frm-paid-status').val().trim();
        var valid = true;

        if (!title) {
            jQuery('#err-paid-title').removeClass('hidden');
            valid = false;
        }

        if (!money) {
            jQuery('#err-paid-money').removeClass('hidden');
            valid = false;
        }

        if (!date) {
            jQuery('#err-paid-date').removeClass('hidden');
            valid = false;
        }

        if (!valid) {
            return false;
        }

        jQuery.ajax({
            url: geckoso.baseURL + '/client/book-paid',
            type: 'post',
            data: {
                id: jQuery('#book-paid').val().trim(),
                title: title,
                money: money,
                date: date,
                status: status,
                note: jQuery('#modalBookPaid .note').val().trim(),
                _token: jQuery('#_tks').val(),
            },
            beforeSend: function () {
                modalLoading('modalBookPaid');
            },
            success: function (response) {
                modalSuccess('modalBookPaid', geckoso.successUPDATE);
                refreshPage();
            },
        });
    }
}
