<?php

use wcf\system\database\table\column\TinyintDatabaseTableColumn;
use wcf\system\database\table\PartialDatabaseTable;

return [
    PartialDatabaseTable::create('wcf1_faq_questions')
        ->columns([
            TinyintDatabaseTableColumn::create('hasEmbeddedObjects')
                ->notNull()
                ->defaultValue(0),
        ]),
];
