<?php
namespace wcf\data\faq;
use wcf\system\request\IRouteController;

class Question extends DatabaseObject implements IRouteController {
	
	/**
	 * @inheritDoc
	 */
	protected static $databaseTableName = 'faq_questions';
	
	/**
	 * @inheritDoc
	 */
	public function getTitle() {
		return $this->question;
	}
}
