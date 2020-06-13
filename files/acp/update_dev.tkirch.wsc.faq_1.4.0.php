<?php

use wcf\system\database\table\column\DefaultFalseBooleanDatabaseTableColumn;
use wcf\system\database\table\PartialDatabaseTable ;
use wcf\system\database\table\DatabaseTableChangeProcessor;
use wcf\system\database\table\column\NotNullInt10DatabaseTableColumn;
use wcf\system\WCF;

$tables = [
    PartialDatabaseTable ::create('wcf1_faq_questions')
        ->columns([
            NotNullInt10DatabaseTableColumn::create('showOrder')
                ->defaultValue(0),
            DefaultFalseBooleanDatabaseTableColumn::create('isDisabled')
        ])
];

(new DatabaseTableChangeProcessor(
	$this->installation->getPackage(),
	$tables,
	WCF::getDB()->getEditor())
)->process();
