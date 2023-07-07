jQuery(document).ready(function () {
    jQuery('.frm-search').on('submit', function(e){
        e.preventDefault();
        frmSearch();
    });

    loadWork();
});

function frmSearch() {
    jQuery('.frm-search .alert').addClass('hidden');
    var from = jQuery('#date_from').val().trim();
    var to = jQuery('#date_to').val().trim();
    var valid = true;

    if (!from) {
        jQuery('.frm-search #err_date_from').removeClass('hidden');
        valid = false;
    }

    if (!to) {
        jQuery('.frm-search #err_date_to').removeClass('hidden');
        valid = false;
    }

    if (!valid) {
        return false;
    }

    loadWork();
}

function loadWork() {
    jQuery.ajax({
        url: geckoso.baseURL + '/todo/worked',
        type: 'post',
        data: {
            _token: jQuery('#_tks').val(),
            from: jQuery('#date_from').val().trim(),
            to: jQuery('#date_to').val().trim(),
            user: jQuery('#work_id').val().trim(),
        },
        beforeSend: function () {
            jQuery('#frm-work').empty().append(geckoso.loadingIMG);
        },
        success: function (response) {
            if (response.BODY && response.BODY !== '') {
                jQuery('#frm-work').empty().append(response.BODY);
            } else {
                jQuery('#frm-work').empty('<div class="alert alert-primary notFound"></div>');
            }
            jQuery('.notfound').text(geckoso.notFound);
        },
        error: function(xhr, status, error){
            // window.location.reload(true);
            setTimeout(function () {
                loadWork();
            }, 3000);
        }
    });
}
