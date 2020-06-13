<?php

use wcf\system\database\table\column\DefaultFalseBooleanDatabaseTableColumn;
use wcf\system\database\table\DatabaseTable;
use wcf\system\database\table\column\ObjectIdDatabaseTableColumn;
use wcf\system\database\table\column\TextDatabaseTableColumn;
use wcf\system\database\table\index\DatabaseTableForeignKey;
use wcf\system\database\table\DatabaseTableChangeProcessor;
use wcf\system\database\table\column\NotNullInt10DatabaseTableColumn;
use wcf\system\WCF;

$tables = [
    DatabaseTable::create('wcf1_faq_questions')
        ->columns([
            ObjectIdDatabaseTableColumn::create('questionID'),
            TextDatabaseTableColumn::create('question'),
            TextDatabaseTableColumn::create('answer'),
            NotNullInt10DatabaseTableColumn::create('categoryID'),
            NotNullInt10DatabaseTableColumn::create('showOrder')
                ->defaultValue(0),
            DefaultFalseBooleanDatabaseTableColumn::create('isDisabled')
        ])
        ->foreignKeys([
            DatabaseTableForeignKey::create()
                ->columns(['categoryID'])
                ->referencedTable('wcf1_category')
                ->referencedColumns(['categoryID'])
                ->onDelete('CASCADE')
        ])
];

(new DatabaseTableChangeProcessor(
	$this->installation->getPackage(),
	$tables,
	WCF::getDB()->getEditor())
)->process();
