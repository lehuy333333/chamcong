jQuery(document).ready(function () {

    //height
    setTimeout(function () {
        if (jQuery('#ele-info-3').length) {
            setDivHeight();
        }
    }, 1500);
});

function setDivHeight() {
    if (jQuery('#user-info-1').length) {
        jQuery('#user-info-1').attr('style', 'min-height: 463px !important;');
    }

    if (jQuery('#user-info-2').length) {
        jQuery('#user-info-2').attr('style', 'min-height: 403px !important;');
    }
}
