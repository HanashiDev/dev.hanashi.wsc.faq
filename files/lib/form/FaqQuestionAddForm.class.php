<?php

namespace wcf\form;

use wcf\system\WCF;

class FaqQuestionAddForm extends \wcf\acp\form\FaqQuestionAddForm
{
    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'articleIsFrontend' => true,
        ]);
    }
}
