<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    MRWEB
 * @package     MRWEB_SocialShareButtons
 * @copyright   Copyright (c) 2015 MR Websolution (http://www.mrwebsolution.in)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
?>
<?php

class QSoft_SocialShareButtons_Helper_Data extends Mage_Payment_Helper_Data
{
    const SSB_STATUS        = 'socialsharebuttonssettings/socialsharebuttonsgeneral/ssb_enable';
    const SSB_MOB_DISABLE  = 'socialsharebuttonssettings/socialsharebuttonsgeneral/ssb_mob_disable';
    const SSB_DISABLE_SHOWHIDE   = 'socialsharebuttonssettings/socialsharebuttonsgeneral/ssb_disable_showhide';
    const SSB_TOP_MARGIN   = 'socialsharebuttonssettings/socialsharebuttonsgeneral/ssb_margin_top';
    const SSB_SIDEBAR_POSITION   = 'socialsharebuttonssettings/socialsharebuttonsgeneral/ssb_position';
    const SSB_SIDEBAR_BUTTONS   = 'socialsharebuttonssettings/socialsharebuttonsgeneral/ssb_num_share_buttons';
    const SSB_SIDEBAR_DELAY_TIME   = 'socialsharebuttonssettings/socialsharebuttonsgeneral/ssb_delay_time';
    const SSB_SIDEBAR_SHOW_HIDE   = 'socialsharebuttonssettings/socialsharebuttonsgeneral/ssb_disable_showhide';
    const SSB_SIDEBAR_HIDE_HOME   = 'socialsharebuttonssettings/socialsharebuttonsgeneral/ssb_home_disable';
    const SSB_SIDEBAR_JQUERY   = 'socialsharebuttonssettings/socialsharebuttonsgeneral/ssb_jquery_yesno';
    const SSB_SIDEBAR_MAIL_URL   = 'socialsharebuttonssettings/socialsharebuttonsgeneral/ssb_mail_url';
    const SSB_SIDEBAR_YT_URL   = 'socialsharebuttonssettings/socialsharebuttonsgeneral/ssb_yt_url';
/*
 * Mobile Detection
 * */      		
public function ssbIsMobile() {
	// Check the server headers to see if they're mobile friendly
	if(isset($_SERVER["HTTP_X_WAP_PROFILE"])) {
		return true;
	}
	// Let's NOT return "mobile" if it's an iPhone, because the iPhone can render normal pages quite well.
	if(strstr($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
		return false;
	}
	// If the http_accept header supports wap then it's a mobile too
	if(preg_match("/wap\.|\.wap/i",$_SERVER["HTTP_ACCEPT"])) {
		return true;
	}
	// Still no luck? Let's have a look at the user agent on the browser. If it contains
	// any of the following, it's probably a mobile device. Kappow!
	if(isset($_SERVER["HTTP_USER_AGENT"])){
		$user_agents = array("midp", "j2me", "avantg", "docomo", "novarra", "palmos", "palmsource", "240x320", "opwv", "chtml", "pda", "windows\ ce", "mmp\/", "blackberry", "mib\/", "symbian", "wireless", "nokia", "hand", "mobi", "phone", "cdm", "up\.b", "audio", "SIE\-", "SEC\-", "samsung", "HTC", "mot\-", "mitsu", "sagem", "sony", "alcatel", "lg", "erics", "vx", "NEC", "philips", "mmm", "xx", "panasonic", "sharp", "wap", "sch", "rover", "pocket", "benq", "java", "pt", "pg", "vox", "amoi", "bird", "compal", "kg", "voda", "sany", "kdd", "dbt", "sendo", "sgh", "gradi", "jb", "\d\d\di", "moto");
		foreach($user_agents as $user_string){
			if(preg_match("/".$user_string."/i",$_SERVER["HTTP_USER_AGENT"])) {
				return true;
			}
		}
	}
	// None of the above? Then it's probably not a mobile device.
	return false;
}
}  
?>
