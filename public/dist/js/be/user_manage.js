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
            url: geckoso.baseURL + '/usr/delete',
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
                refreshPage();
            },
        });
    }
}

function blockItem(id) {
    jQuery('#block-item').val(id);
    jQuery('#modalBlock').modal('show');
}

function confirmBlockItem(value) {
    if (!value) {
        jQuery('#block-item').val("");
        jQuery('#modalBlock').modal('hide');
    } else {
        jQuery.ajax({
            url: geckoso.baseURL + '/usr/block',
            type: 'post',
            data: {
                id: jQuery('#block-item').val().trim(),
                reason: jQuery('#modal-reason').length ? jQuery('#modal-reason').val().trim() : '',
                block: 1,
                _token: jQuery('#_tks').val(),
            },
            beforeSend: function () {
                modalLoading('modalBlock');
            },
            success: function (response) {
                modalSuccess('modalBlock', geckoso.successUPDATE);
                refreshPage();
            },
        });
    }
}

function unblockItem(id) {
    jQuery('#unblock-item').val(id);
    jQuery('#modalUnblock').modal('show');
}

function confirmUnblockItem(value) {
    if (!value) {
        jQuery('#unblock-item').val("");
        jQuery('#modalUnblock').modal('hide');
    } else {
        jQuery.ajax({
            url: geckoso.baseURL + '/usr/block',
            type: 'post',
            data: {
                id: jQuery('#unblock-item').val().trim(),
                _token: jQuery('#_tks').val(),
            },
            beforeSend: function () {
                modalLoading('modalUnblock');
            },
            success: function (response) {
                modalSuccess('modalUnblock', geckoso.successUPDATE);
                refreshPage();
            },
        });
    }
}

function filterBy(value) {
    switch (value) {
        case 'name':
            jQuery('#btn-filter').text("Họ Tên");
            break;
        case 'phone':
            jQuery('#btn-filter').text("Điện Thoại");
            break;
        case 'email':
            jQuery('#btn-filter').text("Email");
            break;
    }
    jQuery('#filter-by').val(value);
}
