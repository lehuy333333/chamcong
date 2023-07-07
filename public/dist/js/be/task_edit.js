jQuery(document).ready(function () {

    $(document).ready(function() {
        $('.mdb-select').materialSelect();
        });
    //select2
    jQuery("#edit-assignees").select2({});
    jQuery("#edit-reporters").select2({});

    $("#edit-files").change(function () {
        jQuery('#err-files').addClass('hidden');
        if(this.files[0].size > geckoso.maxSize) {
            jQuery('#err-files').removeClass('hidden');

            jQuery(this).val("");
            jQuery('#edit-files-preview').empty();
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

                    $('#edit-files-preview').prepend("<div class='popup-upload-preview-item'>"
                        + html
                        + "<div class='popup-upload-preview-name'>" + e.target.fileName + "</div>"
                        + "</div>");
                };

                reader.readAsDataURL(input.files[i]);
            }
        }
    });
});

function removeFile(id) {
    jQuery('#old-files').val(jQuery('#old-files').val().replace('f_' + id + ';', ''));

    jQuery('#file-' + id).remove();
}

