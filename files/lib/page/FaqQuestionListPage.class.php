<?php
namespace wcf\page;
use wcf\data\faq\category\FaqCategoryNodeTree;
use wcf\data\faq\Question;
use wcf\data\faq\QuestionList;
use wcf\system\WCF;

class FaqQuestionListPage extends AbstractPage {

	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();

		//get categories
		$faqs = [];
		$categoryTree = new FaqCategoryNodeTree('dev.tkirch.wsc.faq.category');
		foreach($categoryTree->getIterator() as $category) {
			if (!$category->isAccessible()) continue;

			$questionList = new QuestionList();
			$questionList->getConditionBuilder()->add('categoryID = ?', [$category->categoryID]);
			$questionList->readObjects();

			if($questionList->countObjects() > 0) {
				$faqs[$category->categoryID] = [];
				$faqs[$category->categoryID]['title'] = WCF::getLanguage()->get($category->title);
				$faqs[$category->categoryID]['questions'] = $questionList->getObjects();
			}
		}

		WCF::getTPL()->assign([
            'faqs' => $faqs
		]);
	}
}
