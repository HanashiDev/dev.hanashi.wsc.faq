<?php

namespace wcf\page;

use CuyZ\Valinor\Mapper\MappingError;
use Override;
use wcf\data\faq\category\FaqCategory;
use wcf\data\faq\Question;
use wcf\http\Helper;
use wcf\system\exception\IllegalLinkException;
use wcf\system\message\embedded\object\MessageEmbeddedObjectManager;
use wcf\system\WCF;

class FaqQuestionPage extends AbstractPage
{
    /**
     * @inheritDoc
     */
    public $neededPermissions = ['user.faq.canViewFAQ'];

    protected Question $question;

    public ?FaqCategory $category;

    #[Override]
    public function readParameters()
    {
        parent::readParameters();

        try {
            $queryParameters = Helper::mapQueryParameters(
                $_GET,
                <<<'EOT'
                    array {
                        id: positive-int
                    }
                    EOT
            );

            $this->question = new Question($queryParameters['id']);
            if (!$this->question->questionID || !$this->question->isAccessible()) {
                throw new IllegalLinkException();
            }

            MessageEmbeddedObjectManager::getInstance()->loadObjects(
                'dev.tkirch.wsc.faq.question',
                [$this->question->questionID]
            );

            $this->category = FaqCategory::getCategory($this->question->categoryID);
        } catch (MappingError) {
            throw new IllegalLinkException();
        }
    }

    #[Override]
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'question' => $this->question,
        ]);
    }
}
