<?php

namespace wcf\acp\form;

use wcf\data\faq\Question;
use wcf\system\exception\IllegalLinkException;

class FaqQuestionEditForm extends FaqQuestionAddForm
{
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'wcf.acp.menu.link.faq.questions.list';

    /**
     * @inheritDoc
     */
    public $formAction = 'edit';

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        parent::readParameters();

        if (isset($_REQUEST['id'])) {
            $this->formObject = new Question(intval($_REQUEST['id']));
            if (!$this->formObject->questionID) {
                throw new IllegalLinkException();
            }
        }
    }
}
