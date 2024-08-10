<?php

namespace wcf\page;

use Override;
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

    public int $showFaqAddDialog = 0;

    #[Override]
    public function readParameters()
    {
        parent::readParameters();

        if (!empty($_REQUEST['showFaqAddDialog'])) {
            $this->showFaqAddDialog = 1;
        }
    }

    #[Override]
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

            if (!\count($questionList)) {
                continue;
            }

            $faq = [
                'id' => $category->categoryID,
                'title' => WCF::getLanguage()->get($category->title),
                'attachments' => $questionList->getAttachmentList(),
                'icon24' => $category->getIcon(24),
                'icon64' => $category->getIcon(64),
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
            'showFaqAddDialog' => $this->showFaqAddDialog,
        ]);
    }
}
