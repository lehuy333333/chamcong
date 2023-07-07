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
                refreshPage();
            },
        });
    }
}

function filterBy(value) {
    switch (value) {
        case 'name':
            jQuery('#btn-filter').text("Tên Đối Tác");
            break;
        case 'phone':
            jQuery('#btn-filter').text("Điện Thoại");
            break;
        case 'mo_ta':
            jQuery('#btn-filter').text("Mô Tả");
            break;
    }
    jQuery('#filter-by').val(value);
}
