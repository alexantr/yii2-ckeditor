if (typeof alexantr === 'undefined' || !alexantr) {
    var alexantr = {};
}

alexantr.ckEditorWidget = (function ($) {
    'use strict';

    var baseUrl,
        callbacks = [],
        loading = false,
        loaded = false;

    function callPlugin(inputId, options) {
        var $input = $('#' + inputId);
        $input.ckeditor(options);
        $input.ckeditor().editor.on('change', function () {
            $input.trigger('change');
        });
    }

    $.getCachedScript = function (url, options) {
        options = $.extend(options || {}, {
            dataType: 'script',
            cache: true,
            url: url
        });
        return $.ajax(options);
    };

    return {
        setBaseUrl: function (url) {
            if (!baseUrl) {
                baseUrl = url;
            }
        },
        register: function (inputId, options) {
            if (loaded) {
                callPlugin(inputId, options);
            } else {
                callbacks.push({inputId: inputId, options: options});
                if (!loading && baseUrl) {
                    loading = true;
                    $.getCachedScript(baseUrl + 'ckeditor.js').done(function () {
                        $.getCachedScript(baseUrl + 'adapters/jquery.js').done(function () {
                            loaded = true;
                            loading = false;
                            for (var i = 0; i < callbacks.length; i++) {
                                callPlugin(callbacks[i].inputId, callbacks[i].options);
                            }
                        });
                    });
                }
            }
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