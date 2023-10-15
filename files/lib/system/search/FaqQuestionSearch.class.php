<?php

namespace wcf\system\search;

use wcf\data\faq\QuestionList;
use wcf\data\search\ISearchResultObject;
use wcf\system\page\PageLocationManager;
use wcf\system\WCF;

class FaqQuestionSearch extends AbstractSearchProvider
{
    protected array $faqCache = [];

    /**
     * @inheritDoc
     */
    public function cacheObjects(array $objectIDs, ?array $additionalData = null): void
    {
        $list = new QuestionList();
        $list->setObjectIDs($objectIDs);
        $list->readObjects();
        foreach ($list->getObjects() as $question) {
            if (!$question->isAccessible()) {
                continue;
            }
            $this->faqCache[$question->questionID] = $question;
        }
    }

    /**
     * @inheritDoc
     */
    public function getObject(int $objectID): ?ISearchResultObject
    {
        return $this->faqCache[$objectID];
    }

    /**
     * @inheritDoc
     */
    public function getTableName(): string
    {
        return 'wcf' . WCF_N . '_faq_questions';
    }

    /**
     * @inheritDoc
     */
    public function getIDFieldName(): string
    {
        return $this->getTableName() . '.questionID';
    }

    /**
     * @inheritDoc
     */
    public function getSubjectFieldName(): string
    {
        return $this->getTableName() . '.question';
    }

    /**
     * @inheritDoc
     */
    public function getUsernameFieldName(): string
    {
        return $this->getTableName() . '.question';
    }

    /**
     * @inheritDoc
     */
    public function getTimeFieldName(): string
    {
        return $this->getTableName() . '.showOrder';
    }

    /**
     * @inheritDoc
     */
    public function getFormTemplateName(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function isAccessible(): bool
    {
        return WCF::getSession()->getPermission('user.faq.canViewFAQ');
    }

    /**
     * @inheritDoc
     */
    public function setLocation()
    {
        PageLocationManager::getInstance()->addParentLocation('dev.tkirch.wsc.faq.FaqQuestionList');
    }

    /**
     * @inheritDoc
     */
    public function getCustomIconName(): ?string
    {
        return 'circle-question';
    }
}
