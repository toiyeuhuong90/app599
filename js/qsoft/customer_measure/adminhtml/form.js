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

'use strict';

document.observe('dom:loaded', function(){
    $$('div.collapsible.fieldset').each(function(fieldset){
        fieldset.setStyle({'margin-top':'0px'});
        var legend = fieldset.previous();
        if (legend.hasClassName('entry-edit-head')){
            legend.addClassName('collapseable');
            legend.setStyle({cursor:'pointer'});
            var a = new Element('a', {'class': 'open'});
            a.setStyle({width:'20px', height:'16px'});
            legend.down('.form-buttons').appendChild(a);
            legend.observe('click', function(){
                if (this.hasClassName('collapsed')){
                    this.down('a').addClassName('open');
                    this.removeClassName('collapsed').setStyle({'margin-bottom':'0px'});
                    fieldset.show();
                }else{
                    this.down('a').removeClassName('open');
                    this.addClassName('collapsed').setStyle({'margin-bottom':'3px'});
                    fieldset.hide();
                }
            });
        }
    });
});

var QS = QS || {};
QS.FormElementDependenceController = Class.create(FormElementDependenceController, {
    /**
     * Define whether target element should be toggled and show/hide its row
     *
     * @param e - object event
     * @param idTo - id of target element
     * @param valuesFrom - ids of master elements and reference values
     */
    trackChange : function(e, idTo, valuesFrom)
    {
        // define whether the target should show up
        var shouldShowUp = true;
        for (var idFrom in valuesFrom) {
            var from = $(idFrom);
            if (valuesFrom[idFrom] instanceof Array) {
                if (!from || valuesFrom[idFrom].indexOf(from.value) == -1) {
                    shouldShowUp = false;
                }
            } else {
                if (!from || from.value != valuesFrom[idFrom]) {
                    shouldShowUp = false;
                }
            }
        }

        // toggle target row
        if (shouldShowUp) {
            var currentConfig = this._config;
            $(idTo).up(this._config.levels_up).select('input', 'select', 'td').each(function (item) {
                // don't touch hidden inputs (and Use Default inputs too), bc they may have custom logic
                if ((!item.type || item.type != 'hidden') && !($(item.id+'_inherit') && $(item.id+'_inherit').checked)
                    && !(currentConfig.can_edit_price != undefined && !currentConfig.can_edit_price)) {
                    item.disabled = false;
                }
            });
            $(idTo).up(this._config.levels_up).show();
        } else {
            $(idTo).up(this._config.levels_up).select('input', 'select', 'td').each(function (item){
                // don't touch hidden inputs (and Use Default inputs too), bc they may have custom logic
                if ((!item.type || item.type != 'hidden') && !($(item.id+'_inherit') && $(item.id+'_inherit').checked)) {
                    item.disabled = true;
                }
            });
            $(idTo).up(this._config.levels_up).hide();
        }
    }
});
