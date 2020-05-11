<?php
namespace wcf\data\faq;
use wcf\data\category\Category;
use wcf\data\faq\category\FaqCategory;
use wcf\data\user\User;
use wcf\data\DatabaseObject;
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

	public function getCategory() {
		if ($this->category === null) {
			$category = new Category($this->categoryID);
			$this->category = new FaqCategory($category);
		}
		return $this->category;
	}

	public function isAccessible(User $user = null) {
		$category = $this->getCategory();
		if (empty($category)) return false;

		return $category->isAccessible($user);
	}
}
