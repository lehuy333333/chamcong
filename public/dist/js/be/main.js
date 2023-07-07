jQuery(document).ready(function () {
    jQuery('.max-size-text').text(geckoso.maxSizeText);
    jQuery('.notfound').text(geckoso.notFound);
    jQuery('.money_format').simpleMoneyFormat();

    if (!isMobile()) {
        sideBarClose();
    }

    setTimeout(function () {
        //date picker
        if (jQuery('.date_picker').length) {
            jQuery('.date_picker').datepicker({
                dateFormat: 'dd-mm-yy',
                yearRange: '-80:+5',
                changeYear: true,
                changeMonth: true,
            });
        }
    }, 300);

    if (geckoso.viewer) {
        setInterval(function () {
            getNotifications();
        }, 60000);
    }

    jQuery('#frm-export_name').bind('keyup', function (e) {
        $(this).val($(this).val().replace(/[^A-Za-z0-9_\-]/, ''));
    });
});

function exportExcel(value) {
    jQuery('#modalExport .alert').addClass('hidden');

    var fileName = '';
    switch (value) {
        case 'user':
            fileName = "export_quan_ly_nhan_vien";
            break;

        case 'partner':
            fileName = "export_quan_ly_doi_tac";
            break;

        case 'client':
            fileName = "export_quan_ly_khach_hang";
            break;

    }

    jQuery('#modalExport #frm-export_name').val(fileName);

    jQuery('#modalExport').modal('show');
}

function confirmExportExcel() {
    var fileName = jQuery('#modalExport #frm-export_name').val().trim();
    if (!fileName || fileName === '') {
        jQuery('#modalExport .alert').removeClass('hidden');
        return false;
    }

    jQuery('#modalExport').modal('hide');

    isLoading(true);
    var href = jQuery('#frm-export-link').attr('href') + '&file_name=' + fileName;
    jQuery('#frm-export-link').attr('href', href);

    jQuery('#frm-export-link')[0].click();

    setTimeout(function () {
        isLoading(false);
    }, 1800);
}

function isLoading(status) {
    if (status) {
        jQuery('.modal').modal('hide');

        jQuery('#modalLoading').modal('show');
    } else {
        jQuery('#modalLoading').modal('hide');
    }
}

function isMobile() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

function getNotifications() {
    jQuery.ajax({
        url: geckoso.baseURL + '/notification/get',
        type: 'post',
        data: {
            _token: jQuery('#_tks').val(),
        },
        beforeSend: function () {
            jQuery('.modal-notify').addClass('hidden');
        },
        success: function (response) {
            if (response.BODY && response.BODY !== '') {
                jQuery('.modal-notify-content').empty().append(response.BODY);
                jQuery('.modal-notify').removeClass('hidden');

                setTimeout(function () {
                    jQuery('.modal-notify').addClass('hidden');
                }, 8888);
            }
        }
    });
}

function refreshNotification() {
    // jQuery(ele).parent().addClass('show');
    // jQuery('#ele-panel-notification').addClass('show');
    //
    // setTimeout(function () {
    //     jQuery(ele).parent().removeClass('show');
    //     jQuery('#ele-panel-notification').removeClass('show');
    // }, 4500);

    jQuery.ajax({
        url: geckoso.baseURL + '/notification/refresh',
        type: 'post',
        data: {
            _token: jQuery('#_tks').val(),
        },
        beforeSend: function () {
            jQuery('#my-notification').remove();
        }
    });
}

function getDefaultExt(ext) {
    var img = "<img src='" + geckoso.baseURL + "/public/images/default_file.png' />";
    if (ext === "doc" || ext === "docx") {
        img = "<img src='" + geckoso.baseURL + "/public/images/default_doc.png' />";
    } else if (ext === "xls" || ext === "xlsx") {
        img = "<img src='" + geckoso.baseURL + "/public/images/default_xls.png' />";
    } else if (ext === "ppt" || ext === "pptx") {
        img = "<img src='" + geckoso.baseURL + "/public/images/default_ppt.png' />";
    } else if (ext === "txt") {
        img = "<img src='" + geckoso.baseURL + "/public/images/default_txt.png' />";
    } else if (ext === "xml") {
        img = "<img src='" + geckoso.baseURL + "/public/images/default_xml.png' />";
    } else if (ext === "pdf") {
        img = "<img src='" + geckoso.baseURL + "/public/images/default_pdf.png' />";
    } else if (ext === "zip" || ext === "rar" || ext === "7z") {
        img = "<img src='" + geckoso.baseURL + "/public/images/default_zip.png' />";
    }
    return img;
}

function modalLoading(ele) {
    var bind = jQuery('#' + ele);

    bind.find('.modal-footer').hide();
    bind.find('.modal-footer').after(geckoso.loadingIMG);
    bind.find('.js-loading').addClass('text-right');
}

function modalSuccess(ele, message) {
    var bind = jQuery('#' + ele);

    bind.find('.js-loading').remove();
    var html = '<div class="alert alert-success frm-message">' + message + '</div>';
    bind.find('.modal-footer').after(html);

    setTimeout(function () {
        modalRefresh(ele);
    }, 1500);
}

function modalRefresh(ele) {
    var bind = jQuery('#' + ele);

    bind.find('.frm-message').remove();
    bind.find('.js-loading').remove();
    bind.find('.modal-footer').show();
    bind.modal('hide');
}

function refreshPage() {
    setTimeout(function () {
        window.location.reload(true);
    }, 1500);
}

function frmOrder(ele) {
    jQuery('#search-order').val(jQuery(ele).val());
    jQuery('.frm-search .card-footer button[type=submit]').click();
}

function frmOrderBy(ele) {
    jQuery('#search-order-by').val(jQuery(ele).val());
    jQuery('.frm-search .card-footer button[type=submit]').click();
}

function frmFocus(frm) {
    frm.addClass('frm-focus');
    setTimeout(function () {
        frm.removeClass('frm-focus');

        setTimeout(function () {
            frm.addClass('frm-focus');

            setTimeout(function () {
                frm.removeClass('frm-focus');

                setTimeout(function () {
                    frm.addClass('frm-focus');

                    setTimeout(function () {
                        frm.removeClass('frm-focus');
                    }, 300);
                }, 300);
            }, 300);
        }, 300);
    }, 300);
}

function openPage(href) {
    jQuery('.modal').modal('hide');

    isLoading(true);

    setTimeout(function () {
        isLoading(false);

        parent.window.location.href = href;
    }, 1200);
}

function gotoPage(href) {
    isLoading(true);

    setTimeout(function () {
        isLoading(false);

        window.open(href, '_blank');
    }, 1200);
}

function isInputNumber(evt, obj) {
    if (/^0/.test(obj.value)) {
        obj.value = obj.value.replace(/^0/, "")
    }

    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function isInputPhone(evt, obj) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function isInputNoDot(evt, obj) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode == 46) {
        return false;
    }
    return true;
}

function isInputFloat(evt, obj) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var value = obj.value;
    var dotcontains = value.indexOf(".") != -1;
    if (dotcontains) {
        if (charCode == 46) {
            return false;
        }
        //2
        var dots = value.split(".");
        if (dots[1] && dots[1] > 10) {
            return false;
        }
    }
    if (charCode == 46) {
        return true;
    }
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function sideBarClose() {
    jQuery('.main-content').addClass('no-margin-left');
    jQuery('#sidenav-main').addClass('hidden');
    jQuery('#sidebar-open').removeClass('hidden');
}

function sideBarOpen() {
    jQuery('.main-content').removeClass('no-margin-left');
    jQuery('#sidenav-main').removeClass('hidden');
    jQuery('#sidebar-open').addClass('hidden');
}

function getPriority(color) {
    var priority = 1;
    if (color === '#11cdef') {
        priority = 2;
    } else if (color === '#2f9c0a') {
        priority = 3;
    } else if (color === '#fb6340') {
        priority = 4;
    } else if (color === '#f5365c') {
        priority = 5;
    }
    return priority;
}


