 <?php
class QSoft_ProductDesign_Block_Adminhtml_Frontendgroup_Edit_Grid_Renderer_Thumb extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $image = $row->getData($this->getColumn()->getIndex());
        if(isset($image)){
            $html = '<img src="'.$image.'" style="max-width:200px; max-height:100px;"/>';
        }else{
            $html = 'null';
        }

        return $html;
    }

}

