<?php

/**
 * Set Link and Label for Adminhtml per app
 *
 * Class QSoft_SocialConnect_Block_Facebook_Adminhtml_System_Config_Form_Field_Links
 */
class QSoft_SocialConnect_Block_Facebook_Adminhtml_System_Config_Form_Field_Links
    extends QSoft_SocialConnect_Block_Adminhtml_System_Config_Form_Field_Links
{

    protected function getAuthProviderLink()
    {
        return 'Facebook Developers';
    }

    protected function getAuthProviderLinkHref()
    {
        return 'https://developers.facebook.com/';
    }

}