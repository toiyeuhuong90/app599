<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 22/08/2016
 * Time: 09:23
 */ 
class QSoft_Customer_Block_Customer_Account_Navigation extends Mage_Customer_Block_Account_Navigation {
    public function removeLinkByName($name)
    {
        unset($this->_links[$name]);
        return $this;
    }
}