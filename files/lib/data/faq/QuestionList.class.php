<?php

namespace wcf\data\faq;

use wcf\data\attachment\GroupedAttachmentList;
use wcf\data\DatabaseObjectList;

/**
 * @method  Question        current()
 * @method  Question[]       getObjects()
 * @method  Question|null    getSingleObject()
 * @method  Question|null    search($objectID)
 * @property    Question[] $objects
 */
final class QuestionList extends DatabaseObjectList
{
    /**
     * @inheritDoc
     */
    public $sqlOrderBy = 'showOrder, questionID';

    protected GroupedAttachmentList $attachmentList;

    public function readAttachments()
    {
        if (!empty($this->objectIDs)) {
            $this->attachmentList = new GroupedAttachmentList('dev.tkirch.wsc.faq.question');
            $this->attachmentList->getConditionBuilder()->add('attachment.objectID IN (?)', [$this->objectIDs]);
            $this->attachmentList->readObjects();
        }
    }

    public function getAttachmentList()
    {
        if (!isset($this->attachmentList)) {
            $this->readAttachments();
        }

        return $this->attachmentList;
    }
}
