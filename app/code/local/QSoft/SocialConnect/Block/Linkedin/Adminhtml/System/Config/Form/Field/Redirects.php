<?php

class QSoft_SocialConnect_Block_Linkedin_Adminhtml_System_Config_Form_Field_Redirects
    extends QSoft_SocialConnect_Block_Adminhtml_System_Config_Form_Field_Redirects
{

    protected function getAuthProvider()
    {
        return 'linkedin';
    }

}