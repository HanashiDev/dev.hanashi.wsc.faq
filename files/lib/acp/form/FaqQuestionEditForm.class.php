<?php

namespace wcf\acp\form;

use wcf\data\faq\Question;
use wcf\data\language\item\LanguageItemList;
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
            $this->formObject = new Question((int)$_REQUEST['id']);
            if (!$this->formObject->questionID) {
                throw new IllegalLinkException();
            }
            if ($this->formObject->isMultilingual) {
                $this->isMultilingual = 1;

                $languageItemList = new LanguageItemList();
                $languageItemList->getConditionBuilder()->add('languageItem = ?', [$this->formObject->answer]);
                $languageItemList->readObjects();
                foreach ($languageItemList as $languageItem) {
                    $this->multiLingualAnswers[$languageItem->languageID] = $languageItem->languageItemValue;
                }
            }
        } else {
            throw new IllegalLinkException();
        }
    }
}
