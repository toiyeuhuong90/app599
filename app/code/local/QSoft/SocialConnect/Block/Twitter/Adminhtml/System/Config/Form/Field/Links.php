<?php

/**
 * Set Link and Label for Adminhtml per app
 *
 * Class QSoft_SocialConnect_Block_Twitter_Adminhtml_System_Config_Form_Field_Links
 */

class QSoft_SocialConnect_Block_Twitter_Adminhtml_System_Config_Form_Field_Links
    extends QSoft_SocialConnect_Block_Adminhtml_System_Config_Form_Field_Links
{

    protected function getAuthProviderLink()
    {
        return 'Twitter Applications';
    }

    protected function getAuthProviderLinkHref()
    {
        return 'https://apps.twitter.com/';
    }
    
}