/*
 * Custom config by alexantr
 */
CKEDITOR.editorConfig = function (config) {

    config.toolbar_Standart = [
        {name: 'document', items: ['Source']},
        {name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']},
        {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
        {name: 'insert', items: ['Image', 'Iframe', 'Table', 'HorizontalRule', 'SpecialChar']},
        {name: 'tools', items: ['Maximize', 'ShowBlocks']},
        {name: 'about', items: ['About']},
        '/',
        {name: 'basicstyles', items: ['Bold', 'Italic', '-', 'RemoveFormat']},
        {name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']},
        {name: 'align', items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']},
        {name: 'styles', items: ['Styles', 'Format']},
        {name: 'colors', items: ['TextColor', 'BGColor']}
    ];

    config.toolbar_Basic = [
        {name: 'document', items: ['Source']},
        {name: 'basicstyles', items: ['Bold', 'Italic', '-', 'RemoveFormat']},
        {name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent']},
        {name: 'links', items: ['Link', 'Unlink']},
        {name: 'about', items: ['About']}
    ];

    config.toolbar = 'Standart';

    config.format_tags = 'p;h1;h2;h3;pre;address;div';

    config.allowedContent = {
        $1: {
            elements: CKEDITOR.dtd,
            attributes: true,
            styles: true,
            classes: true
        }
    };
    // http://docs.ckeditor.com/#!/guide/dev_disallowed_content
    config.disallowedContent = 'form legend fieldset input select button script acronym noembed noscript font center nobr strike;' +
        '*[on*,align,border,longdesc,datasrc];' +
        'br[clear];' +
        'img[border]{width,height};' +
        'table[*]';

    config.entities = false;

    config.extraPlugins = 'autogrow,colorbutton,colordialog,iframe,justify,showblocks';
    config.removePlugins = 'resize,scayt,wsc';

    config.autoGrow_maxHeight = 900;

};

// use html5-style for self-closing tags
CKEDITOR.on('instanceReady', function (e) {
    e.editor.dataProcessor.writer.selfClosingEnd = '>';
});

// remove default table width
CKEDITOR.on('dialogDefinition', function (e) {
    var dialogName = e.data.name;
    var dialogDefinition = e.data.definition;
    if (dialogName == 'table') {
        var info = dialogDefinition.getContents('info');
        info.get('txtWidth')['default'] = '';
    }
});
