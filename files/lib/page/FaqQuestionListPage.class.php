<?php

namespace wcf\page;

use wcf\data\faq\category\FaqCategoryNodeTree;
use wcf\data\faq\QuestionList;
use wcf\system\message\embedded\object\MessageEmbeddedObjectManager;
use wcf\system\WCF;

class FaqQuestionListPage extends AbstractPage
{
    /**
     * @inheritDoc
     */
    public $neededPermissions = ['user.faq.canViewFAQ'];

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        //get categories
        $faqs = [];
        $embedObjectIDs = [];
        $categoryTree = new FaqCategoryNodeTree('dev.tkirch.wsc.faq.category');
        foreach ($categoryTree->getIterator() as $category) {
            if (!$category->isAccessible()) {
                continue;
            }

            $questionList = new QuestionList();
            $questionList->getConditionBuilder()->add('categoryID = ?', [$category->categoryID]);
            $questionList->readObjects();

            if (!count($questionList)) {
                continue;
            }

            $faq = [
                'title' => WCF::getLanguage()->get($category->title),
                'attachments' => $questionList->getAttachmentList(),
            ];

            foreach ($questionList->getObjects() as $question) {
                if ($question->isAccessible()) {
                    $faq['questions'][] = $question;
                    if ($question->hasEmbeddedObjects) {
                        $embedObjectIDs[] = $question->questionID;
                    }
                }
            }

            if ($category->getParentNode() && $category->getParentNode()->categoryID) {
                $faqs[$category->getParentNode()->categoryID]['sub'][$category->categoryID] = $faq;
            } else {
                $faqs[$category->categoryID] = $faq;
            }
        }

        if (\count($embedObjectIDs)) {
            MessageEmbeddedObjectManager::getInstance()->loadObjects(
                'dev.tkirch.wsc.faq.question',
                $embedObjectIDs
            );
        }

        WCF::getTPL()->assign([
            'faqs' => $faqs,
        ]);
    }
}
