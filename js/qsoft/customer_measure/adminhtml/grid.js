/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
var QS = QS || {};

QS.InPlaceEditor = Class.create(Ajax.InPlaceEditor, {
    createControl: function(mode, handler, extraClasses) {
        var control = this.options[mode + 'Control'];
        var text = this.options[mode + 'Text'];
        if ('button' == control) {
            if ('ok' == mode)
                var btn = new Element('button', {type:'submit'});
            else
                var btn = new Element('button', {type:'button'});
            var span = new Element('span');
            span.update(text);
            btn.update(span);
            btn.addClassName('editor_' + mode + '_button');
            btn.observe('click', handler);
            this._form.appendChild(btn);
            this._controls[mode] = btn;
        } else if ('link' == control) {
            var link = document.createElement('a');
            link.href = '#';
            link.appendChild(document.createTextNode(text));
            link.onclick = 'cancel' == mode ? this._boundCancelHandler : this._boundSubmitHandler;
            link.className = 'editor_' + mode + '_link';
            if (extraClasses)
                link.className += ' ' + extraClasses;
            this._form.appendChild(link);
            this._controls[mode] = link;
        }
    },

    createEditField: function() {
        var text = this.options.loadTextURL ? this.options.loadingText : this.getText();
        var fld;
        if (1 >= this.options.rows && !/\r|\n/.test(this.getText())) {
            fld = new Element('input');
            fld.type = 'text';
            var size = this.options.size || this.options.cols || 0;
            if (0 < size) fld.size = size;
        } else {
            fld = document.createElement('textarea');
            fld.rows = (1 >= this.options.rows ? this.options.autoRows : this.options.rows);
            fld.cols = this.options.cols || 40;
        }
        fld.setStyle({
            'width': '80px',
            'verticalAlign': 'top'
        });
        fld.name = this.options.paramName;
        fld.value = text; // No HTML breaks conversion anymore
        fld.className = 'editor_field input-text';
        if (this.options.submitOnBlur)
            fld.onblur = this._boundSubmitHandler;
        this._controls.editor = fld;
        if (this.options.loadTextURL)
            this.loadExternalText();
        this._form.appendChild(this._controls.editor);
    }
});

function bindInlineEdit(input){
    var attr    = $(input).readAttribute('attr'),
        entity  = $(input).readAttribute('entity'),
        control = $(input).readAttribute('control'),
        saveUrl = $(input).readAttribute('saveUrl');

    switch (control){
        case 'text':
            new MT.InPlaceEditor(input, saveUrl, {
                callback: function(form, value){
                    return 'entity=' +entity+ '&attr=' +attr+ '&value=' +encodeURIComponent(value);
                },
                onComplete: function(transport){
                    if (typeof transport === 'object'){
                        var response = transport.responseText.evalJSON();
                        if (response.message) alert(response.message);
                        else $(input).update(response.value);
                    }
                },
                onFailure: function(){
                    alert(Translator.translate('Error communicating with the server'));
                },
                cancelControl: 'button',
                cancelText: 'x',
                okText: 'Ok',
                ajaxOptions: {loaderArea: false}
            });
            break;
    }
}