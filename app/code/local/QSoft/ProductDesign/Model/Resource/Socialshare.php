<?php

/**
 * QSoft Vietnam
 * http://www.qsoftvietnam.com
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@qsoftvietnam.com so we can send you a copy immediately.
 *
 * @category    QSoft
 * @package     QSoft_ProductDesign
 * @author      Tuyen Nguyen <tuyennn@qsoftvietnam.com>
 * @copyright   Copyright (c) 2016 (http://www.qsoftvietnam.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class QSoft_ProductDesign_Model_Resource_Socialshare extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('productdesign/socialshare', 'id');
    }

}