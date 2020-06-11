<?php
namespace wcf\data\faq;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\IToggleAction;
use wcf\data\TDatabaseObjectToggle;
use wcf\system\html\input\HtmlInputProcessor;
use wcf\system\language\I18nHandler;
use wcf\system\WCF;

class QuestionAction extends AbstractDatabaseObjectAction implements IToggleAction {
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
	public function create() {
		if(isset($this->parameters['data']['showOrder']) && $this->parameters['data']['showOrder'] !== null) {
			$sql = "UPDATE  wcf" . WCF_N . "_faq_questions
					SET	showOrder = showOrder + 1
					WHERE	showOrder >= ?";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute([
				$this->parameters['data']['showOrder']
			]);
		}

		if (isset($this->parameters['answer_i18n'])) {
			foreach ($this->parameters['answer_i18n'] as $languageID => $answer) {
				$processor = new HtmlInputProcessor();
				$processor->process($answer, 'dev.tkirch.wsc.faq.question', 0);
				$this->parameters['answer_i18n'][$languageID] = $processor->getHtml();
			}
		} else {
			$processor = new HtmlInputProcessor();
			$processor->process($this->parameters['data']['answer'], 'dev.tkirch.wsc.faq.question', 0);
			$this->parameters['data']['answer'] = $processor->getHtml();
		}

		//get question
		$question = parent::create();
		$questionEditor = new QuestionEditor($question);

		//i18n
		$updateData = [];
		if(isset($this->parameters['question_i18n'])) {
			I18nHandler::getInstance()->save(
				$this->parameters['question_i18n'],
				'wcf.faq.question.question'.$question->questionID,
				'wcf.faq'
			);
			$updateData['question'] = 'wcf.faq.question.question'.$question->questionID;
		}
		if(isset($this->parameters['answer_i18n'])) {
			I18nHandler::getInstance()->save(
				$this->parameters['answer_i18n'],
				'wcf.faq.question.answer'.$question->questionID,
				'wcf.faq'
			);
			$updateData['answer'] = 'wcf.faq.question.answer'.$question->questionID;
		}
		
		//update question
		if(!empty($updateData)) {
			$questionEditor->update($updateData);
		}

		if (isset($this->parameters['answer_attachmentHandler']) && $this->parameters['answer_attachmentHandler'] !== null) {
			$this->parameters['answer_attachmentHandler']->updateObjectID($question->questionID);
		}
		
		if (!empty($this->parameters['answer_htmlInputProcessor'])) {
			$this->parameters['answer_htmlInputProcessor']->setObjectID($question->questionID);
        }
		
		return $question;
	}

   /**
	* @inheritDoc
	* https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/data/reaction/type/ReactionTypeAction.class.php#L46
	*/
   public function update() {
		if (isset($this->parameters['answer_i18n'])) {
			foreach ($this->parameters['answer_i18n'] as $languageID => $answer) {
				$processor = new HtmlInputProcessor();
				$processor->process($answer, 'dev.tkirch.wsc.faq.question', 0);
				$this->parameters['answer_i18n'][$languageID] = $processor->getHtml();
			}
		} else {
			$processor = new HtmlInputProcessor();
			$processor->process($this->parameters['data']['answer'], 'dev.tkirch.wsc.faq.question', 0);
			$this->parameters['data']['answer'] = $processor->getHtml();
		}

	   parent::update();
	   
	   foreach ($this->getObjects() as $object) {
			$updateData = [];
				
			//i18n
			if(isset($this->parameters['question_i18n'])) {
				I18nHandler::getInstance()->save(
					$this->parameters['question_i18n'],
					'wcf.faq.question.question'.$object->questionID,
					'wcf.faq'
				);
				$updateData['question'] = 'wcf.faq.question.question'.$object->questionID;
			}
			if(isset($this->parameters['answer_i18n'])) {
				I18nHandler::getInstance()->save(
					$this->parameters['answer_i18n'],
					'wcf.faq.question.answer'.$object->questionID,
					'wcf.faq'
				);
				$updateData['answer'] = 'wcf.faq.question.answer'.$object->questionID;
			}

		   	//update show order
			if(isset($this->parameters['data']['showOrder']) && $this->parameters['data']['showOrder'] !== null) {
				if($object->showOrder < $this->parameters['data']['showOrder']) {
					$sql = "UPDATE  wcf" . WCF_N . "_faq_questions
					SET	showOrder = showOrder - 1
					WHERE	showOrder > ?
					AND     showOrder <= ?
					AND     questionID <> ?";
					$statement = WCF::getDB()->prepareStatement($sql);
					$statement->execute([
						$object->showOrder,
						$this->parameters['data']['showOrder'],
						$object->questionID
					]);
				} else if($object->showOrder > $this->parameters['data']['showOrder']) {
					$sql = "UPDATE  wcf" . WCF_N . "_faq_questions
					SET	showOrder = showOrder + 1
					WHERE	showOrder < ?
					AND     showOrder >= ?
					AND     questionID <> ?";
					$statement = WCF::getDB()->prepareStatement($sql);
					$statement->execute([
						$object->showOrder,
						$this->parameters['data']['showOrder'],
						$object->questionID
					]);
				}
			}
			
			if (!empty($updateData)) {
				$object->update($updateData);
			} 

			if (isset($this->parameters['answer_attachmentHandler']) && $this->parameters['answer_attachmentHandler'] !== null) {
				$this->parameters['answer_attachmentHandler']->updateObjectID($object->questionID);
			}

			if (!empty($this->parameters['answer_htmlInputProcessor'])) {
				$this->parameters['answer_htmlInputProcessor']->setObjectID($object->questionID);
			}
		}
	}

	public function validateSearch() {
		$this->readString('searchString');
	}

	public function search() {
		$sql = "SELECT          faq_questions.questionID
			FROM            wcf".WCF_N."_faq_questions faq_questions
			LEFT JOIN		wcf".WCF_N."_language_item language_item
						ON	language_item.languageItem = faq_questions.question
			WHERE           faq_questions.question LIKE ?
						OR	(
								language_item.languageItemValue LIKE ?
							AND	language_item.languageID = ?
							)
			ORDER BY        faq_questions.question";
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
}
