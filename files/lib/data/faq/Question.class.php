<?php

namespace wcf\data\faq;

use wcf\data\attachment\GroupedAttachmentList;
use wcf\data\category\Category;
use wcf\data\DatabaseObject;
use wcf\data\faq\category\FaqCategory;
use wcf\data\search\ICustomIconSearchResultObject;
use wcf\data\user\User;
use wcf\page\FaqQuestionPage;
use wcf\system\html\output\HtmlOutputProcessor;
use wcf\system\request\IRouteController;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

class Question extends DatabaseObject implements ICustomIconSearchResultObject, IRouteController
{
    protected $category;

    /**
     * @inheritDoc
     */
    protected static $databaseTableName = 'faq_questions';

    /**
     * @inheritDoc
     */
    protected static $databaseTableIndexName = 'questionID';

    protected $attachmentList;

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return WCF::getLanguage()->get($this->question);
    }

    /**
     * @inheritDoc
     */
    public function getAnswer()
    {
        return WCF::getLanguage()->get($this->answer);
    }

    public function getFormattedOutput()
    {
        $processor = new HtmlOutputProcessor();
        $processor->process($this->getAnswer(), 'dev.tkirch.wsc.faq.question', $this->questionID);

        return $processor->getHtml();
    }

    public function getPlainOutput()
    {
        $processor = new HtmlOutputProcessor();
        $processor->setOutputType('text/plain');
        $processor->process($this->getAnswer(), 'dev.tkirch.wsc.faq.question', $this->questionID);

        return $processor->getHtml();
    }

    public function getCategory()
    {
        if ($this->category === null) {
            $category = new Category($this->categoryID);
            $this->category = new FaqCategory($category);
        }

        return $this->category;
    }

    public function isAccessible(?User $user = null)
    {
        if ($this->isDisabled && !WCF::getSession()->getPermission('admin.faq.canViewQuestion')) {
            return false;
        }

        if ($this->getCategory()) {
            return $this->getCategory()->isAccessible($user);
        }

        return WCF::getSession()->getPermission('user.faq.canViewFAQ');
    }

    public function getAttachments()
    {
        if (MODULE_ATTACHMENT && empty($this->attachmentList)) {
            $this->attachmentList = new GroupedAttachmentList('dev.tkirch.wsc.faq.question');
            $this->attachmentList->getConditionBuilder()->add('attachment.objectID = ?', [$this->questionID]);
            $this->attachmentList->readObjects();
        }

        return $this->attachmentList;
    }

    /**
     * @inheritDoc
     */
    public function getUserProfile()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getSubject()
    {
        return $this->getTitle();
    }

    /**
     * @inheritDoc
     */
    public function getTime()
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    public function getLink($query = '')
    {
        return LinkHandler::getInstance()->getControllerLink(FaqQuestionPage::class, [
            'object' => $this,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getObjectTypeName()
    {
        return 'dev.tkirch.wsc.faq.question';
    }

    /**
     * @inheritDoc
     */
    public function getFormattedMessage()
    {
        return $this->getFormattedOutput();
    }

    /**
     * @inheritDoc
     */
    public function getContainerTitle()
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getContainerLink()
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getCustomSearchResultIcon()
    {
        return 'fa-question-circle-o';
    }
}
