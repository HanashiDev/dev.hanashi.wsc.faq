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
class QuestionList extends DatabaseObjectList
{
    /**
     * @inheritDoc
     */
    public $sqlOrderBy = 'showOrder, questionID';

    protected $attachmentList;

    public function readAttachments()
    {
        if (MODULE_ATTACHMENT && !empty($this->objectIDs)) {
            $this->attachmentList = new GroupedAttachmentList('dev.tkirch.wsc.faq.question');
            $this->attachmentList->getConditionBuilder()->add('attachment.objectID IN (?)', [$this->objectIDs]);
            $this->attachmentList->readObjects();
        }
    }

    public function getAttachmentList()
    {
        if ($this->attachmentList === null) {
            $this->readAttachments();
        }

        return $this->attachmentList;
    }
}
