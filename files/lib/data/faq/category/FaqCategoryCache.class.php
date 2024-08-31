<?php

namespace wcf\data\faq\category;

use wcf\system\category\CategoryHandler;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

final class FaqCategoryCache extends SingletonFactory
{
    /**
     * number of total questions
     * @var int[]
     */
    protected array $questions;

    protected function initQuestions()
    {
        $this->questions = [];

        $sql = "SELECT      COUNT(questionID) AS count,
                            categoryID
                FROM        wcf1_faq_questions
                GROUP BY    categoryID";
        $stmnt = WCF::getDB()->prepare($sql);
        $stmnt->execute();
        $contacts = $stmnt->fetchMap('categoryID', 'count');

        $categoryToParent = [];
        /** @var Category $category */
        foreach (CategoryHandler::getInstance()->getCategories(FaqCategory::OBJECT_TYPE_NAME) as $category) {
            if (!isset($categoryToParent[$category->parentCategoryID])) {
                $categoryToParent[$category->parentCategoryID] = [];
            }
            $categoryToParent[$category->parentCategoryID][] = $category->categoryID;
        }

        $this->countQuestions($categoryToParent, $contacts, 0);
    }

    protected function countQuestions(array $categoryToParent, array &$contacts, $categoryID)
    {
        $count = (isset($contacts[$categoryID])) ? $contacts[$categoryID] : 0;
        if (isset($categoryToParent[$categoryID])) {
            foreach ($categoryToParent[$categoryID] as $childCategoryID) {
                $count += $this->countQuestions($categoryToParent, $contacts, $childCategoryID);
            }
        }

        if ($categoryID) {
            $this->questions[$categoryID] = $count;
        }

        return $count;
    }

    public function getQuestions($categoryID)
    {
        if (!isset($this->questions)) {
            $this->initQuestions();
        }

        if (isset($this->questions[$categoryID])) {
            return $this->questions[$categoryID];
        }

        return 0;
    }
}
