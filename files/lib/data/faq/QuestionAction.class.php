<?php
namespace wcf\data\faq;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\language\I18nHandler;
use wcf\system\WCF;

class QuestionAction extends AbstractDatabaseObjectAction {

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
		
		return $question;
	}

   /**
	* @inheritDoc
	* https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/data/reaction/type/ReactionTypeAction.class.php#L46
	*/
   public function update() {
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
				$updateData['question'] = 'wcf.faq.question.question'.$question->questionID;
			}
			if(isset($this->parameters['answer_i18n'])) {
				I18nHandler::getInstance()->save(
					$this->parameters['answer_i18n'],
					'wcf.faq.question.answer'.$object->questionID,
					'wcf.faq'
				);
				$updateData['answer'] = 'wcf.faq.question.answer'.$question->questionID;
			}

		   //update show order
			if(isset($this->parameters['data']['showOrder']) && $this->parameters['data']['showOrder'] !== null) {
				$sql = "UPDATE  wcf" . WCF_N . "_faq_questions
					SET	showOrder = showOrder + 1
					WHERE	showOrder >= ?
					AND     questionID <> ?";
				$statement = WCF::getDB()->prepareStatement($sql);
				$statement->execute([
					$this->parameters['data']['showOrder'],
					$object->questionID
				]);
			}

			
			if (!empty($updateData)) {
				$object->update($updateData);
			} 
		}
	}
}
