var taskActionLoading = false;

function taskQuickEdit(task) {
    resetTaskEdit(task);

    jQuery('#m-task-id').val(task);

    jQuery('#modal-task-update-title').modal('show');
}

function resetTaskEdit(task) {
    var popup = jQuery('#modal-task-update-title');
    var bind = jQuery('#task-old-' + task);

    popup.find('#m-task-title').val(bind.find('.old-title').val());

    popup.find('.alert').addClass('hidden');
}

function taskSaveTitle() {
    var task = jQuery('#m-task-id').val().trim();
    var title = jQuery('#m-task-title').val().trim();

    jQuery('#modal-task-update-title .alert').addClass('hidden');
    var valid = true;

    if (!title || title === '') {
        jQuery('#err-title').removeClass('hidden');
        valid = false;
    }

    if (!valid) {
        return false;
    }

    if (parseInt(task)
        && !taskActionLoading
    ) {
        jQuery.ajax({
            url: geckoso.baseURL + '/todo/update',
            type: 'post',
            data: {
                _token: jQuery('#_tks').val(),
                task: task,
                title: title,
                type: 'title',
            },
            beforeSend: function () {
                taskActionLoading = true;
                modalLoading('modal-task-update-title');
            },
            success: function (response) {
                if (response.VALID) {
                    jQuery('#task-todo-' + task).empty().append(response.BODY);

                    modalSuccess('modal-task-update-title', geckoso.successUPDATE);
                } else {
                    // window.location.reload(true);
                }

                taskActionLoading = false;

            }
        });
    }
}

function taskProgress(task, percent) {
    resetTaskProgress(percent);

    jQuery('#m-task-id').val(task);
    jQuery('#m-task-percent').val(percent);

    jQuery('#modal-task-update-progress').modal('show');
}

function resetTaskProgress(percent) {
    var bind = jQuery('#modal-task-update-progress');
    var sliderProgress = '<div id="task-progress-slider">' +
        '<div id="task-progress-handle" class="ui-slider-handle bg-success slider-progress-bar"></div>' +
        '</div>';

    bind.find('#m-task-hour').val('');
    bind.find('.note').val('');

    bind.find('#task-progress-slider').remove();
    bind.find('#m-task-progress').append(sliderProgress);

    $( function() {
        var handle = $( "#task-progress-handle" );
        $( "#task-progress-slider" ).slider({
            create: function() {
                handle.text(percent);
            },
            slide: function( event, ui ) {
                handle.text( ui.value );
                jQuery('#m-task-percent').val(ui.value);
            }
        });
    } );

    if (parseInt(percent)) {
        setTimeout(function () {
            jQuery('#task-progress-handle').removeAttr('style').attr('style', 'left:' + percent + '%;');
        }, 300);
    }
}

function taskSaveProgress() {
    var task = jQuery('#m-task-id').val().trim();
    var percent = jQuery('#m-task-percent').val().trim();
    var hour = jQuery('#m-task-hour').val().trim();
    var date = jQuery('#m-task-dodate').val().trim();
    jQuery('#modal-task-update-progress .alert').addClass('hidden');
    var valid = true;

    if (!hour) {
        jQuery('#err-task-hour').removeClass('hidden');
        valid = false;
    }

    if (!valid) {
        return false;
    }

    if (parseInt(task)
        && !taskActionLoading
    ) {
        jQuery.ajax({
            url: geckoso.baseURL + '/todo/update',
            type: 'post',
            data: {
                _token: jQuery('#_tks').val(),
                task: task,
                percent: percent,
                hour: hour,
                date: date,
                note: jQuery('#modal-task-update-progress .note').val().trim(),
                type: 'progress_hour',
            },
            beforeSend: function () {
                taskActionLoading = true;
                modalLoading('modal-task-update-progress');
            },
            success: function (response) {
                modalSuccess('modal-task-update-progress', geckoso.successUPDATE);

                refreshPage();
            }
        });
    }
}

function taskDelete(task) {
    jQuery('#m-task-id').val(task);
    jQuery('#modal-task-confirm-delete .note').val('');

    jQuery('#modal-task-confirm-delete').modal('show');
}

function taskDeleteConfirm() {
    var task = jQuery('#m-task-id').val().trim();

    if (parseInt(task)) {
        jQuery.ajax({
            url: geckoso.baseURL + '/todo/delete',
            type: 'post',
            data: {
                _token: jQuery('#_tks').val(),
                task: task,
                note: jQuery('#modal-task-confirm-delete .note').val().trim(),
            },
            beforeSend: function () {
                jQuery('#task-todo-' + task).remove();

                modalLoading('modal-task-confirm-delete');
            },
            success: function (response) {
                modalSuccess('modal-task-confirm-delete', geckoso.successDEL);

                var pageId = jQuery('body').attr('id');
                if (pageId && pageId === 'page-task-info') {
                    parent.window.location.href = geckoso.baseURL + '/';
                }
            }
        });
    }
}

function taskDone(task) {
    jQuery('#m-task-id').val(task);
    jQuery('#modal-task-confirm-done .note').val('');

    jQuery('#modal-task-confirm-done').modal('show');
}

function taskDoneConfirm() {
    var task = jQuery('#m-task-id').val().trim();

    if (parseInt(task)) {
        jQuery.ajax({
            url: geckoso.baseURL + '/todo/done',
            type: 'post',
            data: {
                _token: jQuery('#_tks').val(),
                task: task,
                note: jQuery('#modal-task-confirm-done .note').val().trim(),
            },
            beforeSend: function () {
                modalLoading('modal-task-confirm-done');
            },
            success: function () {
                modalSuccess('modal-task-confirm-done', geckoso.successUPDATE);

                refreshPage();
            },
        });
    }
}

function taskAssign(task) {
    jQuery('#m-task-id').val(task);

    resetTaskAssign(task);

    jQuery('#modal-task-confirm-assign').modal('show');
}

function resetTaskAssign(task) {
    var popup = jQuery('#modal-task-confirm-assign');
    var bind = jQuery('#task-old-' + task);

    var toIds = [];
    var olds = bind.find('.old-assignees').val();
    if (olds) {
        olds = olds.split(',');

        olds.forEach(function (ele, pos) {
            if (parseInt(ele)) {
                toIds.push(parseInt(ele));
            }
        });
    }
    popup.find('#m-task-assignees').select2({}).val(toIds).change();

    popup.find('#m-task-priority').val(bind.find('.old-priority').val());
    popup.find('#m-task-estimation').val(bind.find('.old-estimation').val());
    popup.find('#m-task-deadline').val(bind.find('.old-deadline').val());
    popup.find('.note').val(bind.find('.old-mota').val());

    popup.find('.alert').addClass('hidden');
}

function taskAssignConfirm() {
    var task = jQuery('#m-task-id').val().trim();
    var assignees = jQuery('#m-task-assignees').val();
    var priority = jQuery('#m-task-priority').val().trim();
    var estimation = jQuery('#m-task-estimation').val().trim();
    var deadline = jQuery('#m-task-deadline').val().trim();

    jQuery('#modal-task-confirm-assign .alert').addClass('hidden');
    var valid = true;

    if (!assignees.length) {
        jQuery('#err-assignees').removeClass('hidden');
        valid = false;
    }

    if (!valid) {
        return false;
    }

    if (parseInt(task) && assignees.length) {
        jQuery('#modal-task-confirm-assign-task-id').val(task);
        jQuery('#modal-task-confirm-assign-url').val(jQuery('body').attr('id'));

        modalLoading('modal-task-confirm-assign');

        jQuery('#modal-task-confirm-assign-submit').click();

        return false;

        jQuery.ajax({
            url: geckoso.baseURL + '/todo/assign',
            type: 'post',
            data: {
                _token: jQuery('#_tks').val(),
                task: task,
                note: jQuery('#modal-task-confirm-assign .note').val().trim(),
                assignees: assignees,
                priority: priority,
                estimation: estimation,
                deadline: deadline,
            },
            beforeSend: function () {
                modalLoading('modal-task-confirm-assign');
            },
            success: function (response) {
                modalSuccess('modal-task-confirm-assign', geckoso.successUPDATE);

                refreshPage();
            }
        });
    }
}

function taskRedo(task) {
    jQuery('#m-task-id').val(task);

    resetTaskRedo(task);

    jQuery('#modal-task-confirm-redo').modal('show');
}

function resetTaskRedo(task) {
    var popup = jQuery('#modal-task-confirm-redo');
    var bind = jQuery('#task-old-' + task);

    var toIds = [];
    var olds = bind.find('.old-doers').val();
    if (olds) {
        olds = olds.split(',');

        olds.forEach(function (ele, pos) {
            if (parseInt(ele)) {
                toIds.push(parseInt(ele));
            }
        });
    }
    popup.find('#m-task-redoers').select2({}).val(toIds).change();

    jQuery('#modal-task-confirm-redo .note').val('');

    popup.find('.alert').addClass('hidden');
}

function taskRedoConfirm() {
    var task = jQuery('#m-task-id').val().trim();
    var doers = jQuery('#m-task-redoers').val();

    jQuery('#modal-task-confirm-redo .alert').addClass('hidden');
    var valid = true;

    if (!doers.length) {
        jQuery('#err-redoers').removeClass('hidden');
        valid = false;
    }

    if (!valid) {
        return false;
    }

    if (parseInt(task)) {
        jQuery.ajax({
            url: geckoso.baseURL + '/todo/redo',
            type: 'post',
            data: {
                _token: jQuery('#_tks').val(),
                task: task,
                note: jQuery('#modal-task-confirm-redo .note').val().trim(),
                doers: doers,
            },
            beforeSend: function () {
                modalLoading('modal-task-confirm-redo');
            },
            success: function (response) {
                modalSuccess('modal-task-confirm-redo', geckoso.successUPDATE);

                refreshPage();
            }
        });
    }
}

function taskFee(task) {
    resetTaskFee(task);

    jQuery('#m-task-id').val(task);

    jQuery('#modal-task-fee').modal('show');
}

function resetTaskFee(task) {
    var popup = jQuery('#modal-task-fee');

    popup.find('#m-task-fee-money').val('');
    popup.find('#m-task-fee-date').val('');
    popup.find('.note').val('');

    popup.find('.alert').addClass('hidden');
}

function taskFeeConfirm() {
    var task = jQuery('#m-task-id').val().trim();
    var fee = jQuery('#m-task-fee-money').val().trim();
    var date = jQuery('#m-task-fee-date').val().trim();

    jQuery('#modal-task-fee .alert').addClass('hidden');
    var valid = true;

    if (!fee || fee === '') {
        jQuery('#err-task-fee').removeClass('hidden');
        valid = false;
    }

    if (!date || date === '') {
        jQuery('#err-task-date').removeClass('hidden');
        valid = false;
    }

    if (!valid) {
        return false;
    }

    if (parseInt(task)
        && !taskActionLoading
    ) {
        jQuery.ajax({
            url: geckoso.baseURL + '/todo/fee',
            type: 'post',
            data: {
                _token: jQuery('#_tks').val(),
                task: task,
                fee: fee,
                date: date,
                note: jQuery('#modal-task-fee .note').val().trim(),
            },
            beforeSend: function () {
                taskActionLoading = true;
                modalLoading('modal-task-fee');
            },
            success: function (response) {
                modalSuccess('modal-task-fee', geckoso.successUPDATE);
                refreshPage();
            }
        });
    }
}

function taskFeeCancel(id) {
    jQuery('#task-fee-cancel').val(id);
    jQuery('#modal-task-fee-cancel').modal('show');
}

function taskFeeCancelConfirm() {
    jQuery.ajax({
        url: geckoso.baseURL + '/todo/fee-cancel',
        type: 'post',
        data: {
            id: jQuery('#task-fee-cancel').val().trim(),
            _token: jQuery('#_tks').val(),
        },
        beforeSend: function () {
            modalLoading('modal-task-fee-cancel');
        },
        success: function (response) {
            modalSuccess('modal-task-fee-cancel', geckoso.successUPDATE);
            refreshPage();
        },
    });
}

function taskFeePaid(id) {
    resetTaskFeePaid(id);
    jQuery('#task-fee-paid').val(id);
    jQuery('#modal-task-fee-paid').modal('show');
}

function resetTaskFeePaid(id) {
    var popup = jQuery('#modal-task-fee-paid');
    var bind = jQuery('#task-fee-old-' + id);

    popup.find('#frm-paid-title').val("Hoàn trả chi phí công việc");
    popup.find('#frm-paid-money').val(bind.find('.old-fee').val());
    popup.find('#frm-paid-date').val("");
    popup.find('.note').val("");

    jQuery('.money_format').simpleMoneyFormat();
}

function taskFeePaidConfirm() {
    var title = jQuery('#frm-paid-title').val().trim();
    var money = jQuery('#frm-paid-money').val().trim();
    var date = jQuery('#frm-paid-date').val().trim();
    var status = jQuery('#frm-paid-status').val().trim();
    var valid = true;
    jQuery('#modal-task-fee-paid .alert').addClass('hidden');

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
        url: geckoso.baseURL + '/todo/fee-paid',
        type: 'post',
        data: {
            id: jQuery('#task-fee-paid').val().trim(),
            title: title,
            money: money,
            date: date,
            status: status,
            note: jQuery('#modal-task-fee-paid .note').val().trim(),
            _token: jQuery('#_tks').val(),
        },
        beforeSend: function () {
            modalLoading('modal-task-fee-paid');
        },
        success: function (response) {
            modalSuccess('modal-task-fee-paid', geckoso.successUPDATE);
            refreshPage();
        },
    });
}




