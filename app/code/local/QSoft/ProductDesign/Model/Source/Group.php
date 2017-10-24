<?php

class QSoft_ProductDesign_Model_Source_Group
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return Mage::helper('productdesign/frontendgroup')->getSelectcat(0);
    }


}
