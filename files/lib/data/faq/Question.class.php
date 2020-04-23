<?php
namespace wcf\data\faq;
use wcf\data\DatabaseObject;
use wcf\system\request\IRouteController;
use wcf\system\WCF;

class Question extends DatabaseObject implements IRouteController {
	
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
}
