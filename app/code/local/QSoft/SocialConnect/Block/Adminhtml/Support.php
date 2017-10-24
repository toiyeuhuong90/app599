<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 11/13/2015
 * Time: 11:35 AM
 */
class QSoft_SocialConnect_Block_Adminhtml_Support
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $helper = Mage::helper('qsoft_socialconnect');
        $moduleNameId = 'QSoft_SocialConnect';

        $moduleVersion = $this->_getConfigValue($moduleNameId, 'version');
        $moduleName = $this->_getConfigValue($moduleNameId, 'name');
        $moduleShortDescription = $this->_getConfigValue($moduleNameId, 'descr');
        $moduleLicense = $this->_getConfigValue($moduleNameId, 'license');

        $linkParameters = '';
        $moduleLicenseLink = $this->_getConfigValue($moduleNameId, 'licenselink') . $linkParameters;
        $moduleSupportLink = $this->_getConfigValue($moduleNameId, 'redminelink') . $linkParameters;
        $moduleLink = $this->_getConfigValue($moduleNameId, 'permanentlink') . $linkParameters;
        $servicesLink = $this->_getConfigValue($moduleNameId, 'ourserviceslink') . $linkParameters;

        $magentoVersion = Mage::getVersion();
        $magentoPlatform = $this->_getPlatform();
        $logoLink = $this->getSkinUrl('qsoft/socialconnect/images/qs_logo.gif');

        $html = '
            <table cellspacing="0" cellpading="0" class="qsoft-developer">
                <tr>
                    <td class="qsoft-developer-label">' . $helper->__('Extension:') . '</td>
                    <td class="qsoft-developer-text">' . $helper->__(
                '<strong>%s</strong> (version %s)',
                $moduleName,
                $moduleVersion
            ) . '</td>
                </tr>
                <tr>
                    <td class="qsoft-developer-label">' . $helper->__('License:') . '</td>
                    <td class="qsoft-developer-text">' . $helper->__(
                '<a href="%s" target="_blank">%s</a>',
                $moduleLicenseLink,
                $moduleLicense
            ) . '</td>
                </tr>
                <tr>
                    <td class="qsoft-developer-label">' . $helper->__('Short Description:') . '</td>
                    <td class="qsoft-developer-text">' . $moduleShortDescription . '</td>
                </tr>
                <tr>
                    <td class="qsoft-developer-label">' . $helper->__('Documentation:') . '</td>
                    <td class="qsoft-developer-text">' . $helper->__(
                'You can see description of extension features and answers to the ' .
                'frequently asked questions on <a href="%s" target="_blank">our website</a>.',
                $moduleLink) . '</td>
                </tr>
                <tr>
                    <td class="qsoft-developer-label line">' . $helper->__('Support:') . '</td>
                    <td class="qsoft-developer-text line">' . $helper->__(
                'Extension support is available through <a href="%s" target="_blank">issue tracking system' .
                '</a>.<br>You could find information, but you might have to sign up to open a ticket.<br>' .
                '<br>Please, report all bugs and feature requests that are related to this extension.<br>' .
                '<br>If for any reason you can\'t submit a question, bug report or feature request to our ' .
                'ticket system, you can write us an email - contact@qsoftvietnam.com.',
                $moduleSupportLink) . '</td>
                </tr>
                <tr>
                    <td class="qsoft-developer-label line"><img class="qsoft-logo" src="' . $logoLink . '" width="100px" height="34px"> </td>
                    <td class="qsoft-developer-text line">' . $helper->__(
                'Wanna hire QSoft Magento team to customize your store? Please E-mail us contact@qsoftvietnam.com.<br>' .
                '<br>See our provided services on <a href="%s" target="_blank">website</a>.',
                $servicesLink) . '</td>
                </tr>
            </table>';


        return $html;
    }

    protected function _getConfigValue($module, $config)
    {
        $locale = Mage::app()->getLocale()->getLocaleCode();
        $defaultLocale = 'en_US';
        $mainConfig = Mage::getConfig();
        $moduleConfig = $mainConfig->getNode('modules/' . $module . '/' . $config);

        if ((string)$moduleConfig) {
            return $moduleConfig;
        }

        if ($moduleConfig->$locale) {
            return $moduleConfig->$locale;
        } else {
            return $moduleConfig->$defaultLocale;
        }
    }


    const PLATFORM_CE = 'ce';
    const PLATFORM_PE = 'pe';
    const PLATFORM_EE = 'ee';
    const PLATFORM_GO = 'go';
    const PLATFORM_UNKNOWN = 'unknown';

    protected static $_platformCode = self::PLATFORM_UNKNOWN;

    /**
     * Get edition code
     * @return string
     */
    protected function _getPlatform()
    {
        if (self::$_platformCode == self::PLATFORM_UNKNOWN) {
            // from Magento CE version 1.7. we can get platform from Mage class
            if (property_exists('Mage', '_currentEdition')) {
                switch (Mage::getEdition()) {
                    case Mage::EDITION_COMMUNITY:
                        self::$_platformCode = self::PLATFORM_CE;
                        break;
                    case Mage::EDITION_PROFESSIONAL:
                        self::$_platformCode = self::PLATFORM_PE;
                        break;
                    case Mage::EDITION_ENTERPRISE:
                        self::$_platformCode = self::PLATFORM_EE;
                        break;
                    case Mage::EDITION_ENTERPRISE:
                        self::$_platformCode = self::PLATFORM_EE;
                        break;
                    default:
                        self::$_platformCode = self::PLATFORM_UNKNOWN;
                }
            }

            // if platform still unknown
            if (self::$_platformCode == self::PLATFORM_UNKNOWN) {
                $modulesArray = (array)Mage::getConfig()->getNode('modules')->children();
                $isEnterprise = array_key_exists('Enterprise_Enterprise', $modulesArray);

                $isProfessional = false; // TODO: how determine?
                $isGo = false; // TODO: how?

                if ($isEnterprise) {
                    self::$_platformCode = self::PLATFORM_EE;
                } elseif ($isProfessional) {
                    self::$_platformCode = self::PLATFORM_PE;
                } elseif ($isGo) {
                    self::$_platformCode = self::PLATFORM_GO;
                } else {
                    self::$_platformCode = self::PLATFORM_CE;
                }
            }
        }
        return self::$_platformCode;
    }

}