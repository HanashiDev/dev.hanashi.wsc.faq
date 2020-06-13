<?php
namespace wcf\acp\page;
use wcf\page\SortablePage;
use wcf\data\faq\QuestionList;

class FaqQuestionListPage extends SortablePage {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.faq.questions.list';

	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.faq.canViewQuestion'];

	/**
	 * @inheritDoc
	 */
	public $objectListClassName = QuestionList::class;

	/**
	 * @inheritDoc
	 */
	public $validSortFields = ['questionID', 'showOrder'];
}
