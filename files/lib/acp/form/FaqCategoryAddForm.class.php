<?php

namespace wcf\acp\form;

use wcf\system\request\LinkHandler;
use wcf\system\WCF;

class FaqCategoryAddForm extends AbstractCategoryAddForm
{
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'wcf.acp.menu.link.faq.categories.add';

    /**
     * @inheritDoc
     */
    public $objectTypeName = 'dev.tkirch.wsc.faq.category';

    /**
     * @inheritDoc
     */
    public function save()
    {
        parent::save();

        WCF::getTPL()->assign([
            'objectEditLink' => LinkHandler::getInstance()->getControllerLink(
                FaqCategoryEditForm::class,
                ['id' => $this->objectAction->getReturnValues()['returnValues']->categoryID]
            ),
        ]);
    }
}
