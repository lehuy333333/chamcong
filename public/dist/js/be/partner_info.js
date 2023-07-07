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
            url: geckoso.baseURL + '/partner/delete',
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
                openPage(geckoso.baseURL + '/partners');
            },
        });
    }
}
