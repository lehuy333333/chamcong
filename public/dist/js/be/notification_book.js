jQuery(document).ready(function () {
    jQuery('#frm-book').on('submit', function(e){
        e.preventDefault();
        confirmBookNotification();
    });
});

function refreshFrmBook() {
    jQuery('#frm-book_id').val('');
    jQuery('#frm-book_date').val('');
    jQuery('#frm-book_time').val('8');
    jQuery('#frm-book_times').val('1');
    jQuery('#frm-book_range').val('30');
    jQuery('#frm-book_note').val('');
}

function bookNotification() {
    refreshFrmBook();
    jQuery('#modal-notification-book').modal('show');
}

function confirmBookNotification() {
    jQuery('#frm-book .alert').addClass('hidden');
    var valid = true;
    var date = jQuery('#frm-book_date').val().trim();
    var note = jQuery('#frm-book_note').val().trim();

    if (!date) {
        jQuery('#err-book_date').removeClass('hidden');
        valid = false;
    }

    if (!note) {
        jQuery('#err-book_note').removeClass('hidden');
        valid = false;
    }

    if (!valid) {
        return false;
    }

    modalLoading('modal-notification-book');

    var id = jQuery('#frm-book_id').val();
    if (id) {
        jQuery('#frm-book').removeAttr('action').attr('action', geckoso.baseURL + '/notification/book-edit');
    }

    jQuery('#frm-book')[0].submit();
}

function populateFrmBook(id) {
    var bind = jQuery('#book-old-' + id);

    jQuery('#frm-book_id').val(id);
    jQuery('#frm-book_date').val(bind.find('.old-date_book').val());
    jQuery('#frm-book_time').val(bind.find('.old-time_book').val());
    jQuery('#frm-book_times').val(bind.find('.old-times').val());
    jQuery('#frm-book_range').val(bind.find('.old-range').val());
    jQuery('#frm-book_note').val(bind.find('.old-note').val());
}

function editItem(id) {
    populateFrmBook(id);
    jQuery('#modal-notification-book').modal('show');
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
            url: geckoso.baseURL + '/notification/book-delete',
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
                openPage(geckoso.baseURL + '/reminders');
            },
        });
    }
}
