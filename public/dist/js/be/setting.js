jQuery(document).ready(function () {
    jQuery('.max-size-text').text(geckoso.maxSizeText);

    $("#upload-logo").change(function () {
        jQuery('#req-logo .alert-danger').addClass('hidden');
        if(this.files[0].size > geckoso.maxSize) {
            jQuery('#req-logo .alert-danger').removeClass('hidden');

            jQuery(this).val("");
            jQuery('#logo-preview').empty();
            return false;
        }

        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#logo-preview').empty().append("<div class='img-preview'><img src='" + e.target.result + "' ></div>");
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    });
});

function validateSetting() {
    var name = jQuery('#input-name').val().trim();
    var valid = true;

    jQuery('#frm-setting .alert').addClass('hidden');
    if (!name || name === '') {
        jQuery('#err-name').removeClass('hidden');
        valid = false;
    }

    if (!valid) {
        return false;
    }

}
