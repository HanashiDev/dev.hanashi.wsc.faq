<?php

namespace wcf\page;

use wcf\data\faq\Question;
use wcf\system\exception\IllegalLinkException;
use wcf\system\message\embedded\object\MessageEmbeddedObjectManager;
use wcf\system\WCF;

class FaqQuestionPage extends AbstractPage
{
    /**
     * @inheritDoc
     */
    public $neededPermissions = ['user.faq.canViewFAQ'];

    /**
     * @var Question
     */
    protected $question;

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        parent::readParameters();

        if (isset($_REQUEST['id'])) {
            $this->question = new Question((int)$_REQUEST['id']);
            if (!$this->question->questionID || !$this->question->isAccessible()) {
                throw new IllegalLinkException();
            }

            MessageEmbeddedObjectManager::getInstance()->loadObjects(
                'dev.tkirch.wsc.faq.question',
                [$this->question->questionID]
            );
        } else {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'question' => $this->question,
        ]);
    }
}
