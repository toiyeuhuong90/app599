<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE {$this->getTable('storelocator_store')}  
	  DROP `monday_open` ,
	  DROP `monday_close` ,
	  DROP `tuesday_open` ,
	  DROP `tuesday_close` ,
	  DROP `wednesday_open` ,
	  DROP `wednesday_close`,
	  DROP `thursday_open` ,
	  DROP `thursday_close`,
	  DROP `friday_open` ,
	  DROP `friday_close` ,
	  DROP `saturday_open`,
	  DROP `saturday_close` ,
	  DROP `sunday_open` ,
	  DROP `sunday_close`,
	  DROP `city`,
	  DROP `minimum_gap`;
");

$installer->endSetup(); 