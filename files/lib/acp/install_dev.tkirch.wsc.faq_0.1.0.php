<?php
use wcf\system\database\table\DatabaseTable;
use wcf\system\database\table\column\ObjectIdDatabaseTableColumn;
use wcf\system\database\table\TextDatabaseTableColumn;
use wcf\system\database\table\index\DatabaseTableForeignKey;
use wcf\system\database\table\DatabaseTableChangeProcessor;
use wcf\system\database\table\column\DefaultTrueBooleanDatabaseTableColumn;
use wcf\system\WCF;

$tables = [
    DatabaseTable::create('wcf1_faq_questions')
        ->columns([
            ObjectIdDatabaseTableColumn::create('questionID'),
            TextDatabaseTableColumn::create('question')
                ->notNull(),
            TextDatabaseTableColumn::create('answer')
                ->notNull(),
        ])
        /*
        ->foreignKeys([
            DatabaseTableForeignKey::create()
                ->columns(['gameID'])
                ->referencedTable('wcf1_gm_game')
                ->referencedColumns(['gameID'])
                ->onDelete('CASCADE')
        ])*/
];

(new DatabaseTableChangeProcessor(
	$this->installation->getPackage(),
	$tables,
	WCF::getDB()->getEditor())
)->process();