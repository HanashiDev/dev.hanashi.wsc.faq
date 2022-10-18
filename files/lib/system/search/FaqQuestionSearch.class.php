<?php

namespace wcf\system\search;

use wcf\data\faq\Question;
use wcf\data\faq\QuestionList;
use wcf\system\page\PageLocationManager;
use wcf\system\WCF;

class FaqQuestionSearch extends AbstractSearchableObjectType
{
    protected $faqCache = [];

    /**
     * @inheritDoc
     */
    public function cacheObjects(array $objectIDs, ?array $additionalData = null)
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
    public function getObject($objectID)
    {
        return $this->faqCache[$objectID] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getTableName()
    {
        return 'wcf' . WCF_N . '_faq_questions';
    }

    /**
     * @inheritDoc
     */
    public function getIDFieldName()
    {
        return $this->getTableName() . '.questionID';
    }

    /**
     * @inheritDoc
     */
    public function getSubjectFieldName()
    {
        return $this->getTableName() . '.question';
    }

    /**
     * @inheritDoc
     */
    public function getUsernameFieldName()
    {
        return $this->getTableName() . '.question';
    }

    /**
     * @inheritDoc
     */
    public function getTimeFieldName()
    {
        return $this->getTableName() . '.showOrder';
    }

    /**
     * @inheritDoc
     */
    public function getFormTemplateName()
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function isAccessible()
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
}
