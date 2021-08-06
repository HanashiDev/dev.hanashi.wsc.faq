<?php

namespace wcf\acp\form;

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
}
