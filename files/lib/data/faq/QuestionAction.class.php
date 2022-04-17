<?php

namespace wcf\data\faq;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\ISortableAction;
use wcf\data\IToggleAction;
use wcf\data\TDatabaseObjectToggle;
use wcf\system\exception\UserInputException;
use wcf\system\html\input\HtmlInputProcessor;
use wcf\system\language\I18nHandler;
use wcf\system\message\embedded\object\MessageEmbeddedObjectManager;
use wcf\system\search\SearchIndexManager;
use wcf\system\WCF;

class QuestionAction extends AbstractDatabaseObjectAction implements ISortableAction, IToggleAction
{
    use TDatabaseObjectToggle;

    /**
     * @inheritDoc
     */
    protected $permissionsCreate = ['admin.faq.canAddQuestion'];

    /**
     * @inheritDoc
     */
    protected $permissionsDelete = ['admin.faq.canAddQuestion'];

    /**
     * @inheritDoc
     */
    protected $permissionsUpdate = ['admin.faq.canAddQuestion'];

    /**
     * @inheritDoc
     */
    protected $requireACP = [];

    /**
     * @inheritDoc
     * https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/data/reaction/type/ReactionTypeAction.class.php#L46
     */
    public function create()
    {
        $inputProcessor = null;
        //prepare answer
        if (isset($this->parameters['answer_i18n'])) {
            $answers = '';
            foreach ($this->parameters['answer_i18n'] as $languageID => $answer) {
                $processor = new HtmlInputProcessor();
                $processor->process($answer, 'dev.tkirch.wsc.faq.question', 0);
                $this->parameters['answer_i18n'][$languageID] = $processor->getHtml();
                $answers .= $answer;
            }
            $inputProcessor = new HtmlInputProcessor();
            $inputProcessor->process($answers, 'dev.tkirch.wsc.faq.question', 0);
        } else {
            $inputProcessor = new HtmlInputProcessor();
            $inputProcessor->process($this->parameters['data']['answer'], 'dev.tkirch.wsc.faq.question', 0);
            $this->parameters['data']['answer'] = $inputProcessor->getHtml();
        }

        //get question
        $question = parent::create();
        $questionEditor = new QuestionEditor($question);

        //i18n
        $updateData = [];
        if (isset($this->parameters['question_i18n'])) {
            I18nHandler::getInstance()->save(
                $this->parameters['question_i18n'],
                'wcf.faq.question.question' . $question->questionID,
                'wcf.faq'
            );
            $updateData['question'] = 'wcf.faq.question.question' . $question->questionID;
        }
        if (isset($this->parameters['answer_i18n'])) {
            I18nHandler::getInstance()->save(
                $this->parameters['answer_i18n'],
                'wcf.faq.question.answer' . $question->questionID,
                'wcf.faq'
            );
            $updateData['answer'] = 'wcf.faq.question.answer' . $question->questionID;
        }
        $this->updateSearchIndex($question);

        if (
            isset($this->parameters['answer_attachmentHandler']) &&
            $this->parameters['answer_attachmentHandler'] !== null
        ) {
            $this->parameters['answer_attachmentHandler']->updateObjectID($question->questionID);
        }

        if (!empty($inputProcessor)) {
            $inputProcessor->setObjectID($question->questionID);
            if (
                MessageEmbeddedObjectManager::getInstance()->registerObjects(
                    $inputProcessor
                )
            ) {
                $updateData['hasEmbeddedObjects'] = 1;
            }
        }

        //update question
        if (!empty($updateData)) {
            $questionEditor->update($updateData);
        }

        return $question;
    }

    /**
     * @inheritDoc
     * https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/data/reaction/type/ReactionTypeAction.class.php#L46
     */
    public function update()
    {
        //check if showOrder must be updated
        if (count($this->objects) == 1 && isset($this->parameters['data']['showOrder'])) {
            $objectEditor = $this->getObjects()[0];
            $this->parameters['data']['showOrder'] = $objectEditor->updateShowOrder(
                $this->parameters['data']['showOrder']
            );
        }

        $inputProcessor = null;
        //prepare answer
        if (isset($this->parameters['answer_i18n'])) {
            $answers = '';
            foreach ($this->parameters['answer_i18n'] as $languageID => $answer) {
                $processor = new HtmlInputProcessor();
                $processor->process($answer, 'dev.tkirch.wsc.faq.question', 0);
                $this->parameters['answer_i18n'][$languageID] = $processor->getHtml();
                $answers .= $answer;
            }
            $inputProcessor = new HtmlInputProcessor();
            $inputProcessor->process($answers, 'dev.tkirch.wsc.faq.question', 0);
        } else {
            $inputProcessor = new HtmlInputProcessor();
            $inputProcessor->process($this->parameters['data']['answer'], 'dev.tkirch.wsc.faq.question', 0);
            $this->parameters['data']['answer'] = $inputProcessor->getHtml();
        }

        parent::update();

        foreach ($this->getObjects() as $object) {
            $updateData = [];

            //i18n
            if (isset($this->parameters['question_i18n'])) {
                I18nHandler::getInstance()->save(
                    $this->parameters['question_i18n'],
                    'wcf.faq.question.question' . $object->questionID,
                    'wcf.faq'
                );
                $updateData['question'] = 'wcf.faq.question.question' . $object->questionID;
            }
            if (isset($this->parameters['answer_i18n'])) {
                I18nHandler::getInstance()->save(
                    $this->parameters['answer_i18n'],
                    'wcf.faq.question.answer' . $object->questionID,
                    'wcf.faq'
                );
                $updateData['answer'] = 'wcf.faq.question.answer' . $object->questionID;
            }
            $this->updateSearchIndex($object);

            //update show order
            if (isset($this->parameters['data']['showOrder']) && $this->parameters['data']['showOrder'] !== null) {
                if ($object->showOrder < $this->parameters['data']['showOrder']) {
                    $sql = "UPDATE  wcf" . WCF_N . "_faq_questions
					SET	showOrder = showOrder - 1
					WHERE	showOrder > ?
					AND	 showOrder <= ?
					AND	 questionID <> ?";
                    $statement = WCF::getDB()->prepareStatement($sql);
                    $statement->execute([
                        $object->showOrder,
                        $this->parameters['data']['showOrder'],
                        $object->questionID
                    ]);
                } elseif ($object->showOrder > $this->parameters['data']['showOrder']) {
                    $sql = "UPDATE  wcf" . WCF_N . "_faq_questions
					SET	showOrder = showOrder + 1
					WHERE	showOrder < ?
					AND	 showOrder >= ?
					AND	 questionID <> ?";
                    $statement = WCF::getDB()->prepareStatement($sql);
                    $statement->execute([
                        $object->showOrder,
                        $this->parameters['data']['showOrder'],
                        $object->questionID
                    ]);
                }
            }

            if (
                isset($this->parameters['answer_attachmentHandler']) &&
                $this->parameters['answer_attachmentHandler'] !== null
            ) {
                $this->parameters['answer_attachmentHandler']->updateObjectID($object->questionID);
            }

            if (!empty($inputProcessor)) {
                $inputProcessor->setObjectID($object->questionID);

                if (
                    $object->hasEmbeddedObjects != MessageEmbeddedObjectManager::getInstance()->registerObjects(
                        $inputProcessor
                    )
                ) {
                    $updateData['hasEmbeddedObjects'] = $object->hasEmbeddedObjects ? 0 : 1;
                }
            }

            if (!empty($updateData)) {
                $object->update($updateData);
            }
        }
    }

    protected function updateSearchIndex($object)
    {
        if (isset($this->parameters['answer_i18n'])) {
            foreach ($this->parameters['answer_i18n'] as $languageID => $answer) {
                $title = '';
                if (isset($this->parameters['question_i18n'][$languageID])) {
                    $title = $this->parameters['question_i18n'][$languageID];
                } elseif (isset($this->parameters['data']['question'])) {
                    $title = $this->parameters['data']['question'];
                }

                SearchIndexManager::getInstance()->set(
                    'dev.tkirch.wsc.faq.question',
                    $object->questionID,
                    $answer,
                    $title,
                    \TIME_NOW,
                    0,
                    '',
                    $languageID ?: null
                );
            }
        } else {
            if (isset($this->parameters['question_i18n'])) {
                foreach ($this->parameters['question_i18n'] as $languageID => $question) {
                    SearchIndexManager::getInstance()->set(
                        'dev.tkirch.wsc.faq.question',
                        $object->questionID,
                        $this->parameters['data']['answer'],
                        $question,
                        \TIME_NOW,
                        0,
                        '',
                        $languageID ?: null
                    );
                }
            } else {
                SearchIndexManager::getInstance()->set(
                    'dev.tkirch.wsc.faq.question',
                    $object->questionID,
                    $this->parameters['data']['answer'],
                    $this->parameters['data']['question'],
                    \TIME_NOW,
                    0,
                    '',
                    null
                );
            }
        }
    }

    public function validateSearch()
    {
        $this->readString('searchString');
    }

    public function search()
    {
        $sql = "SELECT		  faq_questions.questionID
			FROM			wcf" . WCF_N . "_faq_questions faq_questions
			LEFT JOIN		wcf" . WCF_N . "_language_item language_item
						ON	language_item.languageItem = faq_questions.question
			WHERE		   faq_questions.question LIKE ?
						OR	(
								language_item.languageItemValue LIKE ?
							AND	language_item.languageID = ?
							)
			ORDER BY		faq_questions.question";
        $statement = WCF::getDB()->prepareStatement($sql, 5);
        $statement->execute([
            '%' . $this->parameters['searchString'] . '%',
            '%' . $this->parameters['searchString'] . '%',
            WCF::getLanguage()->languageID
        ]);

        $questionIDs = [];
        while ($questionID = $statement->fetchColumn()) {
            $questionIDs[] = $questionID;
        }

        $questionList = new QuestionList();
        $questionList->setObjectIDs($questionIDs);
        $questionList->readObjects();

        $questions = [];
        foreach ($questionList as $question) {
            $questions[] = [
                'question' => $question->getTitle(),
                'questionID' => $question->questionID,
            ];
        }

        return $questions;
    }

    /**
     * @inheritDoc
     */
    public function validateUpdatePosition()
    {
        WCF::getSession()->checkPermissions($this->permissionsUpdate);

        if (!isset($this->parameters['data']['structure']) || !\is_array($this->parameters['data']['structure'])) {
            throw new UserInputException('structure');
        }

        $questionList = new QuestionList();
        $questionList->setObjectIDs($this->parameters['data']['structure'][0]);
        $questionList->readObjects();
        if (\count($questionList) !== \count($this->parameters['data']['structure'][0])) {
            throw new UserInputException('structure');
        }

        $this->readInteger('offset', true, 'data');
    }

    /**
     * @inheritDoc
     */
    public function updatePosition()
    {
        $sql = "UPDATE  wcf" . WCF_N . "_faq_questions
                SET     showOrder = ?
                WHERE   questionID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);

        $showOrder = $this->parameters['data']['offset'];
        WCF::getDB()->beginTransaction();
        foreach ($this->parameters['data']['structure'][0] as $questionID) {
            $statement->execute([
                $showOrder++,
                $questionID,
            ]);
        }
        WCF::getDB()->commitTransaction();
    }
}
