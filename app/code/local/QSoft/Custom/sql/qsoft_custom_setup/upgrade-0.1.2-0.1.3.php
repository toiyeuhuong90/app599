<?php
/**
 * Created by PhpStorm.
 * User: doanns
 * Date: 13/04/2016
 * Time: 2:42 PM
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$content = <<<LOL
<div class="qs-page-container bodyscan-page">
				<div class="qs-video-bodyscan"><img src="{{media url="wysiwyg/video_body_scan.jpg"}}" alt="" /></div>

				<div class="qs-what-bodyscan">
					<div class="container">
						<div class="qs-what-bodyscan-content">
							<div class="qs-desc-50">What is body scan?</div>
							<div class="qs-desc-normal-16">Throw away that tape measure. We all know the cost of human error in hand measurements. Styku is 76% more precise than measurements from the most skilled hand-measuring experts.</div>
						</div>
					</div>
				</div>

				<div class="qs-why-using-bodyscan">
					<div class="container">
						<div class="qs-intro-why-using-bodyscan">
							<div class="qs-desc-50">Why using body scan?</div>
							<div class="qs-desc-normal-16">Whether you're a fitness professional, doctor, or even a tailor, Styku's technology enables you to extract key body shape and measurements relevant to your industry.</div>
						</div>

						<div class="qs-col-why-using-bodyscan">
							<div class="row">
								<div class="col-sm-6">
									<div class="qs-col-why-bodyscan-wrapper">
										<div class="qs-img-why-bodyscan"><img src="{{media url="wysiwyg/icon_why_bodyscan_1.png"}}" alt="" /></div>
										<div class="qs-title-why-bodyscan">Fitness & Sports</div>
										<div class="qs-desc-normal-16">Throw away that tape measure. We all know the cost of human error in hand measurements. Styku is 76% more precise than measurements from the most skilled hand-measuring experts.</div>
									</div>
								</div>

								<div class="col-sm-6">
									<div class="qs-col-why-bodyscan-wrapper">
										<div class="qs-img-why-bodyscan"><img src="{{media url="wysiwyg/icon_why_bodyscan_2.png"}}" alt="" /></div>
										<div class="qs-title-why-bodyscan">Aesthetics</div>
										<div class="qs-desc-normal-16">Plastic surgeons, dermatologists, and other aesthetics professionals use Styku's technology to validate the effectiveness of body contouring devices. Before/after 3D images illustrate real change.</div>
									</div>
								</div>

								<div class="col-sm-6">
									<div class="qs-col-why-bodyscan-wrapper">
										<div class="qs-img-why-bodyscan"><img src="{{media url="wysiwyg/icon_why_bodyscan_3.png"}}" alt="" /></div>
										<div class="qs-title-why-bodyscan">Medical Weight Loss</div>
										<div class="qs-desc-normal-16">Body measurements are correlated with obesity related disease, which doctors use to assess and stratify risk. Bariatric surgeons and weight-loss professionals track progress using precise and accurate measurements.</div>
									</div>
								</div>

								<div class="col-sm-6">
									<div class="qs-col-why-bodyscan-wrapper">
										<div class="qs-img-why-bodyscan"><img src="{{media url="wysiwyg/icon_why_bodyscan_4.png"}}" alt="" /></div>
										<div class="qs-title-why-bodyscan">Made-To-Measure</div>
										<div class="qs-desc-normal-16">We enable clothing and footwear manufacturers the ability to improve fit by replacing a measuring tape with a tool that’s more consistent. We also provide retailers the ability to predict size & fit.</div>
									</div>
								</div>
							</div>
						</div>

						<div class="qs-button-learn-more">
							<a href="#">Learn More</a>
						</div>
					</div>
				</div>

				<div class="qs-use-bodyscan">
					<div class="container">
						<div class="qs-desc-50">How will you use body scan?</div>

						<div class="qs-col-use-bodyscan">
							<div class="row">
								<div class="col-md-3">
									<div class="qs-col-use-bodyscan">
										<div class="qs-img-use-bodyscan"><img src="{{media url="wysiwyg/icon_body_1.png"}}" alt="" /></div>
										<div class="qs-title-use-bodyscan">Measurements</div>
										<div class="qs-desc-normal-16">Extract hundreds of measurments. 76% more precise than an expert tailor, and way easier.</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="qs-col-use-bodyscan">
										<div class="qs-img-use-bodyscan"><img src="{{media url="wysiwyg/icon_body_2.png"}}" alt="" /></div>
										<div class="qs-title-use-bodyscan">3D Model</div>
										<div class="qs-desc-normal-16">Rotate, pan, and zoom a full body model from a body scan. Visualize shape and landmarks.</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="qs-col-use-bodyscan">
										<div class="qs-img-use-bodyscan"><img src="{{media url="wysiwyg/icon_body_3.png"}}" alt="" /></div>
										<div class="qs-title-use-bodyscan">Shape Analysis</div>
										<div class="qs-desc-normal-16">Analyze a subjets profile, silouette, and waist-to-hip ratio. View assymetries in shape.</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="qs-col-use-bodyscan">
										<div class="qs-img-use-bodyscan"><img src="{{media url="wysiwyg/icon_body_4.png"}}" alt="" /></div>
										<div class="qs-title-use-bodyscan">Track Progress</div>
										<div class="qs-desc-normal-16">Chart your key measurement over time. Keep your clients, patients, or members motivated.</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="qs-col-use-bodyscan">
										<div class="qs-img-use-bodyscan"><img src="{{media url="wysiwyg/icon_body_5.png"}}" alt="" /></div>
										<div class="qs-title-use-bodyscan">Fat Analysis</div>
										<div class="qs-desc-normal-16">More precise than the gold standard dunk tank or DEXA. Way more convenient.</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="qs-col-use-bodyscan">
										<div class="qs-img-use-bodyscan"><img src="{{media url="wysiwyg/icon_body_6.png"}}" alt="" /></div>
										<div class="qs-title-use-bodyscan">Risk Assessment</div>
										<div class="qs-desc-normal-16">Track risk of obesity related disease using Waist Circumference, Waist-to-Hip ratio, and body fat.</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="qs-col-use-bodyscan">
										<div class="qs-img-use-bodyscan"><img src="{{media url="wysiwyg/icon_body_7.png"}}" alt="" /></div>
										<div class="qs-title-use-bodyscan">Goal Setting</div>
										<div class="qs-desc-normal-16">Meet fat loss goals by settig desired weight loss, burn rate, and activity level.</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="qs-col-use-bodyscan">
										<div class="qs-img-use-bodyscan"><img src="{{media url="wysiwyg/icon_body_8.png"}}" alt="" /></div>
										<div class="qs-title-use-bodyscan">Dashboard</div>
										<div class="qs-desc-normal-16">View data analytics, set preferences, and manage your account from anywhere in the world.</div>
									</div>
								</div>
							</div>
						</div>

						<div class="qs-button-learn-more">
							<a href="#">Learn More</a>
						</div>
					</div>
				</div>

				<div class="qs-product-bodyscan">
					<div class="container">
						<div class="qs-product-bodyscan-wrapper">
							<div class="qs-desc-50">Body scan for<br />your fitting product</div>
							<div class="qs-desc-normal-16">Nullam consectetur ante vel nisl tempor auctor. Maecenas sed posuere urna. Duis maximus sapien in libero semper, a mollis sem tempor. Vivamus molestie viverra justo.</div>
							<div class="qs-button-learn-more">
								<a href="#">Schedule for your body scan</a>
							</div>
						</div>
					</div>
				</div>
			</div>
LOL;




$data = array(
    'title' => 'Body Scan',
    'identifier' => 'body-scan',
    'content' => $content,
    'root_template' => 'one_column',
    'is_active' => 1,
    'stores' => array(0)
);

$model = Mage::getModel('cms/page')->load('body-scan');
if($model->getId()){
    $model->setContent($content)
        ->save();
}else{
    Mage::getModel('cms/page')->setData($data)->save();
}

$installer->endSetup();