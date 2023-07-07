function pressEnter(e) {
    if (e.which == 13 || e.keyCode == 13) {
        confirmUpdateItem(1);
    }
}

function addItem() {
    jQuery('#update-item').val('');
    jQuery('#modalUpdate').modal('show');
    jQuery('#modalUpdate .alert').addClass('hidden');

    jQuery('#frm-update-title').text("Thêm Bộ Phận");
    jQuery('#frm-update-text').val('');
    setTimeout(function () {
        jQuery('#frm-update-text').focus();
    }, 500);
}

function editItem(id) {
    jQuery('#update-item').val(id);
    jQuery('#modalUpdate').modal('show');
    jQuery('#modalUpdate .alert').addClass('hidden');

    jQuery('#frm-update-title').text("Sửa Bộ Phận");
    jQuery('#frm-update-text').val(jQuery('#row-' + id).attr('data-title'));
    setTimeout(function () {
        jQuery('#frm-update-text').focus();
    }, 500);
}

function confirmUpdateItem(value) {
    if (!value) {
        jQuery('#modalUpdate').modal('hide');
    } else {
        var id = jQuery('#update-item').val().trim();
        var name = jQuery('#frm-update-text').val().trim();
        if (!name) {
            jQuery('#modalUpdate .alert').removeClass('hidden').text("Hãy nhập tên bộ phận.");
            return false;
        }
        //check duplicate
        var duplicated = false;
        var stopped = false;
        jQuery('.row-level').each(function (pos, ele) {
            var curSame = false;
            var bind = jQuery(ele);
            var curId = bind.attr('data-id');
            if (name.toLowerCase() === bind.attr('data-title').trim().toLowerCase()) {
                duplicated = true;
                curSame = true;
            }
            if (parseInt(id) && parseInt(id) === parseInt(curId) && curSame) {
                stopped = true;
            }
        });
        if (stopped) {
            confirmUpdateItem(0);
            return false;
        }
        if (duplicated) {
            jQuery('#modalUpdate .alert').removeClass('hidden').text("Tên bộ phận đã có trong hệ thống.");
            return false;
        }

        jQuery.ajax({
            url: geckoso.baseURL + '/level/save',
            type: 'post',
            data: {
                id: id,
                name: name,
                _token: jQuery('#_tks').val(),
            },
            beforeSend: function () {
                modalLoading('modalUpdate');
            },
            success: function (response) {
                modalSuccess('modalUpdate', geckoso.successUPDATE);
                refreshPage();
            },
        });
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
            url: geckoso.baseURL + '/level/delete',
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
