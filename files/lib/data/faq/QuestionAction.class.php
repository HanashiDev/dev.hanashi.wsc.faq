<?php

namespace wcf\data\faq;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\ISortableAction;
use wcf\data\IToggleAction;
use wcf\data\TDatabaseObjectToggle;
use wcf\system\attachment\AttachmentHandler;
use wcf\system\exception\UserInputException;
use wcf\system\html\input\HtmlInputProcessor;
use wcf\system\language\I18nHandler;
use wcf\system\message\embedded\object\MessageEmbeddedObjectManager;
use wcf\system\search\SearchIndexManager;
use wcf\system\WCF;

/**
 * @method  QuestionEditor[] getObjects()
 * @method  QuestionEditor   getSingleObject()
 */
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
     * Adapted from: https://github.com/WoltLab/WCF/blob/9ed5bd7220ff1beec1949dc13777bf2f62acf1f5/wcfsetup/install/files/lib/data/reaction/type/ReactionTypeAction.class.php#L49
     */
    public function create()
    {
        //prepare answer
        if (isset($this->parameters['answer_i18n'])) {
            $this->parameters['data']['isMultilingual'] = 1;
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

        foreach ($this->parameters as $parameter) {
            if ($parameter instanceof AttachmentHandler) {
                $parameter->updateObjectID($question->questionID);
            } elseif ($parameter instanceof HtmlInputProcessor) {
                $parameter->setObjectID($question->questionID);
                if (
                    MessageEmbeddedObjectManager::getInstance()->registerObjects(
                        $parameter
                    )
                ) {
                    $updateData['hasEmbeddedObjects'] = 1;
                }
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
     * Adapted from: https://github.com/WoltLab/WCF/blob/9ed5bd7220ff1beec1949dc13777bf2f62acf1f5/wcfsetup/install/files/lib/data/reaction/type/ReactionTypeAction.class.php#L112
     */
    public function update()
    {
        //check if showOrder must be updated
        if (isset($this->parameters['data']['showOrder']) && \count($this->objects) === 1) {
            $objectEditor = $this->getObjects()[0];
            $this->parameters['data']['showOrder'] = $objectEditor->updateShowOrder(
                $this->parameters['data']['showOrder']
            );
        }

        //prepare answer
        if (isset($this->parameters['answer_i18n'])) {
            $this->parameters['data']['isMultilingual'] = 1;
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
                    $sql = "UPDATE  wcf1_faq_questions
					SET	showOrder = showOrder - 1
					WHERE	showOrder > ?
					AND	 showOrder <= ?
					AND	 questionID <> ?";
                    $statement = WCF::getDB()->prepare($sql);
                    $statement->execute([
                        $object->showOrder,
                        $this->parameters['data']['showOrder'],
                        $object->questionID,
                    ]);
                } elseif ($object->showOrder > $this->parameters['data']['showOrder']) {
                    $sql = "UPDATE  wcf1_faq_questions
					SET	showOrder = showOrder + 1
					WHERE	showOrder < ?
					AND	 showOrder >= ?
					AND	 questionID <> ?";
                    $statement = WCF::getDB()->prepare($sql);
                    $statement->execute([
                        $object->showOrder,
                        $this->parameters['data']['showOrder'],
                        $object->questionID,
                    ]);
                }
            }

            foreach ($this->parameters as $parameter) {
                if ($parameter instanceof AttachmentHandler) {
                    $parameter->updateObjectID($object->questionID);
                } elseif ($parameter instanceof HtmlInputProcessor) {
                    $parameter->setObjectID($object->questionID);
                    if (
                        $object->hasEmbeddedObjects != MessageEmbeddedObjectManager::getInstance()->registerObjects(
                            $parameter
                        )
                    ) {
                        $updateData['hasEmbeddedObjects'] = $object->hasEmbeddedObjects ? 0 : 1;
                    }
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
        } elseif (isset($this->parameters['question_i18n'])) {
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
        $sql = "UPDATE  wcf1_faq_questions
                SET     showOrder = ?
                WHERE   questionID = ?";
        $statement = WCF::getDB()->prepare($sql);

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
