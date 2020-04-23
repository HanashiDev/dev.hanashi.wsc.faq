<?php
namespace wcf\data\faq;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\language\I18nHandler;

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
	protected $requireACP = ['delete'];
	
 	/**
	 * @inheritDoc
	 */
	public function create() {
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
}
