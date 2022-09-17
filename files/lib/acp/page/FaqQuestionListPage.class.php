<?php

namespace wcf\acp\page;

use wcf\data\category\CategoryNodeTree;
use wcf\data\faq\QuestionList;
use wcf\page\SortablePage;
use wcf\system\WCF;
use wcf\util\StringUtil;

class FaqQuestionListPage extends SortablePage
{
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
    public $validSortFields = ['questionID', 'categoryID', 'showOrder'];

    /**
     * category id
     * @var integer
     */
    public $categoryID = 0;

    /**
     * question
     * @var string
     */
    public $question = '';

    /**
     * answer
     * @var string
     */
    public $answer = '';

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        parent::readParameters();

        if (isset($_REQUEST['categoryID'])) {
            $this->categoryID = (int)$_REQUEST['categoryID'];
        }
        if (!empty($_REQUEST['question'])) {
            $this->question = StringUtil::trim($_REQUEST['question']);
        }
        if (!empty($_REQUEST['answer'])) {
            $this->answer = StringUtil::trim($_REQUEST['answer']);
        }
    }

    /**
     * @inheritDoc
     */
    protected function initObjectList()
    {
        parent::initObjectList();

        if ($this->categoryID) {
            $this->objectList->getConditionBuilder()->add(
                'faq_questions.categoryID = ?',
                [$this->categoryID]
            );
        }

        if (!empty($this->question)) {
            $this->objectList->getConditionBuilder()->add(
                'faq_questions.question LIKE ?',
                ['%' . $this->question . '%']
            );
        }

        if (!empty($this->answer)) {
            $this->objectList->getConditionBuilder()->add(
                'faq_questions.answer LIKE ?',
                ['%' . $this->answer . '%']
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'categoryID' => $this->categoryID,
            'question' => $this->question,
            'answer' => $this->answer,
            'categoryNodeList' => (new CategoryNodeTree('dev.tkirch.wsc.faq.category'))->getIterator(),
        ]);
    }
}
