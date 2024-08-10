<?php

namespace wcf\acp\form;

use Override;

class FaqCategoryEditForm extends FaqCategoryAddForm
{
    /**
     * @inheritDoc
     */
    public $formAction = 'edit';

    #[Override]
    public function readParameters()
    {
        parent::readParameters();

        if (isset($this->formObject->additionalData['faqIcon'])) {
            $this->icon = $this->formObject->additionalData['faqIcon'];
        }
    }
}
