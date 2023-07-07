jQuery(document).ready(function () {
    jQuery('#filter-partner').select2({});
    jQuery('#filter-assign').select2({});
    jQuery('#m-client-assignees').select2({});
});

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
                refreshPage();
            },
        });
    }
}

function filterBy(value) {
    switch (value) {
        case 'name':
            jQuery('#btn-filter').text("Khách Hàng");
            break;
        case 'email':
            jQuery('#btn-filter').text("Email");
            break;
        case 'address':
            jQuery('#btn-filter').text("Địa Chỉ");
            break;
        case 'phone':
            jQuery('#btn-filter').text("Điện Thoại");
            break;
        case 'note':
            jQuery('#btn-filter').text("Ghi Chú");
            break;
    }
    jQuery('#filter-by').val(value);
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

