var loadTaskLog = false;
function loadTaskLogs(task) {
    if (loadTaskLog) {
        return false;
    }
    jQuery.ajax({
        url: geckoso.baseURL + '/todo/get-log',
        type: 'post',
        data: {
            _token: jQuery('#_tks').val(),
            task: task,
            last: jQuery('.table-log tr').last().attr('data-id')
        },
        beforeSend: function () {
            loadTaskLog = true;
        },
        success: function (response) {
            if (response.BODY && response.BODY !== '') {
                jQuery('.table-log tbody').last().append(response.BODY);

                jQuery('.money_format').simpleMoneyFormat();

                loadTaskLog = false;
            }
        }
    });
}

function logworkHistory(task) {
    jQuery.ajax({
        url: geckoso.baseURL + '/todo/get-logwork',
        type: 'post',
        data: {
            _token: jQuery('#_tks').val(),
            task: task,
        },
        beforeSend: function () {
            isLoading(true);
        },
        success: function (response) {
            setTimeout(function () {
                isLoading(false);
            }, 200);

            if (response.BODY && response.BODY !== '') {
                jQuery('#modal-task-logwork tbody').empty().append(response.BODY);
                jQuery('#modal-task-logwork').modal('show');

                jQuery('.money_format').simpleMoneyFormat();
            }
        }
    });
}


