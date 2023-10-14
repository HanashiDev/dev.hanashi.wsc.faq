<?php

use wcf\system\database\table\column\DefaultFalseBooleanDatabaseTableColumn;
use wcf\system\database\table\column\NotNullInt10DatabaseTableColumn;
use wcf\system\database\table\column\ObjectIdDatabaseTableColumn;
use wcf\system\database\table\column\TextDatabaseTableColumn;
use wcf\system\database\table\column\TinyintDatabaseTableColumn;
use wcf\system\database\table\DatabaseTable;
use wcf\system\database\table\index\DatabaseTableForeignKey;

return [
    DatabaseTable::create('wcf1_faq_questions')
        ->columns([
            ObjectIdDatabaseTableColumn::create('questionID'),
            TextDatabaseTableColumn::create('question'),
            TextDatabaseTableColumn::create('answer'),
            NotNullInt10DatabaseTableColumn::create('categoryID'),
            NotNullInt10DatabaseTableColumn::create('showOrder')
                ->defaultValue(0),
            DefaultFalseBooleanDatabaseTableColumn::create('isDisabled'),
            TinyintDatabaseTableColumn::create('hasEmbeddedObjects')
                ->notNull()
                ->defaultValue(0),
            TinyintDatabaseTableColumn::create('isMultilingual')
                ->notNull()
                ->defaultValue(0),
        ])
        ->foreignKeys([
            DatabaseTableForeignKey::create()
                ->columns(['categoryID'])
                ->referencedTable('wcf1_category')
                ->referencedColumns(['categoryID'])
                ->onDelete('CASCADE'),
        ]),
];
