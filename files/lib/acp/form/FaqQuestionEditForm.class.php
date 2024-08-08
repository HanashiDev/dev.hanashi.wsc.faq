<?php

namespace wcf\acp\form;

use CuyZ\Valinor\Mapper\MappingError;
use Override;
use wcf\data\faq\Question;
use wcf\http\Helper;
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

            $this->formObject = new Question($queryParameters['id']);
            if (!$this->formObject->questionID) {
                throw new IllegalLinkException();
            }
            if ($this->formObject->isMultilingual) {
                $this->isMultilingual = 1;
            }
        } catch (MappingError) {
            throw new IllegalLinkException();
        }
    }
}
