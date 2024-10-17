<?php

namespace wcf\system\search;

use Override;
use wcf\data\faq\QuestionList;
use wcf\data\search\ISearchResultObject;
use wcf\system\WCF;

final class FaqQuestionSearch extends AbstractSearchProvider
{
    private array $faqCache = [];

    #[Override]
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

    #[Override]
    public function getObject(int $objectID): ?ISearchResultObject
    {
        return $this->faqCache[$objectID];
    }

    #[Override]
    public function getTableName(): string
    {
        return 'wcf' . WCF_N . '_faq_questions';
    }

    #[Override]
    public function getIDFieldName(): string
    {
        return $this->getTableName() . '.questionID';
    }

    #[Override]
    public function getSubjectFieldName(): string
    {
        return $this->getTableName() . '.question';
    }

    #[Override]
    public function getUsernameFieldName(): string
    {
        return $this->getTableName() . '.question';
    }

    #[Override]
    public function getTimeFieldName(): string
    {
        return $this->getTableName() . '.showOrder';
    }

    #[Override]
    public function getFormTemplateName(): string
    {
        return '';
    }

    #[Override]
    public function isAccessible(): bool
    {
        return WCF::getSession()->getPermission('user.faq.canViewFAQ');
    }

    #[Override]
    public function getCustomIconName(): ?string
    {
        return 'circle-question';
    }
}
