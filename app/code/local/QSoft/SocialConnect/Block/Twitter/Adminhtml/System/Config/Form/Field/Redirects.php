<?php
/**
 * Set Return for configuration per app
 *
 * Class QSoft_SocialConnect_Block_Twitter_Adminhtml_System_Config_Form_Field_Redirects
 */

class QSoft_SocialConnect_Block_Twitter_Adminhtml_System_Config_Form_Field_Redirects
    extends QSoft_SocialConnect_Block_Adminhtml_System_Config_Form_Field_Redirects
{

    protected function getAuthProvider() 
    {
        return 'twitter';
    }

}