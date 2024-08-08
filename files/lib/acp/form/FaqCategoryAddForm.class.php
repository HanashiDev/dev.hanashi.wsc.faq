<?php

namespace wcf\acp\form;

class FaqCategoryAddForm extends CategoryAddFormBuilderForm
{
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'wcf.acp.menu.link.faq.categories.add';

    /**
     * @inheritDoc
     */
    public string $objectTypeName = 'dev.tkirch.wsc.faq.category';
}
