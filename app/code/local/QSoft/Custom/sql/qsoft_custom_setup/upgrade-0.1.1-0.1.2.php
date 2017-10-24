<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 13/04/2016
 * Time: 2:42 PM
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$content = <<<LOL
<div class="qs-title-page">
    <h1 class="qs-page-title">About Us</h1>
</div>
<div class="qs-page-container about-us-page">
    <div class="qs-intro-about-us">
        <div class="container">
            <div class="col-sm-offset-5 col-sm-7">
                <div class="qs-intro-content-about">
                    <div class="qs-img-intro-about"><img src="{{media url="wysiwyg/logo_notext.png"}}" alt="" /></div>
                    <div class="qs-desc-18">Our</div>
                    <div class="qs-desc-50">Story</div>
                    <div class="qs-desc-normal-16">Vestibulum tellus neque, pulvinar vitae dolor in, faucibus ullamcorper dui. Duis ultrices purus in neque viverra dignissim. Pellentesque viverra dui non fringilla convallis. Integer vitae tellus imperdiet.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="qs-statement">
        <div class="qs-intro-statement">
            <div class="container">
                <div class="qs-desc-18">Our</div>
                <div class="qs-desc-50">Story</div>
            </div>
        </div>

        <div class="qs-content-statement">
            <div class="container-fluid">
                <div class="row">
                    <div class="qs-row-statement">
                        <div class="col-sm-6 qs-col-statement">
                            <div class="qs-col-statement-img">
                                <img src="{{media url="wysiwyg/about_us_1.jpg"}}" alt="" />
                            </div>
                        </div>
                        <div class="col-sm-6 qs-col-statement">
                            <div class="qs-content-statement-wrapper qs-statement-right">
                                <div class="qs-container-statement">
                                    <div class="qs-number-statement">01.</div>
                                    <div class="qs-title-statement">Proin Imperdiet Magna</div>
                                    <div class="qs-desc-normal-16">Vestibulum tellus neque, pulvinar vitae dolor in, faucibus ullamcorper dui. Duis ultrices purus in neque viverra dignissim. Proin eu neque non nulla ultrices convallis. Pellentesque viverra dui non fringilla convallis. Integer vitae tellus imperdiet, interdum enim ut, ornare massa. Donec justo tellus, lacinia eget blandit et, posuere at magna.</div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="qs-row-statement">
                        <div class="col-sm-6 qs-col-statement">
                            <div class="qs-content-statement-wrapper qs-statement-left">
                                <div class="qs-container-statement">
                                    <div class="qs-number-statement">02.</div>
                                    <div class="qs-title-statement">Proin Imperdiet Magna</div>
                                    <div class="qs-desc-normal-16">Vestibulum tellus neque, pulvinar vitae dolor in, faucibus ullamcorper dui. Duis ultrices purus in neque viverra dignissim. Proin eu neque non nulla ultrices convallis. Pellentesque viverra dui non fringilla convallis. Integer vitae tellus imperdiet, interdum enim ut, ornare massa. Donec justo tellus, lacinia eget blandit et, posuere at magna.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 qs-col-statement">
                            <div class="qs-col-statement-img">
                                <img src="{{media url="wysiwyg/about_us_2.jpg"}}" alt="" />
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="qs-row-statement">
                        <div class="col-sm-6 qs-col-statement">
                            <div class="qs-col-statement-img">
                                <img src="{{media url="wysiwyg/about_us_3.jpg"}}" alt="" />
                            </div>
                        </div>
                        <div class="col-sm-6 qs-col-statement">
                            <div class="qs-content-statement-wrapper qs-statement-right">
                                <div class="qs-container-statement">
                                    <div class="qs-number-statement">03.</div>
                                    <div class="qs-title-statement">Proin Imperdiet Magna</div>
                                    <div class="qs-desc-normal-16">Vestibulum tellus neque, pulvinar vitae dolor in, faucibus ullamcorper dui. Duis ultrices purus in neque viverra dignissim. Proin eu neque non nulla ultrices convallis. Pellentesque viverra dui non fringilla convallis. Integer vitae tellus imperdiet, interdum enim ut, ornare massa. Donec justo tellus, lacinia eget blandit et, posuere at magna.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">// <![CDATA[
    jQuery(document).ready(function(){
        jQuery('.qs-row-statement').each(function() {
            jQuery(this).find('.qs-content-statement-wrapper').css('height',  jQuery(this).find('.qs-col-statement-img').height() + 'px');
        });
    });
// ]]></script>
LOL;

$layoutUpdate = <<<LOL
<reference name="content">
    <block type="testimony/testimonial_list" name="testimonial_list" template="evince_testimony/testimonial/list.phtml" after="-"/>
</reference>
<reference name="head">
    <action method="addItem"><type>skin_css</type><name>css/jquery.bxslider.css</name></action>
    <action method="addItem"><type>skin_js</type><name>js/jquery.bxslider.min.js</name></action>
</reference>
LOL;


$data = array(
    'title' => 'About US',
    'identifier' => 'about-us',
    'content' => $content,
    'root_template' => 'one_column',
    'layout_update_xml'=> $layoutUpdate,
    'is_active' => 1,
    'stores' => array(0)
);

$model = Mage::getModel('cms/page')->load('about-us');
if($model->getId()){
    $model->setContent($content)
        ->save();
}else{
    Mage::getModel('cms/page')->setData($data)->save();
}

$installer->endSetup();