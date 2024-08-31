<?php

namespace wcf\data\faq;

use Override;
use wcf\data\attachment\GroupedAttachmentList;
use wcf\data\category\Category;
use wcf\data\DatabaseObject;
use wcf\data\faq\category\FaqCategory;
use wcf\data\search\ISearchResultObject;
use wcf\data\user\User;
use wcf\page\FaqQuestionPage;
use wcf\system\html\output\HtmlOutputProcessor;
use wcf\system\request\IRouteController;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

/**
 * @property-read   int $questionID         unique id of the question
 * @property-read   string $question        content of the question
 * @property-read   string $answer          content of the answer
 * @property-read   int $categoryID         id of the category the question belongs to
 * @property-read   int $showOrder          sort order of the question
 * @property-read   int $isDisabled         is `1` if the question is disabled, otherwise `0`
 * @property-read   int $hasEmbeddedObjects is `1` if the question has embedded objects, otherwise `0`
 * @property-read   int $isMultilingual
 */
final class Question extends DatabaseObject implements IRouteController, ISearchResultObject
{
    protected FaqCategory $category;

    /**
     * @inheritDoc
     */
    protected static $databaseTableName = 'faq_questions';

    /**
     * @inheritDoc
     */
    protected static $databaseTableIndexName = 'questionID';

    protected GroupedAttachmentList $attachmentList;

    #[Override]
    public function getTitle(): string
    {
        return WCF::getLanguage()->get($this->question);
    }

    public function getAnswer(): string
    {
        return WCF::getLanguage()->get($this->answer);
    }

    public function getFormattedOutput(): string
    {
        $processor = new HtmlOutputProcessor();
        $processor->process($this->getAnswer(), 'dev.tkirch.wsc.faq.question', $this->questionID);

        return $processor->getHtml();
    }

    public function getPlainOutput(): string
    {
        $processor = new HtmlOutputProcessor();
        $processor->setOutputType('text/plain');
        $processor->process($this->getAnswer(), 'dev.tkirch.wsc.faq.question', $this->questionID);

        return $processor->getHtml();
    }

    public function getCategory(): FaqCategory
    {
        if (!isset($this->category)) {
            $category = new Category($this->categoryID);
            $this->category = new FaqCategory($category);
        }

        return $this->category;
    }

    public function isAccessible(?User $user = null): bool
    {
        if ($this->isDisabled && !WCF::getSession()->getPermission('admin.faq.canViewQuestion')) {
            return false;
        }

        if ($this->getCategory()) {
            return $this->getCategory()->isAccessible($user);
        }

        return WCF::getSession()->getPermission('user.faq.canViewFAQ');
    }

    public function getAttachments(): GroupedAttachmentList
    {
        if (empty($this->attachmentList)) {
            $this->attachmentList = new GroupedAttachmentList('dev.tkirch.wsc.faq.question');
            $this->attachmentList->getConditionBuilder()->add('attachment.objectID = ?', [$this->questionID]);
            $this->attachmentList->readObjects();
        }

        return $this->attachmentList;
    }

    #[Override]
    public function getUserProfile()
    {
        return null;
    }

    #[Override]
    public function getSubject()
    {
        return $this->getTitle();
    }

    #[Override]
    public function getTime()
    {
        return 0;
    }

    #[Override]
    public function getLink($query = '')
    {
        return LinkHandler::getInstance()->getControllerLink(FaqQuestionPage::class, [
            'object' => $this,
        ]);
    }

    #[Override]
    public function getObjectTypeName()
    {
        return 'dev.tkirch.wsc.faq.question';
    }

    #[Override]
    public function getFormattedMessage()
    {
        return $this->getFormattedOutput();
    }

    #[Override]
    public function getContainerTitle()
    {
        return '';
    }

    #[Override]
    public function getContainerLink()
    {
        return '';
    }
}
