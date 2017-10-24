<?php
class QSoft_Customer_Block_Adminhtml_Schedulebodyscan_Edit_Renderer_Interested extends Varien_Data_Form_Element_Abstract
{
	public function getElementHtml() {
		$html = '';
		$jsonArr = Mage::helper('core')->jsonDecode($this->getValue());

		$initArr = array("1" => "RUN", "2" => "BIKE", "3" => "SWIM", "4" => "TRIATHLON", "5" => "CUSTOM DESIGN", "6" => "NEWSLETTER");

		foreach($initArr as $index => $value) {
			if(in_array($value, $jsonArr)) {
				$clss = 'checked';
			} else {
				$clss = '';
			}

			$html .= '<label style="margin-right: 10px; display: block;"><input name="checkSchedule['. $index .']" type="checkbox" class="form-control" value="'. $value .'" '. $clss .' />'. $value .'</label>';
		}

		return $html;
	}
}
