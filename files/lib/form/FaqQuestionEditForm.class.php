<?php

namespace wcf\form;

use Override;
use wcf\system\WCF;

class FaqQuestionEditForm extends \wcf\acp\form\FaqQuestionEditForm
{
    #[Override]
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'articleIsFrontend' => true,
        ]);
    }
}
