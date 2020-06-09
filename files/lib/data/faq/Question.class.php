<?php
namespace wcf\data\faq;
use wcf\data\category\Category;
use wcf\data\faq\category\FaqCategory;
use wcf\data\user\User;
use wcf\data\DatabaseObject;
use wcf\system\html\output\HtmlOutputProcessor;
use wcf\system\request\IRouteController;
use wcf\system\WCF;

class Question extends DatabaseObject implements IRouteController {
	protected $category;
	
	/**
	 * @inheritDoc
	 */
	protected static $databaseTableName = 'faq_questions';

	/**
	 * @inheritDoc
	 */
	protected static $databaseTableIndexName = 'questionID';
	
	/**
	 * @inheritDoc
	 */
	public function getTitle() {
		return WCF::getLanguage()->get($this->question);
	}
	
	/**
	 * @inheritDoc
	 */
	public function getAnswer() {
		return WCF::getLanguage()->get($this->answer);
	}

	public function getFormattedOutput() {
		$processor = new HtmlOutputProcessor();
		$processor->process($this->getAnswer(), 'dev.tkirch.wsc.faq.question', $this->questionID);
		
		return $processor->getHtml();
	}

	public function getPlainOutput() {
		$processor = new HtmlOutputProcessor();
		$processor->setOutputType('text/plain');
		$processor->process($this->getAnswer(), 'dev.tkirch.wsc.faq.question', $this->questionID);
		
		return $processor->getHtml();
	}
	
	public function getCategory() {
		if ($this->category === null) {
			$category = new Category($this->categoryID);
			$this->category = new FaqCategory($category);
		}
		return $this->category;
	}

	public function isAccessible(User $user = null) {
		if ($this->isDisabled && !WCF::getSession()->getPermission('admin.faq.canViewQuestion')) return false;

		if ($this->getCategory()) {
			return $this->getCategory()->isAccessible($user);
		}

		return WCF::getSession()->getPermission('user.faq.canViewFAQ');
	}
}
