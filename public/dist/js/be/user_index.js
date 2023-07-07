jQuery(document).ready(function () {
    loadMyTasks();
    loadMyAssignedTasks();
    loadMyReportedTasks();

    loadDoneTasksInWeek();

    //select2
    jQuery("#m-task-assignees").select2({});
    jQuery("#m-task-redoers").select2({});

    jQuery("#m-project-teams").select2({});
    jQuery("#m-project-captain").select2({}).val(geckoso.viewer).change();

    //height
    setTimeout(function () {
        setDivHeight();
    }, 1500);

    //files
    $("#m-project-files").change(function () {
        jQuery('#err-project-files').addClass('hidden');
        if(this.files[0].size > geckoso.maxSize) {
            jQuery('#err-files').removeClass('hidden');

            jQuery(this).val("");
            jQuery('#m-project-files-preview').empty();
            return false;
        }

        var input = this;

        if (input.files && input.files[0]) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                reader.fileName = input.files[i].name;
                reader.extension = input.files[i].name.split('.').pop().toLowerCase();

                reader.onload = function (e) {
                    var html = getDefaultExt(e.target.extension);
                    if (e.target.extension === "gif" || e.target.extension === "png"
                        || e.target.extension === "jpeg" || e.target.extension === "jpg"
                        || e.target.extension === "webp"
                    ) {
                        html = "<img src='" + e.target.result + "' />";
                    }

                    $('#m-project-files-preview').prepend("<div class='popup-upload-preview-item'>"
                        + html
                        + "<div class='popup-upload-preview-name'>" + e.target.fileName + "</div>"
                        + "</div>");
                };

                reader.readAsDataURL(input.files[i]);
            }
        }
    });
});

function setDivHeight() {
    if (jQuery('#todo-lists tbody tr').length && jQuery('#todo-lists tbody tr').length < 6) {
        jQuery('#todo-lists').attr('style', 'min-height: 355px !important;');
    }
    if (jQuery('#todo-assigned tbody tr').length && jQuery('#todo-assigned tbody tr').length < 6) {
        jQuery('#todo-assigned').attr('style', 'min-height: 355px !important;');
    }
    if (jQuery('#todo-reported tbody tr').length && jQuery('#todo-reported tbody tr').length < 6) {
        jQuery('#todo-reported').attr('style', 'min-height: 355px !important;');
    }
    if (jQuery('#todo-done-in-week tbody tr').length && jQuery('#todo-done-in-week tbody tr').length < 6) {
        jQuery('#todo-done-in-week').attr('style', 'min-height: 370px !important;');
    }
}

function loadMyTasks() {
    jQuery.ajax({
        url: geckoso.baseURL + '/todo/mine',
        type: 'post',
        data: {
            _token: jQuery('#_tks').val(),
        },
        beforeSend: function () {
            jQuery('#todo-lists').empty().append(geckoso.loadingIMG);
        },
        success: function (response) {
            if (response.BODY && response.BODY !== '') {
                jQuery('#todo-lists').empty().append(response.BODY);

                jQuery('#todo-lists .count-tc').removeClass('hidden');
                jQuery('#todo-lists .count-tc b').text(response.TC);
            } else {
                jQuery('#todo-lists').empty();
            }
        },
        error: function(xhr, status, error){
            // window.location.reload(true);
            setTimeout(function () {
                loadMyTasks();
            }, 3000);
        }
    });
}

function loadMyAssignedTasks() {
    jQuery.ajax({
        url: geckoso.baseURL + '/todo/my-assigned',
        type: 'post',
        data: {
            _token: jQuery('#_tks').val(),
        },
        beforeSend: function () {
            jQuery('#todo-assigned').empty().append(geckoso.loadingIMG);
        },
        success: function (response) {
            if (response.BODY && response.BODY !== '') {
                jQuery('#todo-assigned').empty().append(response.BODY);

                if (response.TC) {
                    jQuery('#block-todo-assigned .count-tc').removeClass('hidden');
                    jQuery('#block-todo-assigned .count-tc b').text(response.TC);
                }

                jQuery('.notfound').text(geckoso.notFound);
            } else {
                jQuery('#todo-assigned').empty().append('<div class="alert alert-primary">Không tìm thấy dữ liệu phù hợp.</div>');
            }
        },
        error: function(xhr, status, error){
            // window.location.reload(true);
            setTimeout(function () {
                loadMyAssignedTasks();
            }, 3000);
        }
    });
}

function loadMyReportedTasks() {
    jQuery.ajax({
        url: geckoso.baseURL + '/todo/my-reported',
        type: 'post',
        data: {
            _token: jQuery('#_tks').val(),
        },
        beforeSend: function () {
            jQuery('#todo-reported').empty().append(geckoso.loadingIMG);
        },
        success: function (response) {
            if (response.BODY && response.BODY !== '') {
                jQuery('#todo-reported').empty().append(response.BODY);

                if (response.TC) {
                    jQuery('#block-todo-reported .count-tc').removeClass('hidden');
                    jQuery('#block-todo-reported .count-tc b').text(response.TC);
                }

                jQuery('.notfound').text(geckoso.notFound);
            } else {
                jQuery('#todo-reported').empty().append('<div class="alert alert-primary">Không tìm thấy dữ liệu phù hợp.</div>');
            }
        },
        error: function(xhr, status, error){
            // window.location.reload(true);
            setTimeout(function () {
                loadMyReportedTasks();
            }, 3000);
        }
    });
}

function loadDoneTasksInWeek() {
    jQuery.ajax({
        url: geckoso.baseURL + '/todo/mine-week-done',
        type: 'post',
        data: {
            _token: jQuery('#_tks').val(),
        },
        beforeSend: function () {
            jQuery('#todo-done-in-week').empty().append(geckoso.loadingIMG);
        },
        success: function (response) {
            if (response.BODY && response.BODY !== '') {
                jQuery('#todo-done-in-week').empty().append(response.BODY);

                if (response.TC) {
                    jQuery('#block-todo-done .count-tc').removeClass('hidden');
                    jQuery('#block-todo-done .count-tc b').text(response.TC);
                }

                jQuery('.notfound').text(geckoso.notFound);
            }
        },
        error: function(xhr, status, error){
            // window.location.reload(true);
            setTimeout(function () {
                loadDoneTasksInWeek();
            }, 3000);
        }
    });
}

var todoCreating = false;
function todoEnter(evt, ele) {
    if (evt.keyCode === 13) {
        todoCreate(ele);
    }
}

function todoCreate(ele) {
    var bind = jQuery('#' + ele);
    var todo = bind.val().trim();
    var valid = true;
    if (!todo || todo === '') {
        valid = false;
    }

    if (valid && !todoCreating) {
        var htmlLoading = '<div class="input-group-append" id="frm-todo-creating">' +
            '<span class="input-group-text"><i class="fas fa-spinner" style="color: #2dce89;"></i></span>' +
            '</div>';
        bind.after(htmlLoading);

        jQuery.ajax({
            url: geckoso.baseURL + '/todo/save',
            type: 'post',
            data: {
                _token: jQuery('#_tks').val(),
                todo: todo,
            },
            beforeSend: function () {
                todoCreating = true;
            },
            success: function (response) {
                if (response.VALID) {
                    if (response.BODY && response.BODY !== '') {
                        if (!jQuery('#todo-lists tbody').length) {
                            window.location.reload(true);
                        } else {
                            jQuery('#todo-lists tbody').prepend(response.BODY);

                            var tc = jQuery('#todo-lists .count-tc b').text();
                            jQuery('#todo-lists .count-tc b').text(parseInt(tc) + 1);
                        }
                    }
                }

                todoCreating = false;
                jQuery('#frm-todo-creating').remove();
                bind.val("");
                bind.focus();

                //height
                setTimeout(function () {
                    setDivHeight();
                }, 1500);
            },
        });
    }
}

function todoCalendarCreate(title, date) {
    jQuery.ajax({
        url: geckoso.baseURL + '/todo/save',
        type: 'post',
        data: {
            _token: jQuery('#_tks').val(),
            todo: title,
            date: date,
        },
        success: function (response) {
            if (response.VALID) {
                if (response.BODY && response.BODY !== '') {
                    if (!jQuery('#todo-lists tbody').length) {
                        // window.location.reload(true);

                        loadMyTasks();
                        loadMyAssignedTasks();
                        loadMyReportedTasks();
                    } else {
                        jQuery('#todo-lists tbody').prepend(response.BODY);

                        var tc = jQuery('#todo-lists .count-tc b').text();
                        jQuery('#todo-lists .count-tc b').text(parseInt(tc) + 1);
                    }
                }
            }

            //height
            setTimeout(function () {
                setDivHeight();
            }, 1500);
        },
    });
}

function quickCreateProject() {
    jQuery('#modal-project-create').modal('show');
}

function confirmCreateProject() {
    isLoading(true);

    jQuery('#modal-project-create').modal('hide');
}
