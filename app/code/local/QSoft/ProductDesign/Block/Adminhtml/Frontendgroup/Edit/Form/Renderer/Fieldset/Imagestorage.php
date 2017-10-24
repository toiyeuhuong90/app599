<?php

class QSoft_ProductDesign_Block_Adminhtml_Frontendgroup_Edit_Form_Renderer_Fieldset_Imagestorage extends Varien_Data_Form_Element_Abstract {

    public function __construct($data) {
        parent::__construct($data);
    }

    public function getElementHtml() {
        $img = '';
        if ($this->getValue()) {
            $img = '<img src="' . $this->getValue() . '" width="60" style="background:#000" /><span class="del_img" onclick="instOptionImage.delete()">x</span>';
        }

        $html = '<div class="files" style="width:280px">' .
                '<div class="row" >' .
                '<span id="preview-option" class="preview-option" style="width: 70px; float: left;">' . $img . '</span>' .
                '<input type="hidden" onchange="instOptionImage.changeImage(this)" class="validate-optionimages-file" id="' . $this->getHtmlId() . '" name="' . $this->getName() . '" value="' . $this->getValue() . '" />' .
                '<button class="button" type="button" onclick="MT.MediabrowserUtility.openDialog(\'' . Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index') . 'static_urls_allowed/1/target_element_id/' . $this->getHtmlId() . '\')"><span>' . Mage::helper('catalog')->__('Select') . '</span></button>' .
                '</div>' .
                '</div>'
                . '<script type="text/javascript">'
                . 'var OptionImage = Class.create();
                    OptionImage.prototype = {
                        initialize: function () {
                        },
                        changeImage: function (elm) {
                            var uri = $(elm).value;
                            var preview = $(elm).up(\'div\').down(\'span.preview-option\');

                            if (preview) {
                                $(preview).update(\'<img src="\' + uri + \'" width="60" style="background:#000" /><span class="del_img" onclick="instOptionImage.delete()">x</span>\');
                            }
                        },
                        delete: function () {
                            if (window.confirm("' . Mage::helper('catalog')->__('Are you sure?') . '")) {
                                $(\'preview-option\').update(\'\');
                                $(\'' . $this->getHtmlId() . '\').value = \'\';
                            }
                        }
                    };
                    instOptionImage = new OptionImage();'
                . '</script>'
                . '<style type="text/css">
                        span.preview-option{
                            display:block;
                            position:relative;
                        }
                        span.preview-option span.del_img{
                            color: #ffac47;
                            cursor: pointer;
                            display: block;
                            font-weight: 700;
                            height: 10px;
                            position: absolute;
                            right: 10px;
                            top: 0;
                            width: 10px;
                            padding:0 0 10px 10px;
                        }
                    </style>';

        return $html;
    }

    protected function _getUrl() {
        return $this->getValue();
    }

    public function getName() {
        return $this->getData('name');
    }

}
