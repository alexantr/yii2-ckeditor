if (typeof alexantr === 'undefined' || !alexantr) {
    var alexantr = {};
}

alexantr.ckEditorWidget = (function ($) {
    return {
        registerOnChangeHandler: function (id) {
            CKEDITOR && CKEDITOR.instances[id] && CKEDITOR.instances[id].on('change', function () {
                CKEDITOR.instances[id].updateElement();
                $('#' + id).trigger('change');
                return false;
            });
        },
        registerCsrfUploadHandler: function () {
            var selector = '.cke_dialog_tabs a[id^="cke_Upload_"], .cke_dialog_tabs a[id^="cke_upload_"]';
            yii && $(document).off('click', selector).on('click', selector, function () {
                var csrfParam = yii.getCsrfParam(),
                    csrfToken = yii.getCsrfToken();
                var $forms = $('.cke_dialog_ui_input_file iframe').contents().find('form');
                $forms.each(function () {
                    if (!$(this).find('input[name=' + csrfParam + ']').length) {
                        var csrfTokenInput = $('<input/>').attr({
                            type: 'hidden',
                            name: csrfParam
                        }).val(csrfToken);
                        $(this).append(csrfTokenInput);
                    }
                });
            });
        }
    }
})(jQuery);