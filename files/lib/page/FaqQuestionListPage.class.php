<?php

namespace wcf\page;

use CuyZ\Valinor\Mapper\MappingError;
use Override;
use wcf\data\faq\category\FaqCategory;
use wcf\data\faq\category\FaqCategoryNodeTree;
use wcf\data\faq\QuestionList;
use wcf\http\Helper;
use wcf\system\exception\IllegalLinkException;
use wcf\system\message\embedded\object\MessageEmbeddedObjectManager;
use wcf\system\WCF;

class FaqQuestionListPage extends AbstractPage
{
    /**
     * @inheritDoc
     */
    public $neededPermissions = ['user.faq.canViewFAQ'];

    protected int $showFaqAddDialog = 0;

    protected ?FaqCategory $category;

    #[Override]
    public function readParameters()
    {
        parent::readParameters();

        if (!empty($_REQUEST['showFaqAddDialog'])) {
            $this->showFaqAddDialog = 1;
        }

        try {
            $queryParameters = Helper::mapQueryParameters(
                $_GET,
                <<<'EOT'
                    array {
                        id: positive-int|null
                    }
                    EOT
            );

            $this->category = FaqCategory::getCategory($queryParameters['id']);
        } catch (MappingError) {
            throw new IllegalLinkException();
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
            if (
                isset($this->category)
                && $this->category !== null
                && $this->category->categoryID != $category->categoryID
            ) {
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
