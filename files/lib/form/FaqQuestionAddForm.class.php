<?php

namespace wcf\form;

use Override;
use wcf\system\WCF;

class FaqQuestionAddForm extends \wcf\acp\form\FaqQuestionAddForm
{
    /**
     * @inheritDoc
     */
    public $objectEditLinkController = FaqQuestionEditForm::class;

    #[Override]
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'articleIsFrontend' => true,
        ]);
    }
}
